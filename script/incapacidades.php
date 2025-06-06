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


		$sql1 = "SELECT inca.id as id, usu.cedula as cedula, usu.nombre as nombre, usu.apellido as apellido, usu.apellido2 as apellido2, inca.fechaInicio as fechaInicio, inca.fechaFin as fechaFin, inca.observacion as observacion, inca.estatus as estatus FROM incapacidades inca
		INNER JOIN usuarios usu
		ON inca.usuarioId = usu.id 
		WHERE ".$rolCon.$filtrado.$fecha;

		$sql2 = "SELECT inca.id as id, usu.cedula as cedula, usu.nombre as nombre, usu.apellido as apellido, usu.apellido2 as apellido2, inca.fechaInicio as fechaInicio, inca.fechaFin as fechaFin, inca.observacion as observacion, inca.estatus as estatus FROM incapacidades inca
		INNER JOIN usuarios usu
		ON inca.usuarioId = usu.id 
		WHERE ".$rolCon.$filtrado.$fecha." ORDER BY inca.id DESC LIMIT ".$limit." OFFSET ".$offset;

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
						<th class="text-center">Fecha Inicio</th>
						<th class="text-center">Fecha Fin</th>
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
				$fechaFin = $row2["fechaFin"];
				$observacion = $row2["observacion"];
				if($estatus==0){
					$estatusText = "No";
					$button = '<button class="btn btn-danger" onclick="eliminar('.$id.');">Eliminar</button>';
				}else{
					$estatusText = "Si";
					$button = '<button class="btn btn-danger" disabled>Eliminar</button>';
				}
				if($rol==1){
					if($estatus==0){
						$button = '<button class="btn btn-success ml-2" onclick="cambioEstatus('.$id.',1);">Aceptar</button>';
						$button .= '<button class="btn btn-danger ml-2" onclick="eliminar('.$id.');">Eliminar</button>';
					}
				}
				$html .= '
			                <tr id="">
			                    <td style="text-align:center;">'.$nombre.'</td>
			                    <td style="text-align:center;">'.$estatusText.'</td>
			                    <td style="text-align:center;">'.$fechaInicio.'</td>
			                    <td style="text-align:center;">'.$fechaFin.'</td>
			                    <td style="text-align:center;">'.$observacion.'</td>
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
		$desde = $_POST['desde'];
		$hasta = $_POST['hasta'];
		$observacion = $_POST['observacion'];
		$validacion3 = 0;
		$validacion4 = 0;
		$validacion5 = 0;
		$validacion6 = 0;

		$date1 = new DateTime($desde);
		$date2 = new DateTime($hasta);

		if ($date1 > $date2) {
    		$datos = [
				"estatus"	=> "error",
				"msg"	=> "Fecha no valida",
			];
			echo json_encode($datos);
			exit;
		}

		$sql1 = "SELECT * FROM incapacidades WHERE usuarioId = $usuario and (fechaInicio BETWEEN '$desde' AND '$hasta' or fechaFin BETWEEN '$desde' AND '$hasta')";
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

		$inicio = new DateTime($desde);
		$fin = new DateTime($hasta);
		$fin = $fin->modify('+1 day');
		$diferencia = $inicio->diff($fin);
		$diferenciaDias = ($diferencia->days);
		$periodo = new DatePeriod($inicio, new DateInterval('P1D'), $fin);
		$dias_laborales = [];
		$fechas = [];
		$guardados = 0;
		foreach ($periodo as $fecha) {
			$validacionDomingo = 0;

		    $fechaArray = explode("-",$fecha->format('Y-m-d'));
		    $fechaCompleta = $fecha->format('Y-m-d');
			$mesArray = $fechaArray[1];
			$diaArray = $fechaArray[2];
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

			$sql4 = "SELECT * FROM turnos WHERE usuarioId = $usuario and fechaInicio = '$fechaCompleta' ";
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

			$sql5 = "SELECT * FROM permisosLaborales WHERE usuarioId = $usuario and fechaInicio = '$fechaCompleta' and estatus = 1";
			$proceso5 = mysqli_query($conexion,$sql5);
			$contador5 = mysqli_num_rows($proceso5);
			if($diferenciaDias==1 and $contador5>0){
				$datos = [
					"estatus"	=> "error",
					"msg"	=> "Fecha pedida ya tiene un permiso",
				];
				echo json_encode($datos);
				exit;
			}

			$sql6 = "SELECT * FROM vacaciones WHERE usuarioId = $usuario and fechaInicio = '$fechaCompleta' and estatus = 1";
			$proceso6 = mysqli_query($conexion,$sql6);
			$contador6 = mysqli_num_rows($proceso6);
			if($diferenciaDias==1 and $contador6>0){
				$datos = [
					"estatus"	=> "error",
					"msg"	=> "Fecha pedida ya tiene una vacación",
				];
				echo json_encode($datos);
				exit;
			}

			$validacion3 = ($contador3 > 0) ? 1 : 0;
			$validacion4 = ($contador4 > 0) ? 1 : 0;
			$validacion5 = ($contador5 > 0) ? 1 : 0;
			$validacion6 = ($contador6 > 0) ? 1 : 0;

			if ($fecha->format('N')==7) {
		    	$validacionDomingo = 1;
		    }

			if($contador3==0 and $contador4==0 and $contador5==0 and $contador6==0 and $validacionDomingo==0){
				$fechas[] = $fecha->format('Y-m-d');
			}
		}

		$diasTotalesValidos = count($fechas);
		if(count($fechas)>0){
			$primeraFecha = $fechas[0];
			$ultimaFecha = end($fechas);
			$sql2 = "INSERT INTO incapacidades (usuarioId,fechaInicio,fechaFin,observacion,diasTotales) VALUES ($usuario,'$primeraFecha','$ultimaFecha','$observacion',$diasTotalesValidos)";
			$proceso2 = mysqli_query($conexion,$sql2);
			$guardados += 1;
		}

		if($guardados>0){
			$datos = [
				"estatus"	=> "ok",
				"msg"	=> "Se ha guardado al menos un registro",
			];
			echo json_encode($datos);
		}else if($validacion3==1){
			$datos = [
				"estatus"	=> "error",
				"msg"	=> "Ya tienes dias feriados en esas fechas",
			];
			echo json_encode($datos);
			exit;
		}else if($validacion4==1){
			$datos = [
				"estatus"	=> "error",
				"msg"	=> "Ya tienes turnos guardados en esas fecha",
			];
			echo json_encode($datos);
			exit;
		}else if($validacion5==1){
			$datos = [
				"estatus"	=> "error",
				"msg"	=> "Ya tienes permisos laborales en esas fechas",
			];
			echo json_encode($datos);
			exit;
		}else if($validacion6==1){
			$datos = [
				"estatus"	=> "error",
				"msg"	=> "Ya tienes rango de vacaciones en esas fechas",
			];
			echo json_encode($datos);
			exit;
		}else{
			$datos = [
				"estatus"	=> "error",
				"msg"	=> "Ha seleccionado solo domingos",
			];
			echo json_encode($datos);
			exit;
		}

	}

	if($asunto=='cambioEstatus'){
		$id = $_POST['id'];
		$estatus = $_POST['estatus'];
		$sql1 = "UPDATE incapacidades SET estatus = $estatus WHERE id = ".$id;
		$proceso1 = mysqli_query($conexion,$sql1);
		$datos = [
			"estatus"	=> "ok",
		];
		echo json_encode($datos);
	}

	if($asunto=='eliminar'){
		$id = $_POST['id'];
		$sql1 = "DELETE FROM incapacidades WHERE id = $id";
		$proceso1 = mysqli_query($conexion,$sql1);
		$datos = [
			"estatus"	=> "ok",
			"msg"	=> "",
		];
		echo json_encode($datos);
	}

?>