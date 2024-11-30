<?php
require_once 'conexiondb.php';
require_once 'clientefunciones.php';

$datos = [];

if(isset($_POST['action'])){
    $action = $_POST['action'];

    $db = new Database();
    $con = $db->conectar();

    if ($action == 'existeUsuario'){
     
        $datos['ok'] = usuarioExiste($_POST['usuario'],$con); 

    }elseif($action = 'existeEmail'){
        $datos['ok'] = emailExiste($_POST['email'],$con);
    }

}
echo json_encode($datos);