<?php /* click-to-proxy.php */
    
    error_reporting( 0 );
    
    /* Configuration */
        
        $website = "www.solopcms.com"; /* With the WWW always. */
        
    /* Configuration */
    
    $fields = array(
        "id" => "|" . $website . "|" . $_SERVER[ "REMOTE_ADDR" ] . "|" . ( ( string ) time() ) . "|" . $_GET[ "phone_number" ] . "|"
    );
    
    $session = curl_init( "https://cloud.pacific54.com/system/pacific-ctm/form-reactor.php" );
    curl_setopt( $session, CURLOPT_POST, count( $fields ) );
    curl_setopt( $session, CURLOPT_POSTFIELDS, $fields );
    $result = curl_exec( $session );
    curl_close( $session );
    
?>