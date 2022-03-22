<?php require_once("includes/conexion.php"); 

$EstadoIngreso=0;//0-> No ha registrado hoy. 1-> Permitido para ingresar. 2-> No permitido para ingresar

$SQLIngreso=EjecutarSP('usp_VerificarIngreso',$_SESSION['CodUser']);
$rowIngreso=sqlsrv_fetch_array($SQLIngreso);

$SQLSalida=Seleccionar('uvw_tbl_RespuestasEncuestaSalida','*',"IDUsuario='".$_SESSION['CodUser']."' and FechaEncuesta='".date('Ymd')."'");
$rowSalida=sqlsrv_fetch_array($SQLSalida);

$SQLCantIngreso=EjecutarSP('usp_CantidadAdentro',date('Ymd'));
$rowCantIngreso=sqlsrv_fetch_array($SQLCantIngreso);

$SQLCap=EjecutarSP('usp_ConsultarCapacitacionesPendientes',$_SESSION['CodUser']);

?>
<!DOCTYPE html>
<html><!-- InstanceBegin template="/Templates/PlantillaPrincipal.dwt.php" codeOutsideHTMLIsLocked="false" -->

<head>
<?php include_once("includes/cabecera.php"); ?>
<!-- InstanceBeginEditable name="doctitle" -->
<title><?php echo NOMBRE_PORTAL;?> | Inicio</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<style>
	.animar{
		animation-duration: 1.5s;
  		animation-name: tada;
  		animation-iteration-count: infinite;
	}
	.animar2{
		animation-duration: 1s;
  		animation-name: swing;
  		animation-iteration-count: infinite;
	}
	.animar3{
		animation-duration: 3s;
  		animation-name: pulse;
  		animation-iteration-count: infinite;
	}
	.edit1 {/*Widget editado por aordonez*/
		border-radius: 0px !important; 
		padding: 15px 20px;
		margin-bottom: 10px;
		margin-top: 10px;
		height: 120px !important;
	}
	.modal-lg {
		width: 50% !important;
	}
</style>
<?php if(!isset($_SESSION['SetCookie'])||($_SESSION['SetCookie']=="")){?>
<script>
$(document).ready(function(){
	$('#myModal').modal("show");
});
</script>
<?php }?>
<?php 
if(isset($_GET['a'])&&($_GET['a']==base64_encode("OK_EncAdd"))){
	echo "<script>
		$(document).ready(function() {
			swal({
                title: '¡Listo!',
                text: 'Su encuesta ha sido registrada exitosamente.',
                type: 'success'
            });
		});		
		</script>";
}

if(isset($_GET['a'])&&($_GET['a']==base64_encode("OK_EncSalAdd"))){
	echo "<script>
		$(document).ready(function() {
			swal({
                title: '¡Listo!',
                text: 'Su salida ha sido registrada exitosamente.',
                type: 'success'
            });
		});		
		</script>";
}
?>
<!-- InstanceEndEditable -->
</head>

<body>

