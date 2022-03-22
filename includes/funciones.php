<?php 

if(file_exists("includes/conect_srv.php")){
	require_once("includes/conect_srv.php");
}else{
	require_once("conect_srv.php");
}

/*if(file_exists("includes/conect_odbc.php")){
	require_once("includes/conect_odbc.php");
}else{
	require_once("conect_odbc.php");
}*/

function PermitirAcceso($Permiso){//Para evitar acceder a la pagina
	global $conexion;
	$PaginaError="404.php";
	$Consulta="Select 1 From uvw_tbl_PermisosPerfiles Where ID_Permiso='".$Permiso."' and IDPerfilUsuario='".$_SESSION['Perfil']."'";
	$SQL=sqlsrv_query($conexion,$Consulta,array(),array( "Scrollable" => 'Buffered' ));
	$Num=sqlsrv_num_rows($SQL);
	if($Num==1){
		return true;
	}else{
		header("Location:".$PaginaError);
	}
}

function PermitirFuncion($Permiso){//Para evitar acceder a una opcion en particular
	global $conexion;
	$Consulta="Select 1 From uvw_tbl_PermisosPerfiles Where ID_Permiso='".$Permiso."' and IDPerfilUsuario='".$_SESSION['Perfil']."'";
	$SQL=sqlsrv_query($conexion,$Consulta,array(),array( "Scrollable" => 'Buffered' ));
	$Num=sqlsrv_num_rows($SQL);
	if($Num==1){
		return true;
	}else{
		return false;
	}
}

function InsertarLog($Type, $Code, $Consulta){
	global $conexion;
	if($Type==1){$Type="Error";}else{$Type="Success";}
	$Consulta=str_replace("'","''",$Consulta);
	if(!isset($_SESSION['CodUser'])){
		$User=0;
	}else{
		$User=$_SESSION['CodUser'];
	}
	$InsertLog="Insert Into tbl_Log Values (GETDATE(),'".$User."','".$Type."','".$Code."','".$Consulta."')";
	//echo $InsertLog;
	//exit();
	if(!sqlsrv_query($conexion,$InsertLog)){
		echo "Error al insertar Log ".$Code;
		echo "<br>";
		echo $InsertLog;
		exit();
	}
}

function ConsultarPago($DocEntry, $CardCode){
	global $conexion;
	$Con="EXEC usp_ConsultarPagoFactura '".$DocEntry."', '".$CardCode."'";
	$SQL=sqlsrv_query($conexion,$Con);
	$row=sqlsrv_fetch_array($SQL);
	return $row;
}

function DiasTranscurridos($FechaInicial,$FechaFinal){//Calcular dias transcurridos entre dos fechas
	$Dias=(strtotime($FechaInicial)-strtotime($FechaFinal))/86400;
	$Result=array();
	//$Dias=abs($Dias); 
	$Dias=floor($Dias);
	if(($Dias>=-2)&&($Dias<0)){//Establecer clase de colores del texto dependiendo de los dias vencidos
		$Result[0]="text-warning";
	}elseif($Dias>0){
		$Result[0]="text-danger";
	}else{
		$Result[0]="text-primary";
	}
	$Result[1]=$Dias;
	
	return $Result;
}

function FormatoFecha($Fecha,$Hora=''){//Dar formato a la fecha para insertar en BD
	$FechaResult="";
	$FechaResult=str_replace("-","",$Fecha);
	if($Hora!=""){
		$FechaResult=$FechaResult." ".$Hora;
	}	
	return $FechaResult;
}

