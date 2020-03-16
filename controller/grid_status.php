<?php
error_reporting(-1);
session_start();
 include "../database/database_1.php";
 include "convertMoneda.php";

  $valida_id = $_SESSION['user_id'];


if(isset($valida_id)) {
 $query = "SELECT concat(nombre,' ',apellido_p) as user_nom FROM users WHERE RUT = '$valida_id' LIMIT 1";
    $results = mysqli_query($conn, $query);

     $user = "";


      $row=mysqli_fetch_row($results);
      $user = $row[0];    


}

/* Database connection end */


// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;


$columns = array( 
// datatable column index  => database column name
	0 => 'MLPnroPedido',
	1 => 'MLPEstado',
    2 => 'LogDocumento',
    3 => 'LogInfo',
    4 => 'LogFecha',
    5 => 'LogEstadoDoc',
    6 => 'LogTipoDoc',
    7 => 'Detalle'

);

// getting total number records without any search
$sql = "select MLPnroPedido, MLPEstado, case when LogDocumento like '%IGRS_P %' then SUBSTRING(LogDocumento, 8, 9) else LogDocumento END AS LogDocumento , LogInfo,  LogFecha,  LogEstadoDoc, case when LogTipoDoc = 'P' then 'PEDIDO' else 'RECEPCION' END AS LogTipoDoc  from vw_ml_pedidos";
$query=mysqli_query($conn, $sql) or die("grid_status.php: get InventoryItems");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

if( !empty($requestData['search']['value']) ) {
	// if there is a search parameter
	$sql = "select MLPnroPedido, MLPEstado,  case when LogDocumento like '%IGRS_P %' then SUBSTRING(LogDocumento, 8, 9) else LogDocumento END AS LogDocumento, LogInfo,  LogFecha,  LogEstadoDoc, case when LogTipoDoc = 'P' then 'PEDIDO' else 'RECEPCION' END AS LogTipoDoc  from vw_ml_pedidos ";
	$sql.=" where MLPnroPedido LIKE '".$requestData['search']['value']."%' ";   
    $sql.=" OR MLPEstado LIKE '".$requestData['search']['value']."%' ";
    $sql.=" OR LogDocumento LIKE '".$requestData['search']['value']."%' ";
    $sql.=" OR LogInfo LIKE '".$requestData['search']['value']."%' ";
    $sql.=" OR LogFecha LIKE '".$requestData['search']['value']."%'  ";
    $sql.=" OR LogEstadoDoc LIKE '".$requestData['search']['value']."%' ";
    $sql.=" OR LogTipoDoc LIKE '".$requestData['search']['value']."%' ";
	$query=mysqli_query($conn, $sql) or die("grid_status.php: get PO");
	$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result without limit in the query 
    $sql.=" ORDER BY MLPnroPedido, ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."  "; // $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc , $requestData['start'] contains start row number ,$requestData['length'] contains limit length.
    $query=mysqli_query($conn, $sql) or die("grid_status.php: get PO"); // again run query with limit
	
} else {	

    $sql = "select MLPnroPedido, MLPEstado,  case when LogDocumento like '%IGRS_P %' then SUBSTRING(LogDocumento, 8, 9) else LogDocumento END AS LogDocumento, LogInfo,  LogFecha,  LogEstadoDoc, case when LogTipoDoc = 'P' then 'PEDIDO' else 'RECEPCION' END AS LogTipoDoc  from vw_ml_pedidos";

    $sql.=" ORDER BY MLPnroPedido, ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
    $query=mysqli_query($conn, $sql) or die("grid_status.php: get PO");
	
}

$data = array();
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	$nestedData=array(); 
	$nestedData[] = $row["MLPnroPedido"];
	$nestedData[] = $row["MLPEstado"];
    $nestedData[] = $row["LogDocumento"];
    $nestedData[] = $row["LogInfo"];
    $nestedData[] = $row["LogFecha"];
    $nestedData[] = $row["LogEstadoDoc"];
    $nestedData[] = $row["LogTipoDoc"];
    $nestedData[] = '<td><center><button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Ver Detalles</button></a>
    
    <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Detalles</h4>
        </div>
        <div class="modal-body">
          
        <table id="Details" class="table table-bordered table-striped">  
                                     <thead>
                                        <tr>
                                        <th>1</th>
                                        <th>2</th>
                                        <th>3</th>       
                                        <th>4</th>
                                        <th>5</th>
                                        <th>6</th>                                              
                                       </tr>

                                      </thead>
                                        <tbody>
                                        </tbody>
                                    </table>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
      
    </div>
    


    
    ';

    
	$data[] = $nestedData;
    
}




$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
			);

echo json_encode($json_data);  // send data as json format

?>

