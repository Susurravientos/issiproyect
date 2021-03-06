<?php 

function consultarMaquinas($conexion) {
 	$consulta = "SELECT * FROM MAQUINA";
	return $conexion->query($consulta);
}

function getMaquinaOid($conexion,$oid){
	$consulta = "SELECT * FROM CLIENTE WHERE (MAQUINA.OID_MAQ = '$oid')";
	$stmt = $conexion->prepare($consulta);
	$stmt->execute();
	return $stmt->fetch();
}



function borrarMaq($conexion,$oid){
		try{
	$consulta = "DELETE FROM MAQUINA WHERE (MAQUINA.OID_MAQ = '$oid')";
	$stmt = $conexion->prepare($consulta);
	$stmt->execute();
	return 1;
		}catch(PDOException $e) {
		return $e->getMessage();
    }
}

function getEmpleadosNoEnMaquina($conexion,$oid){
	$consulta = "SELECT nombre,apellidos,cargo FROM EMPLEADO WHERE EMPLEADO.OID_MAQ<>'$oid'"; 
	return $conexion->query($consulta);
}


function getEmpleadosMaquina($conexion,$oid){
	$consulta = "SELECT nombre,apellidos,cargo FROM EMPLEADO WHERE EMPLEADO.OID_MAQ='$oid'";
	return $conexion->query($consulta);
}
function getJefeMaquina2($conexion,$oid){
	$consulta = "SELECT * FROM EMPLEADO WHERE EMPLEADO.OID_MAQ='$oid' AND EMPLEADO.CARGO=9";
	$stmt = $conexion->prepare($consulta);
	$stmt->execute();
	
	return $stmt->fetch();
}

function actualizarEmpleadoMaquina($conexion,$dni,$oid){
	try{
		$consulta = "UPDATE EMPLEADO SET EMPLEADO.OID_MAQ='$oid' WHERE(EMPLEADO.DNI='$dni')";
	$stmt = $conexion->prepare($consulta);
	$stmt->execute();
	return 1;
	}catch(PDOException $e){
		return 0;
	}
}

function actualizarMaquina($conexion,$oid,$nombre){
	try{
		$consulta="UPDATE MAQUINA SET MAQUINA.NOMBRE='$nombre' WHERE MAQUINA.OID_MAQ='$oid'";
		$stmt = $conexion->prepare($consulta);
		$stmt->execute();
		return 1;
	}catch(PDOException $e){
		return 0;
	}
	
}
function quitarEmpleadosMaquina($conexion,$oid){
	try{
		$consulta = "UPDATE EMPLEADO SET EMPLEADO.OID_MAQ=NULL WHERE EMPLEADO.OID_MAQ='$oid'";
		$stmt = $conexion->prepare($consulta);
		$stmt->execute();
		$consulta2 = "UPDATE JEFEMAQUINA SET JEFEMAQUINA.OID_MAQ=NULL WHERE JEFEMAQUINA.OID_MAQ='$oid'";
		$stmt2 = $conexion->prepare($consulta2);
		$stmt2->execute();
		return 1;
	}catch(PDOException $e){
		return 0;
	}
}
?>