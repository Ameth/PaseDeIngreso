<?php require_once("includes/conexion.php");
PermitirAcceso(401);

if(isset($_GET['id'])&&($_GET['id']!="")){
	$IDCap=base64_decode($_GET['id']);
}
if(isset($_GET['tl'])&&($_GET['tl']!="")){//0 Si se está creando. 1 Se se está editando.
	$edit=$_GET['tl'];
}elseif(isset($_POST['tl'])&&($_POST['tl']!="")){
	$edit=$_POST['tl'];
}else{
	$edit=0;
}

if($edit==0){
	$Title="Crear capacitación";
}else{
	$Title="Editar capacitación";
}

if(isset($_POST['P'])&&($_POST['P']!="")){//Insertar registro	
	try{
		if($_POST['tl']==1){//Actualizar
			$IDCap=base64_decode($_POST['ID']);
			$Type=2;
		}else{//Crear
			$IDCap="NULL";
			$Type=1;
		}
		
		//Eliminar
		if($_POST['P']==43){
			$IDCap=base64_decode($_POST['ID']);
			$Type=3;
		}
		
		$ParamInsert=array(
			$IDCap,
			"'".$_POST['TituloCapacitacion']."'",
			"'".$_POST['Lugar']."'",
			"'".FormatoFecha($_POST['FechaInicio'],$_POST['HoraInicio'])."'",
			"'".FormatoFecha($_POST['FechaFin'],$_POST['HoraFin'])."'",
			"'".$_POST['Estado']."'",
			"'".$_SESSION['CodUser']."'",
			$Type
		);
		$SQL_InsUser=EjecutarSP('usp_tbl_Capacitaciones',$ParamInsert,$_POST['P']);
		
		if($SQL_InsUser){
			sqlsrv_close($conexion);
			if($_POST['tl']==0){//Creando Entrega	
				header('Location:'.base64_decode($_POST['return']).'&a='.base64_encode("OK_Cap"));
			}else{//Actualizando Entrega
				if($_POST['P']==43){//Eliminada
					header('Location:'.base64_decode($_POST['return']).'&a='.base64_encode("OK_DelCap"));	
				}else{
					header('Location:'.base64_decode($_POST['return']).'&a='.base64_encode("OK_EditCap"));	
				}
								
			}		
		}else{
			throw new Exception('Ha ocurrido un error al insertar la capacitación');			
			sqlsrv_close($conexion);
			}
	}catch (Exception $e){
		echo 'Excepcion capturada: ',  $e->getMessage(), "\n";
	}
	
}

if($edit==1){//Editar
	
	$SQL=Seleccionar('uvw_tbl_Capacitaciones','*',"IDCapacitacion='".$IDCap."'");
	$row=sqlsrv_fetch_array($SQL);
}
	
//Estados
$SQL_Estados=Seleccionar('uvw_tbl_Estados','*');

?>
<!DOCTYPE html>
<html><!-- InstanceBegin template="/Templates/PlantillaPrincipal.dwt.php" codeOutsideHTMLIsLocked="false" -->

