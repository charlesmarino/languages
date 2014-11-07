<?php

function translate($translate_to, $matching_ids, $dbc){
        // Represents the id name in the table so for Russian it
        // would be russian_id
        $translate_id = $translate_to . '_id';

/* QUERY:
SELECT russian.word 
FROM russian 
JOIN(
SELECT 131 AS id, 1 AS word_order
UNION ALL SELECT 26, 2
UNION ALL SELECT 26, 3
UNION ALL SELECT 69, 4) WordsToSearch ON russian.russian_id = WordsToSearch.id
ORDER BY WordsToSearch.word_order;
*/

        $translate_query = 'SELECT word FROM ' . $translate_to . ' JOIN(' . 
            'SELECT ' . $matching_ids[0] . ' AS id, 1 AS word_order'; 

        $array_size_2 = count($matching_ids);

        for ($i = 1; $i < $array_size_2; $i++) {
            $translate_query = $translate_query . " UNION ALL SELECT '" . $matching_ids[$i] .
            "', " . ($i + 1) . " ";
        }

        $translate_query = $translate_query . ') WordsToSearch ON ' .
            $translate_to . '.' . $translate_id . ' = WordsToSearch.id
            ORDER BY WordsToSearch.word_order;';

        // END OF NEW TRANSLATE QUERY

        // Issue the query to the database
        $translate_response = mysqli_query($dbc, $translate_query);

        if($translate_response){

                while($row = mysqli_fetch_array($translate_response)){

                        $translated_text = $translated_text . ' ' . $row['word'];

                }

        }

        return $translated_text;

} // Close function translate

function get_translation($translate_to, $english_words){
        // Trim white space from the name and store the name
        $english_words = trim($english_words);
        $english_array = array();

        // Break the words into an array
        $english_array = explode(" ", $english_words);

        // Get a connection to the database
        require_once('../mysqli_connect_languages.php');

        // Set character set in PHP to get proper characters
        $dbc->set_charset("utf8");
/*QUERY:

UNION ALL combines the result set of multiple SELECT statements
which makes sure we receive all rows even if there are 
duplicates

SELECT english.word
FROM english
JOIN (
SELECT 'A' AS word, 1 AS word_order
UNION ALL SELECT 'dog', 2
UNION ALL SELECT 'a', 3
UNION ALL SELECT 'cat', 4) WordsToSearch ON english.word = WordsToSearch.word
ORDER BY WordsToSearch.word_order;
*/

        $query = "SELECT english_id, english.word FROM english JOIN(
        SELECT '" . $english_array[0] . "' AS word, 1 AS word_order";

        // Get the size of the array
        $array_size = count($english_array);

        for ($i = 1; $i < $array_size; $i++) {
            $query = $query . " UNION ALL SELECT '" . $english_array[$i] .
            "', " . ($i + 1) . " ";
        }
       $query = $query . ") WordsToSearch ON english.word = WordsToSearch.word
ORDER BY WordsToSearch.word_order;";

        // END OF THE NEW QUERY -------------------
        // Issue the query to the database
        $response = @mysqli_query($dbc, $query);

        // Array that contains the matching ids in order
        $matching_ids = array();

        if($response){

            while($row = mysqli_fetch_array($response)){

                $matching_ids[] = $row['english_id'];

                // Holds the array after the select
                $array_after_query[] = $row['word'];

            }

        } // Close if($response)
        return translate($translate_to, $matching_ids, $dbc);

}

if(isset($_GET["action"])){
    $translate_to = $_GET["language"]; 
    $english_words  = urldecode($_GET["english_words"]); 
    $translated_text = get_translation($translate_to, $english_words);

}

exit($translated_text);

?>
