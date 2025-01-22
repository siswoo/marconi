<?php
include("../script.php");
$fecha_creacion = date('Y-m-d');
$asunto = $_POST['asunto'];

	if($asunto=='consultarVentas'){
		$año = $_POST['año'];
		$mes = $_POST['mes'];
		$dia = $_POST['dia'];
		$selectOptions = $_POST['selectOptions'];
		$idUsuario = $_POST['idUsuario'];

		$fecha_desde = $año."-".$mes."-".$dia." 00:00:00";
		$fecha_hasta = $año."-".$mes."-".$dia." 23:59:59";

		$gananciaDiaria = 0;
		$gananciaCan = 0;
		$gananciaEsmad = 0;

		$ganancia_1 = 0;
		$ganancia_2 = 0;
		$ganancia_3 = 0;
		$ganancia_4 = 0;
		$ganancia_5 = 0;
		$ganancia_6 = 0;
		$ganancia_7 = 0;
		$ganancia_8 = 0;
		$ganancia_9 = 0;
		$ganancia_10 = 0;
		$ganancia_11 = 0;
		$ganancia_12 = 0;

		$sql2 = "SELECT * FROM usuarios WHERE id = $idUsuario";
		$proceso2 = mysqli_query($conexion,$sql2);
		while($row2=mysqli_fetch_array($proceso2)){
			$usuarioRol = $row2["rol"];
		}

		if($usuarioRol==1 or $usuarioRol==2 or $usuarioRol==3){
			if($selectOptions==0){
				$sql1 = "SELECT * FROM ventas WHERE fecha_creacion >= '$fecha_desde' and fecha_creacion <= '$fecha_hasta' and (estatus = 0 or estatus = 2 or estatus = 3)";
			}else{
				$sql1 = "SELECT * FROM ventas WHERE fecha_creacion >= '$fecha_desde' and fecha_creacion <= '$fecha_hasta' and (estatus = 0 or estatus = 2 or estatus = 3) and responsableId = $selectOptions";
			}
		}else{
			$sql1 = "SELECT * FROM ventas WHERE fecha_creacion >= '$fecha_desde' and fecha_creacion <= '$fecha_hasta' and (estatus = 0 or estatus = 2 or estatus = 3) and responsableId = ".$idUsuario;
		}

		$proceso1 = mysqli_query($conexion,$sql1);
		$contador1 = mysqli_num_rows($proceso1);
		if($contador1>0){
			while($row1=mysqli_fetch_array($proceso1)){
				$ventaId = $row1["id"];
				$costoT = $row1["costoT"];
				$sucursalId = $row1["sucursalId"];
				$formaPagoId1 = $row1["formaPagoId1"];
				$formaPagoId2 = $row1["formaPagoId2"];
				$ventasEstatus = $row1["estatus"];
				$monto1 = $row1["monto1"];
				$monto2 = $row1["monto2"];
				$estatus = $row1["estatus"];

				if($estatus==0 or $estatus==3){
					$gananciaDiaria += $costoT;
				}else if($estatus==2){
					$gananciaDiaria += $monto2;
				}

				if($formaPagoId1==1){
					$ganancia_1 += $row1["monto1"];
				}
				if($formaPagoId1==2){
					$ganancia_2 += $row1["monto1"];
				}
				if($formaPagoId1==3){
					$ganancia_3 += $row1["monto1"];
				}
				if($formaPagoId1==4){
					$ganancia_4 += $row1["monto1"];
				}
				if($formaPagoId1==5){
					$ganancia_5 += $row1["monto1"];
				}
				if($formaPagoId1==6){
					$ganancia_6 += $row1["monto1"];
				}
				if($formaPagoId1==7){
					$ganancia_7 += $row1["monto1"];
				}
				if($formaPagoId1==8){
					$ganancia_8 += $row1["monto1"];
				}
				if($formaPagoId1==9){
					$ganancia_9 += $row1["monto1"];
				}
				if($formaPagoId1==10){
					$ganancia_10 += $row1["monto1"];
				}
				if($formaPagoId1==11){
					$ganancia_11 += $row1["monto1"];
				}
				if($formaPagoId1==12){
					$ganancia_12 += $row1["monto1"];
				}


				if($formaPagoId2==1){
					$ganancia_1 += $row1["monto2"];
				}
				if($formaPagoId2==2){
					$ganancia_2 += $row1["monto2"];
				}
				if($formaPagoId2==3){
					$ganancia_3 += $row1["monto2"];
				}
				if($formaPagoId2==4){
					$ganancia_4 += $row1["monto2"];
				}
				if($formaPagoId2==5){
					$ganancia_5 += $row1["monto2"];
				}
				if($formaPagoId2==6){
					$ganancia_6 += $row1["monto2"];
				}
				if($formaPagoId2==7){
					$ganancia_7 += $row1["monto2"];
				}
				if($formaPagoId2==8){
					$ganancia_8 += $row1["monto2"];
				}
				if($formaPagoId2==9){
					$ganancia_9 += $row1["monto2"];
				}
				if($formaPagoId2==10){
					$ganancia_10 += $row1["monto2"];
				}
				if($formaPagoId2==11){
					$ganancia_11 += $row1["monto2"];
				}
				if($formaPagoId2==12){
					$ganancia_12 += $row1["monto2"];
				}

			}

			if($usuarioRol==1 or $usuarioRol==2 or $usuarioRol==3){
					$sql3 = "SELECT * FROM historialcreditos WHERE fecha_creacion >= '$fecha_desde' and fecha_creacion <= '$fecha_hasta'";
				}else{
					$sql3 = "SELECT * FROM historialcreditos WHERE fecha_creacion >= '$fecha_desde' and fecha_creacion <= '$fecha_hasta' and responsableId = ".$idUsuario;
				}

				$proceso3 = mysqli_query($conexion,$sql3);
				$contador3 = mysqli_num_rows($proceso3);
				if($contador3>0){
					while($row3=mysqli_fetch_array($proceso3)){
						$metodoPago = $row3["metodoPago"];
						if($metodoPago==1){
							$ganancia_1 += $row3["monto"];
						}
						if($metodoPago==2){
							$ganancia_2 += $row3["monto"];
						}
						if($metodoPago==3){
							$ganancia_3 += $row3["monto"];
						}
						if($metodoPago==4){
							$ganancia_4 += $row3["monto"];
						}
						if($metodoPago==5){
							$ganancia_5 += $row3["monto"];
						}
						if($metodoPago==6){
							$ganancia_6 += $row3["monto"];
						}
						if($metodoPago==7){
							$ganancia_7 += $row3["monto"];
						}
						if($metodoPago==8){
							$ganancia_8 += $row3["monto"];
						}
						if($metodoPago==9){
							$ganancia_9 += $row3["monto"];
						}
						if($metodoPago==10){
							$ganancia_10 += $row3["monto"];
						}
						if($metodoPago==11){
							$ganancia_11 += $row3["monto"];
						}
						if($metodoPago==12){
							$ganancia_12 += $row3["monto"];
						}
					}
				}

			
			
			$datos = [
				"estatus"	=> "ok",
				"gananciaDiaria"	=> $gananciaDiaria,
				"gananciaVentas"	=> $contador1,
				"gananciaCan"	=> $gananciaCan,
				"gananciaEsmad"	=> $gananciaEsmad,
				"ganancia_1"	=> $ganancia_1,
				"ganancia_2"	=> $ganancia_2,
				"ganancia_3"	=> $ganancia_3,
				"ganancia_4"	=> $ganancia_4,
				"ganancia_5"	=> $ganancia_5,
				"ganancia_6"	=> $ganancia_6,
				"ganancia_7"	=> $ganancia_7,
				"ganancia_8"	=> $ganancia_8,
				"ganancia_9"	=> $ganancia_9,
				"ganancia_10"	=> $ganancia_10,
				"ganancia_11"	=> $ganancia_11,
				"ganancia_12"	=> $ganancia_12,
			];
			echo json_encode($datos);
		}else{
			$datos = [
				"estatus"	=> "error",
				"msg"	=> "Sin datos",
			];
			echo json_encode($datos);
		}
	}

	if($asunto=='consultarCreditos'){
		$año = $_POST['año'];
		$mes = $_POST['mes'];
		$dia = $_POST['dia'];
		$idUsuario = $_POST['idUsuario'];

		$fecha_desde = $año."-".$mes."-".$dia." 00:00:00";
		$fecha_hasta = $año."-".$mes."-".$dia." 23:59:59";

		$gananciaDiaria = 0;
		$gananciaCan = 0;
		$gananciaEsmad = 0;

		$ganancia_1 = 0;
		$ganancia_2 = 0;
		$ganancia_3 = 0;
		$ganancia_4 = 0;
		$ganancia_5 = 0;
		$ganancia_6 = 0;
		$ganancia_7 = 0;
		$ganancia_8 = 0;
		$ganancia_9 = 0;
		$ganancia_10 = 0;
		$ganancia_11 = 0;
		$ganancia_12 = 0;

		$sql2 = "SELECT * FROM usuarios WHERE id = $idUsuario";
		$proceso2 = mysqli_query($conexion,$sql2);
		while($row2=mysqli_fetch_array($proceso2)){
			$usuarioRol = $row2["rol"];
		}

		if($usuarioRol==1 or $usuarioRol==2 or $usuarioRol==3){
			$sql1 = "SELECT * FROM historialcreditos WHERE fecha_creacion >= '$fecha_desde' and fecha_creacion <= '$fecha_hasta'";	
		}else{
			$sql1 = "SELECT * FROM historialcreditos WHERE fecha_creacion >= '$fecha_desde' and fecha_creacion <= '$fecha_hasta' and responsableId = ".$idUsuario;
		}

		$proceso1 = mysqli_query($conexion,$sql1);
		$contador1 = mysqli_num_rows($proceso1);
		if($contador1>0){
			while($row1=mysqli_fetch_array($proceso1)){
				$montoCredito = $row1["monto"];
				$formaPagoCredito = $row1["metodoPago"];
				$gananciaDiaria += $montoCredito;

				if($sucursalId==1){
					$gananciaCan = $gananciaCan+$costoT;
				}else{
					$gananciaEsmad = $gananciaEsmad+$costoT;
				}

				if($formaPagoCredito==1){
					$ganancia_1 += $montoCredito;
				}
				if($formaPagoCredito==2){
					$ganancia_2 += $montoCredito;
				}
				if($formaPagoCredito==3){
					$ganancia_3 += $montoCredito;
				}
				if($formaPagoCredito==4){
					$ganancia_4 += $montoCredito;
				}
				if($formaPagoCredito==5){
					$ganancia_5 += $montoCredito;
				}
				if($formaPagoCredito==6){
					$ganancia_6 += $montoCredito;
				}
				if($formaPagoCredito==7){
					$ganancia_7 += $montoCredito;
				}
				if($formaPagoCredito==8){
					$ganancia_8 += $montoCredito;
				}
				if($formaPagoCredito==9){
					$ganancia_9 += $montoCredito;
				}
				if($formaPagoCredito==10){
					$ganancia_10 += $montoCredito;
				}
				if($formaPagoCredito==11){
					$ganancia_11 += $montoCredito;
				}
				if($formaPagoCredito==12){
					$ganancia_12 += $montoCredito;
				}
			}
			
			$datos = [
				"estatus"	=> "ok",
				"gananciaDiaria"	=> $gananciaDiaria,
				"gananciaVentas"	=> $contador1,
				"gananciaCan"	=> $gananciaCan,
				"gananciaEsmad"	=> $gananciaEsmad,
				"ganancia_1"	=> $ganancia_1,
				"ganancia_2"	=> $ganancia_2,
				"ganancia_3"	=> $ganancia_3,
				"ganancia_4"	=> $ganancia_4,
				"ganancia_5"	=> $ganancia_5,
				"ganancia_6"	=> $ganancia_6,
				"ganancia_7"	=> $ganancia_7,
				"ganancia_8"	=> $ganancia_8,
				"ganancia_9"	=> $ganancia_9,
				"ganancia_10"	=> $ganancia_10,
				"ganancia_11"	=> $ganancia_11,
				"ganancia_12"	=> $ganancia_12,
			];
			echo json_encode($datos);
		}else{
			$datos = [
				"estatus"	=> "error",
				"msg"	=> "Sin datos",
			];
			echo json_encode($datos);
		}
	}

?>