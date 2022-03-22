<?php require_once("includes/conexion.php");
PermitirAcceso(301);

$sw=0;
$Fecha=date('Ymd');
$TipoUsuario="";

//Tipo usuario
//$SQL_TipoUsuario=Seleccionar('uvw_tbl_TipoUsuario','*');

//Filtros
$Filtro="";//Filtro
if(isset($_GET['Fecha'])&&$_GET['Fecha']!=""){
	$Fecha=FormatoFecha($_GET['Fecha']);
}
//if(isset($_GET['TipoUsuario'])&&$_GET['TipoUsuario']!=""){
//	$TipoUsuario=$_GET['TipoUsuario'];
//}

$Parametros=array(
	"'".$Fecha."'"
);

$SQL=EjecutarSP('usp_InformeVisitantes',$Parametros);

//echo $Cons;

?>
<!DOCTYPE html>
<html><!-- InstanceBegin template="/Templates/PlantillaPrincipal.dwt.php" codeOutsideHTMLIsLocked="false" -->

<head>
<?php include("includes/cabecera.php"); ?>
<!-- InstanceBeginEditable name="doctitle" -->
<title>Informe de visitantes | <?php echo NOMBRE_PORTAL;?></title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<?php 
if(isset($_GET['a'])&&($_GET['a']==base64_encode("OK_PubAdd"))){
	echo "<script>
		$(document).ready(function() {
			swal({
                title: '¡Listo!',
                text: 'El publicador ha sido creado exitosamente.',
                type: 'success'
            });
		});		
		</script>";
}
if(isset($_GET['a'])&&($_GET['a']==base64_encode("OK_PubUpd"))){
	echo "<script>
		$(document).ready(function() {
			swal({
                title: '¡Listo!',
                text: 'El publicador ha sido actualizado exitosamente.',
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

    <?php include("includes/menu.php"); ?>

    <div id="page-wrapper" class="gray-bg">
        <?php include("includes/menu_superior.php"); ?>
        <!-- InstanceBeginEditable name="Contenido" -->
        <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-sm-8">
                    <h2>Informe de visitantes</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="index1.php">Inicio</a>
                        </li>
                        <li>
                            <a href="#">Informes</a>
                        </li>
                        <li class="active">
                            <strong>Informe de visitantes</strong>
                        </li>
                    </ol>
                </div>
            </div>
         <div class="wrapper wrapper-content">
             <div class="row">
				<div class="col-lg-12">
			    <div class="ibox-content">
					 <?php include("includes/spinner.php"); ?>
				  <form action="informe_visitantes.php" method="get" id="formBuscar" class="form-horizontal">
					 	<div class="form-group">
							<label class="col-lg-1 control-label">Fecha</label>
							<div class="col-lg-2 input-group date">
								 <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input name="Fecha" type="text" class="form-control" id="Fecha" value="<?php if(isset($_GET['Fecha'])&&($_GET['Fecha']!="")){ echo $_GET['Fecha'];}else{echo date('Y-m-d');}?>" readonly="readonly" placeholder="YYYY-MM-DD">
							</div>
							<?php /*?><label class="col-lg-1 control-label">Tipo usuario</label>
							<div class="col-lg-2">
								<select name="Genero" class="form-control m-b" id="Genero">
									<option value="">(Todos)</option>
								  <?php while($row_Genero=sqlsrv_fetch_array($SQL_Genero)){?>
										<option value="<?php echo $row_Genero['IDGenero'];?>" <?php if((isset($_GET['Genero']))&&(strcmp($row_Genero['IDGenero'],$_GET['Genero'])==0)){ echo "selected=\"selected\"";}?>><?php echo $row_Genero['NombreGenero'];?></option>
								  <?php }?>
								</select>
							</div><?php */?>
							<div class="col-lg-1">
								<button type="submit" class="btn btn-outline btn-success pull-right"><i class="fa fa-search"></i> Buscar</button>
							</div>
						</div>
				 </form>
			</div>
			</div>
		  </div>
         <br>
			 <?php //echo $Cons;?>
          <div class="row">
           <div class="col-lg-12">
			    <div class="ibox-content">
					 <?php include("includes/spinner.php"); ?>
			<div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover dataTables-example" >
                    <thead>
                    <tr>
						<th>Nombre</th>
						<th>Dirección</th>
						<th>Teléfono</th>  
						<th>Temperatura</th>
						<th>Fecha registro</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php while($row=sqlsrv_fetch_array($SQL)){?>
						<tr class="gradeX tooltip-demo">
							<td><?php echo $row['NombreCompleto'];?></td>
							<td><?php echo $row['Direccion'];?></td>
							<td><?php echo $row['Telefono'];?></td>
							<td><?php echo $row['Temperatura'];?></td>
							<td><?php if($row['FechaRegistro']!=""){echo $row['FechaRegistro']->format('Y-m-d H:i');}?></td>							
						</tr>
					<?php }?>
                    </tbody>
                    </table>
              </div>
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
			$("#formBuscar").validate({
			 submitHandler: function(form){
				 $('.ibox-content').toggleClass('sk-loading');
				 form.submit();
				}
			});
			 $(".alkin").on('click', function(){
					$('.ibox-content').toggleClass('sk-loading');
				});
			 $('#Fecha').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true,
				format: 'yyyy-mm-dd'
            });
			
			$('.chosen-select').chosen({width: "100%"});
			
            $('.dataTables-example').DataTable({
                pageLength: 25,
                dom: '<"html5buttons"B>lTfgitp',
				//order: [[ 0, "desc" ]],
				language: {
					"decimal":        "",
					"emptyTable":     "No se encontraron resultados.",
					"info":           "Mostrando _START_ - _END_ de _TOTAL_ registros",
					"infoEmpty":      "Mostrando 0 - 0 de 0 registros",
					"infoFiltered":   "(filtrando de _MAX_ registros)",
					"infoPostFix":    "",
					"thousands":      ",",
					"lengthMenu":     "Mostrar _MENU_ registros",
					"loadingRecords": "Cargando...",
					"processing":     "Procesando...",
					"search":         "Filtrar:",
					"zeroRecords":    "Ningún registro encontrado",
					"paginate": {
						"first":      "Primero",
						"last":       "Último",
						"next":       "Siguiente",
						"previous":   "Anterior"
					},
					"aria": {
						"sortAscending":  ": Activar para ordenar la columna ascendente",
						"sortDescending": ": Activar para ordenar la columna descendente"
					}
				},
                buttons: []

            });

        });

    </script>
<!-- InstanceEndEditable -->
</body>

<!-- InstanceEnd --></html>
<?php sqlsrv_close($conexion);?>