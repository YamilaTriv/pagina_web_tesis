<?php require("register.class.php") ?>
<?php

  if(isset($_POST['submit'])){
		$user = new RegisterUser($_POST['username'], $_POST['email'], $_POST['password']);
	}
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	 <link rel="stylesheet" href="styles.css">
	<title>Crear cuenta</title>
</head>
<body>

	<form action="" method="post" enctype="multipart/form-data" autocomplete="off">
		<h2>Crear cuenta</h2>
    <a href="login.php" title="Login"> Inicia sesión </a>
		<h4>Todos los campos son <span>requeridos</span></h4>

		<label>Usuario</label>
		<input type="text" name="username">

		<label>Email</label>
		<input type="email" name="email">

		<label>Contraseña</label>
		<input type="password" name="password" id="myInput" class="b">
    <!-- An element to toggle between password visibility -->
    <input type="checkbox" onclick="myFunction()" class="a"> Ver contraseña

		<button type="submit" name="submit">Registrarse</button>

		<p class="error"><?php echo @$user->error ?></p>
		<p class="success"><?php echo @$user->success ?></p>
	</form>

</body>
</html>
<script> 
  function myFunction() {
  var x = document.getElementById("myInput");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}
</script>