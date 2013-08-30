<?php
session_start();
require_once('twitteroauth.php'); //Path to twitteroauth library
 
$twitteruser = "username"; //Twitter display name
$notweets = 4; //Number of tweets
$consumerkey = "CONSUMER_KEY";
$consumersecret = "CONSUMER_KEY_SECRET";
$accesstoken = "ACCESS_TOKEN";
$accesstokensecret = "ACCESS_TOKEN_SECRET";
 
function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
  $connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
  return $connection;
}
  
$connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);
$tweets = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$twitteruser."&count=".$notweets);
 
echo json_encode($tweets);
?>