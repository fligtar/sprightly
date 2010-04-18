<?php
/*
    This is the main sprightly class, responsible for fetching data
    from remote sources and writing to JSON files. This should probably
    only be called from the cron scripts.
*/

class sprightly {
    
    // Catalog of what reports get run when
    private $reports = array(
        'minutely' => array(
            'firefox_downloads',
            'firefox_tweets'
        ),
        '5minutely' => array(
            'traffic'
        ),
        'hourly' => array(
            'amo',
            'weather',
            'caltrain',
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
    
    // Gets the total Firefox 3.6 downloads
    private function firefox_downloads() {
        $json = $this->load_url('http://downloadstats.mozilla.com/data/country_report.json');

        $data = json_decode($json);
        
        foreach ($data->countries as $country) {
            if ($country->code == '**') {
                $total = array('total' => $country->total, 'rps' => $country->rps, 'sum' => $country->count);
                break;
            }
        }
        
        // We only update download counts once every 5 seconds now, so we need to split these up
        for ($i = 0; $i < 60; $i += 5) {
            $total['dp5'][] = $total['rps'][$i] + $total['rps'][$i + 1] + $total['rps'][$i + 2] + $total['rps'][$i + 3] + $total['rps'][$i + 4];
        }
        unset($total['rps']);
        
        return $total;
    }
    
    // Gets the previous day's AMO stats
    private function amo() {
        // Pull yesterday's stats because today's will be zero.
        $xml = $this->load_url('https://services.addons.mozilla.org/en-US/firefox/api/1.2/stats/'.date('Y-m-d', time() - 86400));
        
        $data = new SimpleXMLElement($xml);
        
        $amo = array(
            'downloads' => (string) $data->addons->downloads,
            'adu' => (string) $data->addons->updatepings,
            'public' => (string) $data->addons->counts->public,
            'pending' => (string) $data->addons->counts->pending,
            'nominated' => (string) $data->addons->counts->nominated,
            'collections' => (string) $data->collections->counts->total,
            'collectiondownloads' => (string) $data->collections->addon_downloads
        );
        
        return $amo;    
    }
    
    // Gets the current weather from Yahoo! Weather
    private function weather() {
        $weather = array();
        $locales = array(
            'sf' => 12797128,
            'mv' => 2487956,
            'sj' => 2488042
        );
        
        foreach ($locales as $locale => $code) {
            $xml = $this->load_url('http://weather.yahooapis.com/forecastrss?w='.$code);
        
            $data = new SimpleXMLElement($xml, LIBXML_NOCDATA);
        
            $description = (string) $data->channel->item->description;
        
            preg_match('/<img src="(.+?)"\/><br \/>\s+<b>Current Conditions:<\/b><br \/>\s+(.+?)<BR \/>/', $description, $matches);
        
            $weather[$locale] = array('img' => $matches[1], 'conditions' => $matches[2]);
        }
        
        return $weather;
    }
    
    // Gets the day's Caltrain schedule from my manually-entered class
    private function caltrain() {
        include dirname(__FILE__).'/caltrain.php';
        
        $schedule = date('N') >= 6 ? 'weekends' : 'weekdays';
        
        return $caltrain[$schedule];
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
    
    // Retrieves the live traffic image from 511 and saves it
    private function traffic() {
        $image = $this->load_url('http://traffic.511.org/portalmap2.gif?'.time());
        
        file_put_contents(dirname(dirname(__FILE__)).'/data/traffic.gif', $image);
    }
    
    // Gets upcoming events from the calendar
    private function calendar() {
        require dirname(__FILE__).'/ical.php';
        
        $ics = dirname(dirname(__FILE__)).'/data/calendar.ics';
        
        if (!file_exists($ics)) return false;
        
        $cal = new iCalReader($ics);

        $events = $cal->getEvents();
        $events = $this->filter_events($events);
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
    private function load_url($url, $post = '') {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // @TODO remove later
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        if (!empty($post)) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }

        $response = curl_exec($ch);

        curl_close($ch);

        return $response;
    }
    
}

?>