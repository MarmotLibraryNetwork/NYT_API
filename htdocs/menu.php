<?php
/**
 * 
 * 
 * 
 */
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

// Do initial application-wide set up
require_once 'bootstrap.php';

require_once('nyt-api.php');
require_once('display-nyt-lists.php');

$api_key = $config['books_API_key'];
// instantiate class with api key
$nyt_api = new NYT_API($api_key);
// instantiate display class with api instance
$display_lists = new Display_NYT_Lists($nyt_api);

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>NYT API Demo</title>
    <meta name="description" content="NYT API Demo">
    <meta name="author" content="Code Club">
    <style>
        ul.list {
            list-style: none;
            margin: 0 20px 20px 20px;
            padding: 0;
        }
        ul.list li {
            clear: both;
            overflow: auto;
            margin: 10px 0;
            padding: 0;
            border-bottom: 1px solid #eee;
        }
        ul.list li img {
            width: 100px;
            height: auto;
            float: left;
            margin: 0 10px 10px 0;
        }
        ul.list li .title {
            font-weight: bold;
        }
        ul.list li .author {
            font-weight: bold;
        }
        ul.list li .description {

        }
    </style>
</head>

<body>
    <h1>NYT API Demo</h1>
 
    <?php
        $display_lists->ouput();
    ?>
</body>
</html>