function Seleccionar($pVista, $pCampos, $pWhere='', $pOrderBy='', $pOrderType='', $pType=1, $pDebugMode=0){//Seleccionar datos de una tabla
	if($pType==1){//Consulta a SQL SERVER
		global $conexion;
		$Consulta="EXEC usp_ConsultarTablasSAP '".$pVista."', '".$pCampos."', '".str_replace("'","''",$pWhere)."', '".$pOrderBy."', '".$pOrderType."'";
		if($pDebugMode==1){
			echo $Consulta."<br>";
			exit();
		}
		$SQL=sqlsrv_query($conexion,$Consulta,array(),array( "Scrollable" => 'Buffered' ));
		return $SQL;
	}elseif($pType==2){//Consulta a SAP HANA
		global $conexion_odbc;
		global $databaseHN;
		$Consulta="CALL ".$databaseHN.".USP_NDG_CONSULTAR_TABLAS_SAP('NDG_ONE_".$pVista."','".str_replace(']','"',str_replace('[','"',$pCampos))."','".str_replace(']','"',str_replace('[','"',str_replace("'","''",$pWhere)))."','".str_replace(']','"',str_replace('[','"',$pOrderBy))."','".$pOrderType."')";
		if($pDebugMode==1){
			echo $Consulta."<br>";
			exit();
		}
		$SQL=odbc_exec($conexion_odbc,$Consulta);
		return $SQL;
	}elseif($pType==3){//Consulta a MySQL (MariaDB)
		global $conexion_mysql;
		$Consulta="CALL sp_ConsultarTablas ('".$pVista."', '".$pCampos."', '".str_replace("'","''",$pWhere)."', '".$pOrderBy."', '".$pOrderType."');";
		if($pDebugMode==1){
			echo $Consulta."<br>";
			exit();
		}
		$SQL=mysqli_query($conexion_mysql,$Consulta);
		mysqli_next_result($conexion_mysql);
		return $SQL;
	}
}

function ReturnCons($pVista, $pCampos, $pWhere='', $pOrderBy='', $pOrderType='', $pType=1){//Devolver la consulta generada
	if($pType==1){//Consulta a SQL SERVER
		$Consulta="EXEC sp_ConsultarTablasSAP '".$pVista."', '".$pCampos."', '".str_replace("'","''",$pWhere)."', '".$pOrderBy."', '".$pOrderType."'";
		return $Consulta;
	}elseif($pType==2){// Consulta a SAP HANA
		$Consulta="CALL ".$databaseHN.".USP_NDG_CONSULTAR_TABLAS_SAP('NDG_ONE_".$pVista."','".str_replace(']','"',str_replace('[','"',$pCampos))."','".str_replace(']','"',str_replace('[','"',str_replace("'","''",$pWhere)))."','".str_replace(']','"',str_replace('[','"',$pOrderBy))."','".$pOrderType."')";
		return $Consulta;
	}
}

function sql_fetch_array($pSQL, $pType=1){//fetch_array SQL or HANNA
	if($pType==1){//Consulta a SQL SERVER
		global $conexion;
		$row=sqlsrv_fetch_array($pSQL);
		return $row;
	}elseif($pType==2){// Consulta a SAP HANA
		global $conexion_odbc;
		global $databaseHN;
		$row=odbc_fetch_array($pSQL);
		return $row;
	}
}

function sql_num_rows($pSQL, $pType=1){//fetch_array SQL or HANNA
	if($pType==1){//Consulta a SQL SERVER
		global $conexion;
		$Num=sqlsrv_num_rows($pSQL);
		return $Num;
	}elseif($pType==2){// Consulta a SAP HANA
		global $conexion_odbc;
		global $databaseHN;
		$Num=odbc_num_rows($pSQL);
		return $Num;
	}
}

