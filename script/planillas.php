<?php
session_start();
include("conexion.php");
$fecha_creacion = date('Y-m-d');
$hora_creacion = date('h:i:s');
$asunto = $_POST['asunto'];

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
			                    <td style="text-align:center;">'.$total.'</td>
			                    <td style="text-align:center;" nowrap>
			                    	<button class="btn btn-primary" data-toggle="modal" data-target="#detalle" onclick="detalle('.$plaId.')">Detalle</button>
			                    </td>
			                </tr>
				';
			}
		}else{
			$html .= '<tr><td colspan="10" class="text-center" style="font-weight:bold;font-size:20px;">Sin Resultados</td></tr>';
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
			//$diasNoTrabajados = diasNoLaborados($conexion,$usuarioId,$inicioMes,$finMes);
			//$calculoDiasNoTrabajados = $diasNoTrabajados*$pagoAlDia;

			$horasExtras = horasExtras($conexion,$usuarioId,$inicioMes,$finMes);
			$calculoHorasExtras = $horasExtras*$pagoHoraExtra;
			$diasFeriadosLaborados = diasFeriados($conexion,$usuarioId,$inicioMes,$finMes);

			$horasFeriadosLaborados = horasFeriados($conexion,$usuarioId,$inicioMes,$finMes);
			//$calculoHorasFeriadosLaborados = $horasFeriadosLaborados*($pagoHora*2);
			$calculoHorasFeriadosLaborados = $horasFeriadosLaborados*$pagoHora;

			$calculoDiasFeriadosLaborados = $diasFeriadosLaborados*$pagoDiaFeriado;
			$montoAguinaldo = aguinaldo($conexion,$usuarioId,$anio,$mes);
			$permisosHorasSinGoce = permisosSinGoce($conexion,$usuarioId,$inicioMes,$finMes);
			$calculoHorasSinGoce = $pagoHora*$permisosHorasSinGoce;

			if($diasMesDiferencia>0 and $diasLaborados>0){
				$diasLaborados += $diasMesDiferencia;
			}else if($diasLaborados>30){
				$diasLaborados = 30;
				//Calculo de horas laboradas quitarle un día si es mayor a 30
			}

			$pagoDiasLaborados = $diasLaborados*$pagoAlDia;
			$pagoHorasLaborados = $horasTrabajados*$pagoHora;

			if($pagoDiasLaborados==0){
				$calculoHorasSinGoce = 0;
			}

			//$subTotal = ($pagoDiasLaborados+$calculoHorasExtras+$calculoDiasFeriadosLaborados);
			$subTotal = ($pagoHorasLaborados+$calculoHorasExtras+$calculoHorasFeriadosLaborados);
			$total = $subTotal-$calculoHorasSinGoce;

			//seguro social
			$ccss = round(($total*10.5)/100,2);
			//impuesto sobre la renta
			$isr = isr(round($total,2));
			$total = round($total-($ccss+$isr),2);

			$total += $montoAguinaldo;

			$sql2 = "INSERT INTO planillas (usuarioId,fecha,pagoDia,pagoHora,horasExtras,diasFeriadosLaborados,aguinaldos,subTotal,total,estatus,ccss,isr) VALUES ($usuarioId,'$finMes','$pagoAlDia','$pagoHora',$horasExtras,$diasFeriadosLaborados,'$montoAguinaldo','$subTotal','$total',0,'$ccss','$isr')";
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
		$datos = [
			"estatus"	=> "ok",
			"msg"	=> "Aceptados",
		];
		echo json_encode($datos);
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
			$sql2 = "SELECT * FROM diasFeriados WHERE mes = '$mes' and dia = '$dia'";
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
		$sql4 = "SELECT * FROM diasFeriados";
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
							$horasFeriados += diferenciaHoras($horaInicio,$horaInicio2);
						}
					}else{
						$horasFeriados += diferenciaHoras($horaInicio,$salida);
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
				$aguinaldo += $row1['total'];
			}
		}
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
		$sql1 = "SELECT * FROM permisosLaborales WHERE usuarioId = $usuarioId and tipo = 'Sin goce de salario' and fechaInicio BETWEEN '$inicioMes' AND '$finMes' and estatus = 1";
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
						if(diferenciaHoras($horaInicio,$horaInicio2)<8){
							$horasLaborados += diferenciaHoras($horaInicio,$horaInicio2);
						}else if(diferenciaHoras($horaInicio,$horaInicio2)>=8){
							$horasLaborados += 8;
						}
					}
				}else{
					if(diferenciaHoras($horaInicio,$salida)!=8){
						$horasLaborados += diferenciaHoras($horaInicio,$salida);
					}else if(diferenciaHoras($horaInicio,$salida)>=8){
						$horasLaborados += 8;
					}
				}
			}
		}
		return $horasLaborados;
	}



?>