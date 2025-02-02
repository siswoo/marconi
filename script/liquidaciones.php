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
			$filtrado = ' and (nombre LIKE "%'.$filtrado.'%" or apellido LIKE "%'.$filtrado.'%")';
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
				$nombre = $row2["nombre"]." ".$row2["apellido"];
				$fechaIngreso = $row2["fechaIngreso"];
				$fechaRetiro = $row2["fechaRetiro"];
				$estado = $row2["estado"];
				$salario = $row2["salario"];
				$sql3 = "SELECT * FROM liquidaciones WHERE usuarioId = $usuario";
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
							$button = '<button class="btn btn-danger ml-2" data-toggle="modal" data-target="#crear" onclick="resumen('.$id.');">Resumen</button>';
						}
					}
				}
				$html .= '
			                <tr id="">
			                    <td style="text-align:center;">'.$nombre.'</td>
			                    <td style="text-align:center;">'.$fechaIngreso.'</td>
			                    <td style="text-align:center;">'.$fechaRetiro.'</td>
			                    <td style="text-align:center;">'.$estado.'</td>
			                    <td style="text-align:center;">'.$salario.'</td>
			                    <td style="text-align:center;" nowrap>'.$button.'</td>
			                </tr>
				';
			}
		}else{
			$html .= '<tr><td colspan="6" class="text-center" style="font-weight:bold;font-size:20px;">Sin Resultados</td></tr>';
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
		$observacion = $_POST['observacion'];

		if(calcularDiasDisponibles($conexion,$usuario)==0){
			$datos = [
				"estatus"	=> "error",
				"msg"	=> "No tienes días disponibles",
			];
			echo json_encode($datos);
			exit;
		}

		$sql1 = "SELECT * FROM liquidaciones WHERE usuarioId = $usuario and fechaInicio = '$fecha'";
		$proceso1 = mysqli_query($conexion,$sql1);
		$contador1 = mysqli_num_rows($proceso1);
		if($contador1>0){
			$datos = [
				"estatus"	=> "error",
				"msg"	=> "Fecha ya solicitada",
			];
			echo json_encode($datos);
			exit;
		}
		$sql2 = "INSERT INTO liquidaciones (usuarioId,fechaInicio,observacion) VALUES ($usuario,'$fecha','$observacion')";
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

	if($asunto=='diasDisponibles'){
		$id = $_POST['id'];
		$disponibles = calcularDiasDisponibles($conexion,$id);
		$datos = [
			"estatus"	=> "ok",
			"diasDisponibles" => $disponibles,
		];
		echo json_encode($datos);
	}

	if($asunto=='calcular'){
		$usuarioId = $_POST['usuarioId'];
		$fecha = $_POST['fecha'];
		$value = $_POST['value'];
		$pagoSalario = false;
		$montoSalario = 0;
		$pagoVacaciones = false;
		$montoVacaciones = 0;
		$pagoAguinaldo = false;
		$montoAguinaldo = 0;
		$pagoPreaviso = false;
		$pagoCesantia = false;

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
		}

		if($value=="Despido con justa causa" or $value="Renuncia voluntaria"){
			$pagoSalario = true;
			$pagoVacaciones = true;
			$pagoAguinaldo = true;
		}else if($value=="Despido sin justa causa" or $value="Despido justificada"){
			$pagoSalario = true;
			$pagoVacaciones = true;
			$pagoAguinaldo = true;
			$pagoPreaviso = true;
			$pagoCesantia = true;
		}

		if($pagoSalario==true){
			$montoSalario = calcularSalario($conexion,$usuarioId,$fecha);
		}

		if($pagoVacaciones==true){
			$montoVacaciones = calcularVacaciones($conexion,$usuarioId);
		}

		if($pagoAguinaldo==true){
			$montoAguinaldo = calcularAguinaldo($conexion,$usuarioId);
		}
		exit;
	}

	function calcularDiasDisponibles($conexion,$usuarioId){
		$sql1 = "SELECT * FROM usuarios WHERE id = ".$usuarioId;
		$proceso1 = mysqli_query($conexion,$sql1);

		while($row1=mysqli_fetch_array($proceso1)){
			$fechaIngreso = $row1["fechaIngreso"];
		}

		$sql2 = "SELECT id FROM liquidaciones WHERE usuarioId = ".$usuarioId;
		$proceso2 = mysqli_query($conexion,$sql2);
		$contador2 = mysqli_num_rows($proceso2);

		$fecha_objetivo = new DateTime($fechaIngreso);
		$fecha_actual = new DateTime();
		$diferencia = $fecha_actual->diff($fecha_objetivo);
		$meses = ($diferencia->y * 12) + $diferencia->m;
		$meses = $meses-$contador2;
		return $meses;
	}

	function calcularSalario($conexion,$usuarioId,$fecha){
		if($fecha==""){
			$fechaSql = "";
		}else{
			$fechaSql = " and fechaInicio = '".$fecha."'";
		}

		$date = new DateTime($fecha);
		$inicioMes = $date->format("Y-m-01");
		$finMes = $date->format("Y-m-t");

		$sql1 = "SELECT * FROM turnos WHERE usuarioId = $usuarioId and fechaInicio BETWEEN '$inicioMes' AND '$fecha' and tipo = 'Entrada'";
		$proceso1 = mysqli_query($conexion,$sql1);
		$contador1 = mysqli_num_rows($proceso1);

		$sql2 = "SELECT * FROM usuarios WHERE id = $usuarioId";
		$proceso2 = mysqli_query($conexion,$sql2);
		while($row2=mysqli_fetch_array($proceso2)){
			$salario = $row2["salario"];
		}
		$pagoDiario = ceil($salario/30);
		$montoSalario = $pagoDiario*$contador1;
		return $montoSalario;
	}

	function calcularVacaciones($conexion,$usuarioId){
		$sql1 = "SELECT * FROM usuarios WHERE id = ".$usuarioId;
		$proceso1 = mysqli_query($conexion,$sql1);

		while($row1=mysqli_fetch_array($proceso1)){
			$fechaIngreso = $row1["fechaIngreso"];
			$salario = $row1["salario"];
		}

		$sql2 = "SELECT id FROM vacaciones WHERE usuarioId = ".$usuarioId;
		$proceso2 = mysqli_query($conexion,$sql2);
		$contador2 = mysqli_num_rows($proceso2);

		$fecha_objetivo = new DateTime($fechaIngreso);
		$fecha_actual = new DateTime();
		$diferencia = $fecha_actual->diff($fecha_objetivo);
		$meses = ($diferencia->y * 12) + $diferencia->m;
		$meses = $meses-$contador2;

		$pagoDiario = ceil($salario/30);
		$montoVacaciones = $pagoDiario*$meses;

		return $montoVacaciones;
	}

	function calcularAguinaldo($conexion,$usuarioId){
		$sql1 = "SELECT * FROM usuarios WHERE id = ".$usuarioId;
		$proceso1 = mysqli_query($conexion,$sql1);

		while($row1=mysqli_fetch_array($proceso1)){
			$fechaIngreso = $row1["fechaIngreso"];
		}

		mesesPlanilla($conexion,$usuarioId);

		///NECESITO TENER EL MODULO DE PLANILLA LISTO
		return 0;
	}

	function mesesPlanilla($conexion,$usuarioId){
		$mesesTrabajados = [];
		$sql1 = "SELECT * FROM turnos WHERE usuarioId = $usuarioId and tipo = 'Entrada' ORDER BY fechaInicio";
		$proceso1 = mysqli_query($conexion,$sql1);
		while($row1=mysqli_fetch_array($proceso1)){
			$fecha = $row1['fechaInicio'];
			$mes = date("Y-m", strtotime($fecha));
			$mesSolo = date("m", strtotime($fecha));
			$anio = date("Y", strtotime($fecha));
			$diasDelMes = cal_days_in_month(CAL_GREGORIAN, $mesSolo, $anio);
			if (!isset($mesesTrabajados[$mes])) {
        		$mesesTrabajados[$mes] = [];
    		}
    		$mesesTrabajados[$mes][$fecha] = true;
		}

		$resultadoFinal = [];
		foreach ($mesesTrabajados as $mes => $dias) {
		    $diasLaborados = count($dias);
		    $resultadoFinal[] = [
		        "mes" => $mes,
		        "diasTrabajados" => min($diasLaborados, 30)
		    ];
		}
		echo json_encode($resultadoFinal, JSON_PRETTY_PRINT);
		exit;
	}


?>