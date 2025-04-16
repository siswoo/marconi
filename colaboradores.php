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
        <?php 
        include("menu.php");
        $sql1 = "SELECT * FROM cargos";
        $sql2 = "SELECT * FROM cargos";
        ?>
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
                                <label for="cedula1" style="font-weight: bold;">Cédula *</label>
                                <input type="text" id="cedula1" name="cedula1" class="form-control" oninput="soloNumeros(this)" autocomplete="off" required>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="nombre1" style="font-weight: bold;">Nombre *</label>
                                <input type="text" id="nombre1" name="nombre1" class="form-control" oninput="soloLetras(this); limpiarEspacios(this)" autocomplete="off" required>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="apellido1" style="font-weight: bold;">Primer Apellido *</label>
                                <input type="text" id="apellido1" name="apellido1" class="form-control" oninput="soloLetras(this); limpiarEspacios(this)" autocomplete="off" required>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="apellido1_2" style="font-weight: bold;">Segundo Apellido *</label>
                                <input type="text" id="apellido1_2" name="apellido1_2" class="form-control" oninput="soloLetras(this); limpiarEspacios(this)" autocomplete="off" required>
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
                                <label for="civil1" style="font-weight: bold;">Estado civil *</label>
                                <select class="form-control" name="civil1" id="civil1" required>
                                    <option value="">Seleccione</option>
                                    <option value="Soltero/a">Soltero/a</option>
                                    <option value="Casado/a">Casado/a</option>
                                </select>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="hijos1" style="font-weight: bold;">¿Tiene hijos? *</label>
                                <select class="form-control" name="hijos1" id="hijos1" required>
                                    <option value="">Seleccione</option>
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                </select>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="telefono1" style="font-weight: bold;">Teléfono *</label>
                                <input type="number" id="telefono1" name="telefono1" oninput="soloNumeros(this)" class="form-control" autocomplete="off" required>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="correo1" style="font-weight: bold;">Correo *</label>
                                <input type="email" id="correo1" name="correo1" class="form-control" oninput="espaciosBlancosInicioFinal(this); limpiarEspacios(this)" autocomplete="off" required>
                            </div>
                            <div class="col-md-12 form-group form-check">
                                <label for="provincia1" style="font-weight: bold;">Provincia *</label>
                                <select id="provincia1" name="provincia1" class="form-control" onchange="provincias(value,1);" autocomplete="off" required></select>
                            </div>
                            <div class="col-md-12 form-group form-check">
                                <label for="canton1" style="font-weight: bold;">Canton *</label>
                                <select id="canton1" name="canton1" class="form-control" onchange="cantones(value,1);" disabled autocomplete="off" required></select>
                            </div>
                            <div class="col-md-12 form-group form-check">
                                <label for="distrito1" style="font-weight: bold;">Distrito *</label>
                                <select id="distrito1" name="distrito1" class="form-control" disabled autocomplete="off" required></select>
                            </div>
                            <div class="col-md-12 form-group form-check">
                                <label for="direccion1" style="font-weight: bold;">Dirección exacta *</label>
                                <input type="text" id="direccion1" name="direccion1" class="form-control" oninput="limpiarEspacios(this)" autocomplete="off" required>
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
                                <input type="password" id="password1" name="password1" class="form-control" minlength="4" autocomplete="off" required>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="Cpassword1" style="font-weight: bold;">Confirmar contraseña *</label>
                                <input type="password" id="Cpassword1" name="Cpassword1" class="form-control" minlength="4" autocomplete="off" required>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="cargo1" style="font-weight: bold;">Cargo *</label>
                                <select class="form-control" name="cargo1" id="cargo1" required>
                                    <option value="">Seleccione</option>
                                    <?php
                                    $proceso1 = mysqli_query($conexion,$sql1);
                                    while($row1=mysqli_fetch_array($proceso1)){
                                        $cargoId = $row1["id"];
                                        $cargoNombre = $row1["nombre"];
                                        echo '<option value="'.$cargoId.'">'.$cargoNombre.'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6 form-group form-check">
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
                                <label for="cedula2" style="font-weight: bold;">Cédula *</label>
                                <input type="text" id="cedula2" name="cedula2" class="form-control" disabled oninput="soloNumeros(this)" autocomplete="off" required>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="nombre2" style="font-weight: bold;">Nombre *</label>
                                <input type="text" id="nombre2" name="nombre2" class="form-control" oninput="soloLetras(this); limpiarEspacios(this)" autocomplete="off" required>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="apellido2" style="font-weight: bold;">Primer Apellido *</label>
                                <input type="text" id="apellido2" name="apellido2" class="form-control" oninput="soloLetras(this); limpiarEspacios(this)" autocomplete="off" required>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="apellido2_2" style="font-weight: bold;">Segundo Apellido *</label>
                                <input type="text" id="apellido2_2" name="apellido2_2" class="form-control" oninput="soloLetras(this); limpiarEspacios(this)" autocomplete="off" required>
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
                                <label for="civil2" style="font-weight: bold;">Estado civil *</label>
                                <select class="form-control" name="civil2" id="civil2" required>
                                    <option value="">Seleccione</option>
                                    <option value="Soltero/a">Soltero/a</option>
                                    <option value="Casado/a">Casado/a</option>
                                </select>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="hijos2" style="font-weight: bold;">¿Tiene hijos? *</label>
                                <select class="form-control" name="hijos2" id="hijos2" required>
                                    <option value="">Seleccione</option>
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                </select>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="telefono2" style="font-weight: bold;">Teléfono *</label>
                                <input type="number" id="telefono2" name="telefono2" oninput="soloNumeros(this)" class="form-control" autocomplete="off" required>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="correo2" style="font-weight: bold;">Correo *</label>
                                <input type="email" id="correo2" name="correo2" class="form-control" oninput="espaciosBlancosInicioFinal(this); limpiarEspacios(this)" autocomplete="off" required>
                            </div>
                            <div class="col-md-12 form-group form-check">
                                <label for="provincia2" style="font-weight: bold;">Provincia *</label>
                                <select id="provincia2" name="provincia2" class="form-control" onchange="provincias(value,2);" autocomplete="off" required></select>
                            </div>
                            <div class="col-md-12 form-group form-check">
                                <label for="canton2" style="font-weight: bold;">Canton *</label>
                                <select id="canton2" name="canton2" class="form-control" onchange="cantones(value,2);" autocomplete="off" required></select>
                            </div>
                            <div class="col-md-12 form-group form-check">
                                <label for="distrito2" style="font-weight: bold;">Distrito *</label>
                                <select id="distrito2" name="distrito2" class="form-control" autocomplete="off" required></select>
                            </div>
                            <div class="col-md-12 form-group form-check">
                                <label for="direccion2" style="font-weight: bold;">Dirección exacta *</label>
                                <input type="text" id="direccion2" name="direccion2" class="form-control" oninput="limpiarEspacios(this)" autocomplete="off" required>
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
                                    <?php
                                    $proceso2 = mysqli_query($conexion,$sql2);
                                    while($row2=mysqli_fetch_array($proceso2)){
                                        $cargoId = $row2["id"];
                                        $cargoNombre = $row2["nombre"];
                                        echo '<option value="'.$cargoId.'">'.$cargoNombre.'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="password2" style="font-weight: bold;">Contraseña</label>
                                <input type="password" id="password2" name="password2" class="form-control" minlength="4" autocomplete="off" >
                            </div>
                            <div class="col-md-6 form-group form-check">
                                <label for="Cpassword2" style="font-weight: bold;">Confirmar contraseña</label>
                                <input type="password" id="Cpassword2" name="Cpassword2" class="form-control" minlength="4" autocomplete="off" >
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
        cargarProvincias('provincia1',1);
        cargarProvincias('provincia2',2);
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
                $('#civil2').val(respuesta["civil"]);
                $('#hijos2').val(respuesta["hijos"]);
                $('#telefono2').val(respuesta["telefono"]);
                $('#correo2').val(respuesta["correo"]);
                $('#provincia2').val(respuesta["provincia"]);
                provincias(respuesta["provincia"],2);
                $('#canton2').val(respuesta["canton"]);
                cantones(respuesta["canton"],2);
                $('#distrito2').val(respuesta["distrito"]);
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
        var civil = $('#civil1').val();
        var hijos = $('#hijos1').val();
        var telefono = $('#telefono1').val();
        var correo = $('#correo1').val();
        var provincia = $('#provincia1').val();
        var canton = $('#canton1').val();
        var distrito = $('#distrito1').val();
        var direccion = $('#direccion1').val();
        var fechaIngreso = $('#fechaIngreso1').val();
        var salario = $('#salario1').val();
        var password = $('#password1').val();
        var Cpassword = $('#Cpassword1').val();
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
        if(password!=Cpassword){
            Swal.fire({
                title: 'Info',
                text: "Las contraseña debe coincidir",
                icon: 'info',
                position: 'center',
                timer: 5000
            });
            return false;
        }
        if(telefono.length!=8){
            Swal.fire({
                title: 'Info',
                text: "El teléfono debe contener 8 caracteres",
                icon: 'info',
                position: 'center',
                timer: 5000
            });
            return false;
        }
        if(nombre.length < 2 || apellido.length < 2 || apellido1_2.length < 2){
            Swal.fire({
                title: 'Info',
                text: "Nombre y apellidos como mínimo 2 caracteres",
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
                "civil": civil,
                "hijos": hijos,
                "telefono": telefono,
                "correo": correo,
                "provincia": provincia,
                "canton": canton,
                "distrito": distrito,
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
                    $('#civil1').val("");
                    $('#hijos1').val("");
                    $('#telefono1').val("");
                    $('#correo1').val("");
                    $('#provincia1').val("");
                    $('#canton1').val("");
                    $('#distrito1').val("");
                    $('#direccion1').val("");
                    $('#fechaIngreso1').val("");
                    $('#salario1').val("");
                    $('#password1').val("");
                    $('#Cpassword1').val("");
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
        var civil = $('#civil2').val();
        var hijos = $('#hijos2').val();
        var telefono = $('#telefono2').val();
        var correo = $('#correo2').val();
        var provincia = $('#provincia2').val();
        var canton = $('#canton2').val();
        var distrito = $('#distrito2').val();
        var direccion = $('#direccion2').val();
        var fechaIngreso = $('#fechaIngreso2').val();
        var salario = $('#salario2').val();
        var password = $('#password2').val();
        var Cpassword = $('#Cpassword2').val();
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
        if(password!=Cpassword && password!=""){
            Swal.fire({
                title: 'Info',
                text: "Las contraseña debe coincidir",
                icon: 'info',
                position: 'center',
                timer: 5000
            });
            return false;
        }
        if(telefono.length!=8){
            Swal.fire({
                title: 'Info',
                text: "El teléfono debe contener 8 caracteres",
                icon: 'info',
                position: 'center',
                timer: 5000
            });
            return false;
        }
        if(nombre.length < 2 || apellido.length < 2 || apellido2_2.length < 2){
            Swal.fire({
                title: 'Info',
                text: "Nombre y apellidos como mínimo 2 caracteres",
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
                "civil": civil,
                "hijos": hijos,
                "telefono": telefono,
                "correo": correo,
                "provincia": provincia,
                "canton": canton,
                "distrito": distrito,
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
                    $('#password2').val("");
                    $('#Cpassword2').val("");
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
        input.value = input.value.replace(/[^A-Za-zñÑ\s]/g, '');
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

    const data = {
        "San José": {
            "San José": ["Carmen", "Merced", "Hospital", "Catedral", "Zapote", "San Francisco de Dos Ríos", "Uruca", "Mata Redonda", "Pavas", "Hatillo", "San Sebastián"],
            "Escazú": ["Escazú", "San Antonio", "San Rafael"],
            "Desamparados": ["Desamparados", "San Miguel", "San Juan de Dios", "San Rafael Arriba", "San Rafael Abajo", "San Antonio", "Frailes", "Patarrá", "San Cristóbal", "Rosario", "Damas", "San Jerónimo", "Gravilias", "Los Guido"],
            "Puriscal": ["Santiago", "Mercedes Sur", "Barbacoas", "Grifo Alto", "San Rafael", "Candelarita", "Desamparaditos", "San Antonio", "Chires"],
            "Tarrazú": ["San Marcos", "San Lorenzo", "San Carlos"],
            "Aserrí": ["Aserrí", "Tarbaca", "Vuelta de Jorco", "San Gabriel", "Legua", "Monterrey", "Salitrillos"],
            "Mora": ["Colón", "Guayabo", "Tabarcia", "Piedras Negras", "Picagres", "Jaris", "Quitirrisí"],
            "Goicoechea": ["Guadalupe", "San Francisco", "Calle Blancos", "Mata de Plátano", "Ipís", "Rancho Redondo", "Purral"],
            "Santa Ana": ["Santa Ana", "Salitral", "Pozos", "Uruca", "Piedades", "Brasil"],
            "Alajuelita": ["Alajuelita", "San Josecito", "San Antonio", "Concepción", "San Felipe"],
            "Vásquez de Coronado": ["San Isidro", "San Rafael", "Dulce Nombre de Jesús", "Patalillo", "Cascajal"],
            "Acosta": ["San Ignacio", "Guaitil", "Palmichal", "Cangrejal", "Sabanillas"],
            "Tibás": ["San Juan", "Cinco Esquinas", "Anselmo Llorente", "León XIII", "Colima"],
            "Moravia": ["San Vicente", "San Jerónimo", "La Trinidad"],
            "Montes de Oca": ["San Pedro", "Sabanilla", "Mercedes", "San Rafael"],
            "Turrubares": ["San Pablo", "San Pedro", "San Juan de Mata", "San Luis", "Carara"],
            "Dota": ["Santa María", "Jardín", "Copey"],
            "Curridabat": ["Curridabat", "Granadilla", "Sánchez", "Tirrases"],
            "Pérez Zeledón": ["San Isidro de El General", "El General", "Daniel Flores", "Rivas", "San Pedro", "Platanares", "Pejibaye", "Cajón", "Barú", "Río Nuevo", "Páramo"],
            "León Cortés Castro": ["San Pablo", "San Andrés", "Llano Bonito", "San Isidro", "Santa Cruz", "San Antonio"]
        },
        "Alajuela": {
            "Alajuela": ["Alajuela", "San José", "Carrizal", "San Antonio", "Guácima", "San Isidro", "Sabanilla", "San Rafael", "Río Segundo", "Desamparados", "Turrúcares", "Tambor", "La Garita", "Sarapiquí"],
            "San Ramón": ["San Ramón", "Santiago", "San Juan", "Piedades Norte", "Piedades Sur", "San Rafael", "San Isidro", "Ángeles", "Alfaro", "Volio", "Concepción", "Zapotal", "Peñas Blancas"],
            "Grecia": ["Grecia", "San Isidro", "San José", "San Roque", "Tacares", "Río Cuarto", "Puente de Piedra", "Bolívar"],
            "San Mateo": ["San Mateo", "Desmonte", "Jesús María", "Labrador"],
            "Atenas": ["Atenas", "Jesús", "Mercedes", "San Isidro", "Concepción", "San José", "Santa Eulalia", "Escobal"],
            "Naranjo": ["Naranjo", "San Miguel", "San José", "Cirrí Sur", "San Jerónimo", "San Juan", "El Rosario", "Palmitos"],
            "Palmares": ["Palmares", "Zaragoza", "Buenos Aires", "Santiago", "Candelaria", "Esquipulas", "La Granja"],
            "Poás": ["San Pedro", "San Juan", "San Rafael", "Carrillos", "Sabana Redonda"],
            "Orotina": ["Orotina", "Mastate", "Hacienda Vieja", "Coyolar", "La Ceiba"],
            "San Carlos": ["Quesada", "Florencia", "Buenavista", "Aguas Zarcas", "Venecia", "Pital", "La Fortuna", "La Tigra", "La Palmera", "Venado", "Cutris", "Monterrey", "Pocosol"],
            "Zarcero": ["Zarcero", "Laguna", "Tapezco", "Guadalupe", "Palmira", "Zapote", "Brisas"],
            "Valverde Vega": ["Sarchí Norte", "Sarchí Sur", "Toro Amarillo", "San Pedro", "Rodríguez"],
            "Upala": ["Upala", "Aguas Claras", "San José (Pizote)", "Bijagua", "Delicias", "Dos Ríos", "Yolillal", "Canalete"],
            "Los Chiles": ["Los Chiles", "Caño Negro", "El Amparo", "San Jorge"],
            "Guatuso": ["San Rafael", "Buenavista", "Cote", "Katira"],
            "Río Cuarto": ["Río Cuarto", "Santa Rita", "Santa Isabel"]
        },
        "Cartago": {
            "Cartago": ["Oriental", "Occidental", "Carmen", "San Nicolás", "Agua Caliente (San Francisco)", "Guadalupe (Arenilla)", "Corralillo", "Tierra Blanca", "Dulce Nombre", "Llano Grande", "Quebradilla"],
            "Paraíso": ["Paraíso", "Santiago", "Orosi", "Cachí", "Llanos de Santa Lucía"],
            "La Unión": ["Tres Ríos", "San Diego", "San Juan", "San Rafael", "Concepción", "Dulce Nombre", "San Ramón", "Río Azul"],
            "Jiménez": ["Juan Viñas", "Tucurrique", "Pejibaye"],
            "Turrialba": ["Turrialba", "La Suiza", "Peralta", "Santa Cruz", "Santa Teresita", "Pavones", "Tuis", "Tayutic", "Santa Rosa", "Tres Equis", "La Isabel", "Chirripó"],
            "Alvarado": ["Pacayas", "Cervantes", "Capellades"],
            "Oreamuno": ["San Rafael", "Cot", "Potrero Cerrado", "Cipreses", "Santa Rosa"],
            "El Guarco": ["El Tejar", "San Isidro", "Tobosi", "Patio de Agua"]
        },
        "Heredia": {
            "Heredia": ["Heredia", "Mercedes", "San Francisco", "Ulloa", "Varablanca"],
            "Barva": ["Barva", "San Pedro", "San Pablo", "San Roque", "Santa Lucía", "San José de la Montaña"],
            "Santo Domingo": ["Santo Domingo", "San Vicente", "San Miguel", "Paracito", "Santo Tomás", "Santa Rosa", "Tures", "Pará"],
            "Santa Bárbara": ["Santa Bárbara", "San Pedro", "San Juan", "Jesús", "Santo Domingo", "Purabá"],
            "San Rafael": ["San Rafael", "San Josecito", "Santiago", "Los Ángeles", "Concepción"],
            "San Isidro": ["San Isidro", "San José", "Concepción", "San Francisco"],
            "Belén": ["San Antonio", "La Ribera", "La Asunción"],
            "Flores": ["San Joaquín", "Barrantes", "Llorente"],
            "San Pablo": ["San Pablo", "Rincón de Sabanilla"],
            "Sarapiquí": ["Puerto Viejo", "La Virgen", "Horquetas", "Llanuras del Gaspar", "Cureña"]
        },
        "Guanacaste": {
            "Liberia": ["Liberia", "Cañas Dulces", "Mayorga", "Nacascolo", "Curubandé"],
            "Nicoya": ["Nicoya", "Mansión", "San Antonio", "Quebrada Honda", "Sámara", "Nosara", "Belén de Nosarita"],
            "Santa Cruz": ["Santa Cruz", "Bolsón", "Veintisiete de Abril", "Tempate", "Cartagena", "Cuajiniquil", "Diriá", "Cabo Velas", "Tamarindo"],
            "Bagaces": ["Bagaces", "La Fortuna", "Mogote", "Río Naranjo"],
            "Carrillo": ["Filadelfia", "Palmira", "Sardinal", "Belén"],
            "Cañas": ["Cañas", "Palmira", "San Miguel", "Bebedero", "Porozal"],
            "Abangares": ["Las Juntas", "Sierra", "San Juan", "Colorado"],
            "Tilarán": ["Tilarán", "Quebrada Grande", "Tronadora", "Santa Rosa", "Líbano", "Tierras Morenas", "Arenal"],
            "Nandayure": ["Carmona", "Santa Rita", "Zapotal", "San Pablo", "Porvenir", "Bejuco"],
            "La Cruz": ["La Cruz", "Santa Cecilia", "La Garita", "Santa Elena"],
            "Hojancha": ["Hojancha", "Monte Romo", "Puerto Carrillo", "Huacas", "Matambú"]
        },
        "Puntarenas": {
            "Puntarenas": ["Puntarenas", "Pitahaya", "Chomes", "Lepanto", "Paquera", "Manzanillo", "Guacimal", "Barranca", "Isla del Coco", "Cóbano", "Chacarita", "Chira", "Acapulco", "El Roble", "Arancibia"],
            "Esparza": ["Espíritu Santo", "San Juan Grande", "Macacona", "San Rafael", "San Jerónimo", "Caldera"],
            "Buenos Aires": ["Buenos Aires", "Volcán", "Potrero Grande", "Boruca", "Pilas", "Colinas", "Chánguena", "Biolley", "Brunka"],
            "Montes de Oro": ["Miramar", "La Unión", "San Isidro"],
            "Osa": ["Puerto Cortés", "Palmar", "Sierpe", "Piedras Blancas", "Bahía Ballena", "Bahía Drake"],
            "Quepos": ["Quepos", "Savegre", "Naranjito"],
            "Golfito": ["Golfito", "Guaycará", "Pavón", "Comte-Burica"],
            "Coto Brus": ["San Vito", "Sabalito", "Aguabuena", "Limoncito", "Pittier", "Gutiérrez Braun"],
            "Parrita": ["Parrita"],
            "Corredores": ["Corredor", "La Cuesta", "Canoas", "Laurel"],
            "Garabito": ["Jacó", "Tárcoles", "Lagunillas"],
            "Monteverde": ["Santa Elena"],
            "Puerto Jiménez": ["Puerto Jiménez"]
        },
        "Limón": {
            "Limón": ["Limón", "Valle La Estrella", "Río Blanco", "Matama"],
            "Pococí": ["Guápiles", "Jiménez", "La Rita", "Roxana", "Cariari", "Colorado"],
            "Siquirres": ["Siquirres", "Pacuarito", "Florida", "Germania", "El Cairo", "Alegría"],
            "Talamanca": ["Bratsi", "Sixaola", "Cahuita", "Telire"],
            "Matina": ["Matina", "Batán", "Carrandi"],
            "Guácimo": ["Guácimo", "Mercedes", "Pocora", "Río Jiménez", "Duacari"]
        }
    };

    function cargarProvincias(provincia,condicion) {
        let selectProvincia = document.getElementById(provincia);
        if(condicion==1){
            selectProvincia.innerHTML = '<option value="">Seleccione una provincia</option>';
        }
        for (let provincia in data) {
            let option = document.createElement("option");
            option.value = provincia;
            option.textContent = provincia;
            selectProvincia.appendChild(option);
        }
    }

    function provincias(value,id){
        let selectCanton = document.getElementById('canton'+id);
        let selectDistrito = document.getElementById('distrito'+id);
        let provinciaSeleccionada = value;
        selectCanton.innerHTML = '<option value="">Seleccione un cantón</option>';
        selectDistrito.disabled = true;
        $('#distrito'+id).val("");
        if (provinciaSeleccionada) {
            selectCanton.disabled = false;
            for (let canton in data[provinciaSeleccionada]) {
                let option = document.createElement("option");
                option.value = canton;
                option.textContent = canton;
                selectCanton.appendChild(option);
            }
        } else {
            selectCanton.disabled = true;
            $('#canton'+id).val("");
        }
    }

    function cantones(value,id){
        var provinciaSeleccionada = $('#provincia'+id).val();
        var cantonSeleccionado = $('#canton'+id).val();
        let selectDistrito = document.getElementById('distrito'+id);
        let distritoSeleccionada = value;
        selectDistrito.innerHTML = '<option value="">Seleccione un cantón</option>';
        if (distritoSeleccionada) {
            selectDistrito.disabled = false;
            for (let distrito of data[provinciaSeleccionada][cantonSeleccionado]) {
                let option = document.createElement("option");
                option.value = distrito;
                option.textContent = distrito;
                selectDistrito.appendChild(option);
            }
        } else {
            selectDistrito.disabled = true;
            $('#distrito'+id).val("");
        }
    }

    function cargarDistritos() {
        let provinciaSeleccionada = document.getElementById("provincia").value;
        let cantonSeleccionado = document.getElementById("canton").value;
        let selectDistrito = document.getElementById("distrito");
        selectDistrito.innerHTML = '<option value="">Seleccione un distrito</option>';
        if (cantonSeleccionado) {
            selectDistrito.disabled = false;
            for (let distrito of data[provinciaSeleccionada][cantonSeleccionado]) {
                let option = document.createElement("option");
                option.value = distrito;
                option.textContent = distrito;
                selectDistrito.appendChild(option);
            }
        }
    }

    function espaciosBlancosInicioFinal(element) {
        let sanitizedValue = element.value.trim();
        element.value = sanitizedValue;
    }

    function sanitizeInput(input) {
        let trimmedInput = input.trim();
        return trimmedInput.length > 0 ? trimmedInput : null;
    }



</script>
