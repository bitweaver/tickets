<?php
$tables = array(

    'ticket_milestone' => "
        milestone_id I4 PRIMARY,
        content_id I4,
        date_from I8 NOT NULL,
        date_to I8 NOT NULL,
		is_default I1
        CONSTRAINT '
    		, CONSTRAINT `ticket_milestone_fkey` FOREIGN KEY( `content_id` ) REFERENCES `".BIT_DB_PREFIX."liberty_content` ( `content_id` )'
    ",

    'tickets' => "
        ticket_id I4 AUTO PRIMARY,
        content_id I4,
        assignee_id I4,
		milestone_id I4
        CONSTRAINT '
    		, CONSTRAINT `tickets_content_fkey` FOREIGN KEY( `content_id` ) REFERENCES `".BIT_DB_PREFIX."liberty_content` ( `content_id` )
			, CONSTRAINT `tickets_mlstone_fkey` FOREIGN KEY( `milestone_id` ) REFERENCES `".BIT_DB_PREFIX."ticket_milestone` ( `milestone_id` )
        '
    ",

    'ticket_field_defs' => "
        def_id I4 AUTO PRIMARY,
        title C(40),
        description C(100),
        use_at_creation I1 DEFAULT (1),
        sort_order I4 NOT NULL,
        is_enabled I1 DEFAULT(1)
    ",

    'ticket_field_values' => "
        field_id I4 AUTO PRIMARY,
        def_id I4 NOT NULL,
        field_value C(100),
        sort_order I4,
        is_default I1,
        is_enabled I1 DEFAULT(1)
        CONSTRAINT '
            , CONSTRAINT `ticket_fval_fkey` FOREIGN KEY( `def_id` ) REFERENCES `".BIT_DB_PREFIX."ticket_field_defs` ( `def_id` )
        '
    ",
    
    'ticket_attributes' => "
		id I4 AUTO PRIMARY,
		ticket_id I4 NOT NULL,
		field_id I4 NOT NULL
		CONSTRAINT '
    		, CONSTRAINT `ticketattr_ticket_fkey` FOREIGN KEY( `ticket_id` ) REFERENCES `".BIT_DB_PREFIX."tickets` ( `ticket_id` )
			, CONSTRAINT `ticketattr_field_fkey` FOREIGN KEY( `field_id` ) REFERENCES `".BIT_DB_PREFIX."ticket_field_values` ( `field_id` )
		'    	
	",
    
    'ticket_history' => "
        def_id I4,
        field_old_value I4,
        field_new_value I4
        CONSTRAINT '
    		, CONSTRAINT `ticket_history_fkey` FOREIGN KEY( `def_id` ) REFERENCES `".BIT_DB_PREFIX."ticket_field_defs` ( `def_id` )'
    ",

    'ticket_queries' => "
        query_id I4 AUTO PRIMARY,
        title C(40) I4,
        user_id I4
    ",

    'ticket_query_map' => "
        query_id I4 PRIMARY,
        def_id I4 NOT NULL,
        sort_order I4 NOT NULL,
        sort_desc I1 DEFAULT (1)
        CONSTRAINT '
            , CONSTRAINT `ticketqmap_query_fkey` FOREIGN KEY( `query_id` ) REFERENCES `".BIT_DB_PREFIX."ticket_queries` ( `query_id` )
            , CONSTRAINT `ticketqmap_field_fkey` FOREIGN KEY( `def_id` ) REFERENCES `".BIT_DB_PREFIX."ticket_field_defs` ( `def_id` )'
    ",


); 

global $gBitInstaller;

foreach( array_keys( $tables ) AS $tableName ) {
	$gBitInstaller->registerSchemaTable( TICKETS_PKG_NAME, $tableName, $tables[$tableName] );
}

$gBitInstaller->registerPackageInfo( TICKETS_PKG_NAME, array(
	'description' => "Tickets.",
	'license' => '<a href="http://www.gnu.org/licenses/licenses.html#LGPL">LGPL</a>',
) );

// ### Indexes
$indices = array(
	'bit_tickets_ticket_id_idx' => array('table' => 'tickets', 'cols' => 'ticket_id', 'opts' => NULL ),
);
$gBitInstaller->registerSchemaIndexes( TICKETS_PKG_NAME, $indices );

// ### Sequences
$sequences = array (
	'tickets_ticket_id_seq'      => array( 'start' => 1 ),
	'tickets_ticket_field_defs_field_id_seq'      => array( 'start' => 1 )
);
$gBitInstaller->registerSchemaSequences( TICKETS_PKG_NAME, $sequences );

