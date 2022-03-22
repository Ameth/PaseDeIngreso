<?php require_once("includes/conexion.php");
PermitirAcceso(401);
$sw=0;//Verificar que hay datos
$And=0;//Agregar mas filtros a la busqueda

$SQL=Seleccionar("uvw_tbl_Capacitaciones","*");

?>
<!DOCTYPE html>
<html><!-- InstanceBegin template="/Templates/PlantillaPrincipal.dwt.php" codeOutsideHTMLIsLocked="false" -->

<head>
<?php include_once("includes/cabecera.php"); ?>
<!-- InstanceBeginEditable name="doctitle" -->
<title>Gestionar capacitaciones | <?php echo NOMBRE_PORTAL;?></title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<?php 
if(isset($_GET['a'])&&($_GET['a']==base64_encode("OK_Cap"))){
	echo "<script>
		$(document).ready(function() {
			swal({
                title: '¡Listo!',
                text: 'La capacitación ha sido agregada exitosamente.',
                type: 'success'
            });
		});		
		</script>";
}
if(isset($_GET['a'])&&($_GET['a']==base64_encode("OK_EditCap"))){
	echo "<script>
		$(document).ready(function() {
			swal({
                title: '¡Listo!',
                text: 'La capacitación ha sido editada exitosamente.',
                type: 'success'
            });
		});		
		</script>";
}
if(isset($_GET['a'])&&($_GET['a']==base64_encode("OK_DelCap"))){
	echo "<script>
		$(document).ready(function() {
			swal({
                title: '¡Listo!',
                text: 'La capacitación ha sido eliminada exitosamente.',
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
                <div class="col-sm-8">
                    <h2>Gestionar capacitaciones</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="index1.php">Inicio</a>
                        </li>
                        <li>
                            <a href="#">Capacitaciones</a>
                        </li>
                        <li class="active">
                            <strong>Gestionar capacitaciones</strong>
                        </li>
                    </ol>
                </div>
                 <div class="col-sm-4">
                    <div class="title-action">
                        <a href="capacitacion.php" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Agregar nueva capacitación</a>
                    </div>
                </div>
            </div>           
        <div class="wrapper wrapper-content">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox-content">
					 <?php include("includes/spinner.php"); ?>
					<form action="gestionar_capacitaciones.php" method="get" id="formBuscar" class="form-horizontal">
						
					</form>
				</div>
			</div>
		</div>
         <br>
          <div class="row">
           <div class="col-lg-12">
			<div class="ibox-content">
				 <?php include("includes/spinner.php"); ?>
           <div class="table-responsive">
			 <table class="table table-striped table-bordered table-hover dataTables-example" >
				<thead>
				<tr>
					<th>ID</th>
					<th>Titulo capacitación</th>
					<th>Lugar</th>
					<th>Fecha inicio</th>
					<th>Fecha fin</th>
					<th>Estado</th>
					<th>Creado por</th>
					<th>Acciones</th>
				</tr>
				</thead>
				<tbody>
				<?php while($row=sqlsrv_fetch_array($SQL)){?>
				<tr>
					<td><?php echo $row['IDCapacitacion'];?></td>
					<td><?php echo $row['TituloCapacitacion'];?></td>
					<td><?php echo $row['Lugar'];?></td>
					<td><?php echo $row['FechaInicio']->format('Y-m-d H:i');?></td>
					<td><?php echo $row['FechaFin']->format('Y-m-d H:i');?></td>
					<td <?php if($row['IDEstado']==1){echo "class='text-navy'";}else{echo "class='text-danger'";}?>><?php echo $row['NombreEstado'];?></td>
					<td><?php echo $row['NombreUsuario'];?></td>
                    <td><a href="capacitacion.php?id=<?php echo base64_encode($row['IDCapacitacion']);?>&tl=1" class="btn btn-info btn-circle" title="Editar"><i class="fa fa-edit"></i></a><a href="asistentes_capacitacion.php?id=<?php echo base64_encode($row['IDCapacitacion']);?>&tl=1" class="btn btn-success btn-circle" title="Ver asistentes"><i class="fa fa-group"></i></a></td>
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