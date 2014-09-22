<?php
/**
* $Header$
* $Id$
*/

/**
* Tickets class to illustrate best practices when creating a new bitweaver package that
* builds on core bitweaver functionality, such as the Liberty CMS engine
*
* date created 2008/10/19
* @author SpOOnman <tomasz2k@poczta.onet.pl>
* @version $Revision$
* @class BitMilestone
*/

require_once( LIBERTY_PKG_PATH.'LibertyMime.php' );
require_once( TICKETS_PKG_PATH.'BitTicket.php' );

/**
* This is used to uniquely identify the object
*/
define( 'BITMILESTONE_CONTENT_TYPE_GUID', 'bitmilestone' );

class BitMilestone extends LibertyMime {
	/**
	 * mMilestoneId Primary key for our mythical Ticket class object & table
	 * 
	 * @var array
	 * @access public
	 */
	var $mMilestoneId;

	/**
	 * Start date of a milestone.
     * @var int
	 * @access public
	 */
	var $mDateFrom;

	/**
	 * End date of a milestone.
     * @var int
	 * @access public
	 */
	var $mDateTo;

    /**
     * Ticket identifiers.
     * @var array
     * @access public
     */
    var $mTickets;

	/**
	 * BitMilestone During initialisation, be sure to call our base constructors
	 * 
	 * @param numeric $pMilestoneId 
	 * @param numeric $pContentId 
	 * @access public
	 * @return void
	 */
	function BitMilestone( $pMilestoneId=NULL, $pContentId=NULL ) {
		parent::__construct();
		$this->mMilestoneId = $pMilestoneId;
		$this->mContentId = $pContentId;
		$this->mAssigneeId = NULL;
		$this->mAttributes = array();
		$this->mContentTypeGuid = BITMILESTONE_CONTENT_TYPE_GUID;
		$this->registerContentType( BITMILESTONE_CONTENT_TYPE_GUID, array(
			'content_type_guid'   => BITMILESTONE_CONTENT_TYPE_GUID,
			'content_name' => 'Ticket Milestone',
			'handler_class'       => 'BitMilestone',
			'handler_package'     => 'tickets',
			'handler_file'        => 'BitMilestone.php',
			'maintainer_url'      => 'http://www.bitweaver.org'
		));
		// Permission setup
		$this->mViewContentPerm   = 'p_tickets_view';
		$this->mCreateContentPerm = 'p_tickets_create';
		$this->mUpdateContentPerm = 'p_tickets_update';
		$this->mAdminContentPerm  = 'p_tickets_admin';
	}

	/**
	 * load Load the data from the database
	 * 
	 * @access public
	 * @return boolean TRUE on success, FALSE on failure - mErrors will contain reason for failure
	 */
	function load() {
		if( $this->verifyId( $this->mMilestoneId ) || $this->verifyId( $this->mContentId ) ) {
			// LibertyContent::load()assumes you have joined already, and will not execute any sql!
			// This is a significant performance optimization
			$lookupColumn = $this->verifyId( $this->mMilestoneId ) ? 'milestone_id' : 'content_id';
			$bindVars = array();
			$selectSql = $joinSql = $whereSql = '';
			array_push( $bindVars, $lookupId = @BitBase::verifyId( $this->mMilestoneId ) ? $this->mMilestoneId : $this->mContentId );
			$this->getServicesSql( 'content_load_sql_function', $selectSql, $joinSql, $whereSql, $bindVars );

			$query = "
				SELECT tm.*, lc.*,
				uue.`login` AS modifier_user, uue.`real_name` AS modifier_real_name,
				uuc.`login` AS creator_user, uuc.`real_name` AS creator_real_name
				$selectSql
				FROM `".BIT_DB_PREFIX."ticket_milestone` tm
					INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON( lc.`content_id` = tm.`content_id` ) $joinSql
					LEFT JOIN `".BIT_DB_PREFIX."users_users` uue ON( uue.`user_id` = lc.`modifier_user_id` )
					LEFT JOIN `".BIT_DB_PREFIX."users_users` uuc ON( uuc.`user_id` = lc.`user_id` )
				WHERE tm.`$lookupColumn`=? $whereSql";

            $ticketQuery = "SELECT t.ticket_id
                FROM `".BIT_DB_PREFIX."tickets` t 
                WHERE t.`milestone_id`=?";

			$result = $this->mDb->query( $query, $bindVars );

			if( $result && $result->numRows() ) {
				$this->mInfo = $result->fields;
				$this->mContentId = $result->fields['content_id'];
				$this->mMilestoneId = $result->fields['milestone_id'];
				
				$date = new BitDate ();
				
				$this->mDateFrom = $result->fields['date_from'];
				$this->mDateTo = $result->fields['date_to'];

				$this->mInfo['creator'] = ( !empty( $result->fields['creator_real_name'] ) ? $result->fields['creator_real_name'] : $result->fields['creator_user'] );
				$this->mInfo['editor'] = ( !empty( $result->fields['modifier_real_name'] ) ? $result->fields['modifier_real_name'] : $result->fields['modifier_user'] );
				$this->mInfo['display_name'] = BitUser::getTitleFromHash( $this->mInfo );
				$this->mInfo['display_url'] = $this->getDisplayUrl();
				$this->mInfo['parsed_data'] = $this->parseData();

                $ticketResult = $this->mDb->getCol( $ticketQuery, array ( $this->mMilestoneId ) );

				$ticket = new BitTicket();
				$pParamHash = array ();
                $this->mTickets = $ticket->getList( $pParamHash, $ticketResult );
                
				LibertyMime::load();
			}
		}
		return( count( $this->mInfo ) );
	}

