<?php
global $gContent;
require_once( TICKETS_PKG_PATH.'BitTickets.php');
require_once( LIBERTY_PKG_PATH.'lookup_content_inc.php' );

// if we already have a gContent, we assume someone else created it for us, and has properly loaded everything up.
if( empty( $gContent ) || !is_object( $gContent ) || !$gContent->isValid() ) {
	// if tickets_id supplied, use that
	if( @BitBase::verifyId( $_REQUEST['tickets_id'] ) ) {
		$gContent = new BitTickets( $_REQUEST['tickets_id'] );

	// if content_id supplied, use that
	} elseif( @BitBase::verifyId( $_REQUEST['content_id'] ) ) {
		$gContent = new BitTickets( NULL, $_REQUEST['content_id'] );

	} elseif (@BitBase::verifyId( $_REQUEST['tickets']['tickets_id'] ) ) {
		$gContent = new BitTickets( $_REQUEST['tickets']['tickets_id'] );

	// otherwise create new object
	} else {
		$gContent = new BitTickets();
	}

	$gContent->load();
	$gBitSmarty->assign_by_ref( "gContent", $gContent );
}
?>