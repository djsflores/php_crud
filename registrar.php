<?php
  // validacion de datos recibidos
  if (empty($_POST["oculto"]) || empty($_POST["txtNombre"]) || empty($_POST["txtApellido"]) || empty($_POST["txtDomicilio"])) {
    header('Location: index.php?mensaje=falta');
    exit();
  }
  // seteo de variables
  $nombre = $_POST["txtNombre"];
  $apellido = $_POST["txtApellido"];
  $domicilio = $_POST["txtDomicilio"];
  // conexion a la DB
  include_once './model/conexion.php';
  // alta de usuario
  $sentencia = $bd -> prepare("INSERT INTO usuarios(nombre, apellido, domicilio) VALUE (?,?,?);");
  $resultado = $sentencia -> execute([$nombre, $apellido, $domicilio]);
  // guardo el id insertado para utilizarlo en los roles por usuario
  $idUsuario = $bd->lastInsertId();
  // alta de roles por usuario
  $rolesSeleccionados = "";
  if (isset($_POST['checkRoles'])){
    foreach($_POST['checkRoles'] as $id){
      $sentencia = $bd -> prepare("INSERT INTO usuarios_roles(id_usuario, id_rol) VALUE (?,?);");
      $resultado = $sentencia -> execute([$idUsuario, $id]);
    }
  }
  // redirecciono una vez concluido el proceso
  if ($resultado === TRUE) {
    header('Location: index.php?mensaje=registrado');
  } else {
    header('Location: index.php?mensaje=error');
    exit();
  }
?>