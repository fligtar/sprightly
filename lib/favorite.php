<?php
/*
    This script takes a Tweet id and retweets it from the favorites account
    designated in config.
*/

require dirname(__FILE__).'/sprightly.php';
require dirname(__FILE__).'/twitteroauth/twitteroauth.php';
$connection = new TwitterOAuth(FAVORITES_CONSUMER_KEY, FAVORITES_CONSUMER_SECRET, OAUTH_TOKEN, OAUTH_TOKEN_SECRET);

$id = $_GET['id'];

$response = $connection->post('statuses/retweet/'.$id.'.xml', array('id' => $id);

$data = new SimpleXMLElement($response);

if (!empty($data->error)) {
    $json['error'] = (string) $data->error;
}
else {
    $json['error'] = false;
}

echo json_encode($json);
?>