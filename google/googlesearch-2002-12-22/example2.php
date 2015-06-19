<?php

/*
* Example to access Google cached pages through GoogleSearch for PHP.
*/

require_once 'GoogleSearch.php';

$gs = new GoogleSearch();

//set Google licensing key
$gs->setKey("your_google_authentication_key");

$page_result = $gs->doGetCachedPage("http://www.google.com");

if(!$page_result)
{
	if($err = $gs->getError())
	{
		echo "<br>Error: " . $err;
	}
}
else
{
	echo "Requested Page: " . $page_result;
}

?>
