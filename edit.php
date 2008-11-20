<?php
// $Header: /cvsroot/bitweaver/_bit_tickets/edit.php,v 1.3 2008/11/20 21:10:19 pppspoonman Exp $
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

if( isset( $_REQUEST['ticket']["title"] ) ) {
	$gContent->mInfo["title"] = $_REQUEST['ticket']["title"];
}

if( isset( $_REQUEST['ticket']["description"] ) ) {
	$gContent->mInfo["description"] = $_REQUEST['ticket']["description"];
}

if( isset( $_REQUEST["format_guid"] ) ) {
	$gContent->mInfo['format_guid'] = $_REQUEST["format_guid"];
}

if( isset( $_REQUEST['ticket']["edit"] ) ) {
	$gContent->mInfo["data"] = $_REQUEST['ticket']["edit"];
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
if( !empty( $_REQUEST["save_ticket"] ) ) {

	// Check if all Request values are delivered, and if not, set them
	// to avoid error messages. This can happen if some features are
	// disabled
	if( $gContent->store( $_REQUEST['ticket'] ) ) {
		header( "Location: ".$gContent->getDisplayUrl() );
		die;
	} else {
		$gBitSmarty->assign_by_ref( 'errors', $gContent->mErrors );
	}
}

$fieldDefinitions = BitTicket::getFieldDefinitions();
$gBitSmarty->assign( 'fieldDefinitions', $fieldDefinitions);
$fieldValues = BitTicket::getFieldValues();
$gBitSmarty->assign( 'fieldValues', $fieldValues);


// Display the template
$gBitSystem->display( 'bitpackage:tickets/edit_ticket.tpl', tra('Tickets') , array( 'display_mode' => 'edit' ));
?>
