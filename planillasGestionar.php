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
                <?php include("header.php"); ?>
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Gestionar Planillas</h1>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Listado de Planillas</h6>
                                </div>
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
                                        <input type="month" class="form-control" id="fechafiltro" name="fechafiltro" onchange="consultar(value)">
                                    </div>
                                    <div class="col-xl-3 col-lg-12 form-group form-check">
                                        <label for="buscarfiltro" style="color:black; font-weight: bold;">Busqueda</label>
                                        <input type="text" class="form-control" id="buscarfiltro" autocomplete="off" name="buscarfiltro">
                                    </div>
                                    <div class="col-xl-3 col-lg-12 text-center" id="buttonAction">
                                        <br>
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

<input type="hidden" id="hiddenId" name="hiddenId" value="">

<!-- Modal Detalles -->
    <div class="modal fade" id="detalle" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Detalles</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 form-group form-check">
                                <label for="nombre1" style="font-weight: bold;">Nombre</label>
                                <input type="text" id="nombre1" name="nombre1" class="form-control" readonly>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="apellido1" style="font-weight: bold;">Apellido</label>
                                <input type="text" id="apellido1" name="apellido1" class="form-control" readonly>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="cedula1" style="font-weight: bold;">Cédula</label>
                                <input type="text" id="cedula1" name="cedula1" class="form-control" readonly>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="diasNoLaborados1" style="font-weight: bold;">No laborados</label>
                                <input type="text" id="diasNoLaborados1" name="diasNoLaborados1" class="form-control" readonly>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="horasExtras1" style="font-weight: bold;">Horas Extras</label>
                                <input type="text" id="horasExtras1" name="horasExtras1" class="form-control" readonly>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="feriados1" style="font-weight: bold;">Feriados</label>
                                <input type="text" id="feriados1" name="feriados1" class="form-control" readonly>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="aguinaldos1" style="font-weight: bold;">Aguinaldos</label>
                                <input type="text" id="aguinaldos1" name="aguinaldos1" class="form-control" readonly>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="salario1" style="font-weight: bold;">Salario</label>
                                <input type="text" id="salario1" name="salario1" class="form-control" readonly>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="ccss1" style="font-weight: bold;">Ccss</label>
                                <input type="text" id="ccss1" name="ccss1" class="form-control" readonly>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="isr1" style="font-weight: bold;">Isr</label>
                                <input type="text" id="isr1" name="isr1" class="form-control" readonly>
                            </div>
                            <div class="col-md-12 form-group form-check">
                                <label for="total1" style="font-weight: bold;">Total</label>
                                <input type="text" id="total1" name="total1" class="form-control" readonly>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
<!----------------->

<script type="text/javascript">
    $(document).ready(function() {
        //filtrar1();
    });

    function paginacion1(value){
        $('#datatables').attr({'data-pagina':value})
        filtrar1();
    }
    
    function filtrar1() {
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
            url: 'script/planillas.php',
            dataType: "JSON",
            data: {
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

    function cambioEstatus(id,estatus){
        $.ajax({
            type: 'POST',
            url: 'script/usuario.php',
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

    function eliminar(fecha){
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
            fechafiltro = $('#fechafiltro').val();
            $.ajax({
                type: 'POST',
                url: 'script/planillas.php',
                dataType: "JSON",
                data: {
                    "fecha": fecha,
                    "asunto": "eliminar",
                },

                success: function(respuesta) {
                    console.log(respuesta);
                    consultar(fechafiltro);
                    filtrar1();
                },

                error: function(respuesta) {
                    console.log(respuesta['responseText']);
                }
            });
          }
        })
    }

    function aceptar(fecha){
        Swal.fire({
          title: 'Estas seguro?',
          text: "Esta acción no podra revertirse",
          icon: 'warning',
          showConfirmButton: true,
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, aceptar los pagos!',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.value) {
            fechafiltro = $('#fechafiltro').val();
            $.ajax({
                type: 'POST',
                url: 'script/planillas.php',
                dataType: "JSON",
                data: {
                    "fecha": fecha,
                    "asunto": "aceptar",
                },

                success: function(respuesta) {
                    console.log(respuesta);
                    consultar(fechafiltro);
                },

                error: function(respuesta) {
                    console.log(respuesta['responseText']);
                }
            });
          }
        })
    }

    function consultar(value){
        $.ajax({
            type: 'POST',
            url: 'script/planillas.php',
            dataType: "JSON",
            data: {
                "value": value,
                "asunto": "consultar",
            },

            success: function(respuesta) {
                console.log(respuesta);
                filtrar1();
                $('#buttonAction').html(respuesta['html']);
            },

            error: function(respuesta) {
                console.log(respuesta['responseText']);
            }
        });
    }

    function generar(){
        var fecha = $('#fechafiltro').val();
        $.ajax({
            type: 'POST',
            url: 'script/planillas.php',
            dataType: "JSON",
            data: {
                "fecha": fecha,
                "asunto": "generar",
            },

            success: function(respuesta) {
                console.log(respuesta);
                if(respuesta["estatus"]=="ok"){
                    consultar(fecha);
                }else{
                    Swal.fire({
                        title: 'Info',
                        text: respuesta["msg"],
                        icon: 'info',
                        position: 'center',
                        timer: 5000
                    });
                }
                //$('#buttonAction').html(respuesta['html']);
            },

            error: function(respuesta) {
                console.log(respuesta['responseText']);
            }
        });
    }

    function detalle(id){
        $.ajax({
            type: 'POST',
            url: 'script/planillas.php',
            dataType: "JSON",
            data: {
                "id": id,
                "asunto": "detalle",
            },

            success: function(respuesta) {
                console.log(respuesta);
                $('#nombre1').val(respuesta['nombre']);
                $('#apellido1').val(respuesta['apellido']);
                $('#cedula1').val(respuesta['cedula']);
                $('#diasNoLaborados1').val(respuesta['diasNoLaborados']);
                $('#horasExtras1').val(respuesta['horasExtras']);
                $('#feriados1').val(respuesta['diasFeriadosLaborados']);
                $('#aguinaldos1').val(respuesta['aguinaldos']);
                $('#salario1').val(respuesta['salario']);
                $('#ccss1').val(respuesta['ccss']);
                $('#isr1').val(respuesta['isr']);
                $('#total1').val(respuesta['total']);
            },

            error: function(respuesta) {
                console.log(respuesta['responseText']);
            }
        });
    }

</script>
