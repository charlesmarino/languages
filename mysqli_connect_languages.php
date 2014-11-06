//SAMPLE
<?php
// Defined as constants so that they can't be changed
DEFINE ('DB_USER', 'SOMETHING');
DEFINE ('DB_PASSWORD', 'SOMETHING');
DEFINE ('DB_HOST', 'localhost');
DEFINE ('DB_NAME', 'language');

// $dbc will contain a resource link to the database
// @ keeps the error from showing in the browser

$dbc = @mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
OR die('Could not connect to MySQL: ' .
mysqli_connect_error());
?>