<?php
/**
 * @version $Header$
 * @package liberty
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../kernel/setup_inc.php' );

require_once( 'lookup_tickets_inc.php' );
 
$XMLContent = "";
$feedback = "";

if( !$gContent->hasUserPermission( 'p_tickets_update', TRUE, TRUE)) {
	$statusCode = 401;
	$formfeedback['error'] = tra( "You do not have the required permissions to update this ticket" );
} elseif( $gContent->isCommentable() ) {
	/* If we are receiving ajax comments request make sure our results also know 
	   we are using ajax comments. This is an insurance measure that if the originating content 
	   forced on ajax comments (even if off system wide) that the return results 
	   continue to use ajax comments. Don't take this out under penalty of death.
	*/
	
	$gBitSystem->setConfig( 'comments_ajax', 'y' );
	if( isset( $_REQUEST['post_header_request'] )) {
		if ($gContent->storeOnlyHeader ($_REQUEST['ticket'])) {
			$statusCode = 200;
			$formfeedback['success'] = tra( "Your changes were successfully saved." );
			
			//It was passed by reference so change_date was set for us!
			$gContent->loadTicketHistory($_REQUEST['ticket']['change_date']);
			
			//Play as Smarty's foreach :)
			foreach($gContent->mHistory as $history) {
				$gBitSmarty->assign_by_ref('history', $history);
				$XMLContent .= $gBitSmarty->fetch( 'bitpackage:tickets/list_history_inc.tpl' );
			}
		}else{
			//if store is requested but it fails for some reason - like captcha mismatch
			$statusCode = 400;
			$formfeedback['error'] = $gContent->mErrors;
		}
	}else{
		//we assume preview request which we return as ok - our js callback knows what to do when preview is requested
		$statusCode = 200;
		$formfeedback['error'] = "This may be the case later when comment is posted along with history in one query. Be sure to cleanup this later";
	}
	
} else {
	$statusCode = 405;
	$formfeedback['error'] = tra( "Sorry, you can not post a comment here." );
}

if( !empty($formfeedback) ){
	$gBitSmarty->loadPlugin( 'smarty_modifier_formfeedback' );
	$feedback = smarty_function_formfeedback( $formfeedback, $gBitSmarty );
}

//We return XML with a status code
$mRet = "<req><status><code>".$statusCode."</code></status>"
	."<formfeedback><![CDATA[".$feedback."]]></formfeedback>"
	."<content><![CDATA[".$XMLContent."]]></content></req>";

//since we are returning xml we must report so in the header
//we also need to tell the browser not to cache the page
//see: http://mapki.com/index.php?title=Dynamic_XML
// Date in the past
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
// always modified
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
// HTTP/1.1
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
// HTTP/1.0
header("Pragma: no-cache");
//XML Header
header("content-type:text/xml");

print_r('<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>');
print_r($mRet);					
die;
?>
