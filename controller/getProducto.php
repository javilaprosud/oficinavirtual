<?php
	require ('../database/database_1.php');
	
	$cliente= $_POST['cli'];
	$linea = $_POST['linea_select'];
	
	$queryM = "select distinct concat('[',a.ProdCodigo,'] ',a.ProdNombre) as ProdNombre from vw_productos_precios as a  inner join clientes2 as b on a.ListCodigo = b.lista where b.descripcion = '$cliente' and a.LproNombre = '$linea'";
	$resultadoM = mysqli_query($conn, $queryM);
	
	$html= "<option value='0'>Seleccionar Producto</option>";
	
	while($rowM = mysqli_fetch_row($resultadoM))
	{
		$html.= "<option value='".$rowM[0]."'>".$rowM[0]."</option>";
	}
	
	echo utf8_encode($html);
	?>