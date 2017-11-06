<?php
/***
 * Uploads file
 *
 */

require 'classes/class.upload.php';
require 'config.php';

//print_r($_FILES);
$size   =   $_FILES['file']["size"];
$name   =   $_FILES['file']["name"];
$tmpName =  $_FILES['file']["tmp_name"];

    $uploader = new Uploader();
    $uploader->setDir(UPLOAD_DIR);
    $uploader->setExtensions(array('xml'));                //allowed extensions list
    $uploader->setMaxSize(5);                              //set max file size to be allowed in MB


    if ($uploader->uploadFile($size, $name, $tmpName)) {   //xmlFile is the filebrowse element name
        $myFile = $uploader->getUploadName();              //get uploaded file name, renames on upload

        echo json_encode($myFile);

    } else {                                               //upload failed
        echo json_encode($uploader->getMessage());         //get upload error message
    }

?>