$insertTicketFieldDefs   = "INSERT INTO `".BIT_DB_PREFIX."ticket_field_defs` (`title`, `description, `sort_order`, `use_at_creation`, `is_enabled`)";
$insertTicketFieldValues = "INSERT INTO `".BIT_DB_PREFIX."ticket_field_values` (`def_id`, `field_value`, `sort_order`, `is_default`, `is_enabled`)";

$gBitInstaller->registerSchemaDefault( TICKETS_PKG_NAME, array(

          "$insertTicketFieldDefs VALUES ('Type', 'Nature of a ticket', 1, 1, 1)",
          "$insertTicketFieldDefs VALUES ('Priority', 'Importance of a ticket', 2, 1, 1)",
          "$insertTicketFieldDefs VALUES ('Status', 'Current work Status of a ticket', 3, 0, 1)",
          "$insertTicketFieldDefs VALUES ('Resolution', 'How ticket is resolved for now', 4, 0, 1)",
          "$insertTicketFieldDefs VALUES ('Component', 'Part of system that ticket affects', 5, 1, 1)",
          "$insertTicketFieldDefs VALUES ('Version', 'Release version that ticket is from', 6, 1, 1)",
    
	      "$insertTicketFieldValues VALUES (1, 'Bug', 1, 1, 1)",
	      "$insertTicketFieldValues VALUES (1, 'Feature Request', 2, 0, 1)",
	      "$insertTicketFieldValues VALUES (1, 'Task', 3, 0, 1)",

	      "$insertTicketFieldValues VALUES (2, 'Trivial', 1, 0, 1)",
	      "$insertTicketFieldValues VALUES (2, 'Minor', 2, 0, 1)",
	      "$insertTicketFieldValues VALUES (2, 'Normal', 3, 1, 1)",
	      "$insertTicketFieldValues VALUES (2, 'Major', 4, 0, 1)",
	      "$insertTicketFieldValues VALUES (2, 'Critical', 5, 0, 1)",
	      "$insertTicketFieldValues VALUES (2, 'Blocker', 6, 0, 1)",

	      "$insertTicketFieldValues VALUES (3, 'New', 1, 1, 1)",
	      "$insertTicketFieldValues VALUES (3, 'Assigned', 2, 0, 1)",
	      "$insertTicketFieldValues VALUES (3, 'Reopened', 3, 0, 1)",
	      "$insertTicketFieldValues VALUES (3, 'Closed', 4, 0, 1)",

	      "$insertTicketFieldValues VALUES (4, 'None', 1, 1, 1)",
	      "$insertTicketFieldValues VALUES (4, 'Fixed', 2, 0, 1)",
	      "$insertTicketFieldValues VALUES (4, 'Invalid', 3, 0, 1)",
	      "$insertTicketFieldValues VALUES (4, 'Duplicate', 4, 0, 1)",
	      "$insertTicketFieldValues VALUES (4, 'Works For Me', 5, 0, 1)",
	      "$insertTicketFieldValues VALUES (4, 'Wont Fix', 6, 0, 1)",

) );

// ### Default UserPermissions
$gBitInstaller->registerUserPermissions( TICKETS_PKG_NAME, array(
	array( 'p_tickets_admin', 'Can admin tickets', 'admin', TICKETS_PKG_NAME ),
	array( 'p_tickets_create', 'Can create tickets', 'registered', TICKETS_PKG_NAME ),
	array( 'p_tickets_update', 'Can update any ticket', 'editors', TICKETS_PKG_NAME ),
	array( 'p_tickets_view', 'Can view tickets', 'basic',  TICKETS_PKG_NAME ),
	array( 'p_tickets_assignee', 'Can be an assignee for a ticket', 'editors', TICKETS_PKG_NAME ),
	array( 'p_tickets_milestone_create', 'Can create milestones', 'editors', TICKETS_PKG_NAME ),
	array( 'p_tickets_milestone_update', 'Can update milestones', 'editors', TICKETS_PKG_NAME )
) );

// ### Default Preferences
$gBitInstaller->registerPreferences( TICKETS_PKG_NAME, array(
	array( TICKETS_PKG_NAME, 'tickets_default_ordering', 'ticket_id_desc' ),
	array( TICKETS_PKG_NAME, 'tickets_list_ticket_id', 'y' ),
	array( TICKETS_PKG_NAME, 'tickets_list_title', 'y' ),
	array( TICKETS_PKG_NAME, 'tickets_list_tickets', 'y' ),
) );
?>
