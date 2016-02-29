<?php

// sanity check output to screen
echo("NYT API Demo<br>");

// include the class which does the work
require_once('nyt-api.php');

// define API key
$api_key = 'ab6166b63457ed361bf8af27ef9b8ef0:10:66006911';

// choose which list to display
$list_name = 'names'; // returns the names of Times best-seller lists.
#$list_name = 'overview'; // returns overview of week's best-seller lists.
#$list_name = 'hardcover-fiction'; // returns Hardcover Fiction list.

// instantiate class with api key
$nyt_api = new NYT_API($api_key);

// get JSON data
$json = $nyt_api->get_list($list_name);

// parse JSON into PHP array
$array = json_decode($json, true);

echo("Results from API request for: $list_name<br>");

// Output PHP array data in human readable format
echo("<pre>".print_r($array,true)."</pre>");

// all done
echo("The End!");