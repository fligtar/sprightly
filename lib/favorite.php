<?php
/*
    This script takes a Tweet id and retweets it from the favorites account
    designated in config.
*/

require dirname(__FILE__).'/sprightly.php';

$id = $_GET['id'];

$response = sprightly::load_url('http://api.twitter.com/1/statuses/retweet/'.$id.'.xml', 'id='.$id, FAVORITES_TWITTER_USER.':'.FAVORITES_TWITTER_PASS);

$data = new SimpleXMLElement($response);

if (!empty($data->error)) {
    $json['error'] = (string) $data->error;
}
else {
    $json['error'] = false;
}

echo json_encode($json);
?>