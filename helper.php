<?php
/**
 * Helper PHP file
 * this file just returns uploaded file list
 *
 * this is just for information
 *
 * Created by PhpStorm.
 * User: Sevada Ghazaryan
 */

require 'classes/class.parser.php';
require 'classes/class.upload.php';
require 'config.php';

if(isset($_POST['data']) && !empty($_POST['data'])){
    $list = '';
    $parser = new parser();
    $parser->setDir(UPLOAD_DIR);

    $fileList = $parser->getXMLFileNames();                    //get file list as an array

    foreach ($fileList as $name){
        $list .= "<li class='list-group-item'>$name</li>";     //bulid list

    }
    echo $list;
}