function EjecutarSP($pNameSP, $pParametros="", $pIdReg=0, $pType=1, $pDebugMode=0){//Ejecutar un SP en la BD
	if($pType==1){//Consulta a SQL SERVER
		global $conexion;
		$Param="";
		if(is_array($pParametros)){
			$Param=implode(',',$pParametros);
		}elseif($pParametros!=""){
			$Param="'".$pParametros."'";
		}
		$Consulta="EXEC ".$pNameSP." ".$Param;
		if($pDebugMode==1){
			echo $Consulta."<br>";
			exit();
		}
		$SQL=sqlsrv_query($conexion,$Consulta,array(),array( "Scrollable" => 'Buffered' ));
		if($SQL){
			InsertarLog(2, $pIdReg, $Consulta);
		}else{
			InsertarLog(1, $pIdReg, $Consulta);
		}
		return $SQL;
	}elseif($pType==2){// Consulta a SAP HANA
		global $conexion_odbc;
		global $databaseHN;
		$Param="";
		if(is_array($pParametros)){
			$Param=implode(',',$pParametros);
		}elseif($pParametros!=""){
			$Param="'".$pParametros."'";
		}		
		if($Param!=""){
			$Param="(".$Param.")";
		}
		$Consulta='CALL "'.$databaseHN.'"."'.$pNameSP.'"'.$Param;
		if($pDebugMode==1){
			echo $Consulta."<br>";
			exit();
		}
		$SQL=odbc_exec($conexion_odbc,$Consulta);
		if($SQL){
			InsertarLog(2, $pIdReg, $Consulta);
		}else{
			InsertarLog(1, $pIdReg, $Consulta);
		}
		return $SQL;
	}elseif($pType==3){//Consulta a MySQL (MariaDB)
		global $conexion_mysql;
		$Param="";
		if(is_array($pParametros)){
			$Param=implode(',',$pParametros);
		}elseif($pParametros!=""){
			$Param="'".$pParametros."'";
		}		
		if($Param!=""){
			$Param="(".$Param.");";
		}
		$Consulta='CALL '.$pNameSP.' '.$Param;
		if($pDebugMode==1){
			echo $Consulta."<br>";
			exit();
		}
		$SQL=mysqli_query($conexion_mysql,$Consulta);
		mysqli_next_result($conexion_mysql);
		if($SQL){
			InsertarLog(2, $pIdReg, $Consulta);
		}else{
			InsertarLog(1, $pIdReg, $Consulta);
		}
		return $SQL;
	}
}

function EliminaMsg(){//Eliminar los mensajes de confirmación
	/*$EliminaMsg=array("a=".base64_encode("OK_ActAdd"),"a=".base64_encode("OK_UpdAdd"),"a=".base64_encode("OK_OVenAdd"),"a=".base64_encode("OK_DelAct"),"a=".base64_encode("OK_OpenAct"));//Eliminar mensajes
	
	if(isset($_GET['return'])){
		$_GET['return']=str_replace($EliminaMsg,"",base64_decode($_GET['return']));
	}
	if(isset($_GET['return'])){
		$return=base64_decode($_GET['pag'])."?".$_GET['return'];
	}else{
		$return="gestionar_actividades.php";
	}*/
}

function ObtenerVariable($Variable){//Obtener valor de variable global
	global $conexion;
	$SQL=Seleccionar('uvw_tbl_VariablesGlobales','Valor',"NombreVariable='".$Variable."'");
	$row=sqlsrv_fetch_array($SQL);
	//$Num=sqlsrv_num_rows($SQL);
	return $row['Valor'];
}

function EliminarTemporal($carpeta){//Eliminar los archivos de la carpeta temporal
    foreach(glob($carpeta . "/*") as $archivos_carpeta){
		//echo $archivos_carpeta;
        if (is_dir($archivos_carpeta)){
            EliminarTemporal($archivos_carpeta);
        }
        else{
            unlink($archivos_carpeta);
        }
    }
    rmdir($carpeta);
}

function LimpiarDirTemp(){//Limpiar la carpeta temporal antes de cargar nuevos anexos
	$temp="tmp";
	$route= $temp."/".$_SESSION['CodUser']."/";
	if(file_exists($route)){
		EliminarTemporal($route);
		mkdir($route,0777, true);
	}else{
		mkdir($route,0777, true);
	}
}

function CrearObtenerDirTemp(){//Crear y retornar la carpeta de anexos temporales
	$temp="tmp";
	$route=$temp."/".$_SESSION['CodUser']."/";
	if(!file_exists($route)){
		mkdir($route,0777, true);
	}
	return $route;
}

function CrearObtenerDirAnx($pCarpetaAnexo){//Crear y retornar la carpeta de anexos locales
	$carp_archivos="archivos";
	$carp_anexos=$pCarpetaAnexo;
	$dir_new=$carp_archivos."/".$carp_anexos."/";
	if(!file_exists($dir_new)){
		mkdir($dir_new,0777, true);
	}
	return $dir_new;
}

function LimpiarDirTempFirma(){//Limpiar la carpeta temporal antes de cargar nuevos anexos
	$temp="tmp_sig";
	$route= $temp."/".$_SESSION['CodUser']."/";
	if(file_exists($route)){
		EliminarTemporal($route);
		mkdir($route,0777, true);
	}else{
		mkdir($route,0777, true);
	}
}

