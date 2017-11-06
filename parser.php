<?php
/**
 * Parses XML files. Get from them required nodes --

 * <addresses_address>
 * <addresses_street>
 * <addresses_cord_y>
 * <addresses_cord_x>
 *
 * and saves in DB
 *
 * Created by PhpStorm.
 * User: Sevada Ghazaryan
 */

require 'classes/class.parser.php';
require 'classes/class.database.php';
require 'config.php';


$database = new Database();
$parser =   new parser();


$parser->setDir(UPLOAD_DIR);
$fn = $parser->getXMLFileNames();
$addr = $parser->parseXML($fn);

/*
 * Here I'm used transactions since its
 * allows you to run multiple changes to a database
 * all in one batch to ensure that your work will not be accessed incorrectly
 * or there will be no outside interferences before you are finished.
 */
$database->beginTransaction();

foreach ($addr as $ad) {
    //echo '<pre>' . print_r($ad,1) . '</pre>';

    $database->query('INSERT INTO address (address_address, address_street, address_cord_lat, address_cord_lon) VALUES (:address, :street, :lat, :lon)');
    $database->bind(':address', $ad['addresses_address']);
    $database->bind(':street', $ad['addresses_street']);
    $database->bind(':lat', $ad['addresses_cord_y']);
    $database->bind(':lon', $ad['addresses_cord_x']);
    $database->execute();
}
//echo $database->lastInsertId();
$database->endTransaction();
