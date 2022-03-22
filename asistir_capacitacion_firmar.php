<?php require_once("includes/conexion.php");
PermitirAcceso(402);

if(isset($_GET['id'])&&($_GET['id']!="")){
	$IDCap=base64_decode($_GET['id']);
}

if(isset($_POST['P'])&&($_POST['P']!="")){//Insertar registro	
	try{
		$dir=CrearObtenerDirTemp();
		$dir_firma=CrearObtenerDirTempFirma();
		$dir_new=CrearObtenerDirAnx("firmas");
		
		$NombreFirma="NULL";
		
		if($_POST['SigFirma']!=""){
			$NombreFirma=base64_decode($_POST['SigFirma']);
			if(copy($dir_firma.$NombreFirma,$dir_new.$NombreFirma)){
				//RedimensionarImagen($NombreFirma,$dir_new.$NombreFirma,300,300);
				$NombreFirma="'".$NombreFirma."'";
			}else{
				$NombreFirma="NULL";
				$sw_error=1;
				$msg_error="No se pudo mover la firma";
			}
		}
		
		$IDCap=base64_decode($_POST['ID']);
		
		$ParamInsert=array(
			$IDCap,
			"'".$_SESSION['CodUser']."'",
			$NombreFirma
		);
		$SQL_InsUser=EjecutarSP('usp_tbl_AsistenciaCapacitacion',$ParamInsert,$_POST['P']);
		
		if($SQL_InsUser){
			sqlsrv_close($conexion);
			header('Location:'.base64_decode($_POST['return']).'&a='.base64_encode("OK_AsisCap"));
		}else{
			throw new Exception('Ha ocurrido un error al insertar la asistencia a la capacitación');			
			sqlsrv_close($conexion);
			}
	}catch (Exception $e){
		echo 'Excepcion capturada: ',  $e->getMessage(), "\n";
	}
	
}

$SQL=Seleccionar('uvw_tbl_Capacitaciones','*',"IDCapacitacion='".$IDCap."'");
$row=sqlsrv_fetch_array($SQL);
	
?>
<!DOCTYPE html>
<html><!-- InstanceBegin template="/Templates/PlantillaPrincipal.dwt.php" codeOutsideHTMLIsLocked="false" -->

<head>
<?php include_once("includes/cabecera.php"); ?>
<!-- InstanceBeginEditable name="doctitle" -->
<title>Firmar asistencia | <?php echo NOMBRE_PORTAL;?></title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<script>
function AbrirFirma(IDCampo){
	var posicion_x;
	var posicion_y;
	posicion_x=(screen.width/2)-(1200/2);  
	posicion_y=(screen.height/2)-(500/2);
	self.name='opener';
	remote=open('popup_firma.php?id='+Base64.encode(IDCampo),'remote',"width=1200,height=500,location=no,scrollbars=yes,menubars=no,toolbars=no,resizable=no,fullscreen=no,directories=no,status=yes,left="+posicion_x+",top="+posicion_y+"");
	remote.focus();
}	
</script>
<!-- InstanceEndEditable -->
</head>

<body>

<div id="wrapper">

    <?php include_once("includes/menu.php"); ?>

    <div id="page-wrapper" class="gray-bg">
        <?php include_once("includes/menu_superior.php"); ?>
        <!-- InstanceBeginEditable name="Contenido" -->
        <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-sm-8">
                    <h2>Firmar asistencia</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="index1.php">Inicio</a>
                        </li>
                        <li>
                            <a href="#">Capacitaciones</a>
                        </li>
                        <li>
                            <a href="asistir_capacitacion.php">Asistir a capacitación</a>
                        </li>
                        <li class="active">
                            <strong>Firmar asistencia</strong>
                        </li>
                    </ol>
                </div>
            </div>
           
         <div class="wrapper wrapper-content">
			<div class="row">
           		<div class="col-xs-12">
					<div class="ibox-content"> 
					<?php include("includes/spinner.php"); ?>
              		<form action="asistir_capacitacion_firmar.php" method="post" class="form-horizontal" id="frmCap">
						<div class="form-group">
							<label class="col-xs-12 text-center"><h3 class="bg-info p-xs b-r-sm"><i class="fa fa-group"></i> Asistencia a capacitación</h3></label>
						</div>
						<div class="form-group">
							<h3 class="col-xs-12 text-danger text-center m-b-xs">CAPACITACIÓN O REUNIÓN</h3>	
							<h3 class="col-xs-12"><?php echo $row['TituloCapacitacion'];?></h3>		
						</div>
						<div class="form-group">
							<h3 class="col-xs-12 text-danger text-center m-b-xs">LUGAR</h3>	
							<h3 class="col-xs-12 text-center"><?php echo $row['Lugar'];?></h3>		
						</div>
						<div class="form-group">
							<div class="col-xs-12">
								<button class="btn btn-warning btn-block btn-lg" type="button" id="FirmaCliente" onClick="AbrirFirma('SigFirma');"><i class="fa fa-pencil-square-o"></i> Firmar mi asistencia</button> 
								<input type="hidden" id="SigFirma" name="SigFirma" value="" form="frmCap" />
								<div id="msgInfoSigFirma" style="display: none;" class="alert alert-info"><i class="fa fa-info-circle"></i> El documento ya ha sido firmado.</div>
							</div>
							<div class="col-xs-12">
								<img id="ImgSigFirma" style="display: none; max-width: 100%; height: auto;" src="" alt="" />
							</div>
						</div>						
						<div class="form-group">
							<div class="col-xs-12">
								<button class="btn btn-success btn-block btn-lg" form="frmCap" type="submit" id="Crear"><i class="fa fa-save"></i> Guardar</button>
							</div>
						</div>
						<div class="form-group">
							<div class="col-xs-12">
								<a href="asistir_capacitacion.php" class="btn btn-default btn-block btn-lg"><i class="fa fa-arrow-circle-o-left"></i> Regresar</a>
							</div>
						</div>
				<?php 			
					if(isset($_GET['return'])){
						$return=base64_decode($_GET['pag'])."?".$_GET['return'];
					}else{
						$return="asistir_capacitacion.php?";
					}				  
				  	$return=QuitarParametrosURL($return,array("a"));?>
				  
				<input type="hidden" id="P" name="P" value="40" />
				<input type="hidden" id="ID" name="ID" value="<?php echo base64_encode($IDCap);?>" />
				<input type="hidden" id="return" name="return" value="<?php echo base64_encode($return);?>" />
			  </form>
		   			</div>
          		</div>
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
		 $("#frmCap").validate({
			 submitHandler: function(form){
				 $('.ibox-content').toggleClass('sk-loading');
				 form.submit();
				}
			});
		 $('.chosen-select').chosen({width: "100%"});
		 $(".select2").select2();
	});
</script>
<!-- InstanceEndEditable -->
</body>

<!-- InstanceEnd --></html>
<?php sqlsrv_close($conexion);?>