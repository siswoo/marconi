<?php
session_start();
include("conexion.php");
$fecha_creacion = date('Y-m-d');
$hora_creacion = date('h:i:s');
$asunto = $_POST['asunto'];

	if($asunto=='table1'){
		$rol = $_POST["rol"];
		$usuario = $_POST["usuario"];
		$pagina = $_POST["pagina"];
		$consultasporpagina = $_POST["consultasporpagina"];
		$filtrado = $_POST["filtrado"];
		$fecha = $_POST["fecha"];

		if($pagina==0 or $pagina==''){
			$pagina = 1;
		}

		if($consultasporpagina==0 or $consultasporpagina==''){
			$consultasporpagina = 10;
		}

		if($filtrado!=''){
			$filtrado = ' and (nombre LIKE "%'.$filtrado.'%" or apellido LIKE "%'.$filtrado.'%" or apellido2 LIKE "%'.$filtrado.'%" or cedula LIKE "%'.$filtrado.'%")';
		}

		$limit = $consultasporpagina;
		$offset = ($pagina - 1) * $consultasporpagina;

		if($rol==1){
			$rolCon = ' rol > 0';
		}else{
			$rolCon = ' id = '.$usuario;
		}


		$sql1 = "SELECT * FROM usuarios WHERE ".$rolCon.$filtrado;
		$sql2 = "SELECT * FROM usuarios WHERE ".$rolCon.$filtrado." ORDER BY id DESC LIMIT ".$limit." OFFSET ".$offset;

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
						<th class="text-center">Empleado</th>
						<th class="text-center">Cédula</th>
						<th class="text-center">Fecha Ingreso</th>
						<th class="text-center">Fecha Retiro</th>
						<th class="text-center">Estatus</th>
						<th class="text-center">Salario actual</th>
						<th class="text-center">Opciones</th>
		            </tr>
		            </thead>
		            <tbody>
		';
		if($conteo1>=1){
			while($row2 = mysqli_fetch_array($proceso2)) {
				$id = $row2["id"];
				$nombre = $row2["nombre"]." ".$row2["apellido"]." ".$row2["apellido2"];
				$cedula = $row2["cedula"];
				$fechaIngreso = $row2["fechaIngreso"];
				$fechaRetiro = $row2["fechaRetiro"];
				$estado = $row2["estado"];
				$salario = $row2["salario"];
				$sql3 = "SELECT * FROM liquidaciones WHERE usuarioId = $id";
				$proceso3 = mysqli_query($conexion,$sql3);
				$conteo3 = mysqli_num_rows($proceso3);
				if($fechaRetiro=="0000-00-00"){
					$fechaRetiro = "";
				}
				if($rol==1){
					if($conteo3==0){
						if($estado=="Activo"){
							$button = '<button class="btn btn-success ml-2" data-toggle="modal" data-target="#crear" onclick="hiddenId('.$id.');">Liquidar</button>';
						}
					}else{
						while($row3=mysqli_fetch_array($proceso3)){
							$estatus = $row3["estatus"];
						}
						if($estatus==0){
							$button = '<button class="btn btn-info ml-2" onclick="cambioEstatus('.$id.',1);">Aceptar</button>';
						}else{
							$button = '<button class="btn btn-primary ml-2" data-toggle="modal" data-target="#detalle" onclick="detalle('.$id.');">Detalle</button>';
						}
					}
				}
				$html .= '
			                <tr id="">
			                    <td style="text-align:center;">'.$nombre.'</td>
			                    <td style="text-align:center;">'.$cedula.'</td>
			                    <td style="text-align:center;">'.$fechaIngreso.'</td>
			                    <td style="text-align:center;">'.$fechaRetiro.'</td>
			                    <td style="text-align:center;">'.$estado.'</td>
			                    <td style="text-align:center;">'.$salario.'</td>
			                    <td style="text-align:center;" nowrap>'.$button.'</td>
			                </tr>
				';
			}
		}else{
			$html .= '<tr><td colspan="7" class="text-center" style="font-weight:bold;font-size:20px;">Sin Resultados</td></tr>';
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

	if($asunto=='crear'){
		$usuario = $_POST['usuario'];
		$fecha = $_POST['fecha'];
		$opcion = $_POST['opcion'];
		
		$sql1 = "SELECT * FROM liquidaciones WHERE usuarioId = $usuario";
		$proceso1 = mysqli_query($conexion,$sql1);
		$contador1 = mysqli_num_rows($proceso1);
		if($contador1>0){
			$datos = [
				"estatus"	=> "error",
				"msg"	=> "El usuario ya ha solicitado antes",
			];
			echo json_encode($datos);
			exit;
		}

		$datos = calcularTodo($usuario,$fecha,$opcion,$conexion);
		$salario = $datos["montoSalario"];
		$vacaciones = $datos["montoVacaciones"];
		$aguinaldos = $datos["montoAguinaldo"];
		$preaviso = $datos["montoPreaviso"];
		$cesantias = $datos["montoCesantia"];
		$total = $datos["total"];

		$sql2 = "INSERT INTO liquidaciones (usuarioId,opcion,fechaInicio,salario,vacaciones,aguinaldos,preaviso,cesantias,total) VALUES ($usuario,'$opcion','$fecha','$salario','$vacaciones','$aguinaldos','$preaviso','$cesantias','$total')";
		$proceso2 = mysqli_query($conexion,$sql2);

		$datos = [
			"estatus"	=> "ok",
			"msg"	=> "Se ha creado satisfactoriamente",
		];
		echo json_encode($datos);
	}

	if($asunto=='cambioEstatus'){
		$id = $_POST['id'];
		$estatus = $_POST['estatus'];
		$sql1 = "UPDATE liquidaciones SET estatus = $estatus WHERE id = ".$id;
		$proceso1 = mysqli_query($conexion,$sql1);
		if($estatus==1){
			$sql2 = "UPDATE usuarios SET estado = 'Inactivo' WHERE id = ".$id;
			$proceso2 = mysqli_query($conexion,$sql2);
		}
		$datos = [
			"estatus"	=> "ok",
		];
		echo json_encode($datos);
	}

	if($asunto=='eliminar'){
		$id = $_POST['id'];
		$sql1 = "DELETE FROM liquidaciones WHERE id = $id";
		$proceso1 = mysqli_query($conexion,$sql1);
		$datos = [
			"estatus"	=> "ok",
			"msg"	=> "",
		];
		echo json_encode($datos);
	}

	if($asunto=='calcular'){
		$usuarioId = $_POST['usuarioId'];
		$fecha = $_POST['fecha'];
		$value = $_POST['value'];
		$datos = calcularTodo($usuarioId,$fecha,$value,$conexion);
		echo json_encode($datos);
	}

	if($asunto=='detalle'){
		$id = $_POST['id'];
		$sql1 = "SELECT liq.id as liqId, liq.opcion, liq.fechaInicio, liq.salario, liq.vacaciones, liq.aguinaldos, liq.preaviso, liq.cesantias, liq.total, usu.nombre, usu.apellido, usu.cedula, usu.id as usuarioId
		FROM liquidaciones liq
		INNER JOIN usuarios usu
		ON liq.usuarioId = usu.id 
		WHERE liq.id = $id";
		$proceso1 = mysqli_query($conexion,$sql1);
		while($row1=mysqli_fetch_array($proceso1)){
			$liqId = $row1['liqId'];
			$opcion = $row1['opcion'];
			$fechaInicio = $row1['fechaInicio'];
			$salario = $row1['salario'];
			$vacaciones = $row1['vacaciones'];
			$aguinaldos = $row1['aguinaldos'];
			$preaviso = $row1['preaviso'];
			$cesantias = $row1['cesantias'];
			$total = $row1['total'];
			$nombre = $row1['nombre'];
			$apellido = $row1['apellido'];
			$cedula = $row1['cedula'];
		}
		$datos = [
			"estatus"	=> "ok",
			"nombre"	=> $nombre,
			"apellido"	=> $apellido,
			"cedula"	=> $cedula,
			"opcion"	=> $opcion,
			"fechaInicio"	=> $fechaInicio,
			"salario"	=> $salario,
			"vacaciones"	=> $vacaciones,
			"aguinaldos"	=> $aguinaldos,
			"preaviso"	=> $preaviso,
			"cesantias"	=> $cesantias,
			"total"	=> $total,
		];
		echo json_encode($datos);
	}

	function calcularTodo($usuarioId,$fecha,$value,$conexion){
		$pagoSalario = false;
		$montoSalario = 0;
		$pagoVacaciones = false;
		$montoVacaciones = 0;
		$pagoAguinaldo = false;
		$montoAguinaldo = 0;
		$pagoPreaviso = false;
		$montoPreaviso = 0;
		$pagoCesantia = false;
		$montoCesantia = 0;
		$total = 0;
		$fechaArray = explode('-',$fecha);
		$anio = $fechaArray[0];
		$mes = $fechaArray[1];
		$inicioMes = $fecha . "-01";
		$ultimoDia = date("t", strtotime($inicioMes));
		$finMes = $fecha . "-" . $ultimoDia;

		$sql1 = "SELECT * FROM usuarios WHERE id = $usuarioId and estado = 'Inactivo'";
		$proceso1 = mysqli_query($conexion,$sql1);
		$contador1 = mysqli_num_rows($proceso1);

		if($contador1>0){
			$datos = [
				"estatus"	=> "error",
				"msg"	=> "Usuario inactivo",
			];
			echo json_encode($datos);
			exit;
		}else{
			$sql2 = "SELECT * FROM usuarios WHERE id = $usuarioId";
			$proceso2 = mysqli_query($conexion,$sql2);
			while($row2 = mysqli_fetch_array($proceso2)){
				$salarioActual = $row2['salario'];
			}
			$pagoAlDia = round($salarioActual/30,2);
			$pagoDiaFeriado = $pagoAlDia*2;
			$pagoHora = round($pagoAlDia/8,2);
			$pagoHoraExtra = round($pagoHora*1.5,2);
		}

		if($value=="Despido sin responsabilidad patronal" or $value=="Renuncia voluntaria"){
			$pagoSalario = true;
			$pagoVacaciones = true;
			$pagoAguinaldo = true;
		}else if($value=="Despido con responsabilidad patronal" or $value=="Despido justificada"){
			$pagoSalario = true;
			$pagoVacaciones = true;
			$pagoAguinaldo = true;
			$pagoPreaviso = true;
			$pagoCesantia = true;
		}

		if($pagoSalario==true){
			$diasTrabajados = diasLaborados($conexion,$usuarioId,$inicioMes,$finMes);
			$horasTrabajados = horasLaborados($conexion,$usuarioId,$inicioMes,$finMes);
			$diasMesDiferencia = $diasTrabajados["diasMesDiferencia"];
			$diasLaborados = $diasTrabajados["diasLaborados"];
		}

		if($pagoVacaciones==true){
			$diasVacaciones = calcularVacaciones($conexion,$usuarioId,$inicioMes,$finMes);
			$montoVacaciones = $diasVacaciones*$pagoHora;
		}

		if($pagoAguinaldo==true){
			$montoAguinaldo = aguinaldo($conexion,$usuarioId,$anio,$mes);
		}

		if($pagoPreaviso==true){
			$montoPreaviso = calcularPreaviso($conexion,$usuarioId,$fecha);
		}

		if($pagoCesantia==true){
			$montoCesantia = calcularCesantia($conexion,$usuarioId,$fecha);
		}

		$total = round($salarioActual+$montoVacaciones+$montoAguinaldo+$montoPreaviso+$montoCesantia,2);

		$datos = [
			"estatus"	=> "ok",
			"montoSalario" => $salarioActual,
			"montoVacaciones" => $montoVacaciones,
			"montoAguinaldo" => $montoAguinaldo,
			"montoPreaviso" => $montoPreaviso,
			"montoCesantia" => $montoCesantia,
			"total" => $total,
		];
		return $datos;
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

	function diferenciaHoras($rango1,$rango2){
		$segundos1 = strtotime($rango1);
		$segundos2 = strtotime($rango2);
		$diferenciaSegundos = abs($segundos2 - $segundos1);
		$diferenciaHoras = floor($diferenciaSegundos / 3600);
		return $diferenciaHoras;
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

	function calcularHorasPermisos($conexion,$usuarioId,$inicioMes,$finMes){
		$horasPermisos = 0;
		$sql1 = "SELECT * FROM permisosLaborales WHERE usuarioId = $usuarioId and tipo = 'Goce de salario' and estatus = 1 and fechaInicio BETWEEN '$inicioMes' AND '$finMes'";
		$proceso1 = mysqli_query($conexion,$sql1);
		$contador1 = mysqli_num_rows($proceso1);
		if($contador1>0){
			while($row1=mysqli_fetch_array($proceso1)){
				$horaInicio = $row1["horaInicio"];
				$horaFin = $row1["horaFin"];
				$horasPermisos += diferenciaHoras($horaInicio,$horaFin);
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

	function incapacidades($conexion,$usuarioId,$inicioMes,$finMes){
		$total = 0;
		$sql1 = "SELECT * FROM incapacidades WHERE usuarioId = $usuarioId and estatus = 1 and fechaInicio BETWEEN '$inicioMes' AND '$finMes' and fechaFin BETWEEN '$inicioMes' AND '$finMes' ";
		$proceso1 = mysqli_query($conexion,$sql1);
		while($row1=mysqli_fetch_array($proceso1)){
			$total += $row1["diasTotales"];
		}
		return $total;
	}

	function calcularVacaciones($conexion,$usuarioId,$inicio,$fin){
		$sql1 = "SELECT * FROM vacaciones WHERE usuarioId = $usuarioId and estatus = 1 and fechaInicio BETWEEN '$inicio' AND '$fin'";
		$proceso1 = mysqli_query($conexion,$sql1);
		$contador1 = mysqli_num_rows($proceso1);
		return $contador1;
	}

	function calcularPreaviso($conexion,$usuarioId,$fecha){
		$preaviso = 0;
		$sql1 = "SELECT * FROM usuarios WHERE id = $usuarioId";
		$proceso1 = mysqli_query($conexion,$sql1);
		while($row1=mysqli_fetch_array($proceso1)){
			$fechaIngreso = $row1['fechaIngreso'];
			$salarioActual = $row1['salario'];
		}
		$pagoAlDia = round($salarioActual/30,2);
		$pagoHora = round($pagoAlDia/8,2);
		$date1 = new DateTime($fecha);
		$date2 = new DateTime($fechaIngreso);
		$diff = $date1->diff($date2);
		$mesesDiferencia = ($diff->y * 12) + $diff->m;
		if($mesesDiferencia>3 and $mesesDiferencia<=6){
			$preaviso = $pagoAlDia*7;
		}else if($mesesDiferencia>6 and $mesesDiferencia<=12){
			$preaviso = $pagoAlDia*15;
		}else if($mesesDiferencia>12){
			$preaviso = $pagoAlDia*30;
		}
		return $preaviso;
	}

	function calcularCesantia($conexion,$usuarioId,$fecha){
		$cesantia = 0;
		$sql1 = "SELECT * FROM usuarios WHERE id = $usuarioId";
		$proceso1 = mysqli_query($conexion,$sql1);
		while($row1=mysqli_fetch_array($proceso1)){
			$fechaIngreso = $row1['fechaIngreso'];
			$salarioActual = $row1['salario'];
		}
		$pagoAlDia = round($salarioActual/30,2);
		$pagoHora = round($pagoAlDia/8,2);
		$date1 = new DateTime($fecha);
		$date2 = new DateTime($fechaIngreso);
		$diff = $date1->diff($date2);
		$mesesDiferencia = ($diff->y * 12) + $diff->m;
		$aniosDiferencia = $mesesDiferencia/12;
		if($mesesDiferencia<12){
			$cesantia = $mesesDiferencia*($pagoAlDia*1.66);
		}else if($mesesDiferencia>=12 and $mesesDiferencia<=60){
			$cesantia = $aniosDiferencia*($pagoAlDia*20);
		}else if($mesesDiferencia>60){
			if($aniosDiferencia>8){
				$aniosDiferencia = 8;
			}
			$cesantia = $aniosDiferencia*($pagoAlDia*10);
		}
		return round($cesantia,2);
	}


?>
