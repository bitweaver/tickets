<?php
// $Header: /cvsroot/bitweaver/_bit_tickets/edit.php,v 1.1 2008/11/19 23:15:13 pppspoonman Exp $
// Copyright (c) 2004 bitweaver Tickets
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

// Initialization
require_once( '../bit_setup_inc.php' );

// Is package installed and enabled
$gBitSystem->verifyPackage( 'tickets' );

require_once( TICKETS_PKG_PATH.'lookup_tickets_inc.php' );

// Now check permissions to access this page
if( $gContent->isValid() ){
	$gContent->verifyUpdatePermission();
}else{
	$gContent->verifyCreatePermission();
}

if( isset( $_REQUEST['tickets']["title"] ) ) {
	$gContent->mInfo["title"] = $_REQUEST['tickets']["title"];
}

if( isset( $_REQUEST['tickets']["description"] ) ) {
	$gContent->mInfo["description"] = $_REQUEST['tickets']["description"];
}

if( isset( $_REQUEST["format_guid"] ) ) {
	$gContent->mInfo['format_guid'] = $_REQUEST["format_guid"];
}

if( isset( $_REQUEST['tickets']["edit"] ) ) {
	$gContent->mInfo["data"] = $_REQUEST['tickets']["edit"];
	$gContent->mInfo['parsed_data'] = $gContent->parseData();
}

// If we are in preview mode then preview it!
if( isset( $_REQUEST["preview"] ) ) {
	$gContent->invokeServices( 'content_preview_function' );
} else {
	$gContent->invokeServices( 'content_edit_function' );
}

// Pro
// Check if the page has changed
if( !empty( $_REQUEST["save_tickets"] ) ) {

	// Check if all Request values are delivered, and if not, set them
	// to avoid error messages. This can happen if some features are
	// disabled
	if( $gContent->store( $_REQUEST['tickets'] ) ) {
		header( "Location: ".$gContent->getDisplayUrl() );
		die;
	} else {
		$gBitSmarty->assign_by_ref( 'errors', $gContent->mErrors );
	}
}

// Display the template
$gBitSystem->display( 'bitpackage:tickets/edit_tickets.tpl', tra('Tickets') , array( 'display_mode' => 'edit' ));
?>
