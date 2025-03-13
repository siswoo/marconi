<?php
session_start();
include("conexion.php");
$fecha_creacion = date('Y-m-d');
$hora_creacion = date('H:i:s');
$asunto = $_POST['asunto'];

	if($asunto=='table1'){
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
			$fecha = ' and tur.fechaInicio LIKE "%'.$fecha.'%"';
		}

		$limit = $consultasporpagina;
		$offset = ($pagina - 1) * $consultasporpagina;

		$sql1 = "SELECT tur.tipo as tipo, tur.id as id, usu.id as usuarioId, usu.cedula as cedula, usu.nombre as nombre, usu.apellido as apellido, usu.apellido2 as apellido2, tur.fechaInicio as fechaInicio, tur.horaInicio as horaInicio, tur.horaFin as horaFin, tur.fechaFin as fechaFin, tur.estatusExtras as estatusExtras FROM turnos tur
		INNER JOIN usuarios usu
		ON tur.usuarioId = usu.id 
		WHERE usu.rol > 1 ".$filtrado.$fecha;

		$sql2 = "SELECT tur.tipo as tipo, tur.id as id, usu.id as usuarioId, usu.cedula as cedula, usu.nombre as nombre, usu.apellido as apellido, usu.apellido2 as apellido2, tur.fechaInicio as fechaInicio, tur.horaInicio as horaInicio, tur.horaFin as horaFin, tur.fechaFin as fechaFin, tur.estatusExtras as estatusExtras FROM turnos tur
		INNER JOIN usuarios usu
		ON tur.usuarioId = usu.id 
		WHERE usu.rol > 1 ".$filtrado.$fecha." ORDER BY tur.id DESC LIMIT ".$limit." OFFSET ".$offset;

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
						<th class="text-center">Identificación</th>
						<th class="text-center">Empleado</th>
						<th class="text-center">Tipo</th>
						<th class="text-center">Fecha Inicio</th>
						<th class="text-center">Horas extras</th>
						<th class="text-center">Aceptada</th>
						<th class="text-center">Opciones</th>
		            </tr>
		            </thead>
		            <tbody>
		';
		if($conteo1>=1){
			while($row2 = mysqli_fetch_array($proceso2)) {
				$id = $row2["id"];
				$usuarioId = $row2["usuarioId"];
				$cedula = $row2["cedula"];
				$nombre = $row2["nombre"]." ".$row2["apellido"]." ".$row2["apellido2"];
				$tipo = $row2["tipo"];
				$fechaInicio = $row2["fechaInicio"];
				$horaInicio = $row2["horaInicio"];
				$fechaFin = $row2["fechaFin"];
				$horaFin = $row2["horaFin"];
				$estatusExtras = $row2["estatusExtras"];
				$diferenciaHorarias = 0;
				if($tipo=='Salida'){
					$diferenciaHorarias = diferenciaHoras($conexion,$usuarioId,$fechaInicio)-9;
				}
				if($fechaFin=="0000-00-00"){
					$fin = "";
				}else{
					$fin = $fechaFin.' '.$horaFin;
				}

				if($diferenciaHorarias>0 and $estatusExtras==1){
					$aceptada = "Si";
				}else{
					$aceptada = "";
				}

				$html .= '
			                <tr id="">
			                    <td style="text-align:center;">'.$cedula.'</td>
			                    <td style="text-align:center;">'.$nombre.'</td>
			                    <td style="text-align:center;">'.$tipo.'</td>
			                    <td style="text-align:center;">'.$fechaInicio.' '.$horaInicio.'</td>
			                    <td style="text-align:center;">'.$diferenciaHorarias.'</td>
			                    <td style="text-align:center;">'.$aceptada.'</td>
			                    <td style="text-align:center;" nowrap>
				';

				if($estatusExtras==0){
					if($diferenciaHorarias>0){
						$html .= '
							<button class="btn btn-success" onclick="cambioExtras('.$id.','.$estatusExtras.');">Aceptar</button>
						';
					}

					if($tipo=="Salida"){
						$html .= '
							<button class="btn btn-danger" onclick="eliminar('.$id.');">Eliminar</button>
						';
					}
				}

				$html .= '
			                    </td>
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
		$nombre = $_POST['nombre'];
		$apellido = $_POST['apellido'];
		$cedula = $_POST['cedula'];
		$fechaNacimiento = $_POST['fechaNacimiento'];
		$genero = $_POST['genero'];
		$telefono = $_POST['telefono'];
		$correo = $_POST['correo'];
		$direccion = $_POST['direccion'];
		$fechaIngreso = $_POST['fechaIngreso'];
		$salario = $_POST['salario'];
		$password = md5($_POST['password']);
		$cargo = $_POST['cargo'];
		$usuario = $cedula;

		$sql3 = "SELECT * FROM usuarios WHERE cedula = '$cedula'";
		$proceso3 = mysqli_query($conexion,$sql3);
		$contador3 = mysqli_num_rows($proceso3);
		if($contador3>0){
			$datos = [
				"estatus"	=> "error",
				"msg"	=> "El documento ya existe en otro registro",
			];
			echo json_encode($datos);
			exit;
		}
		
		$sql1 = "INSERT INTO usuarios (nombre,usuario,apellido,cedula,fechaNacimiento,genero,telefono,correo,direccion,fechaIngreso,salario,password,cargo,rol) VALUES ('$nombre','$usuario','$apellido','$cedula','$fechaNacimiento','$genero','$telefono','$correo','$direccion','$fechaIngreso',$salario,'$password','$cargo',2)";
		$proceso1 = mysqli_query($conexion,$sql1);

		$datos = [
			"estatus"	=> "ok",
			"msg"	=> "Se ha creado exitosamente",
		];
		echo json_encode($datos);
	}

	if($asunto=='cambioEstatus'){
		$id = $_POST['id'];
		$estatus = $_POST['estatus'];

		if($estatus=="Activo"){
			$nuevoEstatus = "Inactivo";
		}else{
			$nuevoEstatus = "Activo";
		}

		$sql1 = "UPDATE usuarios SET estado = '$nuevoEstatus' WHERE id = ".$id;
		$proceso1 = mysqli_query($conexion,$sql1);
		$datos = [
			"estatus"	=> "ok",
		];
		echo json_encode($datos);
	}

	if($asunto=='modificar'){
		$id = $_POST["id"];
		$sql1 = "SELECT * FROM usuarios WHERE id = ".$id;
		$proceso1 = mysqli_query($conexion,$sql1);
		$contador1 = mysqli_num_rows($proceso1);
		if($contador1>=1){
			while($row1=mysqli_fetch_array($proceso1)){
				$nombre = $row1["nombre"];
				$apellido = $row1["apellido"];
				$cedula = $row1["cedula"];
				$fechaNacimiento = $row1["fechaNacimiento"];
				$genero = $row1["genero"];
				$telefono = $row1["telefono"];
				$correo = $row1["correo"];
				$direccion = $row1["direccion"];
				$fechaIngreso = $row1["fechaIngreso"];
				$salario = $row1["salario"];
				$password = $row1["password"];
				$cargo = $row1["cargo"];
			}
			$datos = [
				"estatus"	=> "ok",
				"nombre"	=> $nombre,
				"apellido"	=> $apellido,
				"cedula"		=> $cedula,
				"fechaNacimiento"	=> $fechaNacimiento,
				"genero"	=> $genero,
				"telefono"		=> $telefono,
				"correo"	=> $correo,
				"direccion"	=> $direccion,
				"fechaIngreso"		=> $fechaIngreso,
				"salario"	=> $salario,
				"password"	=> $password,
				"cargo"		=> $cargo,
			];
			echo json_encode($datos);
		}
	}

	if($asunto=='modificar_guardar'){
		$id = $_POST['id'];
		$nombre = $_POST['nombre'];
		$apellido = $_POST['apellido'];
		$cedula = $_POST['cedula'];
		$fechaNacimiento = $_POST['fechaNacimiento'];
		$genero = $_POST['genero'];
		$telefono = $_POST['telefono'];
		$correo = $_POST['correo'];
		$direccion = $_POST['direccion'];
		$fechaIngreso = $_POST['fechaIngreso'];
		$salario = $_POST['salario'];
		if($_POST['password']!=""){
			$password = md5($_POST['password']);
			$password = "password = '$password',";
		}else{
			$password = "";
		}
		$cargo = $_POST['cargo'];
		$usuario = $cedula;

		$sql1 = "UPDATE usuarios SET nombre = '$nombre', usuario = '$usuario', apellido = '$apellido', cedula = '$cedula', fechaNacimiento = '$fechaNacimiento', genero = '$genero', telefono = '$telefono', correo = '$correo', direccion = '$direccion', fechaIngreso = '$fechaIngreso', salario = $salario, $password cargo = '$cargo' WHERE id = ".$id;
		$proceso1 = mysqli_query($conexion,$sql1);
		$datos = [
			"estatus"	=> "ok",
			"msg"	=> "Actualizado correctamente",
		];
		echo json_encode($datos);
	}

	if($asunto=='abrirTurno'){
		$usuarioId = $_POST['idUsuario'];
		$sql1 = "SELECT * FROM turnos WHERE usuarioId = $usuarioId and tipo = 'Entrada' and fechaInicio = '$fecha_creacion'";
		$proceso1 = mysqli_query($conexion,$sql1);
		$contador1 = mysqli_num_rows($proceso1);

		if($contador1>0){
			$datos = [
				"estatus"	=> "error",
				"msg"	=> "Ya iniciaste turno hoy",
			];
			echo json_encode($datos);
			exit;
		}

		$sql2 = "SELECT ho.entrada, ho.entradaMaxima FROM usuarios usu 
		INNER JOIN horarios ho 
		ON ho.id = usu.horarios
		WHERE usu.id = $usuarioId";
		$proceso2 = mysqli_query($conexion,$sql2);
		while($row2=mysqli_fetch_array($proceso2)){
			$entradaMaxima = $row2["entradaMaxima"];
		}

		$sql3 = "INSERT INTO turnos (usuarioId,tipo,fechaInicio,horaInicio) VALUES ($usuarioId,'Entrada','$fecha_creacion','$hora_creacion')";
		$proceso3 = mysqli_query($conexion,$sql3);

		if($fecha_creacion > $entradaMaxima){
			$datos = [
				"estatus"	=> "info",
				"msg"	=> "Excediste tu hora de llegada se le descontara del pago",
			];
			echo json_encode($datos);
		}else{
			$datos = [
				"estatus"	=> "ok",
				"msg"	=> "Turno Abierto",
			];
			echo json_encode($datos);
		}
	}

	if($asunto=='cerrarTurno'){
		$id = $_POST['id'];
		$sql1 = "SELECT * FROM turnos WHERE usuarioId = $id and fechaInicio = '$fecha_creacion' and tipo = 'Entrada'";
		$proceso1 = mysqli_query($conexion,$sql1);
		$contador1 = mysqli_num_rows($proceso1);
		if($contador1==0){
			$datos = [
				"estatus"	=> "error",
				"msg"	=> "No ha iniciado un turno hoy",
			];
			echo json_encode($datos);
		}else{
			$sql2 = "INSERT INTO turnos (usuarioId,tipo,fechaInicio,horaInicio) VALUES ($id,'Salida','$fecha_creacion','$hora_creacion')";
			$proceso2 = mysqli_query($conexion,$sql2);
			$datos = [
				"estatus"	=> "ok",
				"msg"	=> "Turno Cerrado",
			];
			echo json_encode($datos);
		}
	}

	if($asunto=='agregarExtras'){
		$id = $_POST['id'];
		$fecha = $_POST['fecha'];
		$desde = $_POST['desde'];
		$hasta = $_POST['hasta'];

		$timestamp1 = strtotime($desde);
		$timestamp2 = strtotime($hasta);
		$diferencia = abs($timestamp2 - $timestamp1);

		if($timestamp1>=$timestamp2){
			$datos = [
				"estatus"	=> "error",
				"msg"	=> "La primera hora debe ser menor a la segunda",
			];
			echo json_encode($datos);
			exit;
		}else if($diferencia < 3600){
			$datos = [
				"estatus"	=> "error",
				"msg"	=> "Deben tener un mínimo de 1 hora de diferencias",
			];
			echo json_encode($datos);
			exit;
		}

		$sql1 = "SELECT * FROM turnos WHERE usuarioId = $id and fechaInicio = '$fecha' and tipo = 'Extras'";
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
		$sql2 = "INSERT INTO turnos (usuarioId,tipo,fechaInicio,horaInicio,fechaFin,horaFin) VALUES ($id,'Extras','$fecha','$desde','$fecha','$hasta')";
		$proceso2 = mysqli_query($conexion,$sql2);
		$datos = [
			"estatus"	=> "ok",
			"msg"	=> "Se ha creado satisfactoriamente",
		];
		echo json_encode($datos);
	}

	if($asunto=='cambioExtras'){
		$id = $_POST['id'];
		$estatus = $_POST['estatus'];
		if($estatus==0){
			$cambio = 1;
		}else{
			$cambio = 0;
		}
		$sql1 = "UPDATE turnos SET estatusExtras = $cambio WHERE id = $id";
		$proceso1 = mysqli_query($conexion,$sql1);
		$datos = [
			"estatus"	=> "ok",
			"msg"	=> "",
		];
		echo json_encode($datos);
	}

	if($asunto=='eliminar'){
		$id = $_POST['id'];
		$sql1 = "DELETE FROM turnos WHERE id = $id";
		$proceso1 = mysqli_query($conexion,$sql1);
		$datos = [
			"estatus"	=> "ok",
			"msg"	=> "",
		];
		echo json_encode($datos);
	}

	function diferenciaHoras($conexion,$usuarioId,$fechaInicio){
		$diferenciaHoras = 0;
		$sql1 = "SELECT * FROM turnos WHERE usuarioId = $usuarioId and fechaInicio = '$fechaInicio' and tipo = 'Entrada'";
		$proceso1 = mysqli_query($conexion,$sql1);
		$contador1 = mysqli_num_rows($proceso1);
		if($contador1>0){
			$sql2 = "SELECT * FROM turnos WHERE usuarioId = $usuarioId and fechaInicio = '$fechaInicio' and tipo = 'Salida'";
			$proceso2 = mysqli_query($conexion,$sql2);
			$contador2 = mysqli_num_rows($proceso2);
			if($contador2>0){
				while($row1 = mysqli_fetch_array($proceso1)) {
					$horaInicioEntrada = $row1["horaInicio"];
				}
				while($row2 = mysqli_fetch_array($proceso2)) {
					$horaInicioSalida = $row2["horaInicio"];
				}
			}
			$segundos1 = strtotime($horaInicioEntrada);
			$segundos2 = strtotime($horaInicioSalida);
			$diferenciaSegundos = abs($segundos2 - $segundos1);
			$diferenciaHoras = floor($diferenciaSegundos / 3600);
		}
		return $diferenciaHoras;
	}



?>