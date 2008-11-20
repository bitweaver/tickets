<?php
global $gContent;
require_once( TICKETS_PKG_PATH.'BitTicket.php');
require_once( LIBERTY_PKG_PATH.'lookup_content_inc.php' );

// if we already have a gContent, we assume someone else created it for us, and has properly loaded everything up.
if( empty( $gContent ) || !is_object( $gContent ) || !$gContent->isValid() ) {
	// if ticket_id supplied, use that
	if( @BitBase::verifyId( $_REQUEST['ticket_id'] ) ) {
		$gContent = new BitTicket( $_REQUEST['ticket_id'] );

	// if content_id supplied, use that
	} elseif( @BitBase::verifyId( $_REQUEST['content_id'] ) ) {
		$gContent = new BitTicket( NULL, $_REQUEST['content_id'] );

	} elseif (@BitBase::verifyId( $_REQUEST['tickets']['ticket_id'] ) ) {
		$gContent = new BitTicket( $_REQUEST['tickets']['ticket_id'] );

	// otherwise create new object
	} else {
		$gContent = new BitTicket();
	}

	$gContent->load();
	$gBitSmarty->assign_by_ref( "gContent", $gContent );
}
?>
