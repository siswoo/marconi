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
			$filtrado = ' and (usu.nombre LIKE "%'.$filtrado.'%" or usu.apellido LIKE "%'.$filtrado.'%" or usu.apellido2 LIKE "%'.$filtrado.'%")';
		}

		if($fecha!=''){
			$fecha = ' and per.fechaInicio LIKE "%'.$fecha.'%"';
		}

		$limit = $consultasporpagina;
		$offset = ($pagina - 1) * $consultasporpagina;

		if($rol==1){
			$rolCon = ' usu.rol > 0';
		}else{
			$rolCon = ' usu.id = '.$usuario;
		}


		$sql1 = "SELECT vac.id as id, usu.cedula as cedula, usu.nombre as nombre, usu.apellido as apellido, usu.apellido2 as apellido2, vac.fechaInicio as fechaInicio, vac.observacion as observacion, vac.estatus as estatus, usu.id as usuarioId FROM vacaciones vac
		INNER JOIN usuarios usu
		ON vac.usuarioId = usu.id 
		WHERE ".$rolCon.$filtrado.$fecha;

		$sql2 = "SELECT vac.id as id, usu.cedula as cedula, usu.nombre as nombre, usu.apellido as apellido, usu.apellido2 as apellido2, vac.fechaInicio as fechaInicio, vac.observacion as observacion, vac.estatus as estatus, usu.id as usuarioId FROM vacaciones vac
		INNER JOIN usuarios usu
		ON vac.usuarioId = usu.id 
		WHERE ".$rolCon.$filtrado.$fecha." ORDER BY vac.id DESC LIMIT ".$limit." OFFSET ".$offset;

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
						<th class="text-center">Aceptada</th>
						<th class="text-center">Fecha</th>
						<th class="text-center">Observación</th>
						<th class="text-center">Opciones</th>
		            </tr>
		            </thead>
		            <tbody>
		';
		if($conteo1>=1){
			while($row2 = mysqli_fetch_array($proceso2)) {
				$id = $row2["id"];
				$nombre = $row2["nombre"]." ".$row2["apellido"]." ".$row2["apellido2"];
				$estatus = $row2["estatus"];
				$fechaInicio = $row2["fechaInicio"];
				$observacion = $row2["observacion"];
				$usuarioId = $row2["usuarioId"];
				if($estatus==0){
					$estatusText = "No";
					$button = '<button class="btn btn-danger" onclick="eliminar('.$id.');">Eliminar</button>';
				}else{
					$estatusText = "Si";
					$button = '<button class="btn btn-danger" disabled>Eliminar</button>';
				}
				if($rol==1){
					if($estatus==0){
						$button = '<button class="btn btn-success ml-2" onclick="cambioEstatus('.$id.',1,'.$usuarioId.');">Aceptar</button>';
						$button .= '<button class="btn btn-danger ml-2" onclick="eliminar('.$id.');">Eliminar</button>';
					}
				}
				$html .= '
			                <tr id="">
			                    <td style="text-align:center;">'.$nombre.'</td>
			                    <td style="text-align:center;">'.$estatusText.'</td>
			                    <td style="text-align:center;">'.$fechaInicio.'</td>
			                    <td style="text-align:center;">'.$observacion.'</td>
			                    <td style="text-align:center;" nowrap>'.$button.'</td>
			                </tr>
				';
			}
		}else{
			$html .= '<tr><td colspan="5" class="text-center" style="font-weight:bold;font-size:20px;">Sin Resultados</td></tr>';
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
		$fechaDesde = $_POST['fechaDesde'];
		$fechaHasta = $_POST['fechaHasta'];
		$observacion = $_POST['observacion'];
		$diasDescontar = 0;

		$disponibles = calcularDiasDisponibles($conexion,$usuario);

		if($disponibles==0){
			$datos = [
				"estatus"	=> "error",
				"msg"	=> "No tienes días disponibles",
			];
			echo json_encode($datos);
			exit;
		}

		$inicio = new DateTime($fechaDesde);
		$fin = new DateTime($fechaHasta);

		$diferencia = $inicio->diff($fin);
		$diferenciaDias = ($diferencia->days)+1;

		$fin = $fin->modify('+1 day');

		$periodo = new DatePeriod($inicio, new DateInterval('P1D'), $fin);

		$dias_laborales = [];

		foreach ($periodo as $fecha) {
		    if ($fecha->format('N') != 7) {
		        $dias_laborales[] = $fecha->format('Y-m-d');
		    }
		}

		if(count($dias_laborales)>$disponibles){
			$datos = [
				"estatus"	=> "error",
				"msg"	=> "Ha superado los días disponibles",
			];
			echo json_encode($datos);
			exit;
		}

		foreach ($dias_laborales as $dia) {
		    $sql1 = "SELECT * FROM vacaciones WHERE usuarioId = $usuario and fechaInicio = '$dia'";
			$proceso1 = mysqli_query($conexion,$sql1);
			$contador1 = mysqli_num_rows($proceso1);
			if($contador1>0){
				$datos = [
					"estatus"	=> "error",
					"msg"	=> "Tiene una fecha en el rango ya solicitada",
				];
				echo json_encode($datos);
				exit;
			}
		}

		if(count($dias_laborales)==0){
			$datos = [
				"estatus"	=> "error",
				"msg"	=> "Fecha pedida no es valida",
			];
			echo json_encode($datos);
			exit;
		}

		foreach ($dias_laborales as $dia) {
			$fechaArray = explode("-",$dia);
			$diaArray = $fechaArray[2];
			$mesArray = $fechaArray[1];

			$sql3 = "SELECT * FROM diasFeriados WHERE mes = $mesArray and dia = $diaArray";
			$proceso3 = mysqli_query($conexion,$sql3);
			$contador3 = mysqli_num_rows($proceso3);
			if($diferenciaDias==1 and $contador3>0){
				$datos = [
					"estatus"	=> "error",
					"msg"	=> "Fecha pedida es un feriado",
				];
				echo json_encode($datos);
				exit;
			}

			$sql4 = "SELECT * FROM turnos WHERE usuarioId = $usuario and fechaInicio = '$dia' ";
			$proceso4 = mysqli_query($conexion,$sql4);
			$contador4 = mysqli_num_rows($proceso4);
			if($diferenciaDias==1 and $contador4>0){
				$datos = [
					"estatus"	=> "error",
					"msg"	=> "Fecha pedida ya tiene un turno",
				];
				echo json_encode($datos);
				exit;
			}

			$sql5 = "SELECT * FROM turnos WHERE usuarioId = $usuario and fechaInicio = '$dia' ";
			$proceso5 = mysqli_query($conexion,$sql5);
			$contador5 = mysqli_num_rows($proceso5);
			if($diferenciaDias==1 and $contador5>0){
				$datos = [
					"estatus"	=> "error",
					"msg"	=> "Fecha pedida ya tiene un turno",
				];
				echo json_encode($datos);
				exit;
			}

			$sql6 = "SELECT * FROM permisosLaborales WHERE usuarioId = $usuario and fechaInicio = '$dia' ";
			$proceso6 = mysqli_query($conexion,$sql6);
			$contador6 = mysqli_num_rows($proceso6);
			if($diferenciaDias==1 and $contador6>0){
				$datos = [
					"estatus"	=> "error",
					"msg"	=> "Fecha pedida ya tiene un permiso",
				];
				echo json_encode($datos);
				exit;
			}

			$sql7 = "SELECT * FROM incapacidades WHERE usuarioId = $usuario and '$dia' BETWEEN fechaInicio AND fechaFin ";
			$proceso7 = mysqli_query($conexion,$sql7);
			$contador7 = mysqli_num_rows($proceso7);
			if($diferenciaDias==1 and $contador7>0){
				$datos = [
					"estatus"	=> "error",
					"msg"	=> "Fecha pedida ya tiene una incapacidad",
				];
				echo json_encode($datos);
				exit;
			}

			if($contador3==0 and $contador4==0 and $contador5==0 and $contador6==0 and $contador7==0){
			    $sql2 = "INSERT INTO vacaciones (usuarioId,fechaInicio,observacion) VALUES ($usuario,'$dia','$observacion')";
				$proceso2 = mysqli_query($conexion,$sql2);
			}
		}

		$datos = [
			"estatus"	=> "ok",
			"msg"	=> "Se ha creado satisfactoriamente",
		];
		echo json_encode($datos);
	}

	if($asunto=='cambioEstatus'){
		$id = $_POST['id'];
		$estatus = $_POST['estatus'];
		$usuarioId = $_POST['usuarioId'];

		$sql1 = "UPDATE vacaciones SET estatus = $estatus WHERE id = ".$id;
		$proceso1 = mysqli_query($conexion,$sql1);

		$sql2 = "SELECT * FROM vacaciones WHERE id = $id";
		$proceso2 = mysqli_query($conexion,$sql2);
		while($row2=mysqli_fetch_array($proceso2)){
			$fecha = $row2["fechaInicio"];
		}

		$sql3 = "SELECT hor.entrada FROM horarios hor INNER JOIN usuarios usu ON usu.horarios = hor.id WHERE usu.id = ".$usuarioId;
		$proceso3 = mysqli_query($conexion,$sql3);
		while($row3=mysqli_fetch_array($proceso3)){
			$entrada = $row3["entrada"];
		}

		$sql4 = "INSERT INTO turnos (usuarioId,tipo,fechaInicio,horaInicio) VALUES ($usuarioId,'Entrada','$fecha','$entrada')";
		$proceso4 = mysqli_query($conexion,$sql4);

		$datos = [
			"estatus"	=> "ok",
		];
		echo json_encode($datos);
	}

	if($asunto=='eliminar'){
		$id = $_POST['id'];
		$sql1 = "DELETE FROM vacaciones WHERE id = $id";
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

	function calcularDiasDisponibles($conexion,$usuarioId){
		$sql1 = "SELECT * FROM usuarios WHERE id = ".$usuarioId;
		$proceso1 = mysqli_query($conexion,$sql1);

		while($row1=mysqli_fetch_array($proceso1)){
			$fechaIngreso = $row1["fechaIngreso"];
		}

		$sql2 = "SELECT id FROM vacaciones WHERE usuarioId = ".$usuarioId;
		$proceso2 = mysqli_query($conexion,$sql2);
		$contador2 = mysqli_num_rows($proceso2);

		$fecha_objetivo = new DateTime($fechaIngreso);
		$fecha_actual = new DateTime();
		$diferencia = $fecha_actual->diff($fecha_objetivo);
		$meses = ($diferencia->y * 12) + $diferencia->m;
		$meses = $meses-$contador2;
		return $meses;
	}

?>