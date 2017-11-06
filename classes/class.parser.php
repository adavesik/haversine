<?php
/**
 * Created by PhpStorm.
 * User: Sevada Ghazaryan
 */
class parser
{
    /***
     * @var
     */
    private $destinationPath;

    /***
     * @var array
     */
    private $xmlFileNames = array();


    /***
     * sets path
     *
     * @param $path
     */
    public function setDir($path){
        $this->destinationPath  =   $path;
    }

    /***
     * function returns all uploaded file names from uploaded directory
     *
     * @return array
     *
     *
     * TODO
     * 1. set uploaded dir as a param
     */
    public function getXMLFileNames(){
        // Create recursive dir iterator which skips dot folders
        $dir = new RecursiveDirectoryIterator($this->destinationPath,FilesystemIterator::SKIP_DOTS);
        // Flatten the recursive iterator, folders come before their files
        $it  = new RecursiveIteratorIterator($dir,RecursiveIteratorIterator::SELF_FIRST);
        // Maximum depth is 1 level deeper than the base folder
        $it->setMaxDepth(1);
        // Basic loop displaying different messages based on file or folder
        foreach ($it as $fileinfo) {
            if ($fileinfo->isFile()) {
                array_push($this->xmlFileNames, $fileinfo->getFilename());
                //printf("File From %s - %s\n", $it->getSubPath(), $fileinfo->getFilename());
            }
            else{
                continue;
            }
        }
        //print_r($this->xmlFileNames);
        return $this->xmlFileNames;
    }

    /***
     * this function just parses given XML files
     *
     * @param array $fileNames
     * @return array
     */

    public function parseXML($fileNames){
        $addresses = array();

        foreach ($fileNames as $fileName){
            if (file_exists($this->destinationPath.$fileName)) {
                $xml = simplexml_load_string(file_get_contents($this->destinationPath . $fileName));

                foreach ($xml->addresses as $address) {

                    $addresses[] = array('addresses_address'=>(string)$address->addresses_address,
                        'addresses_street'=>(string)$address->addresses_street,
                        'addresses_cord_y'=>(string)$address->addresses_cord_y,
                        'addresses_cord_x'=>(string)$address->addresses_cord_x);

                    //echo '<pre>' . print_r($addresses,1) . '</pre>';

                    /*echo "addresses_address ---- " . $address->addresses_address . "</br>";
                    echo "addresses_street ---- " . $address->addresses_street . "</br>";
                    echo "addresses_cord_y ---- " . $address->addresses_cord_y . "</br>";
                    echo "addresses_cord_x ---- " . $address->addresses_cord_x . "</br>";*/
                    //echo "<hr>";
                }
            } else {
                exit('Failed to open xml.');
            }
            return $addresses;
        }

    }

}