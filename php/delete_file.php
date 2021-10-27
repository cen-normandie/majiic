<?php
// First Check if file exists
$response = array('status'=>false);

if( file_exists($_POST["file"] ) ) {
    unlink($_POST["file"]);
    $response['status'] = true;
}

// Send JSON Data to AJAX Request
echo json_encode($response);
?>