	/**
	 * store Any method named Store inherently implies data will be written to the database
	 * @param pParamHash be sure to pass by reference in case we need to make modifcations to the hash
	 * This is the ONLY method that should be called in order to store( create or update )an tickets!
	 * It is very smart and will figure out what to do for you. It should be considered a black box.
	 * 
	 * @param array $pParamHash hash of values that will be used to store the page
	 * @access public
	 * @return boolean TRUE on success, FALSE on failure - mErrors will contain reason for failure
	 */
	function store( &$pParamHash ) {
		$this->mDb->StartTrans();
		if( $this->verify( $pParamHash )&& LibertyMime::store( $pParamHash ) ) {
			$table = BIT_DB_PREFIX."ticket_milestone";

			if( $this->mMilestoneId ) {
				$locId = array( "milestone_id" => $pParamHash['milestone_id'] );
				$result = $this->mDb->associateUpdate( $table, $pParamHash['milestone_store'], $locId );

			} else {
				$pParamHash['milestone_store']['content_id'] = $pParamHash['content_id'];
				if( @$this->verifyId( $pParamHash['milestone_id'] ) ) {
					// if pParamHash['milestone_id'] is set, some is requesting a particular milestone_id. Use with caution!
					$pParamHash['milestone_store']['milestone_id'] = $pParamHash['milestone_id'];
				} else {
					$pParamHash['milestone_store']['milestone_id'] = $this->mDb->GenID( 'tickets_milestone_id_seq' );
				}
				$this->mMilestoneId = $pParamHash['milestone_store']['milestone_id'];

				$result = $this->mDb->associateInsert( $table, $pParamHash['milestone_store'] );
			}


			$this->mDb->CompleteTrans();
			$this->load();
		}
		return( count( $this->mErrors )== 0 );
	}

