<?php
  require 'configuracion.php';
  require 'conexiondb.php';
  require 'clientefunciones.php';
  $id = isset($_GET['id']) ? $_GET['id']:'';
  $token = isset($_GET['token']) ? $_GET['token']:'';
if($id == ''|| $token == ''){
    header("location: registro.php");
    exit;
}
$db = new Database();
$con = $db->conectar();
validaToken($id,$token,$con);
//echo arriba
?>