function CrearObtenerDirTempFirma(){//Crear y retornar la carpeta de firmas temporales
	$temp="tmp_sig";
	$route=$temp."/".$_SESSION['CodUser']."/";
	if(!file_exists($route)){
		mkdir($route,0777, true);
	}
	return $route;
}

function ObtenerDirAttach(){//Obtener la ruta de la direccion de anexos de SAP B1, parcheada para Windows o Linux
	global $conexion;
	$Ruta=array();
	$Ruta[0]="";
	
	//Selecciono los datos del archivo
	$SQLRutaAttachSAP=Seleccionar('uvw_Sap_tbl_Empresa','AttachPath');
	$RutaAttachSAP=sqlsrv_fetch_array($SQLRutaAttachSAP);
	
	if(SO=="Linux"){
		
		/******* LINUX *******/
		$RutaAttachSAP[0]=str_replace("//","",preg_replace('/\\\/', '/', $RutaAttachSAP[0]));

		//Credenciales
		$Dominio=DOMINIO_WIN;
		$User=USER_WIN;
		$Pass=PASS_WIN;

		$Ruta[0] = "smb://".$Dominio.$User.$Pass.$RutaAttachSAP[0];
		
	}else{
		
		/******* WINDOWS *******/
		$Ruta[0] = $RutaAttachSAP[0];
		
	}
	
	return $Ruta;
}

function EnviarWebServiceSAP($pNombreWS,$pParametros){
	require_once("conect_ws.php");
	$result = $Client->$Metodo($Parametros);
			
	if(is_soap_fault($result)){
		trigger_error("Fallo IntSAPB1: (Codigo: {$result->faultcode}, Mensaje: {$result->faultstring})", E_USER_ERROR);
	}

	$Respuesta=$Client->__getLastResponse();

	$Contenido=new SimpleXMLElement($Respuesta,0,false,"s",true);

	$espaciosDeNombres = $Contenido->getNamespaces(true);
	$Nodos = $Contenido->children($espaciosDeNombres['s']);
	$Nodo=	$Nodos->children($espaciosDeNombres['']);
	$Nodo2=	$Nodo->children($espaciosDeNombres['']);
	//echo $Nodo2[0];
	try{
		$Archivo=json_decode($Nodo2[0],true);
		//$Archivo=explode("#",$Nodo2[0]);
		if($Archivo['ID_Respuesta']=="0"){
			//InsertarLog(1, 0, 'Error al generar el informe');
			throw new Exception('Error al generar el informe. Error de WebServices');		
		}
	}catch (Exception $e){
		echo 'Excepción capturada: ',  $e->getMessage(), "\n";
		InsertarLog(1, 501, 'Excepción capturada: '.$e->getMessage());//501, cod de SAP Download
	}	
	
	try{
		/*$Parametros=array(
			'pIdLlamada' => $row_NewIdLlamada[0]
		);*/
		$Client->$pNombreWS($Parametros);
	}catch (Exception $e) {
		echo 'Excepcion capturada: ',  $e->getMessage(), "\n";
	}
	
	
}

function UltimoDiaMes($pMes, $pAnio=""){//Obtener el ultimo dia del mes
	if($pAnio==""){
		$pAnio=date('Y');
	}
	$month = $pMes; //date('m');
	$year = $pAnio; //date('Y');
	$day = date("d", mktime(0,0,0, $month+1, 0, $year));

	return date('Y-m-d', mktime(0,0,0, $month, $day, $year));
}
 
function PrimerDiaMes($pMes, $pAnio=""){//Obtener el primer dia del mes
	if($pAnio==""){
		$pAnio=date('Y');
	}
	$month = $pMes; //date('m');
	$year = $pAnio; //date('Y');
	return date('Y-m-d', mktime(0,0,0, $month, 1, $year));
}

