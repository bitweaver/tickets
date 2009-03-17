<?php
global $gBitSystem, $gBitThemes;

$registerHash = array(
	'package_name' => 'tickets',
	'package_path' => dirname( __FILE__ ).'/',
	'homeable' => TRUE,
);
$gBitSystem->registerPackage( $registerHash );

if( $gBitSystem->isPackageActive( 'tickets' ) ) {
	define( 'LIBERTY_SERVICE_TICKETS', 'tickets' );

	$menuHash = array(
		'package_name'  => TICKETS_PKG_NAME,
		'index_url'     => TICKETS_PKG_URL.'index.php',
		'menu_template' => 'bitpackage:tickets/menu_tickets.tpl',
	);
	$gBitSystem->registerAppMenu( $menuHash );

	$gLibertySystem->registerService( LIBERTY_SERVICE_TICKETS, TICKETS_PKG_NAME, array(
		'content_verify_function'		=> 'tickets_content_verify',
	) );
	
	$gBitThemes->loadCss( TICKETS_PKG_PATH.'styles/tickets.css' );
}
?>
