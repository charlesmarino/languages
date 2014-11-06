<?php

header('Content-Type:application/json; charset=UTF-8');

	$english_words = "This is a test";

	$encoded_words = urlencode($english_words);

	// Get the specific student data
	$translated_words = file_get_contents('http://localhost/languages/translateme.php?action=translate&english_words=' . $encoded_words . '&language=danish');
	echo "test";
	echo $translated_words;

?>
