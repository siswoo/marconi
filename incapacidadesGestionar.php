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
                        <h1 class="h3 mb-0 text-gray-800">Gestionar Incapacidades</h1>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Listado de Incapacidades</h6>
                                        <div class="dropdown no-arrow">
                                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                                                <a class="dropdown-item" data-toggle="modal" <?php if($esAdmin==true){ echo 'onclick="listadoUsuarios(1)" data-target="#crear"';}else{ echo ' data-target="#crear2"'; } ?> href="#">Crear Nuevo</a>
                                            </div>
                                        </div>
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

<!-- Modal Crear -->
    <div class="modal fade" id="crear" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="#" method="POST" id="formulario1" style="">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Crear Registro</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 form-group form-check">
                                <label for="usuario1" style="font-weight: bold;">Usuario *</label>
                                <select class="form-control" name="usuario1" id="usuario1" required></select>
                            </div>
                            <div class="col-md-12 form-group form-check">
                                <label for="fechaInicio1" style="font-weight: bold;">Fecha Inicio *</label>
                                <input type="date" id="fechaInicio1" name="fechaInicio1" class="form-control" onchange="confirmarNoDomingos(value,'fechaInicio1');" required>
                            </div>
                            <div class="col-md-12 form-group form-check">
                                <label for="fechaFin1" style="font-weight: bold;">Fecha Fin *</label>
                                <input type="date" id="fechaFin1" name="fechaFin1" class="form-control" required>
                            </div>
                            <div class="col-md-12 form-group form-check">
                                <label for="observacion1" style="font-weight: bold;">Observación *</label>
                                <textarea class="form-control" name="observacion1" id="observacion1" required></textarea>
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

<!-- Modal Crear2 -->
    <div class="modal fade" id="crear2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="#" method="POST" id="formulario2" style="">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Crear Registro</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 form-group form-check">
                                <label for="fechaInicio2" style="font-weight: bold;">Fecha Inicio *</label>
                                <input type="date" id="fechaInicio2" name="fechaInicio2" class="form-control" onchange="confirmarNoDomingos(value,'fechaInicio1');"required>
                            </div>
                            <div class="col-md-12 form-group form-check">
                                <label for="fechaFin2" style="font-weight: bold;">Fecha Fin *</label>
                                <input type="date" id="fechaFin2" name="fechaFin2" class="form-control" required>
                            </div>
                            <div class="col-md-12 form-group form-check">
                                <label for="observacion2" style="font-weight: bold;">Observación *</label>
                                <textarea class="form-control" name="observacion2" id="observacion2" required></textarea>
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
        filtrar1();
        setearInputDate('fechaInicio1');
        setearInputDate('fechaInicio2');
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
            url: 'script/incapacidades.php',
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
        var usuario = $('#usuario1').val();
        var desde = $('#fechaInicio1').val();
        var hasta = $('#fechaFin1').val();
        var observacion = $('#observacion1').val();
        $.ajax({
            type: 'POST',
            url: 'script/incapacidades.php',
            dataType: "JSON",
            data: {
                "usuario": usuario,
                "desde": desde,
                "hasta": hasta,
                "observacion": observacion,
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
                    $('#usuario1').val("");
                    $('#fechaInicio1').val("");
                    $('#fechaFin1').val("");
                    $('#observacion1').val("");
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

    $("#formulario2").on("submit", function(e){
        e.preventDefault();
        var usuario = $('#hiddenId').val();
        var desde = $('#fechaInicio2').val();
        var hasta = $('#fechaFin2').val();
        var observacion = $('#observacion2').val();
        $.ajax({
            type: 'POST',
            url: 'script/incapacidades.php',
            dataType: "JSON",
            data: {
                "usuario": usuario,
                "desde": desde,
                "hasta": hasta,
                "observacion": observacion,
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
                    $('#usuario2').val("");
                    $('#fechaInicio2').val("");
                    $('#fechaFin2').val("");
                    $('#observacion2').val("");
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
        Swal.fire({
          title: 'Estas seguro?',
          text: "Esta acción no podra revertirse",
          icon: 'warning',
          showConfirmButton: true,
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, cambiar el estatus!',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.value) {
            $.ajax({
                type: 'POST',
                url: 'script/incapacidades.php',
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
        })
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
                url: 'script/incapacidades.php',
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

    function setearInputDate(id){
        let fechaInput = document.getElementById(id);
        let hoy = new Date();
        let semanaAtras = new Date();
        semanaAtras.setDate(hoy.getDate() - 7);
        fechaInput.min = formatDate(semanaAtras);
        fechaInput.max = formatDate(hoy);
        fechaInput.value = formatDate(hoy);
    }

    function formatDate(fecha) {
        let año = fecha.getFullYear();
        let mes = String(fecha.getMonth() + 1).padStart(2, '0');
        let dia = String(fecha.getDate()).padStart(2, '0');
        return `${año}-${mes}-${dia}`;
    }

    function confirmarNoDomingos(value,id){
        let hoy = new Date();
        let seleccionada = new Date(value);
        if (seleccionada.getDay() === 6) {
            $('#'+id).val(formatDate(hoy));
        }
    }

</script>
