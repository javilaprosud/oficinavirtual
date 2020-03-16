<?php
	require ('../database/database_1.php');
	
	$cliente = $_POST['cli'];
	$sucursal = $_POST['sucursal_select'];
	
	$queryM = "SELECT distinct CodigoPago2 FROM clientes_data WHERE descripcion = '$cliente' and  sucursal = '$sucursal' ORDER BY CodigoPago";
	$resultadoM = mysqli_query($conn, $queryM);
	
	$html= "<option value='0'>Seleccione Forma de Pago</option>";
	
	while($rowM = mysqli_fetch_row($resultadoM))
	{
		$html.= "<option value='".$rowM[0]."'>".$rowM[0]."</option>";
	}
	
	echo utf8_encode($html);
?>