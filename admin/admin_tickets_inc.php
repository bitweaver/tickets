<?php
// $Header: /cvsroot/bitweaver/_bit_tickets/admin/admin_tickets_inc.php,v 1.1 2008/11/19 23:15:14 pppspoonman Exp $
// Copyright (c) 2005 bitweaver Tickets
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

// is this used?
//if (isset($_REQUEST["ticketsset"]) && isset($_REQUEST["homeTickets"])) {
//	$gBitSystem->storeConfig("home_tickets", $_REQUEST["homeTickets"]);
//	$gBitSmarty->assign('home_tickets', $_REQUEST["homeTickets"]);
//}

require_once( TICKETS_PKG_PATH.'BitTickets.php' );

$formTicketsLists = array(
	"tickets_list_tickets_id" => array(
		'label' => 'Id',
		'note' => 'Display the tickets id.',
	),
	"tickets_list_title" => array(
		'label' => 'Title',
		'note' => 'Display the title.',
	),
	"tickets_list_description" => array(
		'label' => 'Description',
		'note' => 'Display the description.',
	),
	"tickets_list_data" => array(
		'label' => 'Text',
		'note' => 'Display the text.',
	),
);
$gBitSmarty->assign( 'formTicketsLists',$formTicketsLists );

$processForm = set_tab();

if( $processForm ) {
	$ticketsToggles = array_merge( $formTicketsLists );
	foreach( $ticketsToggles as $item => $data ) {
		simple_set_toggle( $item, 'ticketss' );
	}

}

$tickets = new BitTickets();
$ticketss = $tickets->getList( $_REQUEST );
$gBitSmarty->assign_by_ref('ticketss', $ticketss['data']);
?>