<div id="wrapper">

    <?php include_once("includes/menu.php"); ?>

    <div id="page-wrapper" class="gray-bg">
        <?php include_once("includes/menu_superior.php"); ?>
        <!-- InstanceBeginEditable name="Contenido" -->
        <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-6">
                    <h2>Bienvenido <?php echo $_SESSION['NomUser'];?></h2>
                </div>
        </div>
        <?php 
		$Nombre_archivo="contrato_confidencialidad.txt";
		$Archivo=fopen($Nombre_archivo,"r");
		$Contenido = fread($Archivo, filesize($Nombre_archivo));
		?>
        <div class="modal inmodal fade" id="myModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false" data-show="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Acuerdo de confidencialidad</h4>
						<small>Por favor lea atentamente este contrato que contiene los T&eacute;rminos y Condiciones de uso de este sitio. Si continua usando este portal, consideramos que usted est&aacute; de acuerdo con ellos.</small>
					</div>
					<div class="modal-body">
						<?php echo $Contenido;?>
					</div>

					<div class="modal-footer">
						<button type="button" onClick="AceptarAcuerdo();" class="btn btn-primary" data-dismiss="modal">Acepto los t&eacute;rminos</button>
					</div>
				</div>
			</div>
		</div>
        <div class="page-wrapper wrapper-content animated fadeInRight">
			<?php if(PermitirFuncion(301)){?>
			<div class="row">
				<div class="col-xs-12">
					<div class="ibox ">
						<div class="ibox-title">
							<h5 class="text-success">Personas adentro</h5>
						</div>
						<div class="ibox-content">
							<div class="row">
								<div class="col-xs-4">
									<i class="fa fa-users fa-3x text-navy"></i>
								</div>
								<div class="col-xs-8 text-right">
									<h1 class="no-margins" id="CG_run1">0</h1>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php }?>			
			<?php while($rowCap=sqlsrv_fetch_array($SQLCap)){
				if(isset($rowCap['TituloCapacitacion'])&&($rowCap['TituloCapacitacion']!="")){?>
					<div class="ibox-content"> 
						<?php include("includes/spinner.php"); ?>
						<div class="row">
							<div class="col-xs-12">
								<div class="alert alert-danger text-center">
									<h2><i class="fa fa-exclamation-triangle fa-2x animar"></i><br><?php echo $rowCap['TituloCapacitacion'];?></h2>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12">
								<a href="asistir_capacitacion_firmar.php?id=<?php echo base64_encode($rowCap['IDCapacitacion']);?>&tl=1" class="btn btn-w-m btn-success btn-block btn-lg"><i class="fa fa-user-circle"></i> Registrar mi asistencia</a>
							</div>
						</div>
					</div>
					<br>
			<?php }
				}?>
			<div class="ibox-content"> 
				<?php include("includes/spinner.php"); ?>
				<div class="row">
					<div class="col-xs-12">
						<?php if(isset($rowSalida['ID'])){ ?>
						<div class="alert alert-success text-center">
							<h2><i class="fa fa-check fa-2x"></i><br>Su salida está registrada</h2>
						</div>
						<?php 
						}else{
							if($rowIngreso['Result']=='X'){?>
							<div class="alert alert-info text-center">
								<h2><i class="fa fa-exclamation-triangle fa-2x animar"></i><br>Aún no ha registrado su autodiagnostico del día de hoy</h2>
							</div>
							<?php }elseif($rowIngreso['Result']=='N'){?>
							<div class="alert alert-danger text-center">
								<h2><i class="fa fa-times fa-2x"></i><br>Según sus resultados, <strong>NO</strong> tiene permitido ingresar el día de hoy <?php echo date('m/d/Y');?></h2>
							</div>
							<?php }elseif($rowIngreso['Result']=='P'){?>
							<div class="alert alert-warning text-center">
								<h2><i class="fa fa-exclamation-triangle fa-2x"></i><br><?php echo date('m/d/Y');?><br><strong>PERMITIDO EL INGRESO CON RESTRICCIONES.</strong><br>Debe usar siempre el tapabocas y evitar el contacto estrecho con las personas.</h2>
							</div>
							<?php }else{?>
							<div class="alert alert-success text-center">
								<h2><i class="fa fa-check fa-2x"></i><br>PERMITIDO EL INGRESO <?php echo date('m/d/Y');?></h2>
							</div>
							<?php }	
						}?>
					</div>
				</div>
				<?php if($rowIngreso['Result']=='X'){?>
				<div class="row">
					<div class="col-xs-12">
						<a href="encuesta.php" class="btn btn-w-m btn-primary btn-block btn-lg"><i class="fa fa-edit"></i> Registrar encuesta diaria</a>
					</div>
				</div>
				<?php }elseif(($rowIngreso['Result']=='Y')&&(!isset($rowSalida['ID']))){?>
				<div class="row">
					<div class="col-xs-12">
						<a href="encuesta_salida.php" class="btn btn-w-m btn-danger btn-block btn-lg"><i class="fa fa-sign-out"></i> Registrar salida</a>
					</div>
				</div>	
				<?php }?>
			</div>
        </div>
        <!-- InstanceEndEditable -->
        <?php include_once("includes/footer.php"); ?>

    </div>
</div>
<?php include_once("includes/pie.php"); ?>
<!-- InstanceBeginEditable name="EditRegion4" -->
<script>	
	 $(document).ready(function(){
		 $('.navy-bg').each(function() {
                animationHover(this, 'pulse');
            });
		  $('.yellow-bg').each(function() {
                animationHover(this, 'pulse');
            });
		 $('.lazur-bg').each(function() {
                animationHover(this, 'pulse');
            });
		 $(".truncate").dotdotdot({
            watch: 'window'
		  });
		 $("span.pie").peity("pie");
	});
</script>
<script>
var amount=<?php echo $rowCantIngreso['Cant'];?>;
	$({c:0}).animate({c:amount},{
		step: function(now){
			$("#CG_run1").html(Math.round(now))
		},
		duration:2000,
		easing:"linear"
	});
</script>
<?php if(isset($_GET['dt'])&&$_GET['dt']==base64_encode("result")){?>
<script>
	$(document).ready(function(){
		toastr.options = {
			closeButton: true,
			progressBar: true,
			showMethod: 'slideDown',
			timeOut: 6000
		};
		toastr.success('¡Su contraseña ha sido modificada!', 'Felicidades');
	});
</script>
<?php }?>
<script src="js/js_setcookie.js"></script>
<!-- InstanceEndEditable -->
</body>

<!-- InstanceEnd --></html>
<?php sqlsrv_close($conexion);?>