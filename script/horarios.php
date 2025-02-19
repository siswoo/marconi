<?php
session_start();
include("conexion.php");
$fecha_creacion = date('Y-m-d');
$hora_creacion = date('h:i:s');
$asunto = $_POST['asunto'];

	if($asunto=='listar'){
		$options = '<option value="">Seleccione</option>';
		$sql1 = "SELECT * FROM horarios";
		$proceso1 = mysqli_query($conexion,$sql1);
		while ($row1 = mysqli_fetch_array($proceso1)) {
			$id = $row1["id"];
			$entrada = $row1["entrada"];
			$salida = $row1["salida"];
			$options .= '<option value="'.$id.'">'.$entrada.' | '.$salida.'</option>';
		}
		$datos = [
			"estatus"	=> "ok",
			"options"	=> $options,
		];
		echo json_encode($datos);
	}