<head>
<?php include_once("includes/cabecera.php"); ?>
<!-- InstanceBeginEditable name="doctitle" -->
<title><?php echo $Title;?> | <?php echo NOMBRE_PORTAL;?></title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<script>
function ValidarHoras(){
	var HInicio = document.getElementById("HoraInicio").value;
	var HFin = document.getElementById("HoraFin").value;
	
	HInicioMinutos = parseInt(HInicio.substr(3,2));
	HInicioHoras = parseInt(HInicio.substr(0,2));

	HFinMinutos = parseInt(HFin.substr(3,2));
	HFinHoras = parseInt(HFin.substr(0,2));

	TranscurridoMinutos = HFinMinutos - HInicioMinutos;
	TranscurridoHoras = HFinHoras - HInicioHoras;

	if (TranscurridoMinutos < 0) {
		TranscurridoHoras--;
		TranscurridoMinutos = 60 + TranscurridoMinutos;
	}
	
	if(TranscurridoHoras < 0){
		swal({
                title: '¡Error!',
                text: 'Tiempo no válido. Ingrese una duración positiva.',
                type: 'error'
            });
		return false;
	}
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
                    <h2><?php echo $Title;?></h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="index1.php">Inicio</a>
                        </li>
                        <li>
                            <a href="#">Capacitaciones</a>
                        </li>
                        <li>
                            <a href="gestionar_capacitaciones.php">Gestionar capacitaciones</a>
                        </li>
                        <li class="active">
                            <strong><?php echo $Title;?></strong>
                        </li>
                    </ol>
                </div>
            </div>
           
         <div class="wrapper wrapper-content">
			<div class="row">
           		<div class="col-lg-12">
					<div class="ibox-content"> 
					<?php include("includes/spinner.php"); ?>
              <form action="capacitacion.php" method="post" class="form-horizontal" id="frmCap">
				  <div class="form-group">
					<label class="col-xs-12"><h3 class="bg-muted p-xs b-r-sm"><i class="fa fa-info-circle"></i> Datos de la capacitación</h3></label>
				  </div>
				<div class="form-group">
					<label class="col-lg-1 control-label">Titulo de capacitación</label>
					<div class="col-lg-6"><textarea name="TituloCapacitacion" rows="2" maxlength="1000" class="form-control" id="TituloCapacitacion" type="text" placeholder="Por ejemplo: Capacitación sobre uso de elementos de EPP"><?php if($edit==1){echo $row['TituloCapacitacion'];}?></textarea></div>
				</div>
				<div class="form-group">
					<label class="col-lg-1 control-label">Lugar</label>
					<div class="col-lg-3">
                    	<input name="Lugar" type="text" required="required" class="form-control" id="Lugar" maxlength="100" value="<?php if($edit==1){echo $row['Lugar'];}?>">
               	  	</div>					
				</div>
				<div class="form-group">
					<label class="col-lg-1 control-label">Fecha inicio</label>
				  	<div class="col-lg-2 input-group date">
                    	 <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input name="FechaInicio" type="text" required="required" class="form-control" id="FechaInicio" value="<?php if(($edit==1)&&($row['FechaInicio'])!=""){echo $row['FechaInicio']->format('Y-m-d');}else{ echo date('Y-m-d');}?>">
               	  	</div>
					<div class="col-lg-2 input-group clockpicker" data-autoclose="true">
						<input name="HoraInicio" id="HoraInicio" type="text" class="form-control" value="<?php if(($edit==1)&&($row['FechaInicio']!="")){echo $row['FechaInicio']->format('H:i');}else{echo date('H:i');}?>" required="required" onChange="ValidarHoras();">
						<span class="input-group-addon">
							<span class="fa fa-clock-o"></span>
						</span>
					</div>				  	
				</div>
				<div class="form-group">
					<label class="col-lg-1 control-label">Fecha fin</label>
				  	<div class="col-lg-2 input-group date">
                    	 <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input name="FechaFin" id="FechaFin" type="text" required="required" class="form-control" value="<?php if(($edit==1)&&($row['FechaFin'])!=""){echo $row['FechaFin']->format('Y-m-d');}else{ echo date('Y-m-d');}?>">
               	  	</div>
					<div class="col-lg-2 input-group clockpicker" data-autoclose="true">
						<?php 
							$nuevahora = strtotime ( '+60 minute' , time() ) ;
							$nuevahora = date ('H:i' , $nuevahora);
						?>
						<input name="HoraFin" id="HoraFin" type="text" class="form-control" value="<?php if(($edit==1)&&($row['FechaInicio']!="")){echo $row['FechaInicio']->format('H:i');}else{echo $nuevahora;}?>" required="required" onChange="ValidarHoras();">
						<span class="input-group-addon">
							<span class="fa fa-clock-o"></span>
						</span>
					</div>					
				</div>
				<div class="form-group">
					<label class="col-lg-1 control-label">Estado</label>
					<div class="col-lg-2">
                    	<select name="Estado" class="form-control" id="Estado">
                          <?php while($row_Estados=sqlsrv_fetch_array($SQL_Estados)){?>
								<option value="<?php echo $row_Estados['IDEstado'];?>" <?php if((isset($row['IDEstado']))&&(strcmp($row_Estados['IDEstado'],$row['IDEstado'])==0)){ echo "selected=\"selected\"";}?>><?php echo $row_Estados['NombreEstado'];?></option>
						  <?php }?>
						</select>
               	  	</div>
				</div>
				<div class="form-group">
					<div class="col-lg-12">
						<?php if($edit==1){?>
						<button class="btn btn-warning" type="submit" id="Crear"><i class="fa fa-refresh"></i> Actualizar capacitación</button>
						<button class="btn btn-danger" form="frmCap" type="submit" id="Eliminar" onClick="EnviarFrm('43');"><i class="fa fa-trash"></i> Eliminar</button>
						<?php }else{?>
						<button class="btn btn-primary" type="submit" id="Crear"><i class="fa fa-check"></i> Crear capacitación</button>
						<?php }?>						
						<a href="gestionar_capacitaciones.php" class="btn btn-outline btn-default"><i class="fa fa-arrow-circle-o-left"></i> Regresar</a>
					</div>
				</div>
				<?php 			
					if(isset($_GET['return'])){
						$return=base64_decode($_GET['pag'])."?".$_GET['return'];
					}else{
						$return="gestionar_capacitaciones.php?";
					}				  
				  	$return=QuitarParametrosURL($return,array("a"));?>
				  
				<input type="hidden" id="P" name="P" value="40" />
				<input type="hidden" id="ID" name="ID" value="<?php if($edit==1){echo base64_encode($IDCap);}?>" />
				<input type="hidden" id="tl" name="tl" value="<?php echo $edit;?>" />
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
		  $('#FechaInicio').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true,
			 	todayHighlight: true,
				format: 'yyyy-mm-dd'
            });
		 $('#FechaFin').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true,
			 	todayHighlight: true,
				format: 'yyyy-mm-dd'
            });
		  $('.clockpicker').clockpicker();	
	});
	
function EnviarFrm(P){
	var vP=document.getElementById('P');
	vP.value=P;
}
</script>
<!-- InstanceEndEditable -->
</body>

<!-- InstanceEnd --></html>
<?php sqlsrv_close($conexion);?>