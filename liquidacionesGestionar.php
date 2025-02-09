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

<style type="text/css">
    @media (max-width: 800px) {
        table{
            display: block;
            overflow: scroll;
            white-space: nowrap;
        }
    }
</style>

<body id="page-top">

    <div id="wrapper">
        <?php include("menu.php"); ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php 
                    include("header.php"); 
                    $esAdmin = false;
                    $sqlRol = "SELECT rol FROM usuarios WHERE id = ".$_SESSION['marconiId'];
                    $procesoRol = mysqli_query($conexion,$sqlRol);
                    while($row1=mysqli_fetch_array($procesoRol)){
                        $rol = $row1["rol"];
                    }
                    if($rol==1){
                        $esAdmin = true;
                    }
                ?>
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Gestionar Liquidaciones</h1>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow mb-4">
                                <div class="row ml-3 mr-3" style="margin-top: 2rem;">
                                    <input type="hidden" name="datatables" id="datatables" data-pagina="1" data-consultasporpagina="10" data-filtrado="" data-fechafiltro="" data-sucursal="">
                                    <div class="col-xl-3 col-lg-12 form-group form-check">
                                        <label for="consultasporpagina" style="color:black; font-weight: bold;">Resultados por página</label>
                                        <select class="form-control" id="consultasporpagina" name="consultasporpagina">
                                            <option value="10">10</option>
                                            <option value="20">20</option>
                                            <option value="30">30</option>
                                            <option value="40">40</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select>
                                    </div>
                                    <div class="col-xl-3 col-lg-12 form-group form-check">
                                        <label for="consultasporpagina" style="color:black; font-weight: bold;">Fecha</label>
                                        <input type="date" class="form-control" id="fechafiltro" name="fechafiltro">
                                    </div>
                                    <div class="col-xl-3 col-lg-12 form-group form-check">
                                        <label for="buscarfiltro" style="color:black; font-weight: bold;">Busqueda</label>
                                        <input type="text" class="form-control" id="buscarfiltro" autocomplete="off" name="buscarfiltro">
                                    </div>
                                    <div class="col-xl-3 col-lg-12 text-center">
                                        <br>
                                        <button type="button" class="btn btn-info" onclick="filtrar1();">Filtrar</button>
                                    </div>
                                    <div class="col-12" style="background-color: white; border-radius: 1rem; padding: 20px 1px 1px 1px;" id="resultado_table1"></div>
                                </div>

                                <?php $ubicacion_url = $_SERVER["PHP_SELF"]; ?>
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

<input type="hidden" id="hiddenId" name="hiddenId" value="<?php echo $_SESSION['marconiId']; ?>">
<input type="hidden" id="hiddenRol" name="hiddenRol" value="<?php echo $rolId; ?>">
<input type="hidden" id="hiddenId2" name="hiddenId2" value="">

<!-- Modal Crear -->
    <div class="modal fade" id="crear" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="#" method="POST" id="formulario1" style="">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Solicitar Liquidación</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 form-group form-check">
                                <label for="fecha1" style="font-weight: bold;">Fecha Solicitud *</label>
                                <input type="date" id="fecha1" name="fecha1" class="form-control" min="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                            <div class="col-md-12 form-group form-check">
                                <label for="opcion1" style="font-weight: bold;">Opción *</label>
                                <select class="form-control" name="opcion1" id="opcion1" onchange="calcular(value)" required>
                                    <option value="">Seleccione</option>
                                    <option value="Despido con justa causa">Despido con justa causa</option>
                                    <option value="Renuncia voluntaria">Renuncia voluntaria</option>
                                    <option value="Despido sin justa causa">Despido sin justa causa</option>
                                    <option value="Despido justificada">Despido justificada</option>
                                </select>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="salario1" style="font-weight: bold;">Salario</label>
                                <input type="text" id="salario1" name="salario1" class="form-control" readonly>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="vacaciones1" style="font-weight: bold;">Vacaciones</label>
                                <input type="text" id="vacaciones1" name="vacaciones1" class="form-control" readonly>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="aguinaldos1" style="font-weight: bold;">Aguinaldos</label>
                                <input type="text" id="aguinaldos1" name="aguinaldos1" class="form-control" readonly>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="preaviso1" style="font-weight: bold;">Preaviso</label>
                                <input type="text" id="preaviso1" name="preaviso1" class="form-control" readonly>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="cesantias1" style="font-weight: bold;">Cesantias</label>
                                <input type="text" id="cesantias1" name="cesantias1" class="form-control" readonly>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="total1" style="font-weight: bold;">Total</label>
                                <input type="text" id="total1" name="total1" class="form-control" readonly>
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


