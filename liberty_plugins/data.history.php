<?php
/**
 * @version  $Revision: 1.1 $
 * @package  liberty
 * @subpackage plugins_data
 */
/**
 * definitions
 */
global $gBitSystem, $gLibertySystem;

define( 'PLUGIN_GUID_DATAHISTORY', 'datahistory' );
$pluginParams = array (
	'tag'           => 'history',
	'auto_activate' => FALSE,
	'requires_pair' => FALSE,
	'load_function' => 'data_history',
	'title'         => 'History',
	'help_page'     => 'DataPluginHistory',
	'description'   => tra( "Displays ticket history information." ),
	'help_function' => 'data_history_help',
	'syntax'        => "{history id= }",
	'plugin_type'   => DATA_PLUGIN
);
$gLibertySystem->registerPlugin( PLUGIN_GUID_DATAHISTORY, $pluginParams );
$gLibertySystem->registerDataTag( $pluginParams['tag'], PLUGIN_GUID_DATAHISTORY );

function data_history_help() {
	$help =
		'<table class="data help">'
			.'<tr>'
				.'<th>'.tra( "Key" ).'</th>'
				.'<th>'.tra( "Type" ).'</th>'
				.'<th>'.tra( "Comments" ).'</th>'
			.'</tr>'
			.'<tr class="odd">'
				.'<td>id</td>'
				.'<td>'.tra( "numeric").'<br /></td>'
				.'<td>'.tra( "History definition identifier." ).'</td>'
			.'</tr>'
			.'</table>'
		.tra( "Example: " )."{history id=7}";
	return $help;
}

function data_history( $pData, $pParams ) {
	global $gBitSystem, $gContent, $gBitSmarty;

	//check package	
	if (!$gBitSystem->isPackageActive( 'tickets' ) ) {
		return '<div class=error>'.tra('Tickets Package Deactivated.'). '</div>';
	}
	
	//check if all parameters are given
	$field = "id";
	
	if (!array_key_exists( $field, $pParams ) || !is_numeric( $pParams[$field] ))
		return '<div class=error>'.tra('Parameter is wrong or not given: ').$field.'. </div>';
	
	require_once( TICKETS_PKG_PATH.'BitTicket.php');
	
	//field $pParams with field names
	$contextTicket = new BitTicket ();
	$contextTicket->getHistoryFieldNames( $pParams[$field], $pParams );
	
	$gBitSmarty->assign('def_title', $pParams['def_title']);
	$gBitSmarty->assign('old_value', $pParams['old_value']);
	$gBitSmarty->assign('new_value', $pParams['new_value']);
	
	return $gBitSmarty->fetch( 'bitpackage:tickets/ticket_history.tpl' );
	
}
?>
