<?php include './template/header.php' ?>

<?php
  // conexion a la DB 
  include_once './model/conexion.php';
  // obtengo los usuarios activos almacenados
  $sentencia = $bd -> query("SELECT * FROM usuarios WHERE activo = 1;");
  $usuarios = $sentencia -> fetchAll(PDO::FETCH_OBJ);
  // obtengo los roles almacenados
  $sentencia = $bd -> query("SELECT * FROM roles;");
  $roles = $sentencia -> fetchAll(PDO::FETCH_OBJ);
?>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-7">
      <!-- distintos tipos de alertas -->
      <?php
        if(isset($_GET['mensaje']) && $_GET['mensaje'] == 'falta'){
      ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <strong>Error!</strong> Todos los campos son requeridos.
      </div>
      <?php
        }
      ?>
      <?php
        if(isset($_GET['mensaje']) && $_GET['mensaje'] == 'registrado'){
      ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <strong>Registrado!</strong> El usuario se agreg&oacute; exitosamente.
      </div>
      <?php
        }
      ?>
      <?php
        if(isset($_GET['mensaje']) && $_GET['mensaje'] == 'error'){
      ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <strong>Error!</strong> Vuelve a intentar.
      </div>
      <?php
        }
      ?>
      <?php
        if(isset($_GET['mensaje']) && $_GET['mensaje'] == 'editado'){
      ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <strong>Actualizado!</strong> Los datos del usuario fueron actualizados.
      </div>
      <?php
        }
      ?>
      <?php
        if(isset($_GET['mensaje']) && $_GET['mensaje'] == 'eliminado'){
      ?>
      <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <strong>Eliminado!</strong> El usuario se borr&oacute; exitosamente.
      </div>
      <?php
        }
      ?>
      <div class="card">
        <div class="card-header">
          Lista de Usuarios
        </div>
        <div class="p-4">
          <table class="table ">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Nombre</th>
                <th scope="col">Apellido</th>
                <th scope="col">Domicilio</th>
                <th scope="col">Roles</th>
                <th scope="col" colspan="2">Opciones</th>
              </tr>
            </thead>
            <tbody>
              <?php
                foreach($usuarios as $dato){
                  // obtengo los roles asignados al usuario
                  $idUsuario = $dato -> id;
                  $sentencia = $bd -> prepare("SELECT b.nombre FROM usuarios_roles a JOIN roles b on a.id_rol = b.id WHERE id_usuario = ?;");
                  $sentencia -> execute([$idUsuario]);
                  $usuariosRoles = $sentencia -> fetchAll(PDO::FETCH_OBJ);
                  $rolesSeleccionados = "";
                  foreach($usuariosRoles as $usuarioRol){
                    if ($rolesSeleccionados == ""){
                      $rolesSeleccionados = $rolesSeleccionados. $usuarioRol -> nombre ;
                    } else {
                      $rolesSeleccionados = $rolesSeleccionados. "," . $usuarioRol -> nombre ;
                    }
                  }
              ?>
              <tr>
                <td scope="row"><?php echo $dato -> id; ?></td>
                <td><?php echo $dato -> nombre; ?></td>
                <td><?php echo $dato -> apellido; ?></td>
                <td><?php echo $dato -> domicilio; ?></td>
                <td><?php echo $rolesSeleccionados; ?></td>
                <td><a class="text-success" href="editar.php?codigo=<?php echo $dato -> id; ?>"><i class="bi bi-pencil-square"></i></a></td>
                <td><a class="text-danger" href="eliminar.php?codigo=<?php echo $dato -> id; ?>" onclick="return confirm('Desea eliminar el usuario?')"><i class="bi bi-trash"></i></a></td>
              </tr>
              <?php
                }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          Ingresar datos del usuario:
        </div>
        <form action="registrar.php" class="p-4" method="POST">
          <div class="mb-3">
            <label class="form-label">Nombre: </label>
            <input type="text" class="form-control" name="txtNombre" autofocus required>
          </div>
          <div class="mb-3">
            <label class="form-label">Apellido: </label>
            <input type="text" class="form-control" name="txtApellido" autofocus required>
          </div>
          <div class="mb-3">
            <label class="form-label">Domicilio: </label>
            <input type="text" class="form-control" name="txtDomicilio" autofocus required>
          </div>
          <div class="mb-3">
            <label class="form-label">Roles: </label>
            <br>
            <?php
              foreach ($roles as $rol) {
            ?>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox<?php echo $rol -> id; ?>" name="checkRoles[]" value="<?php echo $rol -> id; ?>">
              <label class="form-check-label" for="inlineCheckbox<?php echo $rol -> id; ?>"><?php echo $rol -> nombre; ?></label>
            </div>
            <?php
                }
            ?>
          </div>
          <div class="d-grid">
            <input type="hidden" name="oculto" value="1">
            <input type="submit" class="btn btn-primary" value="Registrar">
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include './template/footer.php' ?>