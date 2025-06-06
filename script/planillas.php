<?php
session_start();
include("conexion.php");
$fecha_creacion = date('Y-m-d');
$hora_creacion = date('h:i:s');
$asunto = $_POST['asunto'];
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';

	if($asunto=='table1'){
		$pagina = $_POST["pagina"];
		$consultasporpagina = $_POST["consultasporpagina"];
		$filtrado = $_POST["filtrado"];
		$fecha = $_POST["fecha"];
		$fechaArray = explode('-',$fecha);
		$anio = $fechaArray[0];
		$mes = $fechaArray[1];
		$inicioMes = $fecha . "-01";
		$ultimoDia = date("t", strtotime($inicioMes));
		$finMes = $fecha . "-" . $ultimoDia;

		if($pagina==0 or $pagina==''){
			$pagina = 1;
		}

		if($consultasporpagina==0 or $consultasporpagina==''){
			$consultasporpagina = 10;
		}

		if($filtrado!=''){
			$filtrado = ' and (usu.nombre LIKE "%'.$filtrado.'%" or usu.apellido LIKE "%'.$filtrado.'%" or usu.apellido2 LIKE "%'.$filtrado.'%" or usu.cedula LIKE "%'.$filtrado.'%")';
		}

		$limit = $consultasporpagina;
		$offset = ($pagina - 1) * $consultasporpagina;

		$sql1 = "SELECT pla.id as plaId, pla.horasExtras, pla.diasFeriadosLaborados, pla.aguinaldos, pla.subTotal, pla.total, pla.ccss, pla.isr, usu.nombre, usu.apellido, usu.apellido2, usu.cedula, usu.salario, usu.id as usuarioId
		FROM planillas pla 
		INNER JOIN usuarios usu
		ON pla.usuarioId = usu.id 
		WHERE usu.estado = 'Activo' and pla.fecha = '$finMes' ".$filtrado;

		$sql2 = "SELECT pla.id as plaId, pla.horasExtras, pla.diasFeriadosLaborados, pla.aguinaldos, pla.subTotal, pla.total, pla.ccss, pla.isr, usu.nombre, usu.apellido, usu.apellido2, usu.cedula, usu.salario, usu.id as usuarioId
		FROM planillas pla 
		INNER JOIN usuarios usu
		ON pla.usuarioId = usu.id 
		WHERE usu.estado = 'Activo' and pla.fecha = '$finMes' ".$filtrado." ORDER BY pla.id DESC LIMIT ".$limit." OFFSET ".$offset;

		$proceso1 = mysqli_query($conexion,$sql1);
		$proceso2 = mysqli_query($conexion,$sql2);
		$conteo1 = mysqli_num_rows($proceso1);
		$paginas = ceil($conteo1 / $consultasporpagina);

		$html = '';

		$html .= '
			<div class="col-12">
		        <table class="table table-bordered">
		            <thead>
		            <tr>
						<th class="text-center">Usuario</th>
						<th class="text-center">Cédula</th>
						<th class="text-center">Salario</th>
						<th class="text-center">Horas extras</th>
						<th class="text-center">Feriados</th>
						<th class="text-center">Sub-Total</th>
						<th class="text-center">Ccss</th>
						<th class="text-center">Isr</th>
						<th class="text-center">Rebajos</th>
						<th class="text-center">Total</th>
						<th class="text-center">Opciones</th>
		            </tr>
		            </thead>
		            <tbody>
		';
		if($conteo1>=1){
			while($row2 = mysqli_fetch_array($proceso2)) {
				$plaId = $row2["plaId"];
				$usuarioId = $row2["usuarioId"];
				$nombre = $row2["nombre"]." ".$row2["apellido"]." ".$row2["apellido2"];
				$cedula = $row2["cedula"];
				$salario = $row2["salario"];
				$horasExtras = $row2["horasExtras"];
				$diasFeriadosLaborados = $row2["diasFeriadosLaborados"];
				$subTotal = $row2["subTotal"];
				$ccss = $row2["ccss"];
				$isr = $row2["isr"];
				$rebajos = $ccss+$isr;
				$total = $row2["total"];
				$html .= '
			                <tr id="">
			                    <td style="text-align:center;">'.$nombre.'</td>
			                    <td style="text-align:center;">'.$cedula.'</td>
			                    <td style="text-align:center;">'.$salario.'</td>
			                    <td style="text-align:center;">'.$horasExtras.'</td>
			                    <td style="text-align:center;">'.$diasFeriadosLaborados.'</td>
			                    <td style="text-align:center;">'.$subTotal.'</td>
			                    <td style="text-align:center;">'.$ccss.'</td>
			                    <td style="text-align:center;">'.$isr.'</td>
			                    <td style="text-align:center;">'.$rebajos.'</td>
			                    <td style="text-align:center;">'.$total.'</td>
			                    <td style="text-align:center;" nowrap>
			                    	<button class="btn btn-primary" data-toggle="modal" data-target="#detalle" onclick="detalle('.$plaId.')">Detalle</button>
			                    </td>
			                </tr>
				';
			}
		}else{
			$html .= '<tr><td colspan="11" class="text-center" style="font-weight:bold;font-size:20px;">Sin Resultados</td></tr>';
		}

		$html .= '
		            </tbody>
		        </table>
		        <nav>
		            <div class="row">
		                <div class="col-xs-12 col-sm-4 text-center">
		                    <p>Mostrando '.$consultasporpagina.' de '.$conteo1.' Datos disponibles</p>
		                </div>
		                <div class="col-xs-12 col-sm-4 text-center">
		                    <p>Página '.$pagina.' de '.$paginas.' </p>
		                </div> 
		                <div class="col-xs-12 col-sm-4">
				            <nav aria-label="Page navigation" style="float:right; padding-right:2rem;">
								<ul class="pagination">
		';
		
		if ($pagina > 1) {
			$html .= '
									<li class="page-item">
										<a class="page-link" onclick="paginacion1('.($pagina-1).');" href="#">
											<span aria-hidden="true">Anterior</span>
										</a>
									</li>
			';
		}

		$diferenciapagina = 3;
		
		/*********MENOS********/
		if($pagina==2){
			$html .= '
			                		<li class="page-item">
				                        <a class="page-link" onclick="paginacion1('.($pagina-1).');" href="#">
				                            '.($pagina-1).'
				                        </a>
				                    </li>
			';
		}else if($pagina==3){
			$html .= '
				                    <li class="page-item">
				                        <a class="page-link" onclick="paginacion1('.($pagina-2).');" href="#"">
				                            '.($pagina-2).'
				                        </a>
				                    </li>
				                    <li class="page-item">
				                        <a class="page-link" onclick="paginacion1('.($pagina-1).');" href="#"">
				                            '.($pagina-1).'
				                        </a>
				                    </li>
		';
		}else if($pagina>=4){
			$html .= '
			                		<li class="page-item">
				                        <a class="page-link" onclick="paginacion1('.($pagina-3).');" href="#"">
				                            '.($pagina-3).'
				                        </a>
				                    </li>
				                    <li class="page-item">
				                        <a class="page-link" onclick="paginacion1('.($pagina-2).');" href="#"">
				                            '.($pagina-2).'
				                        </a>
				                    </li>
				                    <li class="page-item">
				                        <a class="page-link" onclick="paginacion1('.($pagina-1).');" href="#"">
				                            '.($pagina-1).'
				                        </a>
				                    </li>
			';
		} 

		/*********MAS********/
		$opcionmas = $pagina+3;
		if($paginas==0){
			$opcionmas = $paginas;
		}else if($paginas>=1 and $paginas<=4){
			$opcionmas = $paginas;
		}
		
		for ($x=$pagina;$x<=$opcionmas;$x++) {
			$html .= '
				                    <li class="page-item 
			';

			if ($x == $pagina){ 
				$html .= '"active"';
			}

			$html .= '">';

			$html .= '
				                        <a class="page-link" onclick="paginacion1('.($x).');" href="#"">'.$x.'</a>
				                    </li>
			';
		}

		if ($pagina < $paginas) {
			$html .= '
				                    <li class="page-item">
				                        <a class="page-link" onclick="paginacion1('.($pagina+1).');" href="#"">
				                            <span aria-hidden="true">Siguiente</span>
				                        </a>
				                    </li>
			';
		}

		$html .= '

							</ul>
						</nav>
					</div>
		        </nav>
		    </div>
		';

		$datos = [
			"estatus"	=> "ok",
			"html"	=> $html,
		];
		echo json_encode($datos);
	}

	if($asunto=='consultar'){
		$fecha = $_POST['value'];
		$fechaArray = explode('-',$fecha);
		$anio = $fechaArray[0];
		$mes = $fechaArray[1];
		$inicioMes = $fecha . "-01";
		$ultimoDia = date("t", strtotime($inicioMes));
		$finMes = $fecha . "-" . $ultimoDia;
		$sql1 = "SELECT * FROM planillas WHERE fecha = '$finMes'";
		$proceso1 = mysqli_query($conexion,$sql1);
		$contador1 = mysqli_num_rows($proceso1);
		$html = '<br>';
		if($contador1==0){
			$html .= '<button type="button" class="btn btn-success" onclick="generar();">Generar</button>';
		}else{
			while($row1=mysqli_fetch_array($proceso1)){
				$estatus = $row1["estatus"];
			}
			$html .= '
				<button type="button" class="btn btn-info" onclick="filtrar1();">Filtrar</button>
			';
			if($estatus==0){
				$html .= '
					<button type="button" class="btn btn-success" onclick="aceptar(\''.$finMes.'\');">Aceptar</button>
					<button type="button" class="btn btn-danger" onclick="eliminar(\''.$finMes.'\');">Eliminar</button>
				';
			}
		}
		$datos = [
			"estatus"	=> "ok",
			"contador" => $contador1,
			"html"	=> $html,
		];
		echo json_encode($datos);
	}

	if($asunto=='generar'){
		$fecha = $_POST['fecha'];
		$fechaArray = explode('-',$fecha);
		$anio = $fechaArray[0];
		$mes = $fechaArray[1];
		$inicioMes = $fecha . "-01";
		$ultimoDia = date("t", strtotime($inicioMes));
		$finMes = $fecha . "-" . $ultimoDia;
		$sql1 = "SELECT * FROM usuarios WHERE estado = 'Activo'";
		//$sql1 = "SELECT * FROM usuarios WHERE estado = 'Activo' and id = 2";
		$proceso1 = mysqli_query($conexion,$sql1);
		$contador1 = mysqli_num_rows($proceso1);
		if($contador1==0){
			$datos = [
				"estatus"	=> "error",
				"msg"	=> "No quedan usuarios Activos",
			];
			echo json_encode($datos);
			exit;
		}
		while($row1=mysqli_fetch_array($proceso1)){
			$usuarioId = $row1['id'];
			$salarioActual = $row1['salario'];
			$pagoAlDia = round($salarioActual/30,2);
			$pagoDiaFeriado = $pagoAlDia*2;
			$pagoHora = round($pagoAlDia/8,2);
			$pagoHoraExtra = round($pagoHora*1.5,2);
			$diasTrabajados = diasLaborados($conexion,$usuarioId,$inicioMes,$finMes);
			$horasTrabajados = horasLaborados($conexion,$usuarioId,$inicioMes,$finMes);
			$diasMesDiferencia = $diasTrabajados["diasMesDiferencia"];
			$diasLaborados = $diasTrabajados["diasLaborados"];
			$civil = $row1["civil"];
			$hijos = $row1["hijos"];
			$sumatoriaCivilHijos = 0;
			//$diasNoTrabajados = diasNoLaborados($conexion,$usuarioId,$inicioMes,$finMes);
			//$calculoDiasNoTrabajados = $diasNoTrabajados*$pagoAlDia;

			$horasExtras = horasExtras($conexion,$usuarioId,$inicioMes,$finMes);
			$calculoHorasExtras = $horasExtras*$pagoHoraExtra;
			$diasFeriadosLaborados = diasFeriados($conexion,$usuarioId,$inicioMes,$finMes);
			$horasFeriadosLaborados = horasFeriados($conexion,$usuarioId,$inicioMes,$finMes);
			$calculoHorasFeriadosLaborados = $horasFeriadosLaborados*$pagoHora;
			$montoAguinaldo = aguinaldo($conexion,$usuarioId,$anio,$mes);
			$permisosHorasSinGoce = permisosSinGoce($conexion,$usuarioId,$inicioMes,$finMes);
			$calculoHorasSinGoce = $pagoHora*$permisosHorasSinGoce;

			$cuentasDiasIncapacidades = incapacidades($conexion,$usuarioId,$inicioMes,$finMes,$pagoAlDia);

			$domingosPagos = 0;
			$calculoDomingosPagos = 0;
			if($diasLaborados>0){
				$domingosPagos = contarDomingos($inicioMes,$finMes);
				if($domingosPagos>0){
					$calculoDomingosPagos = $domingosPagos*$pagoAlDia;
				}
			}

			if($diasMesDiferencia>0 and $diasLaborados>0){
				$diasLaborados += $diasMesDiferencia;
			}else if($diasMesDiferencia<0 and $diasLaborados>0){
				$diasLaborados += $diasMesDiferencia;
			}else if(($diasLaborados+$domingosPagos)>30){
				$diasLaborados = 30;
			}

			$pagoDiasLaborados = $diasLaborados*$pagoAlDia;
			$pagoHorasLaborados = $horasTrabajados*$pagoHora;
			/*
			if($pagoHorasLaborados>$pagoDiasLaborados){
				$pagoHorasLaborados = $pagoDiasLaborados;
			}
			*/

			if($pagoDiasLaborados==0){
				$calculoHorasSinGoce = 0;
			}

			$horasPermisosLaborales = calcularHorasPermisos($conexion,$usuarioId,$inicioMes,$finMes);
			$calculoHorasPermisosLaborales = $horasPermisosLaborales*$pagoHora;


			if($civil == "Casado/a"){
				$calculoCivil = 2600;
			}else{
				$calculoCivil = 0;
			}

			if($hijos>0){
				$calculoHijos = 1720*$hijos;
			}else{
				$calculoHijos = 0;
			}
			
			$montoLaborado = $pagoHorasLaborados;
			$subTotal = ($pagoHorasLaborados+$calculoHorasExtras+$calculoHorasFeriadosLaborados+$calculoHorasPermisosLaborales+$calculoDomingosPagos+$cuentasDiasIncapacidades);

			if($subTotal>=($calculoCivil+$calculoHijos)){
				$sumatoriaCivilHijos = $calculoCivil+$calculoHijos;
			}


			$total = $subTotal-$calculoHorasSinGoce;
			//seguro social
			$ccss = round(($total*11.16)/100,2);

			//$total2 = $subTotal-($calculoHorasSinGoce+$sumatoriaCivilHijos);
			//impuesto sobre la renta
			//$isr = isr(round($total2,2));
			$isr = isr(round($total,2));
			$isr -= $sumatoriaCivilHijos;

			$total = round($total-($ccss+$isr),2);
			$total += $calculoHorasSinGoce;

			$sql2 = "INSERT INTO planillas (usuarioId,fecha,pagoDia,pagoHora,horasExtras,diasFeriadosLaborados,aguinaldos,montoLaborado,subTotal,total,estatus,ccss,isr,civil,hijos) VALUES ($usuarioId,'$finMes','$pagoAlDia','$pagoHora',$horasExtras,$diasFeriadosLaborados,'$montoAguinaldo','$montoLaborado','$subTotal','$total',0,'$ccss','$isr','$calculoCivil','$calculoHijos')";
			$proceso2 = mysqli_query($conexion,$sql2);
		}
		$datos = [
			"estatus"	=> "ok",
			"msg"	=> "Se han creado los pagos",
		];
		echo json_encode($datos);
	}

	if($asunto=='cambioEstatus'){
		$id = $_POST['id'];
		$estatus = $_POST['estatus'];
		$sql1 = "UPDATE liquidaciones SET estatus = $estatus WHERE id = ".$id;
		$proceso1 = mysqli_query($conexion,$sql1);
		$datos = [
			"estatus"	=> "ok",
		];
		echo json_encode($datos);
	}

	if($asunto=='eliminar'){
		$fecha = $_POST['fecha'];
		$sql1 = "DELETE FROM planillas WHERE fecha = '$fecha'";
		$proceso1 = mysqli_query($conexion,$sql1);
		$datos = [
			"estatus"	=> "ok",
			"msg"	=> "Eliminados",
		];
		echo json_encode($datos);
	}

	if($asunto=='aceptar'){
		$fecha = $_POST['fecha'];
		$sql1 = "UPDATE planillas SET estatus = 1 WHERE fecha = '$fecha'";
		$proceso1 = mysqli_query($conexion,$sql1);
		enviarEmail($conexion,$fecha);
	}

	if($asunto=='detalle'){
		$id = $_POST['id'];
		$sql1 = "SELECT pla.id as plaId, pla.ccss, pla.isr, pla.horasExtras, pla.diasFeriadosLaborados, pla.aguinaldos, pla.total, usu.nombre, usu.apellido, usu.cedula, usu.salario, usu.id as usuarioId
		FROM planillas pla 
		INNER JOIN usuarios usu
		ON pla.usuarioId = usu.id 
		WHERE pla.id = $id";
		$proceso1 = mysqli_query($conexion,$sql1);
		while($row1=mysqli_fetch_array($proceso1)){
			$horasExtras = $row1['horasExtras'];
			$diasFeriadosLaborados = $row1['diasFeriadosLaborados'];
			$aguinaldos = $row1['aguinaldos'];
			$total = $row1['total'];
			$nombre = $row1['nombre'];
			$apellido = $row1['apellido'];
			$cedula = $row1['cedula'];
			$salario = $row1['salario'];
			$ccss = $row1['ccss'];
			$isr = $row1['isr'];
			$rebajos = $ccss+$isr;
		}
		$datos = [
			"estatus"	=> "ok",
			"horasExtras"	=> $horasExtras,
			"diasFeriadosLaborados"	=> $diasFeriadosLaborados,
			"aguinaldos"	=> $aguinaldos,
			"total"	=> $total,
			"nombre"	=> $nombre,
			"apellido"	=> $apellido,
			"cedula"	=> $cedula,
			"salario"	=> $salario,
			"ccss"	=> $ccss,
			"isr"	=> $isr,
			"rebajos"	=> $rebajos,
		];
		echo json_encode($datos);
	}

	function diasNoLaborados($conexion,$usuarioId,$inicioMes,$finMes){
		$fechaInicio = new DateTime($inicioMes);
		$fechaFin = new DateTime($finMes);
		$diferencia = $fechaInicio->diff($fechaFin);
		$diferenciaDias = ($diferencia->days)+1;
		$sql1 = "SELECT * FROM turnos WHERE usuarioId = $usuarioId and fechaInicio BETWEEN '$inicioMes' AND '$finMes' and tipo = 'Entrada'";
		$proceso1 = mysqli_query($conexion,$sql1);
		$contador1 = mysqli_num_rows($proceso1);
		$diasNoLaborados = ($diferenciaDias-$contador1);
		return $diasNoLaborados;
	}

	function diasLaborados($conexion,$usuarioId,$inicioMes,$finMes){
		$fechaInicio = new DateTime($inicioMes);
		$fechaFin = new DateTime($finMes);
		$diferencia = $fechaInicio->diff($fechaFin);
		$diasMes = ($diferencia->days)+1;
		$sql1 = "SELECT * FROM turnos WHERE usuarioId = $usuarioId and fechaInicio BETWEEN '$inicioMes' AND '$finMes' and tipo = 'Entrada'";
		$proceso1 = mysqli_query($conexion,$sql1);
		$contador1 = mysqli_num_rows($proceso1);
		$diasLaborados = $contador1;
		$diasMesDiferencia = 30-$diasMes;
		$datos = [
			"diasMesDiferencia"	=> $diasMesDiferencia,
			"diasLaborados"	=> $diasLaborados,
		];
		return $datos;
	}

	function horasExtras($conexion,$usuarioId,$inicioMes,$finMes){
		$horasExtras = 0;
		$sql1 = "SELECT hor.entradaMaxima, hor.entrada, hor.salida FROM horarios hor INNER JOIN usuarios usu ON hor.id = usu.horarios WHERE usu.id = $usuarioId";
		$proceso1 = mysqli_query($conexion,$sql1);
		while($row1=mysqli_fetch_array($proceso1)){
			$entradaMaxima = $row1["entradaMaxima"];
			$entrada = $row1["entrada"];
			$salida = $row1["salida"];
		}
		$sql2 = "SELECT * FROM turnos WHERE usuarioId = $usuarioId and fechaInicio BETWEEN '$inicioMes' AND '$finMes' and tipo = 'Entrada'";
		$proceso2 = mysqli_query($conexion,$sql2);
		$contador2 = mysqli_num_rows($proceso2);
		if($contador2>0){
			while($row2=mysqli_fetch_array($proceso2)){
				$fechaCiclo = $row2["fechaInicio"];
				$horaInicio = $row2["horaInicio"];
				if($horaInicio<$entrada){
					$horaInicio = $entrada;
				}
				$sql3 = "SELECT * FROM turnos WHERE usuarioId = $usuarioId and fechaInicio = '$fechaCiclo' and tipo = 'Salida' and estatusExtras = 1";
				$proceso3 = mysqli_query($conexion,$sql3);
				$contador3 = mysqli_num_rows($proceso3);
				if($contador3>0){
					while($row3=mysqli_fetch_array($proceso3)){
						$horaInicio2 = $row3["horaInicio"];
						if(diferenciaHoras($horaInicio,$horaInicio2)>9){
							$horasExtras += diferenciaHoras($horaInicio,$horaInicio2)-9;
						}
					}
				}else{
					if(diferenciaHoras($horaInicio,$salida)>9){
						$horasExtras += diferenciaHoras($horaInicio,$salida)-9;
					}
				}
			}
		}
		return $horasExtras;
	}

	function diferenciaHoras($rango1,$rango2){
		$segundos1 = strtotime($rango1);
		$segundos2 = strtotime($rango2);
		$diferenciaSegundos = abs($segundos2 - $segundos1);
		$diferenciaHoras = floor($diferenciaSegundos / 3600);
		return $diferenciaHoras;
	}

	function diasFeriados($conexion,$usuarioId,$inicioMes,$finMes){
		$diasTotales = 0;
		$sql1 = "SELECT * FROM turnos WHERE usuarioId = $usuarioId and tipo = 'Entrada' and fechaInicio BETWEEN '$inicioMes' AND '$finMes'";
		$proceso1 = mysqli_query($conexion,$sql1);
		while($row1=mysqli_fetch_array($proceso1)){
			$fechaInicio = $row1["fechaInicio"];
			$fechaArray = explode('-',$fechaInicio);
			$mes = $fechaArray[1];
			$dia = $fechaArray[2];
			$sql2 = "SELECT * FROM diasferiados WHERE mes = '$mes' and dia = '$dia'";
			$proceso2 = mysqli_query($conexion,$sql2);
			$contador2 = mysqli_num_rows($proceso2);
			if($contador2>0){
				$diasTotales++;
			}
		}
		return $diasTotales;
	}

	function horasFeriados($conexion,$usuarioId,$inicioMes,$finMes){
		$horasFeriados = 0;
		$sql1 = "SELECT hor.entradaMaxima, hor.entrada, hor.salida FROM horarios hor INNER JOIN usuarios usu ON hor.id = usu.horarios WHERE usu.id = $usuarioId";
		$proceso1 = mysqli_query($conexion,$sql1);
		while($row1=mysqli_fetch_array($proceso1)){
			$entradaMaxima = $row1["entradaMaxima"];
			$entrada = $row1["entrada"];
			$salida = $row1["salida"];
		}

		$inicioMesArray = explode("-",$inicioMes);
		$inicioMesMes = $inicioMesArray[1];
		$inicioMesDia = $inicioMesArray[2];

		$finMesArray = explode("-",$finMes);
		$finMesDia = $finMesArray[2];

		$sql4 = "SELECT * FROM diasferiados WHERE (dia BETWEEN '$inicioMesDia' AND '$finMesDia' and mes = '$inicioMesMes')";
		$proceso4 = mysqli_query($conexion,$sql4);
		while($row4=mysqli_fetch_array($proceso4)){
			$dia = $row4["dia"];
			$mes = $row4["mes"];
			$anio = explode('-',$finMes);
			$anio = $anio[0];
			$fechaFeriado = $anio."-".$mes."-".$dia;
			$sql2 = "SELECT * FROM turnos WHERE usuarioId = $usuarioId and fechaInicio = '$fechaFeriado' and tipo = 'Entrada'";
			$proceso2 = mysqli_query($conexion,$sql2);
			$contador2 = mysqli_num_rows($proceso2);
			if($contador2>0){
				while($row2=mysqli_fetch_array($proceso2)){
					$fechaCiclo = $row2["fechaInicio"];
					$horaInicio = $row2["horaInicio"];
					if($horaInicio<$entrada){
						$horaInicio = $entrada;
					}
					$sql3 = "SELECT * FROM turnos WHERE usuarioId = $usuarioId and fechaInicio = '$fechaCiclo' and tipo = 'Salida'";
					$proceso3 = mysqli_query($conexion,$sql3);
					$contador3 = mysqli_num_rows($proceso3);
					if($contador3>0){
						while($row3=mysqli_fetch_array($proceso3)){
							$horaInicio2 = $row3["horaInicio"];
							if(diferenciaHoras($horaInicio,$horaInicio2)>=8){
								$horasFeriados += 8;	
							}else{
								$horasFeriados += diferenciaHoras($horaInicio,$horaInicio2);
							}
						}
					}else{
						if(diferenciaHoras($horaInicio,$salida)>=8){
							$horasFeriados += 8;
						}else{
							$horasFeriados += diferenciaHoras($horaInicio,$salida);
						}
					}
				}
			}
		}
		return $horasFeriados;
	}

	function aguinaldo($conexion,$usuarioId,$anio,$mes){
		$aguinaldo = 0;
		if($mes!=12){
			return $aguinaldo;
		}

		$fechaInicio = ($anio - 1) . "-11-01";
		$date = new DateTime($fechaInicio);

		for($i=1;$i<=12;$i++){
			$date->modify('+1 month');
			$fechaResult = $date->format('Y-m-t');
			$sql1 = "SELECT * FROM planillas WHERE usuarioId = $usuarioId and fecha = '$fechaResult'";
			$proceso1 = mysqli_query($conexion,$sql1);
			while($row1=mysqli_fetch_array($proceso1)){
				$aguinaldo += $row1['subTotal'];
			}
		}
		$aguinaldo = round($aguinaldo/12,2);
		return $aguinaldo;
	}

	function isr($salario_bruto){
		$tramos = [
	        [922000, 0.00],
	        [1352000, 0.10],
	        [2373000, 0.15],
	        [4745000, 0.20],
	        [PHP_INT_MAX, 0.25]
	    ];
	    
	    $impuesto_total = 0;
	    $exceso_anterior = 0;
	    
	    foreach ($tramos as [$limite, $tasa]) {
	        if ($salario_bruto > $limite) {
	            $exceso = $limite - $exceso_anterior;
	        } else {
	            $exceso = $salario_bruto - $exceso_anterior;
	        }
	        
	        if ($exceso > 0) {
	            $impuesto_total += $exceso * $tasa;
	        }
	        
	        $exceso_anterior = $limite;
	        if ($salario_bruto <= $limite) break;
	    }
	    
	    return $impuesto_total;

	    /*
		if($total<=941000){
			$resultado = 0;
		}else if ($total>941 and $total <=1381000){
			$resultado = ($total*10)/100;
		}else if ($total>1381000 and $total <=2423000){
			$resultado = ($total*15)/100;
		}else if ($total>2423000 and $total <=4845000){
			$resultado = ($total*20)/100;
		}else if ($total>4845000){
			$resultado = ($total*25)/100;
		}
		return $resultado;
		*/
	}

	function permisosSinGoce($conexion,$usuarioId,$inicioMes,$finMes){
		$horasRestar = 0;
		$sql1 = "SELECT * FROM permisoslaborales WHERE usuarioId = $usuarioId and tipo = 'Sin goce de salario' and fechaInicio BETWEEN '$inicioMes' AND '$finMes' and estatus = 1";
		$proceso1 = mysqli_query($conexion,$sql1);
		$contador1 = mysqli_num_rows($proceso1);
		if($contador1>0){
			while($row1=mysqli_fetch_array($proceso1)){
				$horaInicio = $row1["horaInicio"];
				$horaFin = $row1["horaFin"];
				$horasRestar -= diferenciaHoras($horaInicio,$horaFin);
			}
		}
		return abs($horasRestar);
	}

	function horasLaborados($conexion,$usuarioId,$inicioMes,$finMes){
		$horasLaborados = 0;
		$sql1 = "SELECT hor.entradaMaxima, hor.entrada, hor.salida FROM horarios hor INNER JOIN usuarios usu ON hor.id = usu.horarios WHERE usu.id = $usuarioId";
		$proceso1 = mysqli_query($conexion,$sql1);
		while($row1=mysqli_fetch_array($proceso1)){
			$entradaMaxima = $row1["entradaMaxima"];
			$entrada = $row1["entrada"];
			$salida = $row1["salida"];
		}
		$sql2 = "SELECT * FROM turnos WHERE usuarioId = $usuarioId and fechaInicio BETWEEN '$inicioMes' AND '$finMes' and tipo = 'Entrada'";
		$proceso2 = mysqli_query($conexion,$sql2);
		$contador2 = mysqli_num_rows($proceso2);
		if($contador2>0){
			while($row2=mysqli_fetch_array($proceso2)){
				$fechaCiclo = $row2["fechaInicio"];
				$horaInicio = $row2["horaInicio"];
				if($horaInicio<$entrada){
					$horaInicio = $entrada;
				}
				$sql3 = "SELECT * FROM turnos WHERE usuarioId = $usuarioId and fechaInicio = '$fechaCiclo' and tipo = 'Salida'";
				$proceso3 = mysqli_query($conexion,$sql3);
				$contador3 = mysqli_num_rows($proceso3);
				if($contador3>0){
					while($row3=mysqli_fetch_array($proceso3)){
						$horaInicio2 = $row3["horaInicio"];
						if(diferenciaHoras($horaInicio,$horaInicio2)<9){
							$horasLaborados += diferenciaHoras($horaInicio,$horaInicio2);
						}else if(diferenciaHoras($horaInicio,$horaInicio2)>=9){
							$horasLaborados += 9;
						}
					}
				}else{
					if(diferenciaHoras($horaInicio,$salida)<9){
						$horasLaborados += diferenciaHoras($horaInicio,$salida);
					}else if(diferenciaHoras($horaInicio,$salida)>=9){
						$horasLaborados += 9;
					}
				}
				$horasLaborados -= 1;
			}
		}
		return $horasLaborados;
	}

	function calcularHorasPermisos($conexion,$usuarioId,$inicioMes,$finMes){
		$horasPermisos = 0;
		$sql1 = "SELECT * FROM permisoslaborales WHERE usuarioId = $usuarioId and tipo = 'Goce de salario' and estatus = 1 and fechaInicio BETWEEN '$inicioMes' AND '$finMes'";
		$proceso1 = mysqli_query($conexion,$sql1);
		$contador1 = mysqli_num_rows($proceso1);
		if($contador1>0){
			while($row1=mysqli_fetch_array($proceso1)){
				$horaInicio = $row1["horaInicio"];
				$horaFin = $row1["horaFin"];
				if(diferenciaHoras($horaInicio,$horaFin)>=9){
					$horasPermisos += diferenciaHoras($horaInicio,$horaFin)-1;
				}else{
					$horasPermisos += diferenciaHoras($horaInicio,$horaFin);
				}
			}
		}
		return $horasPermisos;
	}

	function contarDomingos($fechaInicio, $fechaFin) {
	    $inicio = new DateTime($fechaInicio);
	    $fin = new DateTime($fechaFin);
	    $contador = 0;

	    while ($inicio <= $fin) {
	        if ($inicio->format('w') == 0) {
	            $contador++;
	        }
	        $inicio->modify('+1 day');
	    }

	    return $contador;
	}

	function incapacidades($conexion,$usuarioId,$inicioMes,$finMes,$pagoAlDia){
		$total = 0;
		$sql1 = "SELECT * FROM incapacidades WHERE usuarioId = $usuarioId and estatus = 1 and fechaInicio BETWEEN '$inicioMes' AND '$finMes' and fechaFin BETWEEN '$inicioMes' AND '$finMes' ";
		$proceso1 = mysqli_query($conexion,$sql1);
		while($row1=mysqli_fetch_array($proceso1)){
			//$total += $row1["diasTotales"];
			$fechaInicio = $row1["fechaInicio"];
			$fechaFin = $row1["fechaFin"];

			$fechaInicio = new DateTime($fechaInicio);
			$fechaFin = new DateTime($fechaFin);
			$diferencia = $fechaInicio->diff($fechaFin);
			$diferenciaDias = ($diferencia->days)+1;

			if($diferenciaDias>0 and $diferenciaDias<4){
				$total += $diferenciaDias*($pagoAlDia*0.5);
			}
		}
		return $total;
	}

	function enviarEmail($conexion,$fecha){
		$sql1 = "SELECT * FROM planillas WHERE fecha = '$fecha'";
		$proceso1 = mysqli_query($conexion,$sql1);
		$contador1 = mysqli_num_rows($proceso1);
		if($contador1>0){
			try {
			    while($row1=mysqli_fetch_array($proceso1)){
			    	$mail = new PHPMailer(true);
				    $mail->isSMTP();
				    $mail->setFrom('info@dolarsoft.com', 'DolarSoft');
				    $mail->Host = 'mail.dolarsoft.com';
				    $mail->SMTPAuth = true;
				    $mail->Username = 'info@dolarsoft.com';
				    $mail->Password = 'contaseñaDolarSoft';
				    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
				    $mail->Port = 587;

			    	$usuarioId = $row1["usuarioId"];
			    	$aguinaldos = $row1["aguinaldos"];
			    	$ccss = $row1["ccss"];
			    	$isr = $row1["isr"];
			    	$montoLaborado = $row1["montoLaborado"];
			    	$subTotal = $row1["subTotal"];
			    	$total = $row1["total"];

			    	$sql2 = "SELECT * FROM usuarios WHERE id = $usuarioId";
			    	$proceso2 = mysqli_query($conexion,$sql2);
			    	while($row2=mysqli_fetch_array($proceso2)){
			    		$nombre = $row2["nombre"];
			    		$apellido = $row2["apellido"];
			    		$apellido2 = $row2["apellido2"];
			    		$correo = $row2["correo"];
			    		$nombreCompleto = $nombre." ".$apellido." ".$apellido2;
			    	}

			    	$mail->addAddress($correo, $nombre);
			    	$mail->isHTML(true);
			    	$mail->Subject = 'Planilla de pago '.$fecha;
			    	$body = '
						<h2>Estimado/a '.$nombreCompleto.'</h2>
						<p>Se ha generado su planilla digital con los siguientes detalles de pago.</p>
						<p>Aguinaldo: '.$aguinaldos.'</p>
						<p>ccss: '.$ccss.'</p>
						<p>isr: '.$isr.'</p>
						<p>Monto Laborado: '.$montoLaborado.'</p>
						<p>Sub-Total: '.$subTotal.'</p>
						<p>Total: '.$total.'</p>
						<h4>Para mas información o alguno inquietud comunicarse con gerencia</h4>
					';
			    	$mail->Body = $body;
			    	$mail->send();
			    }
			    $datos = [
					"estatus"	=> "ok",
					"msg"	=> "Aceptados",
				];
				echo json_encode($datos);
			} catch (Exception $e) {
			    $datos = [
					"estatus"	=> "info",
					"msg"	=> "Algo ha fallado en el envio de los correos",
				];
				echo json_encode($datos);
			}
		}
	}


?>