<?php
class database {
    private $hostname= "localhost";
    private $database= "dbformulario";
    private $username= "root";
    private $password= "";
    private$charset= "utf8";

    function conectar(){
        try{
        
            $conexion = "mysql:host=" .$this->hostname .";dbname=".$this->database.";charset=".$this->charset;

            $options =  [
                PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES=>false
            ];
            $pdo=new PDO($conexion,$this->username,$this->password,$options);
            /*echo 'Conexión exitosa';*/
            return $pdo;
            
    }catch(PDOException $e){
        echo'error conexion:'. $e->getMessage();
        exit;
    }
 }
}