<!-- Modal Detalles -->
    <div class="modal fade" id="detalle" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Detalles Liquidación</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 form-group form-check">
                                <label for="nombre2" style="font-weight: bold;">Nombre</label>
                                <input type="text" id="nombre2" name="nombre2" class="form-control" readonly>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="apellido2" style="font-weight: bold;">Apellido</label>
                                <input type="text" id="apellido2" name="apellido2" class="form-control" readonly>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="cedula2" style="font-weight: bold;">Cédula</label>
                                <input type="text" id="cedula2" name="cedula2" class="form-control" readonly>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="opcion2" style="font-weight: bold;">Opción</label>
                                <input type="text" id="opcion2" name="opcion2" class="form-control" readonly>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="fechaInicio2" style="font-weight: bold;">Fecha</label>
                                <input type="text" id="fechaInicio2" name="fechaInicio2" class="form-control" readonly>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="salario2" style="font-weight: bold;">Salario</label>
                                <input type="text" id="salario2" name="salario2" class="form-control" readonly>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="vacaciones2" style="font-weight: bold;">Vacaciones</label>
                                <input type="text" id="vacaciones2" name="vacaciones2" class="form-control" readonly>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="aguinaldos2" style="font-weight: bold;">Aguinaldos</label>
                                <input type="text" id="aguinaldos2" name="aguinaldos2" class="form-control" readonly>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="preaviso2" style="font-weight: bold;">Preaviso</label>
                                <input type="text" id="preaviso2" name="preaviso2" class="form-control" readonly>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="cesantias2" style="font-weight: bold;">Cesantias</label>
                                <input type="text" id="cesantias2" name="cesantias2" class="form-control" readonly>
                            </div>
                            <div class="col-md-12 form-group form-check">
                                <label for="total2" style="font-weight: bold;">Total</label>
                                <input type="text" id="total2" name="total2" class="form-control" readonly>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
<!----------------->

