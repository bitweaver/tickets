<?php
// $Header: /cvsroot/bitweaver/_bit_tickets/milestone_display.php,v 1.2 2008/11/25 23:38:35 pppspoonman Exp $
// Copyright (c) 2004 bitweaver Tickets
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

// Initialization
require_once( '../bit_setup_inc.php' );

// Is package installed and enabled
$gBitSystem->verifyPackage( 'tickets' );

// Look up the content
require_once( TICKETS_PKG_PATH.'lookup_milestone_inc.php' );

if( !$gContent->isValid() ) {
	$gBitSystem->setHttpStatus( 404 );
	$gBitSystem->fatalError( "The milestones you requested could not be found." );
}

// Now check permissions to access this content 
$gContent->verifyViewPermission();

// Add a hit to the counter
$gContent->addHit();

// Get field definitions for listing tickets.
$ticket = new BitTicket ();
$fieldDefinitions = $ticket->getFieldDefinitions();
$gBitSmarty->assign_by_ref( 'fieldDefinitions' , $fieldDefinitions );

// Display the template
$gBitSystem->display( 'bitpackage:tickets/milestone_display.tpl', tra( 'Tickets' ) , array( 'display_mode' => 'display' ));
?>
