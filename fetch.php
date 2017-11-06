<?php
/**
 * Fetches data from DB which meet requested value
 *
 * Created by PhpStorm.
 * User: Sevada Ghazaryan
 */

require 'classes/class.database.php';
require 'config.php';


$database = new Database();

$request = $_POST["query"];

/*
 * Here, I'v used MySQL boolean Full-Text Searches in order to search in two columns
 */

$returnValue = str_replace(' ', '* +', $request, $count);       //add necessary search operators

/*
 *  + leading plus sign indicates that this word must be present in each row that is returned
 *   The asterisk (*) serves as the truncation (or wildcard) operator.
 */
$request = '+'.$returnValue.'*';

$database->query("SELECT DISTINCT address_street, address_address, id 
                          FROM address 
                          WHERE MATCH ( address_address, address_street ) AGAINST (:request IN BOOLEAN MODE)");

$term = "$request";
$database->bind(':request', $term);
$rows = $database->resultset();

foreach ($rows as $row){
    $countryResult[] = $row["address_street"].' '.$row["address_address"];            //save returend data in an array
}?>

<?php
/***
 * generating list which will be opened under search box during typing process
 */
foreach($rows as $row) {
    ?>
<div class="list-group">
    <a href="#" class="list-group-item list-group-item-action" onClick="selectCountry(this);" data-id = <?php echo $row["id"]?>><?php echo $row["address_street"].' '.$row["address_address"]; ?></a>
    <?php } ?>
</div>

