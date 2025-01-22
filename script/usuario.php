<?php
session_start();
include("conexion.php");
$fecha_creacion = date('Y-m-d');
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
			}

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
			$filtrado = ' and (usua.usuario LIKE "%'.$filtrado.'%" or usua.nombre LIKE "%'.$filtrado.'%")';
		}

		if($rol!=''){
			$rol = ' and (usua.rol = '.$rol.')';
		}

		$limit = $consultasporpagina;
		$offset = ($pagina - 1) * $consultasporpagina;

		$sql1 = "SELECT usua.id as usua_id, usua.usuario as usua_usuario, usua.nombre as usua_nombre, role.nombre as role_nombre FROM usuarios usua 
			INNER JOIN roles role 
			ON usua.rol = role.id  
			WHERE usua.id != 1 ".$filtrado." ".$rol;

		$sql2 = "SELECT usua.id as usua_id, usua.usuario as usua_usuario, usua.nombre as usua_nombre, role.nombre as role_nombre FROM usuarios usua 
			INNER JOIN roles role 
			ON usua.rol = role.id 
			WHERE usua.id != 1 ".$filtrado." ".$rol." ORDER BY usua.id DESC LIMIT ".$limit." OFFSET ".$offset;
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
						<th class="text-center">Nombre</th>
						<th class="text-center">Usuario</th>
						<th class="text-center">Rol</th>
						<th class="text-center">Acciones</th>
		            </tr>
		            </thead>
		            <tbody>
		';
		if($conteo1>=1){
			while($row2 = mysqli_fetch_array($proceso2)) {
				$usua_id = $row2["usua_id"];
				$usua_nombre = $row2["usua_nombre"];
				$usua_usuario = $row2["usua_usuario"];
				$role_nombre = $row2["role_nombre"];
				$html .= '
			                <tr id="">
			                    <td style="text-align:center;">'.$usua_nombre.'</td>
			                    <td style="text-align:center;">'.$usua_usuario.'</td>
			                    <td style="text-align:center;">'.$role_nombre.'</td>
			                    <td style="text-align:center;">
			                    	<button class="btn btn-primary" data-toggle="modal" data-target="#modificar" onclick="modificar('.$usua_id.');">Modificar</button>
			                    </td>
			                </tr>
				';
			}
		}else{
			$html .= '<tr><td colspan="4" class="text-center" style="font-weight:bold;font-size:20px;">Sin Resultados</td></tr>';
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
		$nombre = $_POST['nombre'];
		$password = md5($_POST['password']);
		$rol = $_POST['rol'];

		$sql3 = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
		$proceso3 = mysqli_query($conexion,$sql3);
		$contador3 = mysqli_num_rows($proceso3);
		if($contador3>0){
			$datos = [
				"estatus"	=> "error",
				"msg"	=> "El usuario ya existe en otro registro",
			];
			echo json_encode($datos);
			exit;
		}
		
		$sql1 = "INSERT INTO usuarios (usuario,nombre,password,rol) VALUES ('$usuario','$nombre','$password',$rol)";
		$proceso1 = mysqli_query($conexion,$sql1);

		/******************/
		auditoriaGeneral("Usuario = $usuario","Creado nuevo usuario");
		/******************/

		$datos = [
			"estatus"	=> "ok",
			"msg"	=> "Se ha creado exitosamente",
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
				$usuario = $row1["usuario"];
				$nombre = $row1["nombre"];
				$rol = $row1["rol"];
			}
			$datos = [
				"estatus"	=> "ok",
				"usuario"	=> $usuario,
				"nombre"	=> $nombre,
				"rol"		=> $rol,
			];
			echo json_encode($datos);
		}
	}

	if($asunto=='modificar_guardar'){
		$id = $_POST['id'];
		$usuario = $_POST['usuario'];
		$nombre = $_POST['nombre'];
		$password = $_POST['password'];
		$rol = $_POST['rol'];

		$sql1 = "SELECT * FROM usuarios WHERE usuario = '$usuario' and id != ".$id;
		$proceso1 = mysqli_query($conexion,$sql1);
		$contador1 = mysqli_num_rows($proceso1);
		if($contador1>=1){
			$datos = [
				"estatus"	=> "error",
				"msg"	=> "Usuario ya existe en otro registro",
			];
			echo json_encode($datos);
		}else{
			if($password==''){
				$sql2 = "UPDATE usuarios SET usuario = '$usuario', nombre = '$nombre', rol = $rol WHERE id = ".$id;
			}else{
				$password = md5($password);
				$sql2 = "UPDATE usuarios SET usuario = '$usuario', nombre = '$nombre', password = '$password', rol = $rol WHERE id = ".$id;
			}
			$proceso2 = mysqli_query($conexion,$sql2);
			/******************/
			auditoriaGeneral("Usuario = $usuario","Actualizado Usuario");
			/******************/
			$datos = [
				"estatus"	=> "ok",
				"msg"	=> "Se ha modificado exitosamente",
			];
			echo json_encode($datos);
		}
	}

	if($asunto=='eliminar'){
		$id = $_POST["id"];
		$sql1 = "DELETE FROM usuarios WHERE id = ".$id;
		$proceso1 = mysqli_query($conexion,$sql1);
		/******************/
		auditoriaGeneral("Usuario id = $id","Eliminado Usuario");
		/******************/
		$datos = [
			"estatus"	=> "ok",
			"msg"	=> "Se ha eliminado exitosamente",
		];
		echo json_encode($datos);
	}

?>