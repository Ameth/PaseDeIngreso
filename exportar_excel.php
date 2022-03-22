<?php 
if(isset($_GET['exp'])&&$_GET['exp']!=""&&$_GET['Cons']!=""){
	require_once("includes/conexion.php");
	
	//Exportar Gestiones de cartera
	if($_GET['exp']==1){
		$Cons=base64_decode($_GET['Cons']);
		$SQL=sqlsrv_query($conexion,$Cons);
		//$Num=sqlsrv_has_rows($SQL);
		//echo $Cons;
		//exit();
		
		if($SQL){
			require_once('Classes/PHPExcel.php');
			$objExcel= new PHPExcel();
			$objSheet=$objExcel->setActiveSheetIndex(0);
			$objExcel->
			getProperties()
				->setCreator("Dialnet");
			
			$EstiloTitulo = array(
				'font' => array(
					'bold' => true,
				)
			);
			
			//Colocar estilos
			$objExcel->getActiveSheet()->getStyle('A1')->applyFromArray($EstiloTitulo);
			$objExcel->getActiveSheet()->getStyle('B1')->applyFromArray($EstiloTitulo);
			$objExcel->getActiveSheet()->getStyle('C1')->applyFromArray($EstiloTitulo);
			$objExcel->getActiveSheet()->getStyle('D1')->applyFromArray($EstiloTitulo);
			$objExcel->getActiveSheet()->getStyle('E1')->applyFromArray($EstiloTitulo);
			$objExcel->getActiveSheet()->getStyle('F1')->applyFromArray($EstiloTitulo);
			$objExcel->getActiveSheet()->getStyle('G1')->applyFromArray($EstiloTitulo);
			$objExcel->getActiveSheet()->getStyle('H1')->applyFromArray($EstiloTitulo);
			$objExcel->getActiveSheet()->getStyle('I1')->applyFromArray($EstiloTitulo);
			$objExcel->getActiveSheet()->getStyle('J1')->applyFromArray($EstiloTitulo);
			$objExcel->getActiveSheet()->getStyle('K1')->applyFromArray($EstiloTitulo);
			$objExcel->getActiveSheet()->getStyle('L1')->applyFromArray($EstiloTitulo);
			$objExcel->getActiveSheet()->getStyle('M1')->applyFromArray($EstiloTitulo);
			
			//Ancho automatico
			/*$objExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);*/
			
			//Titulo de la hoja
			$objExcel->getActiveSheet()->setTitle('Gestiones de cartera');
			
			$objExcel->setActiveSheetIndex(0)
					 ->setCellValue('A1','Codigo de cliente')
					 ->setCellValue('B1','Nombre cliente')
					 ->setCellValue('C1','Tipo de gestion')
					 ->setCellValue('D1','Destino')
					 ->setCellValue('E1','Evento')
				     ->setCellValue('F1','Dirigido')
					 ->setCellValue('G1','Resultado gestion')
				     ->setCellValue('H1','Fecha compromiso')
					 ->setCellValue('I1','Comentarios')
					 ->setCellValue('J1','CausaNoPago')
					 ->setCellValue('K1','Acuerdo de pago')
					 ->setCellValue('L1','Fecha gestion')
					 ->setCellValue('M1','Nombre usuario');
			
			$i=2;
			while($registros=sqlsrv_fetch_array($SQL)){				
				$objSheet->setCellValue('A'.$i,$registros['CardCode']);
				$objSheet->setCellValue('B'.$i,$registros['NombreCliente']);
				$objSheet->setCellValue('C'.$i,$registros['TipoGestion']);
				$objSheet->setCellValue('D'.$i,$registros['Destino']);
				$objSheet->setCellValue('E'.$i,$registros['NombreEvento']);
				$objSheet->setCellValue('F'.$i,$registros['NombreDirigido']);
				$objSheet->setCellValue('G'.$i,$registros['ResultadoGestion']);				
				if($registros['FechaCompromiso']!=""){
					$objSheet->setCellValue('H'.$i,$registros['FechaCompromiso']->format('Y-m-d'));
				}else{
					$objSheet->setCellValue('H'.$i,'');
				}
				$objSheet->setCellValue('I'.$i,$registros['Comentarios']);
				$objSheet->setCellValue('J'.$i,$registros['CausaNoPago']);
				if($registros['AcuerdoPago']=="0"){
					$objSheet->setCellValue('K'.$i,'NO');
				}else{
					$objSheet->setCellValue('K'.$i,'SI');
				}
				$objSheet->setCellValue('L'.$i,$registros['FechaRegistro']->format('Y-m-d H:i:s'));
				$objSheet->setCellValue('M'.$i,$registros['NombreUsuario']);
				
				$i++;
			}
		}
		
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Gestiones_Cartera.xlsx"');
		header('Cache-Control: max-age=0');

		$objWriter=PHPExcel_IOFactory::createWriter($objExcel,'Excel2007');
		$objWriter->save('php://output');
		exit;
	}
	
	//Exportar formularios de hallazgos
	if($_GET['exp']==2){
		$Cons=base64_decode($_GET['Cons']);
		$SQL=sqlsrv_query($conexion,$Cons);
		//$Num=sqlsrv_has_rows($SQL);
		//echo $Cons;
		//exit();
		
		if($SQL){
			require_once('Classes/PHPExcel.php');
			$objExcel= new PHPExcel();
			$objSheet=$objExcel->setActiveSheetIndex(0);
			$objExcel->
			getProperties()
				->setCreator("COPLA GROUP SAS");
			
			$EstiloTitulo = array(
				'font' => array(
					'bold' => true,
				)
			);
			
			//Colocar estilos
			$objExcel->getActiveSheet()->getStyle('A1')->applyFromArray($EstiloTitulo);
			$objExcel->getActiveSheet()->getStyle('B1')->applyFromArray($EstiloTitulo);
			$objExcel->getActiveSheet()->getStyle('C1')->applyFromArray($EstiloTitulo);
			$objExcel->getActiveSheet()->getStyle('D1')->applyFromArray($EstiloTitulo);
			$objExcel->getActiveSheet()->getStyle('E1')->applyFromArray($EstiloTitulo);
			$objExcel->getActiveSheet()->getStyle('F1')->applyFromArray($EstiloTitulo);
			$objExcel->getActiveSheet()->getStyle('G1')->applyFromArray($EstiloTitulo);
			$objExcel->getActiveSheet()->getStyle('H1')->applyFromArray($EstiloTitulo);
			$objExcel->getActiveSheet()->getStyle('I1')->applyFromArray($EstiloTitulo);
			$objExcel->getActiveSheet()->getStyle('J1')->applyFromArray($EstiloTitulo);
			
			//Ancho automatico
			$objExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
			
			//Titulo de la hoja
			$objExcel->getActiveSheet()->setTitle('Panorama de riesgos');
			
			$objExcel->setActiveSheetIndex(0)
					 ->setCellValue('A1','ID')
					 ->setCellValue('B1','Tecnico')
					 ->setCellValue('C1','Tipo visita')
					 ->setCellValue('D1','Cliente')
					 ->setCellValue('E1','Sucursal')
				     ->setCellValue('F1','Area')
					 ->setCellValue('G1','Hallazgo')
				     ->setCellValue('H1','Recomendacion')
					 ->setCellValue('I1','Fecha creacion')
					 ->setCellValue('J1','Estado');
			
			$i=2;
			while($registros=sqlsrv_fetch_array($SQL)){				
				$objSheet->setCellValue('A'.$i,$registros['ID_Frm']);
				$objSheet->setCellValue('B'.$i,$registros['NombreEmpleado']);
				$objSheet->setCellValue('C'.$i,$registros['DeTipoVisita']);
				$objSheet->setCellValue('D'.$i,$registros['NombreCliente']);
				$objSheet->setCellValue('E'.$i,$registros['NombreSucursal']);
				$objSheet->setCellValue('F'.$i,$registros['DeArea']);
				$objSheet->setCellValue('G'.$i,$registros['Hallazgo']);				
				$objSheet->setCellValue('H'.$i,$registros['Recomendaciones']);	
				if($registros['FechaCreacion']!=""){
					$objSheet->setCellValue('I'.$i,$registros['FechaCreacion']->format('Y-m-d H:i'));
				}else{
					$objSheet->setCellValue('I'.$i,'');
				}
				$objSheet->setCellValue('J'.$i,$registros['NombreEstado']);				
				$i++;
			}
		}
		
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="PanoramaRiesgos.xlsx"');
		header('Cache-Control: max-age=0');

		$objWriter=PHPExcel_IOFactory::createWriter($objExcel,'Excel2007');
		$objWriter->save('php://output');
		exit;
	}
	
	sqlsrv_close ($conexion);
}
?>