<?php require_once("includes/conexion.php");
PermitirAcceso(402);
$sw=0;//Verificar que hay datos
$And=0;//Agregar mas filtros a la busqueda

$SQL=Seleccionar("uvw_tbl_Capacitaciones","*","IDEstado=1");

?>
<!DOCTYPE html>
<html><!-- InstanceBegin template="/Templates/PlantillaPrincipal.dwt.php" codeOutsideHTMLIsLocked="false" -->

<head>
<?php include_once("includes/cabecera.php"); ?>
<!-- InstanceBeginEditable name="doctitle" -->
<title>Asistir a capacitaciones | <?php echo NOMBRE_PORTAL;?></title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<?php 
if(isset($_GET['a'])&&($_GET['a']==base64_encode("OK_AsisCap"))){
	echo "<script>
		$(document).ready(function() {
			swal({
                title: '¡Listo!',
                text: 'Su asistencia se registró exitosamente.',
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
                    <h2>Asistir a capacitaciones</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="index1.php">Inicio</a>
                        </li>
                        <li>
                            <a href="#">Capacitaciones</a>
                        </li>
                        <li class="active">
                            <strong>Asistir a capacitaciones</strong>
                        </li>
                    </ol>
                </div>
            </div>           
        <div class="wrapper wrapper-content">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox-content">
					 <?php include("includes/spinner.php"); ?>
					<h3 class="text-danger">Para anotar su asistencia a una capacitación o reunión, busque la que necesite y haga clic en el botón <strong>Firmar asistencia</strong></h3>
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
					<th>Firmar</th>
				</tr>
				</thead>
				<tbody>
				<?php while($row=sqlsrv_fetch_array($SQL)){
					$SQLFirma=Seleccionar("uvw_tbl_AsistenciaCapacitacion","*","IDCapacitacion='".$row['IDCapacitacion']."' and IDUsuario='".$_SESSION['CodUser']."'");
					$rowFirma=sqlsrv_fetch_array($SQLFirma);
					?>
				<tr>
					<td><?php echo $row['IDCapacitacion'];?></td>
					<td><?php echo $row['TituloCapacitacion'];?></td>
					<td><?php echo $row['Lugar'];?></td>
					<td><?php echo $row['FechaInicio']->format('Y-m-d H:i');?></td>
					<td><?php echo $row['FechaFin']->format('Y-m-d H:i');?></td>
					<td <?php if(isset($rowFirma['Firma'])&&($rowFirma['Firma']!="")){echo "class='text-navy'";}?>><?php if(isset($rowFirma['Firma'])&&($rowFirma['Firma']!="")){echo "<i class='fa fa-check-circle'></i> FIRMADO";}else{?><a href="asistir_capacitacion_firmar.php?id=<?php echo base64_encode($row['IDCapacitacion']);?>&tl=1" class="btn btn-success btn-rounded" title="Firmar asistencia"><i class="fa fa-edit"></i> Firmar asistencia</a><?php }?></td>
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