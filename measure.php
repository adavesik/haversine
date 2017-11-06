<?php
/**
 * Created by PhpStorm.
 * User: Sevada Ghazaryan
 */
require 'config.php';
require 'classes/class.database.php';

if(isset($_POST['id']) && !empty($_POST['id']))
{
    /***
     * param used to save HTML code of genereated table
     */
    $tableData = "<table class='table table-bordered'>
  <thead>
    <tr>
      <th scope='col'>Distance < 5 Km</th>
      <th scope='col'>Distance From 5 Km to 30 Km</th>
      <th scope='col'>Distance more than 30 Km</th>
    </tr>
  </thead>
  <tbody>";


    define("DIST_UNIT", 111.045);

    $cordinates = getCoordinatesByID($_POST['id']);
    $upTo5 = distanceUpTo5km($cordinates['address_cord_lat'], $cordinates['address_cord_lon']);
    $from5To30 = distanceFrom5To30km($cordinates['address_cord_lat'], $cordinates['address_cord_lon']);
    $from30ToInf = distanceUpTo30km($cordinates['address_cord_lat'], $cordinates['address_cord_lon']);


    //iterate thorugh three arrays
    $mi = new MultipleIterator(MultipleIterator::MIT_NEED_ANY);
    $mi->attachIterator(new ArrayIterator($upTo5));
    $mi->attachIterator(new ArrayIterator($from5To30));
    $mi->attachIterator(new ArrayIterator($from30ToInf));

    //generate final table rows
    foreach ( $mi as $value ) {
        list($km5, $from5, $inf) = $value;
        $tableData.="<tr>
      <td>$km5[address_street] $km5[address_address] ($km5[distance] km)</td>
      <td>$from5[address_street] $from5[address_address] ($from5[distance] km)</td>
      <td>$inf[address_street] $inf[address_address] ($inf[distance] km)</td>
    </tr>";
    }

    echo $tableData;

}

/***
 * @param $id
 * @return mixed
 */
function getCoordinatesByID($id){

    $database = new Database();
    $database->query('SELECT address_cord_lat, address_cord_lon FROM address WHERE id = :id');
    $database->bind(':id', $id);

    if (!empty($database)) {
        $row = $database->single();
    }

    if (!empty($row)) {
        return $row;
    }
}


/******************************nearest-location finder. Used Haversine formula https://en.wikipedia.org/wiki/Haversine_formula **********************************/


/***
 * function returns all buildings located not far as 5km from given cordinates
 *
 * @param $latitude
 * @param $longitude
 * @return mixed
 */
function distanceUpTo5km($latitude, $longitude){

    $database = new Database();
    $database->query("SELECT *
  FROM (
 SELECT z.*,
        p.radius,
        p.distance_unit
                 * DEGREES(ACOS(COS(RADIANS(p.latpoint))
                 * COS(RADIANS(z.address_cord_lat))
                 * COS(RADIANS(p.longpoint - z.address_cord_lon))
                 + SIN(RADIANS(p.latpoint))
                 * SIN(RADIANS(z.address_cord_lat)))) AS distance
  FROM address AS z
  JOIN (   /* these are the query parameters */
        SELECT  $latitude  AS latpoint,  $longitude AS longpoint,
                5.0 AS radius,      111.045 AS distance_unit
    ) AS p
  WHERE z.address_cord_lat
     BETWEEN p.latpoint  - (p.radius / p.distance_unit)
         AND p.latpoint  + (p.radius / p.distance_unit)
    AND z.address_cord_lon
     BETWEEN p.longpoint - (p.radius / (p.distance_unit * COS(RADIANS(p.latpoint))))
         AND p.longpoint + (p.radius / (p.distance_unit * COS(RADIANS(p.latpoint))))
 ) AS d
 WHERE distance <= radius
 ORDER BY distance
LIMIT 150");

    $rows = $database->resultset();

    if (!empty($rows)) {
       return $rows;
    }

}


/***
 * function returns all buildings located etween 5km and 30km from given cordinates
 *
 * @param $latitude
 * @param $longitude
 * @return mixed
 */
function distanceFrom5To30km($latitude, $longitude){

    $database = new Database();
    $database->query("SELECT *
  FROM (
 SELECT z.*,
        p.radius,
        p.distance_unit
                 * DEGREES(ACOS(COS(RADIANS(p.latpoint))
                 * COS(RADIANS(z.address_cord_lat))
                 * COS(RADIANS(p.longpoint - z.address_cord_lon))
                 + SIN(RADIANS(p.latpoint))
                 * SIN(RADIANS(z.address_cord_lat)))) AS distance
  FROM address AS z
  JOIN (   /* these are the query parameters */
        SELECT  $latitude  AS latpoint,  $longitude AS longpoint,
                30.0 AS radius,      111.045 AS distance_unit
    ) AS p
  WHERE z.address_cord_lat
     BETWEEN p.latpoint  - (p.radius / p.distance_unit)
         AND p.latpoint  + (p.radius / p.distance_unit)
    AND z.address_cord_lon
     BETWEEN p.longpoint - (p.radius / (p.distance_unit * COS(RADIANS(p.latpoint))))
         AND p.longpoint + (p.radius / (p.distance_unit * COS(RADIANS(p.latpoint))))
 ) AS d
 WHERE distance <= radius AND distance > 5
 ORDER BY distance
LIMIT 110");

    $rows = $database->resultset();

    if (!empty($rows)) {
        return $rows;
    }

}

/***
 * function returns all buildings located up to 30km from given cordinates
 *
 * @param $latitude
 * @param $longitude
 * @return mixed
 */
function distanceUpTo30km($latitude, $longitude){

    $database = new Database();
    $database->query("SELECT *
  FROM (
 SELECT z.*,
        p.radius,
        p.distance_unit
                 * DEGREES(ACOS(COS(RADIANS(p.latpoint))
                 * COS(RADIANS(z.address_cord_lat))
                 * COS(RADIANS(p.longpoint - z.address_cord_lon))
                 + SIN(RADIANS(p.latpoint))
                 * SIN(RADIANS(z.address_cord_lat)))) AS distance
  FROM address AS z
  JOIN (   /* these are the query parameters */
        SELECT  $latitude  AS latpoint,  $longitude AS longpoint,
                30.0 AS radius,      111.045 AS distance_unit
    ) AS p
  WHERE z.address_cord_lat
     BETWEEN p.latpoint  - (p.radius / p.distance_unit)
         AND p.latpoint  + (p.radius / p.distance_unit)
    AND z.address_cord_lon
     BETWEEN p.longpoint - (p.radius / (p.distance_unit * COS(RADIANS(p.latpoint))))
         AND p.longpoint + (p.radius / (p.distance_unit * COS(RADIANS(p.latpoint))))
 ) AS d
 WHERE distance > radius
 ORDER BY distance
LIMIT 150");

    $rows = $database->resultset();

    if (!empty($rows)) {
        return $rows;
    }

}