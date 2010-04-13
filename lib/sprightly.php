<?php
/*
    This is the main sprightly class, responsible for fetching data
    from remote sources and writing to JSON files. This should probably
    only be called from the cron scripts.
*/

class sprightly {
    
    // Catalog of what reports get run when
    public $reports = array(
        'minutely' => array(
            'firefox_downloads',
            'firefox_tweets'
        ),
        'hourly' => array(
            'amo',
            'weather',
            'caltrain'
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
        
        file_put_contents('../data/'.$type.'.txt', json_encode($data));
    }
    
    // Gets the total Firefox 3.6 downloads
    public function firefox_downloads() {
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
    public function amo() {
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
    public function weather() {
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
    public function caltrain() {
        include 'caltrain.php';
        
        $schedule = date('N') >= 6 ? 'weekends' : 'weekdays';
        
        return $caltrain[$schedule];
    }
    
    // Gets the latest tweets that mention firefox, #firefox, @firefox, or mozilla
    public function firefox_tweets() {
        $xml = $this->load_url('http://search.twitter.com/search.atom?lang=en&q=%40firefox+OR+%23firefox+OR+firefox+OR+mozilla');
        
        $data = new SimpleXMLElement($xml);
        $tweets = array();
        
        foreach ($data as $item) {
            if (empty($item->content)) continue;
            $tweets[] = array(
                'text' => (string) $item->content,
                'author' => (string) $item->author->name,
                'avatar' => (string) $item->link[1]->attributes()->href,
                'date' => (string) $item->published
            );
        }
        
        return $tweets;
    }
    
    // curl utility function to fetch a URL and return the output
    public function load_url($url, $post = '') {
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