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

                    <form id="formulario1" method="POST" action="script/exportar.php" target="_blank">
                        <input type="hidden" name="asunto" id="asunto" value="dashboard">
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
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

<script type="text/javascript">

    function consultar(){
        var año = $('#año').val();
        var mes = $('#mes').val();
        var dia = $('#dia').val();
        var idUsuario = $('#idUsuario').val();
        var selectOptions = $('#selectOptions').val();
        var asunto = "consultarVentas";
        if(año=="" || mes =="" || dia == ""){
            $("#Menu1").hide();
            return false;
        }
        $.ajax({
            type: 'POST',
            url: 'script/dashboard.php',
            dataType: "JSON",
            data: {
                "año": año,
                "mes": mes,
                "dia": dia,
                "idUsuario": idUsuario,
                "selectOptions": selectOptions,
                "asunto": asunto,
            },

            success: function(respuesta) {
                console.log(respuesta);
                if(respuesta["estatus"]=="ok"){
                    $('#gananciaDiaria').html(respuesta["gananciaDiaria"]);
                    $('#gananciaVentas').html(respuesta["gananciaVentas"]);
                    $('#gananciaCan').html(respuesta["gananciaCan"]);
                    $('#gananciaEsmad').html(respuesta["gananciaEsmad"]);
                    $('#ganancia_1').html(respuesta["ganancia_1"]);
                    $('#ganancia_2').html(respuesta["ganancia_2"]);
                    $('#ganancia_3').html(respuesta["ganancia_3"]);
                    $('#ganancia_4').html(respuesta["ganancia_4"]);
                    $('#ganancia_5').html(respuesta["ganancia_5"]);
                    $('#ganancia_6').html(respuesta["ganancia_6"]);
                    $('#ganancia_7').html(respuesta["ganancia_7"]);
                    $('#ganancia_8').html(respuesta["ganancia_8"]);
                    $('#ganancia_9').html(respuesta["ganancia_9"]);
                    $('#ganancia_10').html(respuesta["ganancia_10"]);
                    $('#ganancia_11').html(respuesta["ganancia_11"]);
                    $('#ganancia_12').html(respuesta["ganancia_12"]);
                    $('#ganancia_pendientesCredito').html(respuesta["pendientesCredito"]);
                    $("#Menu1").show();
                }else{
                    $("#Menu1").hide();
                }
            },

            error: function(respuesta) {
                console.log(respuesta['responseText']);
            }
        });
    }

    $("#formulario1").on("submit", function(e){
        e.preventDefault();
        var desde = $('#desde').val();
        var hasta = $('#hasta').val();
        var condicion = $('#condicion').val();
        $.ajax({
            type: 'POST',
            url: 'script/exportar.php',
            dataType: "JSON",
            data: {
                "condicion": condicion,
                "desde": desde,
                "hasta": hasta,
                "asunto": "dashboard",
            },

            success: function(respuesta) {
                console.log(respuesta);
                if(respuesta["estatus"]=="ok"){
                    window.location.href = respuesta["url"];
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

</script>