function IconAttach($TypeFile){//Colocar un icono en los archivos anexos dependiendo de la extension
	switch (strtolower($TypeFile)){
		case "pdf":
			$Icon="fa fa-file-pdf-o";
			break;
		case "png":
			$Icon="fa fa-file-image-o";
			break;
		case "jpg":
			$Icon="fa fa-file-image-o";
			break;
		case "xls":
			$Icon="fa fa-file-excel-o";
			break;
		case "xlsx":
			$Icon="fa fa-file-excel-o";
			break;
		case "doc":
			$Icon="fa fa-file-word-o";
			break;
		case "docx":
			$Icon="fa fa-file-word-o";
			break;
		case "zip":
			$Icon="fa fa-file-zip-o";
			break;
		case "rar":
			$Icon="fa fa-file-zip-o";
			break;			
		case "txt":
			$Icon="fa fa-file-text-o";
			break;
		default:
			$Icon="fa fa-file-o";	
	}
	
	return $Icon;
}

function ContarSucursalesCliente($CardCode, $ID_Usuario){//Contar cuantas sucursales tiene asignados el usuario
	global $conexion;
	$Con="Select Count(ID_Usuario) as Cant From uvw_tbl_SucursalesClienteUsuario Where CardCode='".$CardCode."' And ID_Usuario='".$ID_Usuario."'";
	$SQL=sqlsrv_query($conexion,$Con);
	$row=sqlsrv_fetch_array($SQL);
	return $row['Cant'];
}

function ConsultarNotasActividad($ID_Actividad){//Consultar si la actividad tiene notas o no
	global $conexion;
	$Con="Select NotasActividad From uvw_Sap_tbl_Actividades Where ID_Actividad='".$ID_Actividad."'";
	$SQL=sqlsrv_query($conexion,$Con);
	$row=sqlsrv_fetch_array($SQL);
	if($row['NotasActividad']==""){
		return "NO";
	}else{
		return "SI";
	}
}

function ConsultarDescargaArchivo($ID_Archivo){//Verificar si un archivo ya fue descargado por el usuario actual
	global $conexion;
	$ConsDown="EXEC sp_tbl_DescargaArchivos '".$_SESSION['CodUser']."','".$ID_Archivo."',1";
	$SQLDown=sqlsrv_query($conexion,$ConsDown);
	$rowDown=sqlsrv_fetch_array($SQLDown);
	return $rowDown['Result'];
}

function ContarClienteUsuario($ID_Usuario){//Contar cuantas sucursales tiene asignados el usuario
	global $conexion;
	$Con="Select Count(ID_Usuario) as Cant From uvw_tbl_ClienteUsuario Where ID_Usuario='".$ID_Usuario."'";
	$SQL=sqlsrv_query($conexion,$Con);
	$row=sqlsrv_fetch_array($SQL);
	return $row['Cant'];
}

function SumarFacturasPendientes($CodigoCliente){//Sumar el valor total de las facturas pendientes de un cliente
	global $conexion;
	$Con="Select SUM(SaldoDocumento) AS Total From uvw_Sap_tbl_FacturasPendientes Where ID_CodigoCliente='".$CodigoCliente."'";
	$SQL=sqlsrv_query($conexion,$Con);
	$row=sqlsrv_fetch_array($SQL);
	return $row['Total'];
}

function FormatoNombreArchivo($NombreArchivo){//Darle formato al nombre del archivo, quitando los "_"
	//$NombreArchivo=utf8_decode($NombreArchivo);
	//Sacar la extension del archivo
	$Ext = end(explode('.',$NombreArchivo));    
	//Sacar el nombre sin la extension
	$OnlyName = substr($NombreArchivo,0,strlen($NombreArchivo)-(strlen($Ext)+1)); 
	$NuevoNombre=substr(str_replace("_"," ",$OnlyName),0,-12).".".$Ext;	
	return $NuevoNombre;	
}

function NormalizarNombreArchivo($NombreArchivo){
    $originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
    $modificadas = 'AAAAAAACEEEEIIIIDNOOOOOOUUUUYbsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
    $NombreArchivo = utf8_decode($NombreArchivo);
    $NombreArchivo = strtr($NombreArchivo, utf8_decode($originales), $modificadas);
    //$NombreArchivo = strtolower($NombreArchivo);
    return utf8_encode($NombreArchivo);
}

