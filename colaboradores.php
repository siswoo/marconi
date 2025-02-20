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
                        <h1 class="h3 mb-0 text-gray-800">Módulo de Colaboradores</h1>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Listado de Colaboradores</h6>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                                            <a class="dropdown-item" data-toggle="modal" data-target="#crear" href="#">Crear Nuevo</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="row ml-3 mr-3" style="margin-top: 2rem;">
                                    <input type="hidden" name="datatables" id="datatables" data-pagina="1" data-consultasporpagina="10" data-filtrado="" data-rol="" data-sucursal="">
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
                                    <div class="col-xl-6 col-lg-12 form-group form-check">
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
                                <input type="text" id="nombre1" name="nombre1" class="form-control" oninput="soloLetras(this); limpiarEspacios(this)" autocomplete="off" required>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="apellido1" style="font-weight: bold;">Apellido *</label>
                                <input type="text" id="apellido1" name="apellido1" class="form-control" oninput="soloLetras(this); limpiarEspacios(this)" autocomplete="off" required>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="apellido1_2" style="font-weight: bold;">Segundo Apellido *</label>
                                <input type="text" id="apellido1_2" name="apellido1_2" class="form-control" oninput="soloLetras(this); limpiarEspacios(this)" autocomplete="off" required>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="cedula1" style="font-weight: bold;">Cédula *</label>
                                <input type="text" id="cedula1" name="cedula1" class="form-control" oninput="soloNumeros(this)" autocomplete="off" required>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="fechaNacimiento1" style="font-weight: bold;">Fecha Nacimiento *</label>
                                <input type="date" id="fechaNacimiento1" name="fechaNacimiento1" class="form-control" autocomplete="off" required>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="genero1" style="font-weight: bold;">Género *</label>
                                <select class="form-control" name="genero1" id="genero1" required>
                                    <option value="">Seleccione</option>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                </select>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="telefono1" style="font-weight: bold;">Teléfono *</label>
                                <input type="number" id="telefono1" name="telefono1" oninput="soloNumeros(this)" class="form-control" autocomplete="off" required>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="correo1" style="font-weight: bold;">Correo *</label>
                                <input type="email" id="correo1" name="correo1" class="form-control" autocomplete="off" required>
                            </div>
                            <div class="col-md-12 form-group form-check">
                                <label for="direccion1" style="font-weight: bold;">Dirección *</label>
                                <textarea id="direccion1" name="direccion1" class="form-control" autocomplete="off" required></textarea>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="fechaIngreso1" style="font-weight: bold;">Fecha Ingreso *</label>
                                <input type="date" id="fechaIngreso1" name="fechaIngreso1" class="form-control" autocomplete="off" required>
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
                                    <option value="">Seleccione</option>
                                    <option value="Cargo1">Cargo1</option>
                                    <option value="Cargo2">Cargo2</option>
                                    <option value="Cargo3">Cargo3</option>
                                </select>
                            </div>
                            <div class="col-md-12 form-group form-check">
                                <label for="horarios1" style="font-weight: bold;">Horarios *</label>
                                <select class="form-control" name="horarios1" id="horarios1" required>
                                    <option value="">Seleccione</option>
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

