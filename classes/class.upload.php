<?php
/**
 * upload class
 * Created by PhpStorm.
 * User: Sevada Ghazaryan
 */


class Uploader
{
    /***
     * Destination directory
     *
     * @var
     */
    private $destinationPath;

    /***
     * @var
     */
    private $errorMessage;

    /***
     * Allowed types
     *
     * @var
     */
    private $extensions;

    /***
     * @var
     */
    private $allowAll;

    /***
     * Max. file size
     *
     * @var
     */
    private $maxSize;

    /***
     * @var
     */
    private $uploadName;

    /***
     * @var string
     */
    public $name='Uploader';

    /***
     * @var bool
     */
    public $useTable    =false;

/*****************************************/


    /***
     * @param $path
     */
    public function setDir($path){
        $this->destinationPath  =   $path;
        $this->allowAll =   false;
    }

    /***
     *
     */
    public function allowAllFormats(){
        $this->allowAll =   true;
    }


    /***
     * @param $sizeMB
     */
    public function setMaxSize($sizeMB){
        $this->maxSize  =   $sizeMB * (1024*1024);
    }

    /***
     * @param $options
     */
    public function setExtensions($options){
        $this->extensions   =   $options;
    }

    /***
     *
     */
    public function setSameFileName(){
        $this->sameFileName =   true;
        $this->sameName =   true;
    }

    /***
     * @param $string
     * @return string
     */
    public function getExtension($string){
        $ext    =   "";
        try{
            $parts  =   explode(".",$string);
            $ext        =   strtolower($parts[count($parts)-1]);
        }catch(Exception $c){
            $ext    =   "";
        }
        return $ext;
    }

    /***
     * @param $message
     */
    public function setMessage($message){
        $this->errorMessage =   $message;
    }

    /***
     * @return mixed
     */
    public function getMessage(){
        return $this->errorMessage;
    }

    /***
     * @return mixed
     */
    public function getUploadName(){
        return $this->uploadName;
    }

    /***
     * @param $seq
     */
    public function setSequence($seq){
        $this->imageSeq =   $seq;
    }

    /***
     * @return string
     */
    public function getRandom(){
        return strtotime(date('Y-m-d H:i:s')).rand(1111,9999).rand(11,99).rand(111,999);
    }

    /***
     * @param $true
     */
    public function sameName($true){
        $this->sameName =   $true;
    }

    /***
     * @param $fileBrowse
     * @return bool
     */
    public function uploadFile($size_arg, $name_arg, $tmpName_arg){
        $result =   false;
        $size   =   $size_arg;
        $name   =   $name_arg;
        $ext    =   $this->getExtension($name);
        if(!is_dir($this->destinationPath)){
            $this->setMessage("Destination folder is not a directory ".$this->destinationPath);
        }else if(!is_writable($this->destinationPath)){
            $this->setMessage("Destination is not writable !");
        }else if(empty($name)){
            $this->setMessage("File not selected ");
        }else if($size>$this->maxSize){
            $this->setMessage("Too large file !");
        }else if($this->allowAll || (!$this->allowAll && in_array($ext,$this->extensions))){

            $this->uploadName=  $name;

/*            if($this->sameName==false){
                $this->uploadName   =  $name; // renaming file????? $this->imageSeq."-".substr(md5(rand(1111,9999)),0,8).$this->getRandom().rand(1111,1000).rand(99,9999).".".$ext;
            }else{
                $this->uploadName=  $name;
            }*/
            if(move_uploaded_file($tmpName_arg,$this->destinationPath.$this->uploadName)){
                $result =   true;
            }else{
                $this->setMessage("Upload failed , try later !");
            }
        }else{
            $this->setMessage("Invalid file format !");
        }
        return $result;
    }


}

?>