function ValidarEstadoArchivoCargue($NombreCliente, $NombreCategoria, $Sucursal, $Archivo){//Validar la información de cargue
	global $conexion;
	$Error=array();
	if($NombreCliente==""){
		$Error[0][0]=1;
		$Error[0][1]="No existe el cliente";
	}elseif($Sucursal==""){
		$Error[0][0]=2;
		$Error[0][1]="No existe la sucursal";
	}elseif($NombreCategoria==""){
		$Error[0][0]=3;
		$Error[0][1]="No existe la categoria";
	}elseif($Archivo==""){
		$Error[0][0]=4;
		$Error[0][1]="No se digitó el archivo";
	}elseif(!file_exists("cargue/".$Archivo)){
		$Error[0][0]=5;
		$Error[0][1]="No existe el archivo en la ruta de cargue: ".$Archivo;
	}else{
		$Error[0][0]=0;
		$Error[0][1]="";
	}
	return $Error;
}

function ValidarEstadoProductosCargue($ItemName, $Categoria, $Archivo){//Validar la información de cargue
	global $conexion;
	$Error=array();
	if($ItemName==""){
		$Error[0][0]=1;
		$Error[0][1]="No existe el Item";
	}elseif($Categoria==""){
		$Error[0][0]=2;
		$Error[0][1]="No existe la categoria";
	}elseif($Archivo==""){
		$Error[0][0]=3;
		$Error[0][1]="No se digitó el archivo";
	}elseif(!file_exists("cargue/".$Archivo)){
		$Error[0][0]=4;
		$Error[0][1]="No existe el archivo en la ruta de cargue: ".$Archivo;
	}else{
		$Error[0][0]=0;
		$Error[0][1]="";
	}
	return $Error;
}

function ConsultarFechaDescarga($ID_Archivo){//Consultar la ultima fecha de descarga de un archivo
	global $conexion;
	$Con="SELECT MAX(FechaHora) AS Fecha FROM uvw_tbl_DescargaArchivos WHERE ID_Archivo='".$ID_Archivo."'";
	$SQL=sqlsrv_query($conexion,$Con);
	$row=sqlsrv_fetch_array($SQL);
	return $row['Fecha']->format('Y-m-d H:i:s');
}

function ConsultarUsuarioCargue($ID_Archivo){//Consultar que usuario cargo el archivo para que solo el pueda eliminarlo
	global $conexion;
	$Con="SELECT Usuario FROM uvw_tbl_Archivos WHERE ID_Archivo='".$ID_Archivo."'";
	$SQL=sqlsrv_query($conexion,$Con);
	$row=sqlsrv_fetch_array($SQL);
	if($row['Usuario']==$_SESSION['CodUser']){
		return true;
	}else{
		return false;
	}
}

function ConsultarUsuarioCargueProd($ID_Producto){//Consultar que usuario cargo el archivo para que solo el pueda eliminarlo
	global $conexion;
	$Con="SELECT Usuario FROM uvw_tbl_Productos WHERE ID_Producto='".$ID_Producto."'";
	$SQL=sqlsrv_query($conexion,$Con);
	$row=sqlsrv_fetch_array($SQL);
	if($row['Usuario']==$_SESSION['CodUser']){
		return true;
	}else{
		return false;
	}
}

function FormatUnitBytes($bytes){//Dar formato a los tamaños de archivos
        if ($bytes >= 1073741824){
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }elseif ($bytes >= 1048576){
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }elseif ($bytes >= 1024){
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }elseif ($bytes > 1){
            $bytes = $bytes . ' bytes';
        }elseif ($bytes == 1){
            $bytes = $bytes . ' byte';
        }else{
            $bytes = '0 bytes';
        }
		
        return $bytes;
}

function CalcularCuotasAcuerdo($Fecha,$Cuotas,$Valor){//Calcular las fechas de los acuerdos de pago en gestionar cartera
	$ArrCuotas=array();
	$FechaActual=strtotime($Fecha);
	$FechaActual = strtotime ('+30 day',$FechaActual);
	$j=1;
	$ValorCuota=$Valor/$Cuotas;
	for($i=0;$i<$Cuotas;$i++){
		$FechaMostrar=date('Y-m-d', $FechaActual);
		
		$ArrCuotas[$j][0]=$j;
		$ArrCuotas[$j][1]=$FechaMostrar;
		$ArrCuotas[$j][2]=($j/$Cuotas)*100;
		$ArrCuotas[$j][3]=$ValorCuota;
		
		$nuevafecha = strtotime ('+30 day',$FechaActual);
		$nuevafecha = date ('Y-m-d', $nuevafecha);
		$FechaActual=strtotime($nuevafecha);
		$j++;		
	}
	
	return $ArrCuotas;
}