<!-- Modal Modificar -->
    <div class="modal fade" id="modificar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="#" method="POST" id="formulario2" style="">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modificar Registro</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 form-group form-check">
                                <label for="nombre2" style="font-weight: bold;">Nombre *</label>
                                <input type="text" id="nombre2" name="nombre2" class="form-control" oninput="soloLetras(this); limpiarEspacios(this)" autocomplete="off" required>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="apellido2" style="font-weight: bold;">Apellido *</label>
                                <input type="text" id="apellido2" name="apellido2" class="form-control" oninput="soloLetras(this); limpiarEspacios(this)" autocomplete="off" required>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="apellido2_2" style="font-weight: bold;">Segundo Apellido *</label>
                                <input type="text" id="apellido2_2" name="apellido2_2" class="form-control" oninput="soloLetras(this); limpiarEspacios(this)" autocomplete="off" required>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="cedula2" style="font-weight: bold;">Cédula *</label>
                                <input type="text" id="cedula2" name="cedula2" class="form-control" oninput="soloNumeros(this)" autocomplete="off" required>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="fechaNacimiento2" style="font-weight: bold;">Fecha Nacimiento *</label>
                                <input type="date" id="fechaNacimiento2" name="fechaNacimiento2" class="form-control" autocomplete="off">
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="genero2" style="font-weight: bold;">Género *</label>
                                <select class="form-control" name="genero2" id="genero2" required>
                                    <option value="">Seleccione</option>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                </select>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="telefono2" style="font-weight: bold;">Teléfono *</label>
                                <input type="number" id="telefono2" name="telefono2" oninput="soloNumeros(this)" class="form-control" autocomplete="off" required>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="correo2" style="font-weight: bold;">Correo *</label>
                                <input type="email" id="correo2" name="correo2" class="form-control" autocomplete="off" required>
                            </div>
                            <div class="col-md-12 form-group form-check">
                                <label for="direccion2" style="font-weight: bold;">Dirección *</label>
                                <textarea id="direccion2" name="direccion2" class="form-control" autocomplete="off" required></textarea>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="fechaIngreso2" style="font-weight: bold;">Fecha Ingreso *</label>
                                <input type="date" id="fechaIngreso2" name="fechaIngreso2" class="form-control" autocomplete="off" required>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="fechaRetiro2" style="font-weight: bold;">Fecha Retiro</label>
                                <input type="date" id="fechaRetiro2" name="fechaRetiro2" class="form-control" readonly>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="salario2" style="font-weight: bold;">Salario *</label>
                                <input type="number" id="salario2" name="salario2" min="0" oninput="soloNumeros(this)" class="form-control" autocomplete="off" required>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="cargo2" style="font-weight: bold;">Cargo *</label>
                                <select class="form-control" name="cargo2" id="cargo2" required>
                                    <option value="">Seleccione</option>
                                    <option value="Cargo1">Cargo1</option>
                                    <option value="Cargo2">Cargo2</option>
                                    <option value="Cargo3">Cargo3</option>
                                </select>
                            </div>
                            <div class="col-md-12 form-group form-check">
                                <label for="password2" style="font-weight: bold;">Contraseña</label>
                                <input type="password" id="password2" name="password2" class="form-control" autocomplete="off" >
                            </div>
                            <div class="col-md-12 form-group form-check">
                                <label for="horarios2" style="font-weight: bold;">Horarios *</label>
                                <select class="form-control" name="horarios2" id="horarios2" required>
                                    <option value="">Seleccione</option>
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
    $(document).ready(function() {
        filtrar1();
        iniciarHorarios();
    });

    function paginacion1(value){
        $('#datatables').attr({'data-pagina':value})
        filtrar1();
    }
    
    function filtrar1() {
        var input_consultasporpagina = $('#consultasporpagina').val();
        var input_buscarfiltro = $('#buscarfiltro').val();

        $('#datatables').attr({
            'data-consultasporpagina': input_consultasporpagina
        })
        $('#datatables').attr({
            'data-filtrado': input_buscarfiltro
        })

        var pagina = $('#datatables').attr('data-pagina');
        var consultasporpagina = $('#datatables').attr('data-consultasporpagina');
        var filtrado = $('#datatables').attr('data-filtrado');
        var ubicacion_url = '<?php echo $ubicacion_url; ?>';

        $.ajax({
            type: 'POST',
            url: 'script/usuario.php',
            dataType: "JSON",
            data: {
                "pagina": pagina,
                "consultasporpagina": consultasporpagina,
                "filtrado": filtrado,
                "link1": ubicacion_url,
                "rol": 2,
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

    function modificar(id){
        $.ajax({
            type: 'POST',
            url: 'script/usuario.php',
            dataType: "JSON",
            data: {
                "id": id,
                "asunto": "modificar",
            },

            success: function(respuesta) {
                console.log(respuesta);
                $('#hiddenId').val(id);
                $('#nombre2').val(respuesta["nombre"]);
                $('#apellido2').val(respuesta["apellido"]);
                $('#apellido2_2').val(respuesta["apellido2"]);
                $('#cedula2').val(respuesta["cedula"]);
                $('#fechaNacimiento2').val(respuesta["fechaNacimiento"]);
                $('#genero2').val(respuesta["genero"]);
                $('#telefono2').val(respuesta["telefono"]);
                $('#correo2').val(respuesta["correo"]);
                $('#direccion2').val(respuesta["direccion"]);
                $('#fechaIngreso2').val(respuesta["fechaIngreso"]);
                $('#fechaRetiro2').val(respuesta["fechaRetiro"]);
                $('#salario2').val(respuesta["salario"]);
                $('#password2').val("");
                $('#cargo2').val(respuesta["cargo"]);
                $('#horarios2').val(respuesta["horarios"]);
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

    $("#formulario1").on("submit", function(e){
        e.preventDefault();
        var nombre = $('#nombre1').val();
        var apellido = $('#apellido1').val();
        var apellido1_2 = $('#apellido1_2').val();
        var cedula = $('#cedula1').val();
        var fechaNacimiento = $('#fechaNacimiento1').val();
        var genero = $('#genero1').val();
        var telefono = $('#telefono1').val();
        var correo = $('#correo1').val();
        var direccion = $('#direccion1').val();
        var fechaIngreso = $('#fechaIngreso1').val();
        var salario = $('#salario1').val();
        var password = $('#password1').val();
        var cargo = $('#cargo1').val();
        var horarios = $('#horarios1').val();
        var validarTelefono = primerNumero(telefono);
        if(validarTelefono>=2 && validarTelefono!=3 && validarTelefono<=8){}else{
            Swal.fire({
                title: 'Info',
                text: "Teléfono debe iniciar con 2, 4, 5, 6, 7 u 8",
                icon: 'info',
                position: 'center',
                timer: 5000
            });
            return false;
        }
        if(salario<=0){
            Swal.fire({
                title: 'Info',
                text: "Salario debe ser mayor a cero",
                icon: 'info',
                position: 'center',
                timer: 5000
            });
            return false;
        }
        if(cedula.length < 9 || cedula.length > 12){
            Swal.fire({
                title: 'Info',
                text: "Cédula debe tener entre 9 a 12 caracteres",
                icon: 'info',
                position: 'center',
                timer: 5000
            });
            return false;
        }
        $.ajax({
            type: 'POST',
            url: 'script/usuario.php',
            dataType: "JSON",
            data: {
                "nombre": nombre,
                "apellido": apellido,
                "apellido1_2": apellido1_2,
                "cedula": cedula,
                "fechaNacimiento": fechaNacimiento,
                "genero": genero,
                "telefono": telefono,
                "correo": correo,
                "direccion": direccion,
                "fechaIngreso": fechaIngreso,
                "salario": salario,
                "password": password,
                "cargo": cargo,
                "horarios": horarios,
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
                    $('#nombre1').val("");
                    $('#apellido1').val("");
                    $('#apellido1_2').val("");
                    $('#cedula1').val("");
                    $('#fechaNacimiento1').val("");
                    $('#genero1').val("");
                    $('#telefono1').val("");
                    $('#correo1').val("");
                    $('#direccion1').val("");
                    $('#fechaIngreso1').val("");
                    $('#salario1').val("");
                    $('#password1').val("");
                    $('#cargo1').val("");
                    $('#horarios1').val("");
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
        var id = $('#hiddenId').val();
        var nombre = $('#nombre2').val();
        var apellido = $('#apellido2').val();
        var apellido2_2 = $('#apellido2_2').val();
        var cedula = $('#cedula2').val();
        var fechaNacimiento = $('#fechaNacimiento2').val();
        var genero = $('#genero2').val();
        var telefono = $('#telefono2').val();
        var correo = $('#correo2').val();
        var direccion = $('#direccion2').val();
        var fechaIngreso = $('#fechaIngreso2').val();
        var salario = $('#salario2').val();
        var password = $('#password2').val();
        var cargo = $('#cargo2').val();
        var horarios = $('#horarios2').val();
        var validarTelefono = primerNumero(telefono);
        if(validarTelefono>=2 && validarTelefono!=3 && validarTelefono<=8){}else{
            Swal.fire({
                title: 'Info',
                text: "Teléfono debe iniciar con 2, 4, 5, 6, 7 u 8",
                icon: 'info',
                position: 'center',
                timer: 5000
            });
            return false;
        }
        if(salario<=0){
            Swal.fire({
                title: 'Info',
                text: "Salario debe ser mayor a cero",
                icon: 'info',
                position: 'center',
                timer: 5000
            });
            return false;
        }
        if(cedula.length < 9 || cedula.length > 12){
            Swal.fire({
                title: 'Info',
                text: "Cédula debe tener entre 9 a 12 caracteres",
                icon: 'info',
                position: 'center',
                timer: 5000
            });
            return false;
        }
        $.ajax({
            type: 'POST',
            url: 'script/usuario.php',
            dataType: "JSON",
            data: {
                "id": id,
                "nombre": nombre,
                "apellido": apellido,
                "apellido2_2": apellido2_2,
                "cedula": cedula,
                "fechaNacimiento": fechaNacimiento,
                "genero": genero,
                "telefono": telefono,
                "correo": correo,
                "direccion": direccion,
                "fechaIngreso": fechaIngreso,
                "salario": salario,
                "password": password,
                "cargo": cargo,
                "horarios": horarios,
                "asunto": "modificar_guardar",
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

    function soloLetras(input) {
        input.value = input.value.replace(/[^A-Za-z\s]/g, '');
    }

    function sinCaracteresEspeciales(input) {
        input.value = input.value.replace(/[^A-Za-z0-9\s]/g, '');
    }

    function sinNumeros(input) {
        input.value = input.value.replace(/[0-9]/g, '');
    }

    function sinEspacios(input) {
        input.value = input.value.replace(/\s/g, '');
    }

    function soloNumeros(input) {
        input.value = input.value.replace(/[^0-9]/g, '');
    }

    function primerNumero(cadena) {
        let match = cadena.match(/\d/);
        return match ? match[0] : null;
    }

    function limpiarEspacios(input) {
        input.value = input.value.replace(/^\s+|\s+$/g, '').replace(/\s{2,}/g, ' ');
    }

    function iniciarHorarios(){
        $.ajax({
            type: 'POST',
            url: 'script/horarios.php',
            dataType: "JSON",
            data: {
                "asunto": "listar",
            },

            success: function(respuesta) {
                console.log(respuesta);
                $('#horarios1').html(respuesta["options"]);
                $('#horarios2').html(respuesta["options"]);
            },

            error: function(respuesta) {
                console.log(respuesta['responseText']);
            }
        });
    }


</script>
