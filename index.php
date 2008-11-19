<?php
// $Header: /cvsroot/bitweaver/_bit_tickets/index.php,v 1.2 2008/11/19 23:15:13 pppspoonman Exp $
// Copyright (c) 2004 bitweaver Tickets
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

// Initialization
require_once( '../bit_setup_inc.php' );

// Is package installed and enabled
$gBitSystem->verifyPackage( 'tickets' );

// Get the default content if none is requested 
if( !isset( $_REQUEST['tickets_id'] ) ) {
	$_REQUEST['tickets_id'] = $gBitSystem->getConfig( "home_tickets" );
}

// Look up the content
require_once( TICKETS_PKG_PATH.'lookup_tickets_inc.php' );

if( !$gContent->isValid() ) {
	$gBitSystem->setHttpStatus( 404 );
	$gBitSystem->fatalError( "The tickets you requested could not be found." );
}

// Now check permissions to access this content 
$gContent->verifyViewPermission();

// Add a hit to the counter
$gContent->addHit();

// Display the template
$gBitSystem->display( 'bitpackage:tickets/tickets_display.tpl', tra( 'Tickets' ) , array( 'display_mode' => 'display' ));
?>