	/**
	 * verify Make sure the data is safe to store
	 * @param pParamHash be sure to pass by reference in case we need to make modifcations to the hash
	 * This function is responsible for data integrity and validation before any operations are performed with the $pParamHash
	 * NOTE: This is a PRIVATE METHOD!!!! do not call outside this class, under penalty of death!
	 * 
	 * @param array $pParamHash reference to hash of values that will be used to store the page, they will be modified where necessary
	 * @access private
	 * @return boolean TRUE on success, FALSE on failure - $this->mErrors will contain reason for failure
	 */
	function verify( &$pParamHash ) {
		global $gBitUser, $gBitSystem;

		// make sure we're all loaded up of we have a mMilestoneId
		if( $this->verifyId( $this->mMilestoneId ) && empty( $this->mInfo ) ) {
			$this->load();
		}

		if( @$this->verifyId( $this->mInfo['content_id'] ) ) {
			$pParamHash['content_id'] = $this->mInfo['content_id'];
		}

		// It is possible a derived class set this to something different
		if( @$this->verifyId( $pParamHash['content_type_guid'] ) ) {
			$pParamHash['content_type_guid'] = $this->mContentTypeGuid;
		}

		if( @$this->verifyId( $pParamHash['content_id'] ) ) {
			$pParamHash['milestone_store']['content_id'] = $pParamHash['content_id'];
		}

		// oh no!
		// copy - paste from articles		
		if( !empty( $pParamHash['from_Month'] ) ) {
			$dateString = $pParamHash['from_Year'].'-'.$pParamHash['from_Month'].'-'.$pParamHash['from_Day'].' '.$pParamHash['from_Hour'].':'.$pParamHash['from_Minute'];
			//$timestamp = strtotime( $dateString );
			$timestamp = $gBitSystem->mServerTimestamp->getUTCFromDisplayDate( strtotime( $dateString ) );
			if( $timestamp !== -1 ) {
				$pParamHash['milestone_store']['date_from'] = $timestamp;
			}
		}
		
		if( !empty( $pParamHash['date_from'] ) ) {
			$pParamHash['milestone_store']['date_from'] = $pParamHash['date_from'];
		}
		
		// oh no!
		// copy - paste from articles		
		if( !empty( $pParamHash['to_Month'] ) ) {
			$dateString = $pParamHash['to_Year'].'-'.$pParamHash['to_Month'].'-'.$pParamHash['to_Day'].' '.$pParamHash['to_Hour'].':'.$pParamHash['to_Minute'];
			//$timestamp = strtotime( $dateString );
			$timestamp = $gBitSystem->mServerTimestamp->getUTCFromDisplayDate( strtotime( $dateString ) );
			if( $timestamp !== -1 ) {
				$pParamHash['milestone_store']['date_to'] = $timestamp;
			}
		}
		
		if( !empty( $pParamHash['date_to'] ) ) {
			$pParamHash['milestone_store']['date_to'] = $pParamHash['date_to'];
		}
		
		if( !empty( $pParamHash['data'] ) ) {
			$pParamHash['edit'] = $pParamHash['data'];
		}

		// check for name issues, first truncate length if too long
		if( !empty( $pParamHash['title'] ) ) {
			if( empty( $this->mMilestoneId ) ) {
				if( empty( $pParamHash['title'] ) ) {
					$this->mErrors['title'] = 'You must enter a name for this page.';
				} else {
					$pParamHash['content_store']['title'] = substr( $pParamHash['title'], 0, 160 );
				}
			} else {
				$pParamHash['content_store']['title'] =( isset( $pParamHash['title'] ) )? substr( $pParamHash['title'], 0, 160 ): '';
			}
		} else if( empty( $pParamHash['title'] ) ) {
			// no name specified
			$this->mErrors['title'] = 'You must specify a name';
		}

		return( count( $this->mErrors )== 0 );
	}

	/**
	 * expunge 
	 * 
	 * @access public
	 * @return boolean TRUE on success, FALSE on failure
	 */
	function expunge() {
		$ret = FALSE;
		if( $this->isValid() ) {
			$this->mDb->StartTrans();
			$query = "DELETE FROM `".BIT_DB_PREFIX."tickets` WHERE `content_id` = ?";
			$result = $this->mDb->query( $query, array( $this->mContentId ) );
			if( LibertyMime::expunge() ) {
				$ret = TRUE;
				$this->mDb->CompleteTrans();
			} else {
				$this->mDb->RollbackTrans();
			}
		}
		return $ret;
	}

	/**
	 * isValid Make sure tickets is loaded and valid
	 * 
	 * @access public
	 * @return boolean TRUE on success, FALSE on failure
	 */
	function isValid() {
		return( @BitBase::verifyId( $this->mMilestoneId ) && @BitBase::verifyId( $this->mContentId ));
	}

