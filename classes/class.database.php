<?php

/**
 * Created by PhpStorm.
 * User: Sevada Ghazaryan
 */
class Database
{
    private $host      = DB_HOST;
    private $user      = DB_USER;
    private $pass      = DB_PASS;
    private $dbname    = DB_NAME;

    private $dbh;
    private $error;

    private $stmt;

    public function __construct(){
        // Set DSN
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname .';charset=utf8' ;
        // Set options
        $options = array(
            PDO::ATTR_PERSISTENT    => true,
            PDO::ATTR_ERRMODE       => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"
        );
        // Create a new PDO instanace
        try{
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        }
            // Catch any errors
        catch(PDOException $e){
            echo $this->error = $e->getMessage();
        }
    }

    /***
     * @param $query
     */
    public function query($query){
        $this->stmt = $this->dbh->prepare($query);
    }

    /***
     * We need to bind the inputs with the placeholders we put in place
     * @param $param
     * @param $value
     * @param null $type
     */
    public function bind($param, $value, $type = null){
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    /***
     * method executes the prepared statement
     *
     * @return mixed
     */
    public function execute(){
        return $this->stmt->execute();
    }

    /***
     * function returns an array of the result set rows
     *
     * @return mixed
     */
    public function resultset(){
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /***
     * method simply returns a single record from the database
     *
     * @return mixed
     */
    public function single(){
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    /***
     * method returns the number of effected rows from the previous action
     *
     * @return mixed
     */
    public function rowCount(){
        return $this->stmt->rowCount();
    }


    /***
     * method returns the last inserted Id as a string
     *
     * @return string
     */
    public function lastInsertId(){
        return $this->dbh->lastInsertId();
    }


    /***
     * allows run multiple changes to a database all in one batch
     * begin
     *
     * @return bool
     */
    public function beginTransaction(){
        return $this->dbh->beginTransaction();
    }

    /***
     * allows run multiple changes to a database all in one batch
     * end a transaction and commit changes
     *
     * @return bool
     */
    public function endTransaction(){
        return $this->dbh->commit();
    }


    /***
     * roll back
     *
     * @return bool
     */
    public function cancelTransaction(){
        return $this->dbh->rollBack();
    }

}