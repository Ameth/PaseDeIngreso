<?php require_once("includes/conexion.php");
PermitirAcceso(401);
$sw=0;//Verificar que hay datos
$And=0;//Agregar mas filtros a la busqueda

$dir_new=CrearObtenerDirAnx("firmas");

if(isset($_GET['id'])&&($_GET['id']!="")){
	$IDCap=base64_decode($_GET['id']);
}
$SQLCap=Seleccionar("uvw_tbl_Capacitaciones","*","IDCapacitacion='".$IDCap."'");
$rowCap=sqlsrv_fetch_array($SQLCap);

$SQL=Seleccionar("uvw_tbl_AsistenciaCapacitacion","*","IDCapacitacion='".$IDCap."'");

?>
<!DOCTYPE html>
<html><!-- InstanceBegin template="/Templates/PlantillaPrincipal.dwt.php" codeOutsideHTMLIsLocked="false" -->

<head>
<?php include_once("includes/cabecera.php"); ?>
<!-- InstanceBeginEditable name="doctitle" -->
<title>Asistentes a capacitación | <?php echo NOMBRE_PORTAL;?></title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->

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
                    <h2>Asistentes a capacitación</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="index1.php">Inicio</a>
                        </li>
                        <li>
                            <a href="#">Capacitaciones</a>
                        </li>
                        <li class="active">
                            <strong>Asistentes a capacitación</strong>
                        </li>
                    </ol>
                </div>
            </div>           
        <div class="wrapper wrapper-content">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox-content">
					 <?php include("includes/spinner.php"); ?>
					<form action="gestionar_capacitaciones.php" method="get" id="formBuscar" class="form-horizontal">
						<div class="form-group">
							<div class="col-lg-12">
								<a href="gestionar_capacitaciones.php" class="btn btn-default"><i class="fa fa-arrow-circle-o-left"></i> Regresar</a>
							</div>
						</div>
						<div class="form-group">
							<div class="col-xs-12">
								<div class="alert alert-info">
									<h3><strong>Titulo:</strong> <?php echo $rowCap['TituloCapacitacion'];?></h3>
									<h3><strong>Lugar:</strong> <?php echo $rowCap['Lugar'];?></h3>
								</div>		
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
         <br>
          <div class="row">
           <div class="col-xs-12">
			<div class="ibox-content">
				 <?php include("includes/spinner.php"); ?>
           <div class="table-responsive">
			 <table class="table table-striped table-bordered table-hover dataTables-example" >
				<thead>
				<tr>
					<th>Nombre</th>
					<th>Cédula</th>
					<th>Fecha asistencia</th>
					<th>Firma</th>
				</tr>
				</thead>
				<tbody>
				<?php while($row=sqlsrv_fetch_array($SQL)){?>
				<tr>
					<td><?php echo $row['NombreUsuario'];?></td>
					<td><?php echo $row['Cedula'];?></td>
					<td><?php echo $row['FechaRegistro']->format('Y-m-d H:i');?></td>
					<td><img src="<?php echo $dir_new.$row['Firma'];?>" width="100" height="50">
					</td>
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
        <?php include_once("includes/footer.php"); ?>

    </div>
</div>
<?php include_once("includes/pie.php"); ?>
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
			$(".select2").select2();
            $('.dataTables-example').DataTable({
                pageLength: 25,
				order: [[ 0, "desc" ]],
                dom: '<"html5buttons"B>lTfgitp',
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