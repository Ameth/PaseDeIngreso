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
			"'".LSiqml($_POST['Empresa'])."'",
			"'".LSiqml($_POST['EPS'])."'",
			"'".LSiqml($_POST['ARL'])."'",
			"'".$_POST['Respuesta1']."'",
			"'".$_POST['Respuesta2']."'",
			"'".$_POST['Respuesta3']."'"
		);
		$SQL=EjecutarSP('usp_tbl_RegistroPosibles',$Parametros);
		if($SQL){
			sqlsrv_close($conexion);
			header('Location:encuesta_posibles.php?a='.base64_encode("OK"));		
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

<title>Registro de posibles contagios</title>
</head>

<body>

<div id="wrapper">
    <div id="" class="gray-bg">
        <div class="row wrapper border-bottom white-bg page-heading">
			<div class="col-xs-12 text-center">
				<img src="img/logo_CC150X150.jpg">
				<h2 class="font-bold">Ingrese su información</h2>
				<h3 class="text-success">Con esta encuesta podemos registrar y monitorear a las personas que son posibles positivos para COVID-19</h3>
			</div>
		</div>
           
      <div class="wrapper wrapper-content">
		<div class="ibox-content">
			 <?php include("includes/spinner.php"); ?>
          <div class="row"> 
           <div class="col-lg-12">
			   <form action="encuesta_posibles.php" method="post" class="form" enctype="multipart/form-data" id="frmEncuesta">
				<?php 
				if(isset($_GET['a'])&&($_GET['a']==base64_encode("OK"))){?>
					<div class="row">
						<div class="col-xs-12">
							<div class="alert alert-success text-center">
								<h2><i class="fa fa-check fa-2x"></i><br>Registro guardado exitosamente</h2>
							</div>
						</div>
					</div>
				<?php 
					}
				?>				
				<div class="form-group">
					<h3 class="col-xs-12 text-danger">Nombre completo*</h3>
					<input name="Nombre" type="text" required="required" class="form-control" id="Nombre" maxlength="150" />
				</div>
				<div class="form-group">
					<h3 class="col-xs-12 text-danger">Dirección*</h3>
					<input name="Direccion" type="text" required="required" class="form-control" id="Direccion" maxlength="150" />
				</div>
				<div class="form-group">
					<h3 class="col-xs-12 text-danger">Teléfono*</h3>
					<input name="Telefono" type="tel" required="required" class="form-control" id="Telefono" maxlength="50" />
				</div>
				<div class="form-group">
					<h3 class="col-xs-12 text-danger">Empresa donde labora*</h3>
					<input name="Empresa" type="text" required="required" class="form-control" id="Empresa" maxlength="50" />
				</div>
				<div class="form-group">
					<h3 class="col-xs-12 text-danger">EPS</h3>
					<input name="EPS" type="text" class="form-control" id="EPS" maxlength="50" />
				</div>
				<div class="form-group">
					<h3 class="col-xs-12 text-danger">ARL</h3>
					<input name="ARL" type="text" class="form-control" id="ARL" maxlength="50" />
				</div>
				<div class="form-group">
					<h3 class="col-xs-12 text-danger m-b-md">1. ¿Presenta algunos de los siguientes síntomas?*</h3>
					<div class="col m-l-md m-b-sm"><div class="i-checks"><input type="radio" value="Tos seca" name="Respuesta1" id="opt11"> Tos seca</div></div>
					<div class="col m-l-md m-b-sm"><div class="i-checks"><input type="radio" value="Fiebre mayor a 37.5" name="Respuesta1" id="opt12"> Fiebre mayor a 37.5°</div></div>
					<div class="col m-l-md m-b-sm"><div class="i-checks"><input type="radio" value="Malestar general" name="Respuesta1" id="opt13"> Malestar general</div></div>
					<div class="col m-l-md m-b-sm"><div class="i-checks"><input type="radio" value="Dificultad para respirar" name="Respuesta1" id="opt14"> Dificultad para respirar</div></div>
					<div class="col m-l-md m-b-sm"><div class="i-checks"><input type="radio" value="Escalofrios" name="Respuesta1" id="opt15"> Escalofrios</div></div>
					<div class="col m-l-md m-b-sm"><div class="i-checks"><input type="radio" value="Congestion nasal" name="Respuesta1" id="opt16"> Congestión nasal</div></div>
					<div class="col m-l-md m-b-sm"><div class="i-checks"><input type="radio" value="Dolor de garganta" name="Respuesta1" id="opt17"> Dolor de garganta</div></div>
					<div class="col m-l-md m-b-sm"><div class="i-checks"><input type="radio" value="Ninguno" name="Respuesta1" id="opt18"> Ninguno</div></div>			
				</div>
				<div class="form-group">
					<h3 class="col-xs-12 text-danger m-b-md">2. ¿Usted o algún miembro de su hogar o quien vive con usted ha tenido contacto con alguna persona confirmada positivo para COVID-19, o están en observación o en espera de resultados?*</h3>
					<div class="col m-l-md m-b-sm"><div class="i-checks"><input type="radio" value="SI" name="Respuesta2" id="opt21"> SI</div></div>
					<div class="col m-l-md m-b-sm"><div class="i-checks"><input type="radio" value="NO" name="Respuesta2" id="opt22"> NO</div></div>
				</div>
				<div class="form-group">
					<h3 class="col-xs-12 text-danger m-b-md">3. ¿Ha viajado fuera de la ciudad en el último mes?*</h3>
					<div class="col m-l-md m-b-sm"><div class="i-checks"><input type="radio" value="SI" name="Respuesta3" id="opt31"> SI</div></div>
					<div class="col m-l-md m-b-sm"><div class="i-checks"><input type="radio" value="NO" name="Respuesta3" id="opt32"> NO</div></div>
				</div>
				<div class="form-group">
					<div class="col-xs-12">
						<button class="btn btn-success btn-block btn-lg" form="frmEncuesta" type="submit" id="Crear"><i class="fa fa-save" aria-hidden="true"></i> Guardar datos</button>
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
	
	if(!$("input[name=Respuesta1]:checked").val()) {
		estado=1;
	}
	
	if(!$("input[name=Respuesta2]:checked").val()) {
		estado=1;
	}
	
	if(!$("input[name=Respuesta3]:checked").val()) {
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
	
	return result;
}
</script>
</body>

<!-- InstanceEnd --></html>