<?php

/*
* Example to access Google spelling suggestion through GoogleSearch for PHP.
*/

require_once 'GoogleSearch.php';

$gs = new GoogleSearch();

//set Google licensing key
$gs->setKey("your_google_authentication_key");


$gs->setSpellingSuggestionPhrase("googgle saerch");

//perform spelling suggestion
$spell_result = $gs->doSpellingSuggestion();

//check for errors
if(!$spell_result)
{
	if(!$err = $gs->getError())
	{
		echo "<br>No spelling suggestions necessary.<br>";
	}
	else
	{
		echo "<br>Error: " . $err;
	}
}
else
{
	echo "<br>Suggested Spelling: " . $spell_result;
}

?>