function QuitarParametrosURL($url,$keys=array()){//Elimina los parámetros suministrador mediante la array $keys de la URL $url
	$url_parts = parse_url($url);
	if(empty($url_parts['query'])) return $url;

	parse_str($url_parts['query'], $result_array);
	foreach ( $keys as $key ) { unset($result_array[$key]); }
	$url_parts['query'] = http_build_query($result_array);
	$url = (isset($url_parts["scheme"])?$url_parts["scheme"]."://":"").
			(isset($url_parts["user"])?$url_parts["user"].":":"").
			(isset($url_parts["pass"])?$url_parts["pass"]."@":"").
			(isset($url_parts["host"])?$url_parts["host"]:"").
			(isset($url_parts["port"])?":".$url_parts["port"]:"").
			(isset($url_parts["path"])?$url_parts["path"]:"").
			(isset($url_parts["query"])?"?".$url_parts["query"]:"").
			(isset($url_parts["fragment"])?"#".$url_parts["fragment"]:"");
	return $url;
}

function EnviarMail($email_destino, $nombre_destino="", $tipo_email=0, $asunto="", $mensaje="", $concopia="", $nombre_cc="", $cliente="", $sucursal="", $categoria="", $comentarios="", $archivo=""){
	global $conexion;
	if(file_exists('../mailer/PHPMailerAutoload.php')){
		require_once('../mailer/PHPMailerAutoload.php');
	}else{
		require_once('mailer/PHPMailerAutoload.php');
	}
	
	$Cons_Mail="Select * From tbl_EmailNotificaciones";
	$SQL_Mail=sqlsrv_query($conexion,$Cons_Mail);
	$row_Mail=sqlsrv_fetch_array($SQL_Mail);
	
	if($row_Mail['Usuario']==""){
		return;
	}
	
	//instancio un objeto de la clase PHPMailer
	$mail = new PHPMailer(); // defaults to using php "mail()"
	$mail->CharSet = "UTF-8";
	$mail->Encoding = "quoted-printable"; 
	//indico a la clase que use SMTP
	$mail->isSMTP();
	$mail->setLanguage('es');
	
	//permite modo debug para ver mensajes de las cosas que van ocurriendo
	//$mail->SMTPDebug = 2;
	//Debo de hacer autenticación SMTP
	if($row_Mail['AutenticacionSMTP']==1){
		$mail->SMTPAuth = true;
	}else{
		$mail->SMTPAuth = false;
	}	
	$mail->SMTPSecure = $row_Mail['TipoConexion'];
	//indico el servidor de Gmail para SMTP
	$mail->Host = $row_Mail['ServidorSMTP'];
	//indico el puerto que usa Gmail
	$mail->Port = $row_Mail['PuertoSMTP'];
	//indico un usuario / clave de un usuario de gmail
	$mail->Username = $row_Mail['Usuario'];
	$mail->Password = base64_decode($row_Mail['Clave']);
	$mail->SetFrom($row_Mail['Usuario'], NOMBRE_PORTAL);
	$mail->AddReplyTo($row_Mail['Usuario'], NOMBRE_PORTAL);
	$mail->IsHTML(true);
	
	//Datos del mensaje
	if($tipo_email==1){//Cargar archivos
		$Cons_Platilla="Select ID_Plantilla, Asunto, Mensaje From uvw_tbl_PlantillaEmail Where ID_TipoNotificacion=1 and Estado=1";
		$SQL_Platilla=sqlsrv_query($conexion,$Cons_Platilla);
		$row_Platilla=sqlsrv_fetch_array($SQL_Platilla);
		if($row_Platilla['ID_Plantilla']==""){
			return;
		}
		$asunto=$row_Platilla['Asunto'];
		$mensaje=$row_Platilla['Mensaje'];
	}elseif($tipo_email==2){//Descargar archivos
		$Cons_Platilla="Select ID_Plantilla, Asunto, Mensaje From uvw_tbl_PlantillaEmail Where ID_TipoNotificacion=2 and Estado=1";
		$SQL_Platilla=sqlsrv_query($conexion,$Cons_Platilla);
		$row_Platilla=sqlsrv_fetch_array($SQL_Platilla);
		if($row_Platilla['ID_Plantilla']==""){
			return;
		}
		$asunto=$row_Platilla['Asunto'];
		$mensaje=$row_Platilla['Mensaje'];
	}
	
	//Verificar si la sucursal tiene habilitado el envío de correos
	if(($cliente!="")&&($sucursal!="")){
		$ConsEnviaCorreo="Select EnviaCorreo From uvw_Sap_tbl_Clientes_Sucursales Where CodigoCliente='".$cliente."' And NombreSucursal='".$sucursal."'";
		$SQL_EnviaCorreo=sqlsrv_query($conexion,$ConsEnviaCorreo);
		$row_EnviaCorreo=sqlsrv_fetch_array($SQL_EnviaCorreo);
		if($row_EnviaCorreo['EnviaCorreo']=="NO"){
			return;
		}
	}
	
	//Reemplazar variables en el mensaje
	
	//Cliente
	if($cliente!=""){//[Nombre_Cliente]
		$Cons_Reemp="Select NombreCliente From uvw_Sap_tbl_Clientes Where CodigoCliente='".$cliente."'";
		$SQL_Reemp=sqlsrv_query($conexion,$Cons_Reemp);
		$row_Reemp=sqlsrv_fetch_array($SQL_Reemp);
		$mensaje=str_replace("[Nombre_Cliente]",$row_Reemp['NombreCliente'],$mensaje);
	}
	//Sucursal
	if($sucursal!=""){//[Nombre_Sucursal]
		$mensaje=str_replace("[Nombre_Sucursal]",$sucursal,$mensaje);
	}
	//Categoria
	if($categoria!=""){//[Nombre_Categoria]
		$Cons_Reemp="Select NombreCategoria From uvw_tbl_Categorias Where ID_Categoria='".$categoria."'";
		$SQL_Reemp=sqlsrv_query($conexion,$Cons_Reemp);
		$row_Reemp=sqlsrv_fetch_array($SQL_Reemp);
		$mensaje=str_replace("[Nombre_Categoria]",$row_Reemp['NombreCategoria'],$mensaje);
	}
	//Comentarios
	if($comentarios!=""){//[Comentarios]
		$mensaje=str_replace("[Comentarios]",$comentarios,$mensaje);
	}
	//Archivo
	if($archivo!=""){//[Nombre_Archivo]
		$mensaje=str_replace("[Nombre_Archivo]",$archivo,$mensaje);
	}
	
	//[Fecha]
	$mensaje=str_replace("[Fecha]",date('Y-m-d'),$mensaje);
	//[Hora]
	$mensaje=str_replace("[Hora]",date('H:i:s'),$mensaje);
	//[Nombre_Usuario]
	if(isset($_SESSION['NomUser'])){
		$mensaje=str_replace("[Nombre_Usuario]",$_SESSION['NomUser'],$mensaje);
	}	
	
	//Nombre portal
	/*if(NOMBRE_PORTAL!=""){//[Nombre_Portal]
		$mensaje=str_replace("[Nombre_Portal]",NOMBRE_PORTAL,$mensaje);
	}*/
	
	//Asignar variables del email
	$mail->Subject = $asunto;
	$mail->MsgHTML($mensaje);
	
	//indico destinatario
	$address = $email_destino;
	$mail->AddAddress($address, $nombre_destino);
	
	//Añadir con copia
	if($concopia!=""){
		$mail->AddCC($concopia, $nombre_cc);
	}
	
	if(!$mail->Send()){
		$InsertLog="Insert Into tbl_Log Values ('".date('Y-m-d H:i:s')."','".$_SESSION['CodUser']."','Error',50,'".$mail->ErrorInfo."')";
		sqlsrv_query($conexion,$InsertLog);
	}/*else{
		$InsertLog="Insert Into tbl_Log Values ('".date('Y-m-d H:i:s')."','".$_SESSION['CodUser']."','Success',50,'Send Email: ".$email_destino."')";
		sqlsrv_query($conexion,$InsertLog);
	}*/
}


?>