<?php
	session_start();
	session_destroy();
?>
<!doctype html>
<html>
    <head>
        <link rel="shortcut icon" href="#" />
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Marconi</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"> 
        <link rel="stylesheet" href="css/estilos.css">
        <link rel="stylesheet" type="text/css" href="fuentes/iconic/css/material-design-iconic-font.min.css">
        
    </head>
    
    <body>
     
      <div class="container-login">
        <div class="wrap-login">
            <form class="login-form validate-form" id="formulario1" action="#" method="post">
                <span class="login-form-title">LOGIN</span>
                <div class="wrap-input100">
                    <input type="text" class="input100" name="usuario" id="usuario" placeholder="Usuario" autocomplete="off" minlength="4" required>
                    <span class="focus-efecto"></span>
                </div>
                
                <div class="wrap-input100">
                	<input type="password" class="input100" name="password" id="password" placeholder="Clave" minlength="8" required>
                    <span class="focus-efecto"></span>
                </div>
                
                <div class="container-login-form-btn">
                    <div class="wrap-login-form-btn">
                        <div class="login-form-bgbtn"></div>
                        <button type="submit" name="submit" class="login-form-btn">CONECTAR</button>
                    </div>
                </div>
            </form>
        </div>
    </div>     
        
        
     <script src="js/jquery-3.3.1.min.js"></script>
     <script type="text/javascript" src="plugins/sweetalert2/sweetalert2.js"></script>
    </body>
</html>

<script>
	$(document).ready(function() {
		//
	});

	$("#formulario1").on("submit", function(e){
		e.preventDefault();
		var usuario = $('#usuario').val();
		var password = $('#password').val();
		$.ajax({
			type: 'POST',
			url: 'script/usuario.php',
			dataType: "JSON",
			data: {
				"usuario": usuario,
				"password": password,
				"asunto": "login",
			},

			success: function(respuesta) {
				console.log(respuesta);
				if(respuesta["estatus"]=="ok"){
					window.location.href = respuesta["redireccion"];
				}else if(respuesta["estatus"]=="error"){
					Swal.fire({
		 				title: 'Error',
		 				text: respuesta["msg"],
		 				icon: 'error',
		 				position: 'center',
		 				timer: 5000
					});
				}else if(respuesta["estatus"]=="info"){
					Swal.fire({
		 				title: 'Info',
		 				text: respuesta["msg"],
		 				icon: 'info',
		 				position: 'center',
		 				timer: 5000
					});
				}
			},

			error: function(respuesta) {
				console.log(respuesta['responseText']);
			}
		});
  	});
</script>