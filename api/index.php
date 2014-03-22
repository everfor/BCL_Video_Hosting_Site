<?php
	require_once('api.php');
	// Requests from the same server don't have a HTTP_ORIGIN header
	if (!array_key_exists('HTTP_ORIGIN', $_SERVER)) {
	    $_SERVER['HTTP_ORIGIN'] = $_SERVER['SERVER_NAME'];
	}

	try {
	    $API = new API($_REQUEST['request'], $_SERVER['HTTP_ORIGIN']);
	    $data = $API->processAPI();
	    $API->sendResponse($data, 'application/json');
	} catch (Exception $e) {
	    echo json_encode(Array('error' => $e->getMessage()));
	}
?>