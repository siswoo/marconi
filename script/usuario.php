<?php
session_start();
include("conexion.php");
$fecha_creacion = date('Y-m-d');
$hora_creacion = date('h:i:s');
$asunto = $_POST['asunto'];

	if($asunto=="login"){
		$usuario = $_POST['usuario'];
		$password = md5($_POST['password']);
		$sql1 = "SELECT * FROM usuarios WHERE usuario = '$usuario' and password = '$password' LIMIT 1";
		$proceso1 = mysqli_query($conexion,$sql1);
		$contador1 = mysqli_num_rows($proceso1);
		if($contador1==0){
			$datos = [
				"estatus"	=> "error",
				"msg"	=> "Credenciales Incorrectas",
			];
			echo json_encode($datos);
			exit;
		}else if($contador1>=1){
			while($row1=mysqli_fetch_array($proceso1)){
				$usuarioId = $row1["id"];
				$rol = $row1["rol"];
				$estado = $row1["estado"];
			}

			if($estado=="Inactivo"){
				$datos = [
					"estatus"	=> "error",
					"msg"	=> "Usuario inactivo",
				];
				echo json_encode($datos);
				exit;
			}

			$turnoCierre = validarCierre($conexion,$usuarioId,$fecha_creacion);
			if($turnoCierre==false){
				$datos = [
					"estatus"	=> "info",
					"msg"	=> "Turno finalizado",
				];
				echo json_encode($datos);
				exit;
			}

			//iniciarTurno($conexion,$usuarioId,$fecha_creacion,$hora_creacion);

			$redireccion = "admin.php";
			$_SESSION["marconiId"] = $usuarioId;
			$_SESSION["marconiRol"] = $rol;
			
			$datos = [
				"estatus"	=> "ok",
				"redireccion"	=> $redireccion,
			];
			echo json_encode($datos);
		}
	}

	if($asunto=='table1'){
		$pagina = $_POST["pagina"];
		$consultasporpagina = $_POST["consultasporpagina"];
		$filtrado = $_POST["filtrado"];
		$rol = $_POST["rol"];

		if($pagina==0 or $pagina==''){
			$pagina = 1;
		}

		if($consultasporpagina==0 or $consultasporpagina==''){
			$consultasporpagina = 10;
		}

		if($filtrado!=''){
			$filtrado = ' and (nombre LIKE "%'.$filtrado.'%" or apellido LIKE "%'.$filtrado.'%" or apellido2 LIKE "%'.$filtrado.'%")';
		}

		$limit = $consultasporpagina;
		$offset = ($pagina - 1) * $consultasporpagina;

		$sql1 = "SELECT * FROM usuarios WHERE rol = $rol ".$filtrado;
		$sql2 = "SELECT * FROM usuarios WHERE rol = $rol ".$filtrado." ORDER BY id DESC LIMIT ".$limit." OFFSET ".$offset;

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
						<th class="text-center">ID</th>
						<th class="text-center">Nombre</th>
						<th class="text-center">Cédula</th>
						<th class="text-center">Genero</th>
						<th class="text-center">Teléfono</th>
						<th class="text-center">Correo</th>
						<th class="text-center">Dirección</th>
						<th class="text-center">Ingreso</th>
						<th class="text-center">Estado</th>
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
				$genero = $row2["genero"];
				$telefono = $row2["telefono"];
				$correo = $row2["correo"];
				$direccion = $row2["direccion"];
				$fechaIngreso = $row2["fechaIngreso"];
				$estado = $row2["estado"];
				$html .= '
			                <tr id="">
			                	<td style="text-align:center;">'.$id.'</td>
			                    <td style="text-align:center;">'.$nombre.'</td>
			                    <td style="text-align:center;">'.$cedula.'</td>
			                    <td style="text-align:center;">'.$genero.'</td>
			                    <td style="text-align:center;">'.$telefono.'</td>
			                    <td style="text-align:center;">'.$correo.'</td>
			                    <td style="text-align:center;">'.$direccion.'</td>
			                    <td style="text-align:center;">'.$fechaIngreso.'</td>
			                    <td style="text-align:center;">'.$estado.'</td>
			                    <td style="text-align:center;" nowrap>
			                    	<button class="btn btn-info" onclick=cambioEstatus('.$id.',"'.$estado.'");>Estatus</button>
			                    	<button class="btn btn-primary" data-toggle="modal" data-target="#modificar" onclick="modificar('.$id.');">Modificar</button>
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

	if($asunto=='crear'){
		$nombre = $_POST['nombre'];
		$apellido = $_POST['apellido'];
		$apellido1_2 = $_POST['apellido1_2'];
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
		$horarios = $_POST['horarios'];
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
		
		$sql1 = "INSERT INTO usuarios (nombre,usuario,apellido,apellido2,cedula,fechaNacimiento,genero,telefono,correo,direccion,fechaIngreso,salario,password,cargo,rol,horarios) VALUES ('$nombre','$usuario','$apellido','$apellido1_2','$cedula','$fechaNacimiento','$genero','$telefono','$correo','$direccion','$fechaIngreso',$salario,'$password','$cargo',2,$horarios)";
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
				$apellido2 = $row1["apellido2"];
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
				$horarios = $row1["horarios"];
			}
			$datos = [
				"estatus"	=> "ok",
				"nombre"	=> $nombre,
				"apellido"	=> $apellido,
				"apellido2"	=> $apellido2,
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
				"horarios"		=> $horarios,
			];
			echo json_encode($datos);
		}
	}

	if($asunto=='modificar_guardar'){
		$id = $_POST['id'];
		$nombre = $_POST['nombre'];
		$apellido = $_POST['apellido'];
		$apellido2_2 = $_POST['apellido2_2'];
		$cedula = $_POST['cedula'];
		$fechaNacimiento = $_POST['fechaNacimiento'];
		$genero = $_POST['genero'];
		$telefono = $_POST['telefono'];
		$correo = $_POST['correo'];
		$direccion = $_POST['direccion'];
		$fechaIngreso = $_POST['fechaIngreso'];
		$salario = $_POST['salario'];
		$horarios = $_POST['horarios'];
		if($_POST['password']!=""){
			$password = md5($_POST['password']);
			$password = "password = '$password',";
		}else{
			$password = "";
		}
		$cargo = $_POST['cargo'];
		$usuario = $cedula;

		$sql1 = "UPDATE usuarios SET nombre = '$nombre', usuario = '$usuario', apellido = '$apellido', apellido2 = '$apellido2_2', cedula = '$cedula', fechaNacimiento = '$fechaNacimiento', genero = '$genero', telefono = '$telefono', correo = '$correo', direccion = '$direccion', fechaIngreso = '$fechaIngreso', salario = $salario, $password cargo = '$cargo', horarios = $horarios WHERE id = ".$id;
		$proceso1 = mysqli_query($conexion,$sql1);
		$datos = [
			"estatus"	=> "ok",
			"msg"	=> "Actualizado correctamente",
		];
		echo json_encode($datos);
	}

	if($asunto=='listadoUsuarios'){
		$options = '<option value="">Seleccione</option>';
		$sql1 = "SELECT * FROM usuarios";
		$proceso1 = mysqli_query($conexion,$sql1);
		while ($row1 = mysqli_fetch_array($proceso1)) {
			$usuarioId = $row1["id"];
			$usuarioNombre = $row1["nombre"]." ".$row1["apellido"]." | ".$row1["cedula"];
			$options .= '<option value="'.$usuarioId.'">'.$usuarioNombre.'</option>';
		}
		$datos = [
			"estatus"	=> "ok",
			"options"	=> $options,
		];
		echo json_encode($datos);
	}

	function validarCierre($conexion,$usuarioId,$fecha_creacion){
		$sql1 = "SELECT * FROM turnos WHERE usuarioId = $usuarioId and tipo = 'Salida' and fechaInicio = '$fecha_creacion'";
		$proceso1 = mysqli_query($conexion,$sql1);
		$contador1 = mysqli_num_rows($proceso1);
		if($contador1==0){
			return true;
		}else{
			return false;
		}
	}

	function iniciarTurno($conexion,$usuarioId,$fecha_creacion,$hora_creacion){
		$sql1 = "SELECT * FROM turnos WHERE usuarioId = $usuarioId and fechaInicio = '$fecha_creacion'";
		$proceso1 = mysqli_query($conexion,$sql1);
		$contador1 = mysqli_num_rows($proceso1);
		if($contador1==0){
			$sql2 = "INSERT INTO turnos (usuarioId,tipo,fechaInicio,horaInicio) VALUES ($usuarioId,'Entrada','$fecha_creacion','$hora_creacion')";
			$proceso2 = mysqli_query($conexion,$sql2);
		}
	}

?>