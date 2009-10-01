<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_tickets/remove_tickets.php,v 1.5 2009/10/01 14:17:06 wjames5 Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See below for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details
 *
 * $Id: remove_tickets.php,v 1.5 2009/10/01 14:17:06 wjames5 Exp $
 * @package tickets
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );
include_once( TICKETS_PKG_PATH.'BitTicket.php');
include_once( TICKETS_PKG_PATH.'lookup_tickets_inc.php' );

$gBitSystem->verifyPackage( 'tickets' );

if( !$gContent->isValid() ) {
	$gBitSystem->fatalError( "No tickets indicated" );
}

$gContent->verifyUpdatePermission();

if( isset( $_REQUEST["confirm"] ) ) {
	if( $gContent->expunge()  ) {
		header ("location: ".BIT_ROOT_URL );
		die;
	} else {
		vd( $gContent->mErrors );
	}
}

$gBitSystem->setBrowserTitle( tra( 'Confirm delete of: ' ).$gContent->getTitle() );
$formHash['remove'] = TRUE;
$formHash['ticket_id'] = $_REQUEST['ticket_id'];
$msgHash = array(
	'label' => tra( 'Delete Tickets' ),
	'confirm_item' => $gContent->getTitle(),
	'warning' => tra( 'This tickets will be completely deleted.<br />This cannot be undone!' ),
);
$gBitSystem->confirmDialog( $formHash,$msgHash );

?>
