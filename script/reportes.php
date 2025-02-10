<?php
include("conexion.php");
require '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
$fecha_creacion = date('Y-m-d');
$fechaHora = date('Y-m-d h:i:s');
$asunto = $_POST['tipo'];

	if($asunto=="Usuarios"){
		$excel = new Spreadsheet();
		$hojaActiva = $excel->getActiveSheet();
		$hojaActiva->setCellValue('A1','Usuario');
		$hojaActiva->setCellValue('B1','Nombre');
		$hojaActiva->setCellValue('C1','Apellido');
		$hojaActiva->setCellValue('D1','Cedula');
		$hojaActiva->setCellValue('E1','Fecha Nacimiento');
		$hojaActiva->setCellValue('F1','Genero');
		$hojaActiva->setCellValue('G1','Telefono');
		$hojaActiva->setCellValue('H1','Correo');
		$hojaActiva->setCellValue('I1','Fecha Ingreso');
		$hojaActiva->setCellValue('J1','Fecha Retiro');
		$hojaActiva->setCellValue('K1','Salario');
		$hojaActiva->setCellValue('L1','Estado');
		$fila = 2;
		$sql1 = "SELECT * FROM usuarios";
		$proceso1 = mysqli_query($conexion,$sql1);
		$contador1 = mysqli_num_rows($proceso1);
		if($contador1>=1){
			while($row1=mysqli_fetch_array($proceso1)){
				$usuario = $row1["usuario"];
				$nombre = $row1["nombre"];
				$apellido = $row1["apellido"];
				$cedula = $row1["cedula"];
				$fechaNacimiento = $row1["fechaNacimiento"];
				$genero = $row1["genero"];
				$telefono = $row1["telefono"];
				$correo = $row1["correo"];
				$fechaIngreso = $row1["fechaIngreso"];
				$fechaRetiro = $row1["fechaRetiro"];
				$salario = $row1["salario"];
				$estado = $row1["estado"];

				$hojaActiva->setCellValue('A'.$fila,$usuario);
				$hojaActiva->setCellValue('B'.$fila,$nombre);
				$hojaActiva->setCellValue('C'.$fila,$apellido);
				$hojaActiva->setCellValue('D'.$fila,$cedula);
				$hojaActiva->setCellValue('E'.$fila,$fechaNacimiento);
				$hojaActiva->setCellValue('F'.$fila,$genero);
				$hojaActiva->setCellValue('G'.$fila,$telefono);
				$hojaActiva->setCellValue('H'.$fila,$correo);
				$hojaActiva->setCellValue('I'.$fila,$fechaIngreso);
				$hojaActiva->setCellValue('J'.$fila,$fechaRetiro);
				$hojaActiva->setCellValue('K'.$fila,$salario);
				$hojaActiva->setCellValue('L'.$fila,$estado);
				$fila++;
			}

			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename=Usuarios.xlsx');
			header('Cache-Control: max-age');
			$writer = IOFactory::createWriter($excel,'Xlsx');
			$writer->save('php://output');
		}else{
			echo '<script>window.close();</script>';
		}
	}

	if($asunto=="Turnos"){
		$excel = new Spreadsheet();
		$hojaActiva = $excel->getActiveSheet();
		$hojaActiva->setCellValue('A1','Nombre');
		$hojaActiva->setCellValue('B1','Cedula');
		$hojaActiva->setCellValue('C1','Tipo');
		$hojaActiva->setCellValue('D1','Fecha Inicio');
		$hojaActiva->setCellValue('E1','Hora Inicio');
		$hojaActiva->setCellValue('F1','Fecha Fin');
		$hojaActiva->setCellValue('G1','Hora Fin');
		$hojaActiva->setCellValue('H1','Extras Aceptadas');
		$fila = 2;
		$sql1 = "SELECT tur.tipo, tur.fechaInicio, tur.horaInicio, tur.fechaFin, tur.horaFin, tur.estatusExtras, usu.nombre, usu.apellido, usu.cedula FROM turnos tur INNER JOIN usuarios usu ON tur.usuarioId = usu.id";
		$proceso1 = mysqli_query($conexion,$sql1);
		$contador1 = mysqli_num_rows($proceso1);
		if($contador1>=1){
			while($row1=mysqli_fetch_array($proceso1)){
				$nombre = $row1["nombre"]." ".$row1["apellido"];
				$cedula = $row1["cedula"];
				$tipo = $row1["tipo"];
				$fechaInicio = $row1["fechaInicio"];
				$horaInicio = $row1["horaInicio"];
				$fechaFin = $row1["fechaFin"];
				$horaFin = $row1["horaFin"];
				$estatusExtras = $row1["estatusExtras"];

				$hojaActiva->setCellValue('A'.$fila,$nombre);
				$hojaActiva->setCellValue('B'.$fila,$cedula);
				$hojaActiva->setCellValue('C'.$fila,$tipo);
				$hojaActiva->setCellValue('D'.$fila,$fechaInicio);
				$hojaActiva->setCellValue('E'.$fila,$horaInicio);
				$hojaActiva->setCellValue('F'.$fila,$fechaFin);
				$hojaActiva->setCellValue('G'.$fila,$horaFin);
				$hojaActiva->setCellValue('H'.$fila,$estatusExtras);
				$fila++;
			}

			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename=Turnos.xlsx');
			header('Cache-Control: max-age');
			$writer = IOFactory::createWriter($excel,'Xlsx');
			$writer->save('php://output');
		}else{
			echo '<script>window.close();</script>';
		}
	}

	if($asunto=="Permisos"){
		$excel = new Spreadsheet();
		$hojaActiva = $excel->getActiveSheet();
		$hojaActiva->setCellValue('A1','Nombre');
		$hojaActiva->setCellValue('B1','Cedula');
		$hojaActiva->setCellValue('C1','Tipo');
		$hojaActiva->setCellValue('D1','Fecha Inicio');
		$hojaActiva->setCellValue('E1','Hora Inicio');
		$hojaActiva->setCellValue('F1','Hora Fin');
		$hojaActiva->setCellValue('G1','Observacion');
		$hojaActiva->setCellValue('H1','Estatus');
		$fila = 2;
		$sql1 = "SELECT per.tipo, per.fechaInicio, per.horaInicio, per.horaFin, per.horaFin, per.observacion, per.estatus, usu.nombre, usu.apellido, usu.cedula FROM permisoslaborales per INNER JOIN usuarios usu ON per.usuarioId = usu.id";
		$proceso1 = mysqli_query($conexion,$sql1);
		$contador1 = mysqli_num_rows($proceso1);
		if($contador1>=1){
			while($row1=mysqli_fetch_array($proceso1)){
				$nombre = $row1["nombre"]." ".$row1["apellido"];
				$cedula = $row1["cedula"];
				$tipo = $row1["tipo"];
				$fechaInicio = $row1["fechaInicio"];
				$horaInicio = $row1["horaInicio"];
				$horaFin = $row1["horaFin"];
				$observacion = $row1["observacion"];
				$estatus = $row1["estatus"];

				$hojaActiva->setCellValue('A'.$fila,$nombre);
				$hojaActiva->setCellValue('B'.$fila,$cedula);
				$hojaActiva->setCellValue('C'.$fila,$tipo);
				$hojaActiva->setCellValue('D'.$fila,$fechaInicio);
				$hojaActiva->setCellValue('E'.$fila,$horaInicio);
				$hojaActiva->setCellValue('F'.$fila,$horaFin);
				$hojaActiva->setCellValue('G'.$fila,$observacion);
				$hojaActiva->setCellValue('H'.$fila,$estatus);
				$fila++;
			}

			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename=Permisos.xlsx');
			header('Cache-Control: max-age');
			$writer = IOFactory::createWriter($excel,'Xlsx');
			$writer->save('php://output');
		}else{
			echo '<script>window.close();</script>';
		}
	}

	if($asunto=="Incapacidades"){
		$excel = new Spreadsheet();
		$hojaActiva = $excel->getActiveSheet();
		$hojaActiva->setCellValue('A1','Nombre');
		$hojaActiva->setCellValue('B1','Cedula');
		$hojaActiva->setCellValue('C1','Fecha Inicio');
		$hojaActiva->setCellValue('D1','Fecha Fin');
		$hojaActiva->setCellValue('E1','Observacion');
		$hojaActiva->setCellValue('F1','Estatus');
		$fila = 2;
		$sql1 = "SELECT inc.fechaInicio, inc.fechaFin, inc.observacion, inc.estatus, usu.nombre, usu.apellido, usu.cedula FROM incapacidades inc INNER JOIN usuarios usu ON inc.usuarioId = usu.id";
		$proceso1 = mysqli_query($conexion,$sql1);
		$contador1 = mysqli_num_rows($proceso1);
		if($contador1>=1){
			while($row1=mysqli_fetch_array($proceso1)){
				$nombre = $row1["nombre"]." ".$row1["apellido"];
				$cedula = $row1["cedula"];
				$fechaInicio = $row1["fechaInicio"];
				$fechaFin = $row1["fechaFin"];
				$observacion = $row1["observacion"];
				$estatus = $row1["estatus"];

				$hojaActiva->setCellValue('A'.$fila,$nombre);
				$hojaActiva->setCellValue('B'.$fila,$cedula);
				$hojaActiva->setCellValue('C'.$fila,$fechaInicio);
				$hojaActiva->setCellValue('D'.$fila,$fechaFin);
				$hojaActiva->setCellValue('E'.$fila,$observacion);
				$hojaActiva->setCellValue('F'.$fila,$estatus);
				$fila++;
			}

			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename=Incapacidades.xlsx');
			header('Cache-Control: max-age');
			$writer = IOFactory::createWriter($excel,'Xlsx');
			$writer->save('php://output');
		}else{
			echo '<script>window.close();</script>';
		}
	}

	if($asunto=="Vacaciones"){
		$excel = new Spreadsheet();
		$hojaActiva = $excel->getActiveSheet();
		$hojaActiva->setCellValue('A1','Nombre');
		$hojaActiva->setCellValue('B1','Cedula');
		$hojaActiva->setCellValue('C1','Fecha Inicio');
		$hojaActiva->setCellValue('D1','Fecha Fin');
		$hojaActiva->setCellValue('E1','Observacion');
		$hojaActiva->setCellValue('F1','Estatus');
		$fila = 2;
		$sql1 = "SELECT vaca.fechaInicio, vaca.observacion, vaca.estatus, usu.nombre, usu.apellido, usu.cedula FROM vacaciones vaca INNER JOIN usuarios usu ON vaca.usuarioId = usu.id";
		$proceso1 = mysqli_query($conexion,$sql1);
		$contador1 = mysqli_num_rows($proceso1);
		if($contador1>=1){
			while($row1=mysqli_fetch_array($proceso1)){
				$nombre = $row1["nombre"]." ".$row1["apellido"];
				$cedula = $row1["cedula"];
				$fechaInicio = $row1["fechaInicio"];
				$observacion = $row1["observacion"];
				$estatus = $row1["estatus"];

				$hojaActiva->setCellValue('A'.$fila,$nombre);
				$hojaActiva->setCellValue('B'.$fila,$cedula);
				$hojaActiva->setCellValue('C'.$fila,$fechaInicio);
				$hojaActiva->setCellValue('D'.$fila,$observacion);
				$hojaActiva->setCellValue('E'.$fila,$estatus);
				$fila++;
			}

			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename=Vacaciones.xlsx');
			header('Cache-Control: max-age');
			$writer = IOFactory::createWriter($excel,'Xlsx');
			$writer->save('php://output');
		}else{
			echo '<script>window.close();</script>';
		}
	}

	if($asunto=="Liquidaciones"){
		$excel = new Spreadsheet();
		$hojaActiva = $excel->getActiveSheet();
		$hojaActiva->setCellValue('A1','Nombre');
		$hojaActiva->setCellValue('B1','Cedula');
		$hojaActiva->setCellValue('C1','Opción');
		$hojaActiva->setCellValue('D1','Fecha Inicio');
		$hojaActiva->setCellValue('E1','Salario');
		$hojaActiva->setCellValue('F1','Vacaciones');
		$hojaActiva->setCellValue('G1','Aguinaldos');
		$hojaActiva->setCellValue('H1','Preaviso');
		$hojaActiva->setCellValue('I1','Cesantia');
		$hojaActiva->setCellValue('J1','Total');
		$fila = 2;
		$sql1 = "SELECT liq.opcion, liq.fechaInicio, liq.salario, liq.vacaciones, liq.aguinaldos, liq.preaviso, liq.cesantias, liq.total, liq.estatus, usu.nombre, usu.apellido, usu.cedula FROM liquidaciones liq INNER JOIN usuarios usu ON liq.usuarioId = usu.id";
		$proceso1 = mysqli_query($conexion,$sql1);
		$contador1 = mysqli_num_rows($proceso1);
		if($contador1>=1){
			while($row1=mysqli_fetch_array($proceso1)){
				$nombre = $row1["nombre"]." ".$row1["apellido"];
				$cedula = $row1["cedula"];
				$opcion = $row1["opcion"];
				$fechaInicio = $row1["fechaInicio"];
				$salario = $row1["salario"];
				$vacaciones = $row1["vacaciones"];
				$aguinaldos = $row1["aguinaldos"];
				$preaviso = $row1["preaviso"];
				$cesantias = $row1["cesantias"];
				$estatus = $row1["estatus"];

				$hojaActiva->setCellValue('A'.$fila,$nombre);
				$hojaActiva->setCellValue('B'.$fila,$cedula);
				$hojaActiva->setCellValue('C'.$fila,$opcion);
				$hojaActiva->setCellValue('D'.$fila,$fechaInicio);
				$hojaActiva->setCellValue('E'.$fila,$salario);
				$hojaActiva->setCellValue('F'.$fila,$vacaciones);
				$hojaActiva->setCellValue('G'.$fila,$aguinaldos);
				$hojaActiva->setCellValue('H'.$fila,$preaviso);
				$hojaActiva->setCellValue('I'.$fila,$cesantias);
				$hojaActiva->setCellValue('J'.$fila,$estatus);
				$fila++;
			}

			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename=Liquidaciones.xlsx');
			header('Cache-Control: max-age');
			$writer = IOFactory::createWriter($excel,'Xlsx');
			$writer->save('php://output');
		}else{
			echo '<script>window.close();</script>';
		}
	}

	if($asunto=="Planillas"){
		$excel = new Spreadsheet();
		$hojaActiva = $excel->getActiveSheet();
		$hojaActiva->setCellValue('A1','Nombre');
		$hojaActiva->setCellValue('B1','Cedula');
		$hojaActiva->setCellValue('C1','Fecha');
		$hojaActiva->setCellValue('D1','Pago Dia');
		$hojaActiva->setCellValue('E1','Pago Hora');
		$hojaActiva->setCellValue('F1','Horas Extras');
		$hojaActiva->setCellValue('G1','Feriados Laborados');
		$hojaActiva->setCellValue('H1','Aguinaldos');
		$hojaActiva->setCellValue('I1','Ccss');
		$hojaActiva->setCellValue('J1','Isr');
		$hojaActiva->setCellValue('K1','SubTotal');
		$hojaActiva->setCellValue('L1','Total');
		$fila = 2;
		$sql1 = "SELECT pla.fecha, pla.pagoDia, pla.pagoHora, pla.horasExtras, pla.diasFeriadosLaborados, pla.aguinaldos, pla.ccss, pla.isr, pla.subTotal, pla.total, usu.nombre, usu.apellido, usu.cedula FROM planillas pla INNER JOIN usuarios usu ON pla.usuarioId = usu.id ORDER BY fecha DESC";
		$proceso1 = mysqli_query($conexion,$sql1);
		$contador1 = mysqli_num_rows($proceso1);
		if($contador1>=1){
			while($row1=mysqli_fetch_array($proceso1)){
				$nombre = $row1["nombre"]." ".$row1["apellido"];
				$cedula = $row1["cedula"];
				$fecha = $row1["fecha"];
				$pagoDia = $row1["pagoDia"];
				$pagoHora = $row1["pagoHora"];
				$horasExtras = $row1["horasExtras"];
				$diasFeriadosLaborados = $row1["diasFeriadosLaborados"];
				$aguinaldos = $row1["aguinaldos"];
				$ccss = $row1["ccss"];
				$isr = $row1["isr"];
				$subTotal = $row1["subTotal"];
				$total = $row1["total"];

				$hojaActiva->setCellValue('A'.$fila,$nombre);
				$hojaActiva->setCellValue('B'.$fila,$cedula);
				$hojaActiva->setCellValue('C'.$fila,$fecha);
				$hojaActiva->setCellValue('D'.$fila,$pagoDia);
				$hojaActiva->setCellValue('E'.$fila,$pagoHora);
				$hojaActiva->setCellValue('F'.$fila,$horasExtras);
				$hojaActiva->setCellValue('G'.$fila,$diasFeriadosLaborados);
				$hojaActiva->setCellValue('H'.$fila,$aguinaldos);
				$hojaActiva->setCellValue('I'.$fila,$ccss);
				$hojaActiva->setCellValue('J'.$fila,$isr);
				$hojaActiva->setCellValue('K'.$fila,$subTotal);
				$hojaActiva->setCellValue('L'.$fila,$total);
				$fila++;
			}

			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename=Planillas.xlsx');
			header('Cache-Control: max-age');
			$writer = IOFactory::createWriter($excel,'Xlsx');
			$writer->save('php://output');
		}else{
			echo '<script>window.close();</script>';
		}
	}

	if($asunto=="Aguinaldos"){
		$anio = $_POST['anio'];
		$excel = new Spreadsheet();
		$hojaActiva = $excel->getActiveSheet();
		$hojaActiva->setCellValue('A1','Nombre');
		$hojaActiva->setCellValue('B1','Cedula');
		$hojaActiva->setCellValue('C1','Enero');
		$hojaActiva->setCellValue('D1','Febrero');
		$hojaActiva->setCellValue('E1','Marzo');
		$hojaActiva->setCellValue('F1','Abril');
		$hojaActiva->setCellValue('G1','Mayo');
		$hojaActiva->setCellValue('H1','Junio');
		$hojaActiva->setCellValue('I1','Julio');
		$hojaActiva->setCellValue('J1','Agosto');
		$hojaActiva->setCellValue('K1','Septiembre');
		$hojaActiva->setCellValue('L1','Octubre');
		$hojaActiva->setCellValue('M1','Noviembre');
		$hojaActiva->setCellValue('N1','Diciembre');
		$fila = 2;
		$sql1 = "SELECT * FROM usuarios";
		$proceso1 = mysqli_query($conexion,$sql1);
		$contador1 = mysqli_num_rows($proceso1);
		$arrayMes = [0,'C','D','E','F','G','H','I','J','K','L','M','N'];
		if($contador1>=1){
			while($row1=mysqli_fetch_array($proceso1)){
				$usuarioId = $row1["id"];
				$nombre = $row1["nombre"]." ".$row1["apellido"];
				$cedula = $row1["cedula"];

				$hojaActiva->setCellValue('A'.$fila,$nombre);
				$hojaActiva->setCellValue('B'.$fila,$cedula);

				for ($i=1;$i<=12;$i++) { 
					
					if($i<10){
						$mesInicio = $anio."-0".$i."-01";
						$mesFin = $anio."-0".$i."-31";
					}else{
						$mesInicio = $anio."-0".$i."-01";
						$mesFin = $anio."-".$i."-31";
					}

					$sql2 = "SELECT * FROM planillas WHERE usuarioId = $usuarioId and fecha BETWEEN '$mesInicio' AND '$mesFin'";
					$proceso2 = mysqli_query($conexion,$sql2);
					$contador2 = mysqli_num_rows($proceso2);
					if($contador2>0){
						while($row2=mysqli_fetch_array($proceso2)){
							$total = $row2["total"];
							$hojaActiva->setCellValue($arrayMes[$i].$fila,$total);
						}
					}else{
						$hojaActiva->setCellValue($arrayMes[$i].$fila,"0");
					}
				}

				$fila++;
			}

			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename=Aguinaldos.xlsx');
			header('Cache-Control: max-age');
			$writer = IOFactory::createWriter($excel,'Xlsx');
			$writer->save('php://output');
		}else{
			echo '<script>window.close();</script>';
		}
	}


?>