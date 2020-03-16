<?php
	require ('../database/database_1.php');
	
	$cliente_select = $_POST['cliente_select'];
	
	$queryM = "SELECT distinct sucursal FROM clientes2 WHERE descripcion = '$cliente_select' ORDER BY sucursal";
	$resultadoM = mysqli_query($conn, $queryM);
	
	$html= "<option value='0'>Seleccionar Sucursal</option>";
	
	while($rowM = mysqli_fetch_row($resultadoM))
	{
		$html.= "<option value='".$rowM[0]."'>".$rowM[0]."</option>";
	}
	
	echo utf8_encode($html);
?>