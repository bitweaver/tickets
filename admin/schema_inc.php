<?php
$tables = array(
	'ticketss' => "
		tickets_id I4 PRIMARY,
		content_id I4 NOTNULL,
		description C(160)
	",
);

global $gBitInstaller;

foreach( array_keys( $tables ) AS $tableName ) {
	$gBitInstaller->registerSchemaTable( TICKETS_PKG_NAME, $tableName, $tables[$tableName] );
}

$gBitInstaller->registerPackageInfo( TICKETS_PKG_NAME, array(
	'description' => "Tickets package to demonstrate how to build a bitweaver package.",
	'license' => '<a href="http://www.gnu.org/licenses/licenses.html#LGPL">LGPL</a>',
) );

// ### Indexes
$indices = array(
	'bit_ticketss_tickets_id_idx' => array('table' => 'ticketss', 'cols' => 'tickets_id', 'opts' => NULL ),
);
$gBitInstaller->registerSchemaIndexes( TICKETS_PKG_NAME, $indices );

// ### Sequences
$sequences = array (
	'tickets_tickets_id_seq'      => array( 'start' => 1 )
);
$gBitInstaller->registerSchemaSequences( TICKETS_PKG_NAME, $sequences );

$gBitInstaller->registerSchemaDefault( TICKETS_PKG_NAME, array(
	//      "INSERT INTO `".BIT_DB_PREFIX."bit_tickets_types` (`type`) VALUES ('Tickets')",
) );

// ### Default UserPermissions
$gBitInstaller->registerUserPermissions( TICKETS_PKG_NAME, array(
	array( 'p_tickets_admin', 'Can admin tickets', 'admin', TICKETS_PKG_NAME ),
	array( 'p_tickets_create', 'Can create a tickets', 'registered', TICKETS_PKG_NAME ),
	array( 'p_tickets_update', 'Can update any tickets', 'editors', TICKETS_PKG_NAME ),
	array( 'p_tickets_view', 'Can view tickets', 'basic',  TICKETS_PKG_NAME ),
) );

// ### Default Preferences
$gBitInstaller->registerPreferences( TICKETS_PKG_NAME, array(
	array( TICKETS_PKG_NAME, 'tickets_default_ordering', 'tickets_id_desc' ),
	array( TICKETS_PKG_NAME, 'tickets_list_tickets_id', 'y' ),
	array( TICKETS_PKG_NAME, 'tickets_list_title', 'y' ),
	array( TICKETS_PKG_NAME, 'tickets_list_description', 'y' ),
	array( TICKETS_PKG_NAME, 'tickets_list_ticketss', 'y' ),
) );
?>
