<?php 
// $Header: /cvsroot/bitweaver/_bit_tickets/admin/admin_definitions.php,v 1.3 2010/02/08 21:27:26 wjames5 Exp $
require_once( '../../kernel/setup_inc.php' );

include_once( TICKETS_PKG_PATH.'BitTicket.php' );
include_once( TICKETS_PKG_PATH.'lookup_tickets_inc.php' );

// Is package installed and enabled
$gBitSystem->verifyPackage( 'tickets' );
$gBitSystem->verifyPermission( 'p_tickets_admin' );

$ticket = new BitTicket();

$fieldDefinitions = $ticket->getFieldDefinitions ();
$gBitSmarty->assign_by_ref( 'fieldDefinitions', $fieldDefinitions );

$fieldValues = $ticket->getFieldValues ();
$gBitSmarty->assign_by_ref( 'fieldValues', $fieldValues);

$gBitSystem->display( 'bitpackage:tickets/admin_definitions.tpl', tra( 'Edit Field Definitions' ) , array( 'display_mode' => 'admin' ));
?>