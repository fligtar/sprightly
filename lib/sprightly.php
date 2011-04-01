<?php
/*
    This is the main sprightly class, responsible for fetching data
    from remote sources and writing to JSON files. This should probably
    only be called from the cron scripts.
*/
include dirname(__FILE__).'/config.php';

class sprightly {
    
    // Catalog of what reports get run when
    private $reports = array(
        'minutely' => array(
            'firefox_tweets'
            //'firefox_input'
        ),
        '5minutely' => array(
            'favorite_tweets',
            'firefox_caltrain'
        ),
        'hourly' => array(
            'calendar'
        )
    );
    
    // Runs the given report type -- either hourly or minutely
    public function update_data($type) {
        $reports = $this->reports[$type];
        $data = array();
        
        foreach ($reports as $report) {
            $output = call_user_func(array('sprightly', $report));
            
            $data[$report] = $output;
        }
        
        print_r($data);
        
        file_put_contents(dirname(dirname(__FILE__)).'/data/'.$type.'.txt', json_encode($data));
    }
    
    public function firefox_input() {
        $xml = $this->load_url('http://input.mozilla.com/en-US/beta/search/atom?nocache-'.time());
        $data = new SimpleXMLElement($xml);
        $input = array();
        
        foreach ($data as $item) {
            if (empty($item->summary)) continue;
            
            if ((string) $item->category[0]->attributes()->term != 'locale:en-US') continue;
            
            $input[] = array(
                'url' => (string) $item->id,
                'date' => (string) $item->updated,
                'text' => (string) $item->summary,
                'version' => str_replace('version:', '', (string) $item->category[2]->attributes()->term),
                'os' => str_replace('os:', '', (string) $item->category[3]->attributes()->term),
                'sentiment' => str_replace('sentiment:', '', (string) $item->category[4]->attributes()->term)
            );
        }
        
        return $input;
    }
    
    // Gets the latest tweets that mention firefox, #firefox, @firefox, or mozilla
    private function firefox_tweets() {
        $xml = $this->load_url('http://search.twitter.com/search.atom?lang=en&q=%40firefox+OR+%23firefox+OR+firefox+OR+mozilla');
        
        $data = new SimpleXMLElement($xml);
        $tweets = array();
        
        foreach ($data as $item) {
            if (empty($item->content)) continue;
            $tweets[] = array(
                'text' => (string) $item->content,
                'author' => (string) $item->author->name,
                'author_url' => (string) $item->author->uri,
                'url' => (string) $item->link[0]->attributes()->href,
                'avatar' => (string) $item->link[1]->attributes()->href,
                'date' => (string) $item->published
            );
        }
        
        return $tweets;
    }
    
    // Gets the latest tweets that mention firefox or mozilla + caltrain
    private function firefox_caltrain() {
        $xml = $this->load_url('http://search.twitter.com/search.atom?lang=en&q=firefox+OR+mozilla+caltrain');
        
        $data = new SimpleXMLElement($xml);
        $tweets = array();
        
        foreach ($data as $item) {
            if (empty($item->content)) continue;
            $tweets[] = array(
                'text' => (string) $item->content,
                'author' => (string) $item->author->name,
                'author_url' => (string) $item->author->uri,
                'url' => (string) $item->link[0]->attributes()->href,
                'avatar' => (string) $item->link[1]->attributes()->href,
                'date' => (string) $item->published
            );
        }
        
        return $tweets;
    }
    
