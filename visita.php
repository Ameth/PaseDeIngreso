<?php 
if(isset($_POST['Nombre'])&&($_POST['Nombre']!="")){//Insertar registro	
	try{
		require("includes/conect_srv.php");
		require("includes/LSiqml.php");			
		require("includes/funciones.php");	


		$sw_error=0;
		$msg_error="";//Mensaje del error
		
		$Parametros=array(
			"'".LSiqml(LSiqmlObs(strtoupper($_POST['Nombre'])))."'",
			"'".LSiqml(strtoupper($_POST['Direccion']))."'",
			"'".LSiqml($_POST['Telefono'])."'",
			"'".$_POST['Temperatura']."'"
		);
		$SQL=EjecutarSP('usp_tbl_RegistroVisitas',$Parametros);
		if($SQL){
			sqlsrv_close($conexion);
			header('Location:visita.php?a='.base64_encode("OK"));		
		}else{
			$sw_error=1;
			$msg_error="Ha ocurrido un error al insertar el registro";
		}
	}catch (Exception $e){
		echo 'Excepcion capturada: ',  $e->getMessage(), "\n";
	}
	
}
?>
<!DOCTYPE html>
<html>

<head>
<?php include("includes/cabecera.php"); ?>

<title>Registro de visita</title>
<?php 
if(isset($_GET['a'])&&($_GET['a']==base64_encode("OK"))){
	echo "<script>
		$(document).ready(function() {
			swal({
                title: '¡Muchas gracias!',
                text: 'Su registro fue guardado exitosamente.',
                type: 'success'
            });
		});		
		</script>";
}
?>
</head>

<body>

<div id="wrapper">
    <div id="" class="gray-bg">
        <div class="row wrapper border-bottom white-bg page-heading">
			<div class="col-xs-12 text-center">
				<img src="img/logo_CC150X150.jpg">
				<h2 class="font-bold">Registre su visita</h2>
				<h3 class="text-success">Ayudanos a protegerte. En tus manos está prevenir el contagio del COVID-19</h3>
			</div>
		</div>
           
      <div class="wrapper wrapper-content">
		<div class="ibox-content">
			 <?php include("includes/spinner.php"); ?>
          <div class="row"> 
           <div class="col-lg-12">
			   <form action="visita.php" method="post" class="form" enctype="multipart/form-data" id="frmEncuesta">				
				<div class="form-group text-center">
					<h2 class="col-xs-12 m-b-md">Fecha: <?php echo date('d/m/Y');?></h2>	
				</div>
				<div class="form-group">
					<label>Nombre completo</label>
					<input name="Nombre" type="text" required="required" class="form-control" id="Nombre" maxlength="150" autofocus />
				</div>
				<div class="form-group">
					<label>Dirección</label>
					<input name="Direccion" type="text" required="required" class="form-control" id="Direccion" maxlength="150" />
				</div>
				<div class="form-group">
					<label>Teléfono</label>
					<input name="Telefono" type="tel" required="required" class="form-control" id="Telefono" maxlength="50" />
				</div>
				<div class="form-group">
					<label>Temperatura</label>
					<input name="Temperatura" type="number" required="required" maxlength="5" placeholder="Ejemplo: 37.5" class="form-control" id="Temperatura" value="" onKeyUp="revisaCadena(this);" onKeyPress="return justNumbers(event,this.value);">
				</div>
				<div class="form-group">
					<div class="col-xs-12">
						<button class="btn btn-success btn-block btn-lg" form="frmEncuesta" type="submit" id="Crear"><i class="fa fa-user-circle" aria-hidden="true"></i> Registrar mi visita</button>
					</div>
				</div>
				</form>
		   </div>
			</div>
          </div>
		</div>
    </div>
</div>
<?php include("includes/pie.php"); ?>
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
	
	var temp = $("#Temperatura").val();
	
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
</body>

<!-- InstanceEnd --></html>