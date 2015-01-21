<?php

define( 'XMLRPC_URL', 'http://example.dev/xmlrpc.php' );

function send( $data )
{
	if( !function_exists( 'curl_init' ) )
	{
 		die( "Curl PHP package not installed!" );
 	}

	/** Initializing CURL **/
	$ch = curl_init();
 	curl_setopt( $ch, CURLOPT_URL, XMLRPC_URL );
 	curl_setopt( $ch, CURLOPT_HEADER, false );
 	curl_setopt( $ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml") );
 	curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );

 	/** Now execute the CURL, download the URL specified **/
 	$response = curl_exec( $ch );
 	return $response;
}

$xmlrpc_username = ''; // User setup with contributor role 
$xmlrpc_password = ''; // The user's password

$content = array(
	'post_title'   => 'test title',
	'post_content' => 'test content',
	'post_excerpt' => 'test excerpt',
	'post_status' => 'pending'
);

/** Encode the request **/
$request = xmlrpc_encode_request( "wp.newPost", array( 1, $xmlrpc_username, $xmlrpc_password, $content) );

/** Making the request to wordpress XMLRPC **/
$xml_response = send( $request );

$response = xmlrpc_decode( $xml_response );

/** Printing the response on to the console **/
print_r( $response );

?>