	/**
	 * getList This function generates a list of records from the liberty_content database for use in a list page
	 * 
	 * @param array $pParamHash 
	 * @access public
	 * @return array List of ticketss
	 */
	function getList( &$pParamHash ) {
		global $gBitSystem, $gBitUser;
		// this makes sure parameters used later on are set
		LibertyContent::prepGetList( $pParamHash );

		$selectSql = $joinSql = $whereSql = '';
		$bindVars = array();
		array_push( $bindVars, $this->mContentTypeGuid );
		$this->getServicesSql( 'content_list_sql_function', $selectSql, $joinSql, $whereSql, $bindVars );

		// this will set $find, $sort_mode, $max_records and $offset
		extract( $pParamHash );

		if( is_array( $find ) ) {
			// you can use an array of pages
			$whereSql .= " AND lc.`title` IN( ".implode( ',',array_fill( 0,count( $find ),'?' ) )." )";
			$bindVars = array_merge ( $bindVars, $find );
		} elseif( is_string( $find ) ) {
			// or a string
			$whereSql .= " AND UPPER( lc.`title` )like ? ";
			$bindVars[] = '%' . strtoupper( $find ). '%';
		}

		$query = "
			SELECT tm.*, lc.`title`, lc.`data` $selectSql
			FROM `".BIT_DB_PREFIX."ticket_milestone` tm
				INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON( lc.`content_id` = tm.`content_id` ) $joinSql
			WHERE lc.`content_type_guid` = ? $whereSql
			ORDER BY ".$this->mDb->convertSortmode( $sort_mode );
			
		$query_cant = "
			SELECT COUNT(*)
			FROM `".BIT_DB_PREFIX."ticket_milestone` tm
				INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON( lc.`content_id` = tm.`content_id` ) $joinSql
			WHERE lc.`content_type_guid` = ? $whereSql";
			
		$result = $this->mDb->query( $query, $bindVars/*, $max_records, $offset*/ );
		
		$ret = array();
		$ids = array();
		
		while( $res = $result->fetchRow() ) {
			$ret[$res['milestone_id']] = $res;
		}
		
		$pParamHash["cant"] = $this->mDb->getOne( $query_cant, $bindVars, $max_records, $offset );

		/*if ( $pParamHash["cant"] > 0 ) {
			
        	$query_tickets = "SELECT t.`ticket_id`
                FROM `".BIT_DB_PREFIX."tickets` t
                WHERE t.`milestone_id`=?";
			
			$result = $this->mDb->query( $query_tickets, $ids );
                
			while( $res = $result->fetchRow() ) 
				$ret[$res['milestone_id']]['tickets'][] = $res;
		}*/

		// add all pagination info to pParamHash
		LibertyContent::postGetList( $pParamHash );
		return $ret;
	}

	/**
	 * getDisplayUrl Generates the URL to the tickets page
	 * 
	 * @access public
	 * @return string URL to the tickets page
	 */
	function getDisplayUrl() {
		global $gBitSystem;
		$ret = NULL;
		if( @$this->isValid() ) {
			if( $gBitSystem->isFeatureActive( 'pretty_urls' ) || $gBitSystem->isFeatureActive( 'pretty_urls_extended' )) {
				$ret = TICKETS_PKG_URL.$this->mMilestoneId;
			} else {
				$ret = TICKETS_PKG_URL."index.php?milestone_id=".$this->mMilestoneId;
			}
		}
		return $ret;
	}

	static function getFields () {
		global $gBitDb;
		
		$query = "SELECT * FROM `".BIT_DB_PREFIX."ticket_fields` ORDER BY `field_guid`";
		
		$result = $gBitDb->query ($query);
		$ret = array ();
		
		while( $res = $result->fetchRow() ) {
			$ret[$res['field_guid']][] = $res;
		}
		
		return $ret;
	}

	static function getFieldNames () {
		global $gBitDb;
		
        // use group by rather then distinct
		$query = "SELECT DISTINCT `field_guid` FROM `".BIT_DB_PREFIX."ticket_fields` WHERE `isdeleted`=0 ORDER BY `field_guid`";
		
		$result = $gBitDb->query ($query);

        return $result;
	}
}
?>
