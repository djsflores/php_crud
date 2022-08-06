<?php 
$contrasena = "";
$usuario = "root";
$nombre_bd = "php_crud";

try {
	$bd = new PDO (
		'mysql:host=127.0.0.1:3307;
		dbname='.$nombre_bd,
		$usuario,
		$contrasena,
		array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
	);
} catch (Exception $e) {
	echo "Problema con la conexion: ".$e->getMessage();
}
?>