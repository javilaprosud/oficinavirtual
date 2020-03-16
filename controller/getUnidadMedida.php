<?php
	require ('../database/database_1.php');
	
	$cliente = $_POST['cli'];
	$sucursal = $_POST['sucursal_select'];
	
	$queryMed = "select * from UniMed where UnimCodigo in ('UNID','CAJA')";
	$resultadoMed = mysqli_query($conn, $queryMed);
	
	$html= "<option value='0'>Seleccione Unidad de Medida</option>";
	
	while($rowM = mysqli_fetch_row($resultadoMed))
	{
		$html.= "<option value='".$rowM[1]."'>".$rowM[1]."</option>";
	}
	
	echo utf8_encode($html);
?>