<script type="text/javascript">
    $(document).ready(function() {
        filtrar1();
    });

    function paginacion1(value){
        $('#datatables').attr({'data-pagina':value})
        filtrar1();
    }
    
    function filtrar1() {
        var usuario = $('#hiddenId').val();
        var rol = $('#hiddenRol').val();
        var input_consultasporpagina = $('#consultasporpagina').val();
        var input_buscarfiltro = $('#buscarfiltro').val();
        var input_fechafiltro = $('#fechafiltro').val();

        $('#datatables').attr({
            'data-consultasporpagina': input_consultasporpagina
        })
        $('#datatables').attr({
            'data-filtrado': input_buscarfiltro
        })
        $('#datatables').attr({
            'data-fechafiltro': input_fechafiltro
        })

        var pagina = $('#datatables').attr('data-pagina');
        var consultasporpagina = $('#datatables').attr('data-consultasporpagina');
        var filtrado = $('#datatables').attr('data-filtrado');
        var fecha = $('#datatables').attr('data-fechafiltro');
        var ubicacion_url = '<?php echo $ubicacion_url; ?>';

        $.ajax({
            type: 'POST',
            url: 'script/liquidaciones.php',
            dataType: "JSON",
            data: {
                "usuario": usuario,
                "rol": rol,
                "pagina": pagina,
                "consultasporpagina": consultasporpagina,
                "filtrado": filtrado,
                "fecha": fecha,
                "link1": ubicacion_url,
                "asunto": "table1",
            },

            success: function(respuesta) {
                console.log(respuesta);
                $('#resultado_table1').html(respuesta["html"]);
            },

            error: function(respuesta) {
                console.log(respuesta['responseText']);
            }
        });
    }

    $("#formulario1").on("submit", function(e){
        e.preventDefault();
        var usuario = $('#hiddenId2').val();
        var fecha = $('#fecha1').val();
        var opcion = $('#opcion1').val();
        $.ajax({
            type: 'POST',
            url: 'script/liquidaciones.php',
            dataType: "JSON",
            data: {
                "usuario": usuario,
                "fecha": fecha,
                "opcion": opcion,
                "asunto": "crear",
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
                    $('#fecha1').val("");
                    $('#opcion1').val("");
                    $('#salario1').val("");
                    $('#vacaciones1').val("");
                    $('#aguinaldos1').val("");
                    $('#preaviso1').val("");
                    $('#cesantias1').val("");
                    $('#total1').val("");
                    filtrar1();
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

    function cambioEstatus(id,estatus){
        $.ajax({
            type: 'POST',
            url: 'script/liquidaciones.php',
            dataType: "JSON",
            data: {
                "id": id,
                "estatus": estatus,
                "asunto": "cambioEstatus",
            },
              
            success: function(respuesta) {
                console.log(respuesta);
                filtrar1();
            },

            error: function(respuesta) {
                console.log(respuesta['responseText']);
            }
        });
    }

    function eliminar(id){
        Swal.fire({
          title: 'Estas seguro?',
          text: "Esta acción no podra revertirse",
          icon: 'warning',
          showConfirmButton: true,
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, eliminar el registro!',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.value) {
            $.ajax({
                type: 'POST',
                url: 'script/liquidaciones.php',
                dataType: "JSON",
                data: {
                    "id": id,
                    "asunto": "eliminar",
                },

                success: function(respuesta) {
                    console.log(respuesta);
                    filtrar1();
                },

                error: function(respuesta) {
                    console.log(respuesta['responseText']);
                }
            });
          }
        })
    }

    function listadoUsuarios(idSelect){
        $.ajax({
            type: 'POST',
            url: 'script/usuario.php',
            dataType: "JSON",
            data: {
                "asunto": "listadoUsuarios",
            },

            success: function(respuesta) {
                console.log(respuesta);
                $('#usuario'+idSelect).html(respuesta["options"]);
            },

            error: function(respuesta) {
                console.log(respuesta['responseText']);
            }
        });
    }

    function hiddenId(usuarioId){
        $('#hiddenId2').val(usuarioId);
        $('#fecha1').val("");
        $('#opcion1').val("");
        $('#salario1').val("");
        $('#vacaciones1').val("");
        $('#aguinaldos1').val("");
        $('#preaviso1').val("");
        $('#cesantias1').val("");
        $('#total1').val("");
    }

    function calcular(value){
        var usuarioId = $('#hiddenId2').val();
        var fecha = $('#fecha1').val();
        if(fecha==""){
            Swal.fire({
                title: 'Info',
                text: "Debe elegir una fecha",
                icon: 'info',
                position: 'center',
                timer: 5000
            });
            return false;
        }
        if(value==""){
            return false;
        }
        $.ajax({
            type: 'POST',
            url: 'script/liquidaciones.php',
            dataType: "JSON",
            data: {
                "usuarioId": usuarioId,
                "fecha": fecha,
                "value": value,
                "asunto": "calcular",
            }, 

            success: function(respuesta) {
                console.log(respuesta);
                $('#salario1').val(respuesta["montoSalario"]);
                $('#vacaciones1').val(respuesta["montoVacaciones"]);
                $('#aguinaldos1').val(respuesta["montoAguinaldo"]);
                $('#preaviso1').val(respuesta["montoPreaviso"]);
                $('#cesantias1').val(respuesta["montoCesantia"]);
                $('#total1').val(respuesta["total"]);
                filtrar1();
            },

            error: function(respuesta) {
                console.log(respuesta['responseText']);
            }
        });
    }

    function liquidar(usuarioId){
        $.ajax({
            type: 'POST',
            url: 'script/liquidaciones.php',
            dataType: "JSON",
            data: {
                "usuarioId": usuarioId,
                "asunto": "crear",
            },

            success: function(respuesta) {
                console.log(respuesta);
                $('#usuario'+idSelect).html(respuesta["options"]);
            },

            error: function(respuesta) {
                console.log(respuesta['responseText']);
            }
        });
    }

    function detalle(id){
        $.ajax({
            type: 'POST',
            url: 'script/liquidaciones.php',
            dataType: "JSON",
            data: {
                "id": id,
                "asunto": "detalle",
            },

            success: function(respuesta) {
                console.log(respuesta);
                $('#nombre2').val(respuesta['nombre']);
                $('#apellido2').val(respuesta['apellido']);
                $('#cedula2').val(respuesta['cedula']);
                $('#opcion2').val(respuesta['opcion']);
                $('#fechaInicio2').val(respuesta['fechaInicio']);
                $('#salario2').val(respuesta['salario']);
                $('#vacaciones2').val(respuesta['vacaciones']);
                $('#aguinaldos2').val(respuesta['aguinaldos']);
                $('#preaviso2').val(respuesta['preaviso']);
                $('#cesantias2').val(respuesta['cesantias']);
                $('#total2').val(respuesta['total']);
            },

            error: function(respuesta) {
                console.log(respuesta['responseText']);
            }
        });
    }

</script>
