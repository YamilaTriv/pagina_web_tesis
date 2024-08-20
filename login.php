<?php require("login.class.php") ?>
<?php 
	if(isset($_POST['submit'])){
		$user = new LoginUser($_POST['username'], $_POST['email'],$_POST['password']);
	}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	 <link rel="stylesheet" href="styles.css">
	<title>Log in form</title>
</head>
<body>
	<form action="" method="post" enctype="multipart/form-data" autocomplete="off">
		<h2>Inicia sesión</h2>
		<h4>Todos los campos son <span>obligatorios</span></h4>

		<label>Usuario</label>
		<input type="text" name="username">

		<label>Email</label>
		<input type="email" name="email" required>

		<label>Contraseña</label>
		<input type="password" name="password" id="myInput" class="b">
		
		<input type="checkbox" onclick="myFunction()" class="a"> Ver contraseña

		<button type="submit" name="submit">Inicia sesión</button>
    <div style="text-align: center; justify-content: center;align-items: center; margin-top: 20px; ;display: flex;">
      <p>O</p>
    </div>
    <a href="index.php">
     <button type="button">Registrarse</button>
    </a>

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