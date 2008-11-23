<?php
global $gContent;
require_once( TICKETS_PKG_PATH.'BitMilestone.php');
require_once( LIBERTY_PKG_PATH.'lookup_content_inc.php' );

// if we already have a gContent, we assume someone else created it for us, and has properly loaded everything up.
if( empty( $gContent ) || !is_object( $gContent ) || !$gContent->isValid() ) {
	// if milestone_id supplied, use that
	if( @BitBase::verifyId( $_REQUEST['milestone_id'] ) ) {
		$gContent = new BitMilestone( $_REQUEST['milestone_id'] );

	// if content_id supplied, use that
	} elseif( @BitBase::verifyId( $_REQUEST['content_id'] ) ) {
		$gContent = new BitMilestone( NULL, $_REQUEST['content_id'] );

	} elseif (@BitBase::verifyId( $_REQUEST['milestones']['milestone_id'] ) ) {
		$gContent = new BitMilestone( $_REQUEST['milestones']['milestone_id'] );

	// otherwise create new object
	} else {
		$gContent = new BitMilestone();
	}

	$gContent->load();
	$gBitSmarty->assign_by_ref( "gContent", $gContent );
}
?>
