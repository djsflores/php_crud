<?php
  // validacion de datos recibidos
  if (!isset($_GET['codigo'])) {
    header('Location: index.php?mensaje=error');
    exit();
  }
  // conexion a la DB
  include_once './model/conexion.php';
  // seteo de variables
  $codigo = $_GET["codigo"];
  // baja de usuario (borrado logico)
  $sentencia = $bd -> prepare("UPDATE usuarios SET activo = 0 WHERE id = ?;");
  $resultado = $sentencia -> execute([$codigo]);
  // redirecciono una vez concluido el proceso
  if ($resultado === TRUE) {
    header('Location: index.php?mensaje=eliminado');
  } else {
    header('Location: index.php?mensaje=error');
    exit();
  }
?>