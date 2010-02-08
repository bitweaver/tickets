<?php
// $Header: /cvsroot/bitweaver/_bit_tickets/index.php,v 1.15 2010/02/08 21:27:26 wjames5 Exp $
// Copyright (c) 2004 bitweaver Tickets
// All Rights Reserved. See below for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details.

// Initialization
require_once( '../kernel/setup_inc.php' );

require_once( TICKETS_PKG_PATH.'BitMilestone.php');

// Is package installed and enabled
$gBitSystem->verifyPackage( 'tickets' );

// Get the default content if none is requested 
if( !isset( $_REQUEST['ticket_id'] ) ) {
	$_REQUEST['ticket_id'] = $gBitSystem->getConfig( "home_tickets" );
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

if( is_object( $gContent ) && $gContent->isCommentable() ) {
	$commentsParentId = $gContent->mContentId;
	$comments_vars = Array( BITTICKET_CONTENT_TYPE_GUID );
	$comments_prefix_var = BITTICKET_CONTENT_TYPE_GUID.':';
	$comments_object_var = BITTICKET_CONTENT_TYPE_GUID;
	$comments_return_url = $gContent->getDisplayUrl();
	$comments_sort_mode = "commentDate_asc";
	$gBitSmarty->assign( 'item_display_comments', TRUE );
	include_once( LIBERTY_PKG_PATH.'comments_inc.php' );
}

// Get necessary lists.
$contextTicket = new BitTicket();

$fieldDefinitions = $contextTicket->getFieldDefinitions();
$gBitSmarty->assign( 'fieldDefinitions', $fieldDefinitions);
$fieldValues = $contextTicket->getFieldValues();
$gBitSmarty->assign( 'fieldValues', $fieldValues);

$milestone = new BitMilestone();
$pParamHash = array();
$milestones = $milestone->getList( $pParamHash );
$gBitSmarty->assign( 'milestones', $milestones);

$gBitThemes->loadJavascript( TICKETS_PKG_PATH.'scripts/BitTicket.js', TRUE );

// Display the template
$gBitSystem->display( 'bitpackage:tickets/ticket_display.tpl', tra( 'Tickets' ) , array( 'display_mode' => 'display' ));
?>
