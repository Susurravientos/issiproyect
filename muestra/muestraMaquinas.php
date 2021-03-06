<?php

	session_start();
		if(!isset($_SESSION["cargo"]) or ($_SESSION['cargo']!="JEFEPERSONAL" and $_SESSION['cargo']!="PRESIDENTE" and  $_SESSION['cargo']!="VICEPRESIDENTE")){
		echo "</p>No tienes permisos para acceder a esta página</p>";
		
	}else{
    require_once("../gestionas/gestionBD.php");
    require_once("../gestionas/gestionarMaquina.php");
    require_once("../consultaPaginada.php");
	unset($_SESSION["paginacion"]);
	
	if (isset($_SESSION["paginacion"])) $paginacion = $_SESSION["paginacion"];
	$pagina_seleccionada = isset($_GET["PAG_NUM"])? (int)$_GET["PAG_NUM"]: (isset($paginacion)? (int)$paginacion["PAG_NUM"]: 1);

	$pag_tam = isset($_GET["PAG_TAM"])? (int)$_GET["PAG_TAM"]: (isset($paginacion)? (int)$paginacion["PAG_TAM"]: 5);

	if ($pagina_seleccionada < 1) $pagina_seleccionada = 1;
	if ($pag_tam < 1) $pag_tam = 5;

	unset($_SESSION["paginacion"]);

	$conexion = crearConexionBD();

	$query = "SELECT * FROM MAQUINA";

	
	$total_registros = total_consulta($conexion,$query);
	$total_paginas = (int) ($total_registros / $pag_tam);

	if ($total_registros % $pag_tam > 0) $total_paginas++;
	if ($pagina_seleccionada > $total_paginas) $pagina_seleccionada = $total_paginas;

	$paginacion["PAG_NUM"] = $pagina_seleccionada;
	$paginacion["PAG_TAM"] = $pag_tam;
	$_SESSION["paginacion"] = $paginacion;
	
	$filas = consulta_paginada($conexion, $query, $pagina_seleccionada, $pag_tam);
	
    cerrarConexionBD($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" type="text/css" href="../css/muestraTabla.css" />
  <link rel="stylesheet" type="text/css" href="../css/muestraMaquinas.css" />
   <link rel="stylesheet" type="text/css" href="../css/popupocultar2.css" />
   <link rel="stylesheet" type="text/css" href="../css/modificarForm.css" />
    <link rel="stylesheet" type="text/css" href="../css/footer.css" />
  <script type="text/javascript" src="../js/filtro.js"></script>
  <title>Lista de máquinas</title>
</head>

<body style="background-color:#dfdfdf7d;">


<?php
	include_once ("header.php");
	?>
<main>

	<div class="titulotabla">
	 	<div><p class="titulo">MÁQUINAS</p></div>
	 </div>
	 
	 <?php if(isset($_SESSION['mOkBorrarMaq'])){
	 	unset($_SESSION['mOkBorrarMaq']);
		echo "<div>
	<div class=\"error\">
		<div class=\"tick\"><img src=\"../img/tick.png\" /></div>
		<div class=\"errortext\" style=\"display: inline-block; align-items: center;\" ><p>¡La maquina se ha eliminado correctamente!</p></div>
	</div>";
	 } ?>
	<div class="selectpag">
	
	
	<form class="formpag" method="get" action="muestraMaquinas.php">

			<input id="PAG_NUM" name="PAG_NUM" type="hidden" value="<?php echo $pagina_seleccionada?>"/>

			Mostrando

			<input id="PAG_TAM" name="PAG_TAM" type="number"

				min="1" max="<?php echo $total_registros;?>"

				value="<?php echo $pag_tam;
							?>" autofocus="autofocus" />
			
			entradas de <?php echo $total_registros?>

			<input type="submit" value="Cambiar">

		</form>
		
		</div>
		
		<div class ="tabla">
	 <table class="tabla" id="tablaMaquina">
	 	
		<tr>
    		<th class="primerault">Nombre</th>
  		</tr>

	<?php
		$contador=0;
		$contador2=0;
		foreach($filas as $fila) {

	?>

		<form method="post" action="../controladores/controlador_maquinas.php">

			<div class="fila_maquina">

				<div class="datos_maquina">

					<input id="OID_MAQ" name="OID_MAQ" type="hidden" value="<?php echo $fila["OID_MAQ"]; ?>"/>
					<input id="NOMBRE" name="NOMBRE" type="hidden" value="<?php echo $fila["NOMBRE"]; ?>"/>

						<tr class="fila" >
							<td class="nombre" align="center" onclick="window.location='#popup<?php echo $contador; ?>';"><p><?php echo $fila['NOMBRE'] ?></p></td>
							
							<?php if($_SESSION['cargo']=="JEFEPERSONAL"){ ?>
								
								<td class ="boton">
									<button id="editar" name="editar" type="submit" class="vistacliente">
									<img src="../img/lapizEditar.png" class="boton" alt="Papelera Borrar" height="40" width="40">
									</button>
								</td>
								
								<td class ="boton">
									<button id="borrar" name="borrar" type="button" class="vistacliente" onclick="window.location='#popup<?php echo $fila["NOMBRE"]; echo "Remove";?>';" >
									<img src="../img/ocultar.png" class="boton" alt="Papelera Borrar" height="34" width="34">
								</button>
								</td>
								<?php } ?>
							<form action="post" action="../controladores/controlador_maquinas.php">
								
								<div id="popup<?php echo $fila["NOMBRE"]; echo "Remove"; ?>" class="overlay" align="left">
									<div class="popup">
										<a class="close" href="#">X</a>
										<p class="textp" align="center">¿Seguro que quieres ocultar la máquina <?php echo $fila['NOMBRE'];?>?</p>
									</br>
										<button id="borrar" name="borrar" type="submit" class="bPop"><img src="../img/ocultar.png" width="30px" height="30px"/></button>
									</div>
								</div>
							
							</form>
								
								
						</tr>
								
								</form>	
								
								
								<div id="popup<?php echo $contador; ?>" class="overlay" align="left">
									<div class="popup">
										<a class="close" href="#">X</a>
										<div class="dJefe" align="center">
										<label class="jefMaq" style="text-decoration:underline;">Jefe de Maquina</label></br>
										<label class="jefMaq"> <?php 
												$conexion=crearConexionBD();
	
												$jefe = getJefeMaquina2($conexion,$fila['OID_MAQ']);
												cerrarConexionBD($conexion);

												echo $jefe['NOMBRE']; echo " "; echo $jefe['APELLIDOS']; echo " "; echo "</br>";
											
										
										?>
										</label>
										</div>
										<div class="dPeones" align="center">
										<label class="peones" style="text-decoration:underline;">Empleados</label></br>
										<label class="peones"><?php 
												$conexion=crearConexionBD();
	
												$peones = getEmpleadosMaquina($conexion,$fila['OID_MAQ']);
												cerrarConexionBD($conexion);

											
												foreach($peones as $peon){
													if($peon['NOMBRE']==$jefe['NOMBRE'] and $peon['APELLIDOS']==$jefe['APELLIDOS']){
														
													}else{
														echo $peon['NOMBRE']; echo " "; echo $peon['APELLIDOS']; echo " "; echo "</br>";								
													}
													
												}
											
										
										?>
										</label>
										</div>
										
									</br>
									</div>
									
								</div>
								
						
						
				<?php $contador++;$contador2++; } ?>

				</div>
			</div>
	
	 </table>
	</div>
	
	<div class="paginas">
		<nav>
			<div id="enlaces">
			<?php
			
				if($total_paginas <=6){
					 for( $pagina = 1; $pagina <= $total_paginas; $pagina++ )
						if ( $pagina == $pagina_seleccionada) { 	?>
							<span class="current"><?php echo $pagina; ?></span>

			<?php }	else { ?>

						<a href="muestraMaquinas.php?PAG_NUM=<?php echo $pagina; ?>&PAG_TAM=<?php echo $pag_tam; ?>"><?php echo $pagina; ?></a>

			<?php }
			 }
				
				else if($pagina_seleccionada >= $total_paginas-3) {
					 for( $pagina = $pagina_seleccionada-(6-($total_paginas-$pagina_seleccionada)); $pagina <= $total_paginas; $pagina++ )
						if ( $pagina == $pagina_seleccionada) { 	?>

						<span class="current"><?php echo $pagina; ?></span>

			<?php }	else { ?>

						<a href="muestraMaquinas.php?PAG_NUM=<?php echo $pagina; ?>&PAG_TAM=<?php echo $pag_tam; ?>"><?php echo $pagina; ?></a>

			<?php }
			 }
				else if($pagina_seleccionada <= 4) { 
					for( $pagina = 1; $pagina <= $pagina_seleccionada+(7-$pagina_seleccionada); $pagina++ )
					if ( $pagina == $pagina_seleccionada) { 	?>

						<span class="current"><?php echo $pagina; ?></span>

			<?php }	else { ?>

						<a href="muestraMaquinas.php?PAG_NUM=<?php echo $pagina; ?>&PAG_TAM=<?php echo $pag_tam; ?>"><?php echo $pagina; ?></a>

			<?php } 
				}
				else {
					for( $pagina = $pagina_seleccionada-3; $pagina <= $pagina_seleccionada+3; $pagina++ )
				if ( $pagina == $pagina_seleccionada) { 	?>

						<span class="current"><?php echo $pagina; ?></span>

			<?php }	else { ?>

						<a href="muestraMaquinas.php?PAG_NUM=<?php echo $pagina; ?>&PAG_TAM=<?php echo $pag_tam; ?>"><?php echo $pagina; ?></a>

			<?php } 
				} ?>
			

		</div>
		</nav>
		</div>
	<?php
	include_once ("footer.php");
	?>
</main>
</body>
<?php } ?>
</html>