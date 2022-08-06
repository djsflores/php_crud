<?php
  // validacion de datos recibidos
  if (!isset($_POST['codigo'])) {
    header('Location: index.php?mensaje=error');
    exit();
  }
  // conexion a la DB
  include_once './model/conexion.php';
  // seteo de variables
  $nombre = $_POST["txtNombre"];
  $apellido = $_POST["txtApellido"];
  $domicilio = $_POST["txtDomicilio"];
  $codigo = $_POST["codigo"];
  // obtengo los roles asignados al usuario
  $sentencia = $bd -> prepare("SELECT * FROM usuarios_roles WHERE id_usuario = ?;");
  $sentencia -> execute([$codigo]);
  $rolesUsuario = $sentencia -> fetchAll(PDO::FETCH_OBJ);
  // recorro los roles recibidos y cruzo con los roles almacenados
  if (isset($_POST['checkRoles'])){
    foreach($_POST['checkRoles'] as $id){
      // verifico si el usuario no tiene asignado el rol recibido
      $sentencia = $bd -> prepare("SELECT * FROM usuarios_roles WHERE id_usuario = ? and id_rol = ?;");
      $sentencia -> execute([$codigo, $id]);
      $rolUsuario = $sentencia -> fetchAll(PDO::FETCH_OBJ);
      if(count($rolUsuario) === 0 ){
        // el rol no esta asignado al usuario, entonces lo asigno
        $sentencia = $bd -> prepare("INSERT INTO usuarios_roles(id_usuario, id_rol) VALUE (?,?);");
        $resultado = $sentencia -> execute([$codigo, $id]);
      }
    }
  }
  // recorro los roles almacenados y cruzo con los roles recibidos
  foreach ($rolesUsuario as $rolUsuario) {
    // verifico si el rol almacenado esta dentro de los roles recibidos
    if(!in_array($rolUsuario -> id_rol, $_POST['checkRoles'])){
      // el rol almacenado no esta entre lo recibido, entonces no continua
      $sentencia = $bd -> prepare("DELETE FROM usuarios_roles WHERE id = ?;");
      $resultado = $sentencia -> execute([$rolUsuario -> id]);
    }
  }
  // redirecciono una vez concluido el proceso
  if ($resultado === TRUE) {
    header('Location: index.php?mensaje=editado');
  } else {
    header('Location: index.php?mensaje=error');
    exit();
  }


?>