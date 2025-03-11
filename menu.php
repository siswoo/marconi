        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">ATP<sup>2</sup></div>
            </a>
            <hr class="sidebar-divider my-0">
            <li class="nav-item active">
                <a class="nav-link" href="admin.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Inicio</span></a>
            </li>

            <hr class="sidebar-divider">

            <div class="sidebar-heading">
                Módulos
            </div>

            <?php
                include("script/conexion.php");
                $sql1Usuario = "SELECT * FROM usuarios WHERE id = ".$_SESSION['marconiId'];
                $proceso1Usuario = mysqli_query($conexion,$sql1Usuario);
                $contador1Usuario = mysqli_num_rows($proceso1Usuario);
                if($contador1Usuario>=1){
                    while($row1Usuario=mysqli_fetch_array($proceso1Usuario)){
                        $nombreUsuario = $row1Usuario["usuario"];
                        $rolId = $row1Usuario["rol"];
                    }
                }
                $menuHtml1 = '';
                $menuHtml2 = '';
                $sql1 = "SELECT * FROM permisos WHERE rolId = ".$rolId;
                $proceso1 = mysqli_query($conexion,$sql1);
                $contador1 = mysqli_num_rows($proceso1);
                if($contador1>=1){
                    while($row1=mysqli_fetch_array($proceso1)){
                        $submoduloId = $row1["submoduloId"];
                        $sql2 = "SELECT * FROM submodulos WHERE id = ".$submoduloId;
                        $proceso2 = mysqli_query($conexion,$sql2);
                        $contador2 = mysqli_num_rows($proceso2);
                        if($contador2>=1){
                            while($row2=mysqli_fetch_array($proceso2)){
                                $submodulosNombre = $row2["nombre"];
                                $submodulosIdmodulo = $row2["idModulo"];
                                if(empty($arraySubmodulos[$submodulosIdmodulo])){
                                    $arraySubmodulos[$submodulosIdmodulo][$submoduloId] = $submodulosNombre;
                                }else{
                                    $arraySubmodulos[$submodulosIdmodulo][$submoduloId] = $submodulosNombre;
                                }
                            }
                        }
                    }

                    $cantidadModulosMenu = count($arraySubmodulos);
                    $moduloIdKeyG = array_keys($arraySubmodulos);
                    $incremento = 1;
                    
                    for($i=1;$i<=$cantidadModulosMenu;$i++){
                        $moduloIdKeyF = $moduloIdKeyG[$i-1];
                        $sql3 = "SELECT * FROM modulos WHERE id = ".$moduloIdKeyF;
                        $proceso3 = mysqli_query($conexion,$sql3);
                        $contador3 = mysqli_num_rows($proceso3);
                        if($contador3>=1){
                            while($row3=mysqli_fetch_array($proceso3)){
                                $moduloNombre = $row3["nombre"];
                            }
                                $menuHtml1 .= '
                                    <li class="nav-item">
                                        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#'.$moduloNombre.'" aria-expanded="true" aria-controls="'.$moduloNombre.'">
                                            <i class="fas fa-fw fa-cog"></i>
                                            <span>'.$moduloNombre.'</span>
                                        </a>
                                        <div id="'.$moduloNombre.'" class="collapse" data-parent="#accordionSidebar">
                                ';
                                $submoduloIdKey = array_keys($arraySubmodulos[$moduloIdKeyF]);
                                $cantidadSubmodulosMenu = count($submoduloIdKey);
                                for ($j=0;$j<$cantidadSubmodulosMenu;$j++) {
                                    $sql4 = "SELECT * FROM submodulos WHERE id = ".$submoduloIdKey[$j];
                                    $proceso4 = mysqli_query($conexion,$sql4);
                                    $contador4 = mysqli_num_rows($proceso4);
                                    if($contador4>=1){
                                        while($row4=mysqli_fetch_array($proceso4)){
                                            $submoduloLink = $row4["link"];
                                            $submoduloNombre = $arraySubmodulos[$moduloIdKeyF][$submoduloIdKey[$j]];
                                            //print_r($arraySubmodulos);
                                            $menuHtml1 .= '
                                                    <div class="bg-white py-2 collapse-inner rounded">
                                                        <a class="collapse-item" href="'.$submoduloLink.'">'.$submoduloNombre.'</a>
                                            ';
                                        }
                                    }
                                }
                            $menuHtml1 .= '
                                    </div>
                                </div>
                                <!--<hr class="sidebar-divider d-none d-md-block">-->
                            ';
                        }
                    }
                }
                echo $menuHtml1;
            ?>
        </ul>