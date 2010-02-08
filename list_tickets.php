<?php
// $Header: /cvsroot/bitweaver/_bit_tickets/list_tickets.php,v 1.11 2010/02/08 21:27:26 wjames5 Exp $
// Copyright (c) 2008 bitweaver Tickets
// All Rights Reserved. See below for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details.
// Initialization
require_once( '../kernel/setup_inc.php' );
require_once( TICKETS_PKG_PATH.'BitTicket.php' );

// Is package installed and enabled
$gBitSystem->verifyPackage( 'tickets' );

// Now check permissions to access this page
//$gBitSystem->verifyViewPermission();

// Remove ticketss if we don't want them anymore
if( isset( $_REQUEST["submit_mult"] ) && isset( $_REQUEST["checked"] ) && $_REQUEST["submit_mult"] == "remove_tickets" ) {

	// Now check permissions to remove the selected ticketss
	$gBitSystem->verifyPermission( 'p_tickets_update' );

	if( !empty( $_REQUEST['cancel'] ) ) {
		// user cancelled - just continue on, doing nothing
	} elseif( empty( $_REQUEST['confirm'] ) ) {
		$formHash['delete'] = TRUE;
		$formHash['submit_mult'] = 'remove_tickets';
		foreach( $_REQUEST["checked"] as $del ) {
			$tmpPage = new BitTicket( $del);
			if ( $tmpPage->load() && !empty( $tmpPage->mInfo['title'] )) {
				$info = $tmpPage->mInfo['title'];
			} else {
				$info = $del;
			}
			$formHash['input'][] = '<input type="hidden" name="checked[]" value="'.$del.'"/>'.$info;
		}
		$gBitSystem->confirmDialog( $formHash, 
			array(
				'warning' => tra('Are you sure you want to delete ').count( $_REQUEST["checked"] ).' tickets?',
				'error' => tra('This cannot be undone!')
			)
		);
	} else {
		foreach( $_REQUEST["checked"] as $deleteId ) {
			$tmpPage = new BitTicket( $deleteId );
			if( !$tmpPage->load() || !$tmpPage->expunge() ) {
				array_merge( $errors, array_values( $tmpPage->mErrors ) );
			}
		}
		if( !empty( $errors ) ) {
			$gBitSmarty->assign_by_ref( 'errors', $errors );
		}
	}
}

// Create new tickets object
$tickets = new BitTicket();
$ticketsList = $tickets->getList( $_REQUEST );
$gBitSmarty->assign_by_ref( 'ticketsList', $ticketsList );

$fieldDefinitions = $tickets->getFieldDefinitions ();
$gBitSmarty->assign_by_ref( 'fieldDefinitions', $fieldDefinitions );

// getList() has now placed all the pagination information in $_REQUEST['listInfo']
$gBitSmarty->assign_by_ref( 'listInfo', $_REQUEST['listInfo'] );

// Display the template
$gBitSystem->display( 'bitpackage:tickets/list_tickets.tpl', tra( 'Tickets' ) , array( 'display_mode' => 'list' ));

?>
