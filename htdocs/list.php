<?php
/**
 * Display a list of available lists from the NYT API and allow the user to find the list in Pika and create it if it doesn't exist
 *
 * @category NYT_API
 * @author Mark Noble <mark@marmot.org>
 * Date: 3/28/2016
 * Time: 12:07 PM
 */

ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

// Do initial application-wide set up
require_once 'bootstrap.php';
global $config;

require_once('nyt-api.php');

$api_key = $config['books_API_key'];
// instantiate class with api key
$nyt_api = new NYT_API($api_key);

//Get the raw response from the API with a list of all the names
$availableListsRaw = $nyt_api->get_list('names');
//Convert into an object that can be processed
$availableLists = json_decode($availableListsRaw);

//If the user has selected a specific list, check Pika to see if a list exists already
$isListSelected = isset($_GET['selectedList']);
$selectedList = null;
if ($isListSelected){
	$selectedList = $_GET['selectedList'];

	//Check Pika to see if the list has been created
	//  currently we have 2 options:
	//  1) Get a list of all public lists (for all people)
	//  2) Get a list of lists for a particular account
	$pikaUsername = $config['pika_username'];
	$pikaPassword = $config['pika_password'];
	$pikaUrl = $config['pika_url'];

	//Call Pika to get a list of all lists for our username
	$apiUrl = $pikaUrl . "/API/ListAPI?method=getUserLists&username=" . urlencode($pikaUsername) . "&password=" . urlencode($pikaPassword);
	$getUserListResults = file_get_contents($apiUrl);

	$getUserListResultsJSON = json_decode($getUserListResults);
	//Loop through the set of all lists to see if we have one by this name
	$listExistsInPika = false;
	foreach ($getUserListResultsJSON->result->lists as $listName){
		if ($listName == $selectedList){
			$listExistsInPika = true;
		}
	}
}

?>
<html>
	<head>
		<title>Create Lists in Pika for NYT List</title>
	</head>
	<body>
		<h1>Create Lists in Pika for NYT List</h1>
		<?php
			if ($isListSelected && !$listExistsInPika){
				echo("The list did not exist in Pika");
			}
		?>
		<form action="list.php" method="get">
			<label for="">Pick a NYT list to build a Pika list for: </label>
			<!-- Give the user a list of all available lists from NYT -->
			<select name="selectedList">
				<?php
					foreach ($availableLists->results as $listInfo){
						//Make the list selected within the dropdown if the user has selected something already
						$selected = '';
						if ($isListSelected && $listInfo->list_name_encoded == $selectedList){
							$selected = "selected='selected'";
						}
						echo("<option value='{$listInfo->list_name_encoded}' $selected>{$listInfo->display_name}</option>");
					}
				?>
			</select>
			<button type="submit">Find/Create List</button>
		</form>
	</body>
</html>