    // Gets our favorite tweets
    private function favorite_tweets() {
        require dirname(__FILE__).'/twitteroauth/twitteroauth.php';
        $connection = new TwitterOAuth(FAVORITES_CONSUMER_KEY, FAVORITES_CONSUMER_SECRET, OAUTH_TOKEN, OAUTH_TOKEN_SECRET);
        
        $json = $connection->get('statuses/retweeted_by_me.json?count=10');
        
        $tweets = array();
        
        foreach ($json as $tweet) {
            $tweets[] = array(
                'text' => (string) $tweet->retweeted_status->text,
                'author' => (string) $tweet->retweeted_status->user->screen_name.' ('.$tweet->retweeted_status->user->name.')',
                'author_url' => (string) 'http://twitter.com/'.$tweet->retweeted_status->user->screen_name,
                'url' => (string) 'http://twitter.com/'.$tweet->retweeted_status->user->screen_name.'/statuses/'.$tweet->retweeted_status->id,
                'avatar' => (string) $tweet->retweeted_status->user->profile_image_url,
                'date' => (string) $tweet->retweeted_status->created_at
            );
        }
        
        return $tweets;
    }
    
    // Gets upcoming events from the calendar
    private function calendar() {
        require dirname(__FILE__).'/ical.php';
        
        $ics = dirname(dirname(__FILE__)).'/data/calendar.ics';
        
        $file = $this->load_url('https://mail.mozilla.com/home/justin@mozilla.com/moco%20calendar');
        
        file_put_contents($ics, $file);
        
        if (file_exists($ics)) {
            $cal = new iCalReader($ics);
            
            $events = $cal->getEvents();
            $events = $this->filter_events($events);
        }
        else
            $events = array();
        
        $events = $this->add_events($events);
        
        usort($events, array('sprightly', 'sort_events'));
        
        return $events;
    }
    
    // Filter events down to those this week
    private function filter_events($events) {
        $filtered = array();
        
        // Look for events that start or end during the week or contain days in the week
        foreach ($events as $event) {
            // Find start and end times
            if (!empty($event['DTSTART;TZID="America/Los_Angeles"']))
                $start = strtotime($event['DTSTART;TZID="America/Los_Angeles"']);
            if (!empty($event['DTEND;TZID="America/Los_Angeles"']))
                $end = strtotime($event['DTEND;TZID="America/Los_Angeles"']);
            if (!empty($event['DTSTART;VALUE=DATE']))
                $start = strtotime($event['DTSTART;VALUE=DATE']);
            if (!empty($event['DTEND;VALUE=DATE']))
                $end = strtotime($event['DTEND;VALUE=DATE']);
            
            // Find start and end times for week
            $weekstart = strtotime('today');
            $weekend = strtotime('+6 days');
            
            $include = false;
            // Event ends this week
            if ($end <= $weekstart && $end >= $weekend)
                $include = true;
            // Event starts this week
            if ($start >= $weekstart && $start <= $weekend)
                $include = true;
            // Event contains this week
            if ($start < $weekstart && $end > $weekend)
                $include = true;
            
            if ($include == true) {
                $filtered[] = array(
                    'name' => $event['SUMMARY'],
                    'start' => date('Y-m-d\TH:i:sP', $start),
                    'end' => date('Y-m-d\TH:i:sP', $end)
                );
            }
        }
        
        return $filtered;
    }
    
    // Rather than deal with supporting recurring events, for now just add them manually
    function add_events($events) {
        $events[] = array(
            'name' => 'Weekly Project Meeting',
            'start' => date('Y-m-d\TH:i:sP', strtotime('monday 11:00 America/Los_Angeles')),
            'end' => date('Y-m-d\TH:i:sP', strtotime('monday 12:00 America/Los_Angeles'))
        );
        
        return $events;
    }
    
    // Custom comparison function for sorting events
    static function sort_events($a, $b) {
        if ($a['start'] == $b['start'])
            return 0;
        
        return ($a['start'] < $b['start']) ? -1 : 1;
    }
    
    // curl utility function to fetch a URL and return the output
    public function load_url($url, $post = '', $credentials = '') {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        if (!empty($post)) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        
        if (!empty($credentials)) {
            curl_setopt($ch, CURLOPT_USERPWD, $credentials);
        }

        $response = curl_exec($ch);

        curl_close($ch);

        return $response;
    }
    
}

?>