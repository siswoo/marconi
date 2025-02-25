<?php
session_start();
if (!isset($_SESSION['marconiId'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>AdminJJ</title>
    <link href="plugins/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/prueba1.css" rel="stylesheet">
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include("menu.php"); ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php 
                    include("header.php");
                    $sql1 = "SELECT * FROM usuarios WHERE id = ".$_SESSION['marconiId'];
                    $proceso1 = mysqli_query($conexion,$sql1);
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
                        $cargo = $row1["cargo"];
                    }
                ?>

                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Inicio</h1>
                    </div>
                        <div class="row">
                            <div class="col-xl-12 col-md-12 mb-4">
                                <div class="card border-left-primary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-4 mt-3">
                                                <label>Nombre</label>
                                                <input type="text" id="nombre" name="nombre" class="form-control" autocomplete="off" readonly value="<?php echo $nombre; ?>">
                                            </div>
                                            <div class="col-4 mt-3">
                                                <label>Apellido</label>
                                                <input type="text" id="apellido" name="apellido" class="form-control" autocomplete="off" readonly value="<?php echo $apellido; ?>">
                                            </div>
                                            <div class="col-4 mt-3">
                                                <label>Cédula</label>
                                                <input type="text" id="cedula" name="cedula" class="form-control" autocomplete="off" readonly value="<?php echo $cedula; ?>">
                                            </div>
                                            <div class="col-4 mt-3">
                                                <label>Teléfono</label>
                                                <input type="number" id="telefono" name="telefono" class="form-control" autocomplete="off" readonly value="<?php echo $telefono; ?>">
                                            </div>
                                            <div class="col-4 mt-3">
                                                <label>Correo</label>
                                                <input type="text" id="correo" name="correo" class="form-control" autocomplete="off" readonly value="<?php echo $correo; ?>">
                                            </div>
                                            <div class="col-4 mt-3">
                                                <label>Cargo</label>
                                                <input type="text" id="cargo" name="cargo" class="form-control" autocomplete="off" readonly value="<?php echo $cargo; ?>">
                                            </div>
                                            <div class="col-12 mt-3 text-center">
                                                <input type="button" class="btn btn-success" onclick="abrirTurno();" value="Iniciar Turno">
                                                <input type="button" class="btn btn-danger" onclick="cerrarTurno();" value="Cerrar turno">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>

            <?php include("footer.php"); ?>

        </div>
    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="plugins/jquery-easing/jquery.easing.min.js"></script>
    <script type="text/javascript" src="plugins/sweetalert2/sweetalert2.js"></script>
</body>

</html>

<input type="hidden" name="idUsuario" id="idUsuario" value="<?php echo $_SESSION['marconiId']; ?>">

<!-- Modal Crear -->
    <div class="modal fade" id="extras" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="#" method="POST" id="formulario1" style="">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Horas extras</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 form-group form-check">
                                <label for="fecha1" style="font-weight: bold;">Fecha *</label>
                                <input type="date" id="fecha1" name="fecha1" class="form-control" autocomplete="off" required>
                            </div>
                            <div class="col-md-12 form-group form-check">
                                <label for="desde1" style="font-weight: bold;">Desde *</label>
                                <input type="time" id="desde1" name="desde1" class="form-control" autocomplete="off" required>
                            </div>
                            <div class="col-md-12 form-group form-check">
                                <label for="hasta1" style="font-weight: bold;">Hasta *</label>
                                <input type="time" id="hasta1" name="hasta1" class="form-control" autocomplete="off" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<!----------------->

<script type="text/javascript">
    $(document).ready(function() {
        setearFechas();
    });

    $("#formulario1").on("submit", function(e){
        e.preventDefault();
        var idUsuario = $('#idUsuario').val();
        var fecha = $('#fecha1').val();
        var desde = $('#desde1').val();
        var hasta = $('#hasta1').val();
        $.ajax({
            type: 'POST',
            url: 'script/turno.php',
            dataType: "JSON",
            data: {
                "id": idUsuario,
                "fecha": fecha,
                "desde": desde,
                "hasta": hasta,
                "asunto": "agregarExtras",
            },

            success: function(respuesta) {
                console.log(respuesta);
                if(respuesta["estatus"]=="ok"){
                    Swal.fire({
                        title: 'Ok',
                        text: respuesta["msg"],
                        icon: 'success',
                        position: 'center',
                        timer: 5000
                    });
                }else if(respuesta["estatus"]=="error"){
                    Swal.fire({
                        title: 'Error',
                        text: respuesta["msg"],
                        icon: 'error',
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

    function abrirTurno(){
        var idUsuario = $('#idUsuario').val();
        $.ajax({
            type: 'POST',
            url: 'script/turno.php',
            dataType: "JSON",
            data: {
                "idUsuario": idUsuario,
                "asunto": "abrirTurno",
            },

            success: function(respuesta) {
                console.log(respuesta);
                if(respuesta["estatus"]=="ok"){
                    Swal.fire({
                        title: 'Ok',
                        text: respuesta["msg"],
                        icon: 'success',
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
                }else if(respuesta["estatus"]=="error"){
                    Swal.fire({
                        title: 'Error',
                        text: respuesta["msg"],
                        icon: 'error',
                        position: 'center',
                        timer: 5000
                    });
                }
            },

            error: function(respuesta) {
                console.log(respuesta['responseText']);
            }
        });
    }

    function cerrarTurno(){
        Swal.fire({
          title: 'Estas seguro?',
          text: "Esta acción no podra revertirse",
          icon: 'warning',
          showConfirmButton: true,
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, Cerrar Turno!',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.value) {
            var idUsuario = $('#idUsuario').val();
            $.ajax({
                type: 'POST',
                url: 'script/turno.php',
                dataType: "JSON",
                data: {
                    "id": idUsuario,
                    "asunto": "cerrarTurno",
                },

                success: function(respuesta) {
                    console.log(respuesta);
                    window.location.href = 'index.php';
                },

                error: function(respuesta) {
                    console.log(respuesta['responseText']);
                }
            });
          }
        })
    }

    function setearFechas(){
        const fechaInput = document.getElementById('fecha1');
        const hoy = new Date();
        const fechaMin = new Date();
        fechaMin.setDate(hoy.getDate() - 30);
        const fechaMax = new Date();
        fechaMax.setDate(hoy.getDate() + 30);
        const formatoFecha = (date) => {
            return date.toISOString().split('T')[0];
        };
        fechaInput.min = formatoFecha(fechaMin);
        fechaInput.max = formatoFecha(fechaMax);
        fechaInput.value = formatoFecha(hoy);
    }


</script>