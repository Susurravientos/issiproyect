<?php session_start();

include_once ("gestionas/gestionBD.php");
include_once ("gestionas/gestionarEmpleado.php");

if (isset($_POST['submit'])) {
	$pass = $_POST['pass'];
	$passC = $_POST['passC'];
	if ($pass == $passC) {
		$dni = $_SESSION['dni'];
		$conexion = crearConexionBD();
		$salida = establecePassBD($conexion, $dni, $pass);
		cerrarConexionBD($conexion);

		$conexion = crearConexionBD();
		$usuario = consultaPassBD($conexion, $pass, $dni);
		cerrarConexionBD($conexion);

		if ($salida = true) {
			$_SESSION['login'] = $usuario['DNI'];
			$_SESSION['nombre'] = $usuario['NOMBRE'];
			$_SESSION['cargo'] = getCargoString($usuario['CARGO']);
			Header("Location: muestra/index1.php");

		} else {
			Header("Location: login.php");
		}
	} else {
		$error=1;

	}

}
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="css/formLogin.css" />
		 <link rel="icon" href="img/logo_coenca.png" />

		<title>Coenca | Registro</title>
	</head>

	<body>
		<a href="muestra/index1.php"><img src="img/back.png" width="80px" alt="Volver" height="50px" style="position: absolute"/></a>

		<div align="center" class="login">

		<img class="logoCoenca" src="img/logo_coenca.png">

		<?php if(isset($error)) echo "<div align=\"center\" class=\"error\"><p align=\"center\">Las contraseñas no coinciden.</p></div>";?>
			<form action="registro.php" method="post">
				<div><label for="pass"></label>
					<input placeholder="Introduce una contraseña" type="password" name="pass" id="pass" style="position: absolute; top: 60%; left: 18.5%;" />
					
				</div>
				<div>
					<label for="passC"></label>
					<input placeholder="Confirma la contraseña"type="password" name="passC" id="passC" style="position: absolute; top: 72%; left: 18.5%;"/>
				</div>
				<input class="botonLogin" type="submit" name="submit" value="Registrate" style="width:150px; height:30px; position: absolute; top: 84%; left: 35%;"/>
			</form>
		</div>

	</body>
</html>

