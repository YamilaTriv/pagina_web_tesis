<?php 
	session_start();
	if(!isset($_SESSION['user'])){
		header("location: login.php");	exit();
	}

	if(isset($_GET['logout'])){
		unset($_SESSION['user']);
		header("location: login.php");	exit();
	}
 ?>

<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-icons/3.0.1/iconfont/material-icons.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  <link rel="stylesheet" href="contacto.css">  
</head>
<body>
  <nav>
    <div class="nav-wrapper">
      <a href="datos_cultivos.php" class="brand-logo">
        <img class="logo" src="img/1_utec.png" alt="Logo de mi página">
        <img class="logo" src="img/inia.png" alt="Logo INIA">
        <img class="logo" src="img/3_utec.png" alt="Logo IMEC">
      </a>
      <ul class="right hide-on-med-and-down">
        <li><a href="datos_cultivos.php">Inicio</a></li>
        <li><a href="#">Acerca de</a></li>
        <li><a href="#">Servicios</a></li>
        <li><a href="contacto.php">Contacto</a></li>
      </ul>
    </div>
  </nav>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

  <h1 class="center-align">Lista de Contactos</h1>
  <div class="contacts">
    <div class="contact">
      <div class="avatar">
        <img src="/img/hombre.png" alt="Avatar">
      </div>
      <div class="details">
        <h2>Mariano Arbiza</h2>
        <p>Estudiante Ing.en Mecatrónica</p>
        <p>Email: mariano.arbiza@estudiantes.utec.edu.uy</p>
        <p>Phone: a definir </p>
      </div>
    </div>

    <div class="contact">
      <div class="avatar">
        <img src="/img/mujer.png" alt="Avatar">
      </div>
      <div class="details">
        <h2>Yamila Triviño</h2>
        <p>Estudiante Ing.en Mecatrónica</p>
        <p>Email: yamila.trivino@estudiantes.utec.edu.uy</p>
        <p>Phone: a definir</p>
      </div>
    </div>

    <div class="contact">
      <div class="avatar">
        <img src="/img/diego.png" alt="Avatar">
      </div>
      <div class="details">
        <h2>Diego Quiroga</h2>
        <p>Tutor</p>
        <p>Email: michael@example.com</p>
        <p>Phone: +1 555 123 4567</p>
      </div>
    </div>
  </div>
</body>
</html>