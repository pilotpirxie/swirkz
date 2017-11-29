<?php

$db_host = 'localhost';
$db_name = 'swirkz';
$db_user = 'root';
$db_pass = '';

$mysqli = new mysqli( $db_host, $db_user, $db_pass, $db_name );

if ( $mysqli->connect_errno ) {
    echo 'Internal error. Back soon. #CE';
    exit( );
}

if ( !$mysqli->set_charset( "utf8" ) ) {
    echo 'Internal error. Back soon. #SC';
    exit( );
}
