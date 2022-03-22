<?php require_once("includes/conexion.php");
PermitirAcceso(201);

$sw_error=0;
$msg_error="";//Mensaje del error

if(isset($_POST['P'])&&($_POST['P']!="")){//Insertar registro	
	try{
		
		$Parametros=array(
			"'".date('Ymd')."'",
			"'".$_SESSION['CodUser']."'",
			"'".$_POST['Respuesta1']."'",
			"'".$_POST['Respuesta2']."'"
		);
		$SQL_Pub=EjecutarSP('usp_tbl_RespuestasEncuestaSalida',$Parametros);
		if($SQL_Pub){
			sqlsrv_close($conexion);
			header('Location:index1.php?a='.base64_encode("OK_EncSalAdd"));		
		}else{
			$sw_error=1;
			$msg_error="Ha ocurrido un error al insertar la encuesta";
		}
	}catch (Exception $e){
		echo 'Excepcion capturada: ',  $e->getMessage(), "\n";
	}
	
}

$SQL=Seleccionar('uvw_tbl_RespuestasEncuestaSalida','*',"IDUsuario='".$_SESSION['CodUser']."' and FechaEncuesta='".date('Ymd')."'");
$row=sqlsrv_fetch_array($SQL);

?>
<!DOCTYPE html>
<html><!-- InstanceBegin template="/Templates/PlantillaPrincipal.dwt.php" codeOutsideHTMLIsLocked="false" -->

<head>
<?php include("includes/cabecera.php"); ?>
<!-- InstanceBeginEditable name="doctitle" -->
<title>Registrar encuesta de salida | <?php echo NOMBRE_PORTAL;?></title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
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

if(isset($sw_error)&&($sw_error==1)){
	echo "<script>
		$(document).ready(function() {
			swal({
                title: '¡Ha ocurrido un error!',
                text: '".$msg_error."',
                type: 'error'
            });
		});		
		</script>";
}
?>
<script type="text/javascript">
	$(document).ready(function() {
		
	});
</script>
<!-- InstanceEndEditable -->
</head>

<body>

<div id="wrapper">

    <?php include("includes/menu.php"); ?>

    <div id="page-wrapper" class="gray-bg">
        <?php include("includes/menu_superior.php"); ?>
        <!-- InstanceBeginEditable name="Contenido" -->
        <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-sm-8">
                    <h2>Registrar encuesta de salida</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="index1.php">Inicio</a>
                        </li>
                        <li>
                            <a href="#">Encuesta diaria</a>
                        </li>
                        <li class="active">
                            <strong>Registrar encuesta de salida</strong>
                        </li>
                    </ol>
                </div>
            </div>
           
      <div class="wrapper wrapper-content">
		<div class="ibox-content">
			 <?php include("includes/spinner.php"); ?>
          <div class="row"> 
           <div class="col-lg-12">
			   <form action="encuesta_salida.php" method="post" class="form" enctype="multipart/form-data" id="frmEncuesta" >   
				<div class="form-group">
					<label class="col-xs-12 text-center"><h3 class="bg-success p-xs b-r-sm"><i class="fa fa-edit"></i> Encuesta diaria de salud</h3></label>
				</div>
				<div class="form-group">
					<h3 class="col-xs-12 m-b-md">Fecha: <?php echo date('d/m/Y');?></h3>	
				</div>
				<?php if(!isset($row['ID'])){?>
				<div class="form-group">
					<h3 class="col-xs-12 text-danger m-b-md">1. ¿Cual fue su temperatura el día de hoy en su salida?*</h3>
					<div class="col m-l-md m-b-sm">
						<input name="Respuesta1" type="number" maxlength="5" required="required" placeholder="Ejemplo: 37.5" class="form-control" id="Respuesta1" value="" onKeyUp="revisaCadena(this);" onKeyPress="return justNumbers(event,this.value);">
					</div>
				</div>
				<div class="form-group">
					<h3 class="col-xs-12 text-danger m-b-md">2. ¿Le entregaron los Elementos de Protección Personal (EPP)?*</h3>
					<div class="col m-l-md m-b-sm"><div class="i-checks"><input type="radio" value="SI" name="Respuesta2" id="opt21"> SI</div></div>
					<div class="col m-l-md m-b-sm"><div class="i-checks"><input type="radio" value="NO" name="Respuesta2" id="opt22"> NO</div></div>
				</div>
				<br>
				<input type="hidden" id="P" name="P" value="20" />
				<div class="form-group">
					<div class="col-xs-12">
						<button class="btn btn-danger btn-block btn-lg" form="frmEncuesta" type="submit" id="Crear"><i class="fa fa-sign-out"></i> Registrar salida</button>
					</div>
				</div>
				<?php }else{?>
				<div class="col-xs-12">
					<div class="alert alert-info">
						<h2><i class="fa fa-info-circle"></i> Usted ya ha registrado la encuesta de salida de hoy.</h2>
					</div>
				</div>
				<?php }?>
				</form>
		   </div>
			</div>
          </div>
	</div>
        <!-- InstanceEndEditable -->
        <?php include("includes/footer.php"); ?>

    </div>
</div>
<?php include("includes/pie.php"); ?>
<!-- InstanceBeginEditable name="EditRegion4" -->
<script>
	 $(document).ready(function(){
		 $("#frmEncuesta").validate({
			 submitHandler: function(form){
				 $('.ibox-content').toggleClass('sk-loading',true);
					 if(Validar()){
						 form.submit();
					 }else{
						 $('.ibox-content').toggleClass('sk-loading',false);
					 }
				}
		 });
		 
		 $(".alkin").on('click', function(){
				 $('.ibox-content').toggleClass('sk-loading');
			});
		 
		 $(".select2").select2();
		 $('.i-checks').iCheck({
			 checkboxClass: 'icheckbox_square-green',
             radioClass: 'iradio_square-green',
          });
	});
</script>
<script>
function Validar(){
	var result=true;
	var estado=0;
	
	var temp = $("#Respuesta1").val();
	
	if(!$("input[name=Respuesta2]:checked").val()) {
		estado=1;
	}
	
	if(estado==1){
		result=false;
		swal({
			title: '¡Advertencia!',
			text: 'Debe responder todas las preguntas',
			type: 'warning'
		});
	}
	
	if(temp>100){
		result=false;
		swal({
			title: '¡Advertencia!',
			text: 'Su temperatura es muy alta, por favor verifique',
			type: 'warning'
		});
	}
	
	return result;
}
</script>
<!-- InstanceEndEditable -->
</body>

<!-- InstanceEnd --></html>
<?php sqlsrv_close($conexion);?>