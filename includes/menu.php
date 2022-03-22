<?php 
require_once("includes/conexion.php");
/*
$Cons_Menu="Select * From uvw_tbl_Categorias Where ID_Padre=0 and EstadoCategoria=1 and ID_Permiso IN (Select ID_Permiso From uvw_tbl_PermisosPerfiles Where ID_PerfilUsuario='".$_SESSION['Perfil']."')";
$SQL_Menu=sqlsrv_query($conexion,$Cons_Menu,array(),array( "Scrollable" => 'Buffered' ));
$Num_Menu=sqlsrv_num_rows($SQL_Menu);
*/
?>
      <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav metismenu" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element">
	                    <img src="img/logo_150X150.png" alt=""/>
	                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
							<span class="clear">
								<br>
								<span class="block m-t-xs"><strong class="font-bold"><?php echo $_SESSION['NomUser'];?></strong></span> 
								<span class="text-muted text-xs block"><?php echo $_SESSION['NomPerfil'];?></span>
							</span>
						</a>
	                </div>
                    <div class="logo-element">
                    	<img src="img/logo_30X30.png" class="img-circle" alt="" width="30" height="30"/> 
                    </div>
                </li>
                <li class="active">
                    <a class="alnk" href="index1.php"><i class="fa fa-home"></i> <span class="nav-label">Inicio</span></a>
                </li>
		   		<?php if(PermitirFuncion(201)){?>
            	<li>
                    <a href="#"><i class="fa fa-edit"></i> <span class="nav-label">Encuesta diaria</span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
						<?php if(PermitirFuncion(201)){?><li><a class="alnk" href="encuesta.php"><i class="fa fa-pencil-square"></i> Registrar encuesta</a></li><?php }?>
                    </ul>
                </li>
                <?php }?>
				<?php if(PermitirFuncion(301)){?>
            	<li>
                    <a href="#"><i class="fa fa-line-chart"></i> <span class="nav-label">Informes</span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
						<?php if(PermitirFuncion(301)){?><li><a class="alnk" href="informe_encuestas.php"><i class="fa fa-edit"></i> Encuestas diarias</a></li><?php }?>
						<?php if(PermitirFuncion(301)){?><li><a class="alnk" href="informe_visitantes.php"><i class="fa fa-users"></i> Visitantes</a></li><?php }?>
                    </ul>
                </li>
                <?php }?>
				<?php if(PermitirFuncion(401)||PermitirFuncion(402)){?>
            	<li>
                    <a href="#"><i class="fa fa-group"></i> <span class="nav-label">Capacitaciones</span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
						<?php if(PermitirFuncion(401)){?><li><a class="alnk" href="gestionar_capacitaciones.php"><i class="fa fa-edit"></i> Gestionar capacitaciones</a></li><?php }?>
						<?php if(PermitirFuncion(402)){?><li><a class="alnk" href="asistir_capacitacion.php"><i class="fa fa-child"></i> Asistir a capacitación</a></li><?php }?>
                    </ul>
                </li>
                <?php }?>
	            <li>
                    <a href="#"><i class="fa fa-gears"></i> <span class="nav-label">Administraci&oacute;n</span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
						<li><a href="cambiar_clave.php"><i class="fa fa-lock"></i> Cambiar contrase&ntilde;a</a></li>
						<?php if(PermitirFuncion(502)){?><li><a class="alnk" href="gestionar_usuarios.php"><i class="fa fa-user"></i> Gestionar usuarios</a></li><?php }?>
                  		<?php if(PermitirFuncion(501)){?><li><a class="alnk" href="gestionar_perfiles.php"><i class="fa fa-users"></i> Gestionar perfiles</a></li><?php }?>
                  		<li><a class="alnk" href="contrato_confidencialidad.php"><i class="fa fa-handshake-o"></i> Acuerdo de confidencialidad</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>