<?php

// sanity check output to screen
echo("Basic NYT API Demo<br>");

// define base API url
$base_url = 'http://api.nytimes.com/svc/books/v2/lists/';

// define API key
$api_key = 'key-goes-here';

// define which list to retrieve
$list_name = 'hardcover-fiction';

// build request url
$request_url = $base_url . $list_name . '?api-key=' . $api_key;

// get the response
$response = file_get_contents($request_url);

// Output the response
echo("Results from API request for: $list_name<br>");
var_dump($response);

// all done
echo("The End!");
