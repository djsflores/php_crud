<?php include './template/header.php' ?>

<?php
  // validacion de datos recibidos
  if (!isset($_GET['codigo'])) {
    header('Location: index.php?mensaje=error');
    exit();
  }
  // conexion a la DB
  include_once './model/conexion.php';
  // seteo de variables
  $codigo = $_GET['codigo'];
  // obtengo los datos del usuario
  $sentencia = $bd -> prepare("select * from usuarios where id = ?;");
  $sentencia -> execute([$codigo]);
  $usuario = $sentencia -> fetch(PDO::FETCH_OBJ);
  // obtengo los roles y adicionalmente si lo tiene asignado el usuario
  $sentencia = $bd -> prepare("SELECT a.id, a.nombre, b.id_rol FROM roles a LEFT JOIN (SELECT * FROM usuarios_roles WHERE id_usuario = ? ) b on a.id = b.id_rol;");
  $sentencia -> execute([$codigo]);
  $rolesUsuario = $sentencia -> fetchAll(PDO::FETCH_OBJ);
?>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          Editar datos:
        </div>
        <form action="actualizar.php" class="p-4" method="POST">
          <div class="mb-3">
            <label class="form-label">Nombre: </label>
            <input type="text" class="form-control" name="txtNombre" required value="<?php echo $usuario -> nombre; ?>">
          </div>
          <div class="mb-3">
            <label class="form-label">Apellido: </label>
            <input type="text" class="form-control" name="txtApellido" required value="<?php echo $usuario -> apellido; ?>">
          </div>
          <div class="mb-3">
            <label class="form-label">Domicilio: </label>
            <input type="text" class="form-control" name="txtDomicilio" required value="<?php echo $usuario -> domicilio; ?>">
          </div>
          <div class="mb-3">
            <label class="form-label">Roles: </label>
            <br>
            <?php
              foreach ($rolesUsuario as $rolUsuario) {
            ?>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox<?php echo $rolUsuario -> id; ?>" name="checkRoles[]" value="<?php echo $rolUsuario -> id; ?>" <?php echo isset($rolUsuario -> id_rol) ? 'checked' : '' ?> >
              <label class="form-check-label" for="inlineCheckbox<?php echo $rolUsuario -> id; ?>"><?php echo $rolUsuario -> nombre; ?></label>
            </div>
            <?php
                }
            ?>
          </div>
          <div class="d-grid">
            <input type="hidden" name="codigo" value="<?php echo $usuario -> id; ?>">
            <input type="submit" class="btn btn-primary" value="Editar">
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include './template/footer.php' ?>