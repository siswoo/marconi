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
                        <h1 class="h3 mb-0 text-gray-800">Módulo de Reportes</h1>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Listado de Reportes</h6>
                                </div>
                                <form method="POST" action="script/reportes.php" target="_blank">
                                    <div class="row ml-3 mr-3" style="margin-top: 2rem;">
                                        <input type="hidden" name="datatables" id="datatables" data-pagina="1" data-consultasporpagina="10" data-filtrado="" data-rol="" data-sucursal="">
                                        <div class="col-xl-4 col-lg-12 form-group form-check">
                                            <label for="buscarfiltro" style="color:black; font-weight: bold;">Tipo</label>
                                            <select class="form-control" name="tipo" id="tipo" onchange="cambio(value);" required>
                                                <option value="">Seleccione</option>
                                                <option value="Usuarios">Usuarios</option>
                                                <option value="Turnos">Turnos</option>
                                                <option value="Permisos">Permisos</option>
                                                <option value="Incapacidades">Incapacidades</option>
                                                <option value="Vacaciones">Vacaciones</option>
                                                <option value="Liquidaciones">Liquidaciones</option>
                                                <option value="Planillas">Planillas</option>
                                                <option value="Aguinaldos">Aguinaldos</option>
                                            </select>
                                        </div>
                                        <div class="col-xl-4 col-lg-12 form-group form-check" id="anio_div" style="display: none;">
                                            <label for="buscarfiltro" style="color:black; font-weight: bold;">Año</label>
                                            <select class="form-control" name="anio" id="anio" required>
                                                <option value="2025">2025</option>
                                                <option value="2026">2026</option>
                                                <option value="2027">2027</option>
                                                <option value="2028">2028</option>
                                            </select>
                                        </div>
                                        <div class="col-xl-3 col-lg-12 text-center">
                                            <br>
                                            <button type="submit" class="btn btn-success">Generar</button>
                                        </div>
                                        <div class="col-12" style="background-color: white; border-radius: 1rem; padding: 20px 1px 1px 1px;" id="resultado_table1"></div>
                                    </div>
                                </form>

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
                            <div class="col-md-6 form-group form-check">
                                <label for="nombre1" style="font-weight: bold;">Nombre *</label>
                                <input type="text" id="nombre1" name="nombre1" class="form-control" oninput="soloLetras(this)" autocomplete="off" required>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="apellido1" style="font-weight: bold;">Apellido *</label>
                                <input type="text" id="apellido1" name="apellido1" class="form-control" oninput="soloLetras(this)" autocomplete="off" required>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="cedula1" style="font-weight: bold;">Cédula *</label>
                                <input type="text" id="cedula1" name="cedula1" class="form-control" oninput="soloNumeros(this)" autocomplete="off" required>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="fechaNacimiento1" style="font-weight: bold;">Fecha Nacimiento *</label>
                                <input type="date" id="fechaNacimiento1" name="fechaNacimiento1" class="form-control" autocomplete="off">
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="genero1" style="font-weight: bold;">Género *</label>
                                <select class="form-control" name="genero1" id="genero1" required>
                                    <option>Seleccione</option>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                </select>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="telefono1" style="font-weight: bold;">Teléfono *</label>
                                <input type="number" id="telefono1" name="telefono1" oninput="soloNumeros(this)" class="form-control" autocomplete="off" required>
                            </div>
                            <div class="col-md-12 form-group form-check">
                                <label for="correo1" style="font-weight: bold;">Correo *</label>
                                <input type="email" id="correo1" name="correo1" class="form-control" autocomplete="off">
                            </div>
                            <div class="col-md-12 form-group form-check">
                                <label for="direccion1" style="font-weight: bold;">Dirección</label>
                                <textarea id="direccion1" name="direccion1" class="form-control" autocomplete="off"></textarea>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="fechaIngreso1" style="font-weight: bold;">Fecha Ingreso</label>
                                <input type="date" id="fechaIngreso1" name="fechaIngreso1" class="form-control" autocomplete="off">
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="salario1" style="font-weight: bold;">Salario *</label>
                                <input type="number" id="salario1" name="salario1" min="0" oninput="soloNumeros(this)" class="form-control" autocomplete="off" required>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="password1" style="font-weight: bold;">Contraseña *</label>
                                <input type="password" id="password1" name="password1" class="form-control" autocomplete="off" required>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="cargo1" style="font-weight: bold;">Cargo *</label>
                                <select class="form-control" name="cargo1" id="cargo1" required>
                                    <option>Seleccione</option>
                                    <option value="Cargo1">Cargo1</option>
                                    <option value="Cargo2">Cargo2</option>
                                    <option value="Cargo3">Cargo3</option>
                                </select>
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

    function cambio(value){
        var tipo = $('#tipo').val();
        if(tipo=="Aguinaldos"){
            $('#anio_div').show();
        }else{
            $('#anio_div').hide();
        }
    }

</script>
