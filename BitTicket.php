<?php
/**
* $Header: /cvsroot/bitweaver/_bit_tickets/BitTicket.php,v 1.10 2008/11/23 18:55:51 pppspoonman Exp $
* $Id: BitTicket.php,v 1.10 2008/11/23 18:55:51 pppspoonman Exp $
*/

/**
* Tickets class to illustrate best practices when creating a new bitweaver package that
* builds on core bitweaver functionality, such as the Liberty CMS engine
*
* date created 2008/10/19
* @author SpOOnman <tomasz2k@poczta.onet.pl>
* @version $Revision: 1.10 $ $Date: 2008/11/23 18:55:51 $ $Author: pppspoonman $
* @class BitTicket
*/

require_once( LIBERTY_PKG_PATH.'LibertyMime.php' );

/**
* This is used to uniquely identify the object
*/
define( 'BITTICKET_CONTENT_TYPE_GUID', 'bitticket' );

class BitTicket extends LibertyMime {
	/**
	 * mTicketId Primary key for our mythical Ticket class object & table
	 * 
	 * @var array
	 * @access public
	 */
	var $mTicketId;

	/**
	 * Assignee user's id.
     * @var int
	 * @access public
	 */
	var $mAssigneeId;

    /**
     * Attributes.
     * @var array
     * @access public
     */
    var $mAttributes;

	/**
	 * BitTicket During initialisation, be sure to call our base constructors
	 * 
	 * @param numeric $pTicketId 
	 * @param numeric $pContentId 
	 * @access public
	 * @return void
	 */
	function BitTicket( $pTicketId=NULL, $pContentId=NULL ) {
		LibertyMime::LibertyMime();
		$this->mTicketId = $pTicketId;
		$this->mContentId = $pContentId;
		$this->mAssigneeId = NULL;
		$this->mAttributes = array();
		$this->mContentTypeGuid = BITTICKET_CONTENT_TYPE_GUID;
		$this->registerContentType( BITTICKET_CONTENT_TYPE_GUID, array(
			'content_type_guid'   => BITTICKET_CONTENT_TYPE_GUID,
			'content_description' => 'Tickets package provide a simple system of tracking tasks, requests or bugs.',
			'handler_class'       => 'BitTicket',
			'handler_package'     => 'tickets',
			'handler_file'        => 'BitTicket.php',
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
		if( $this->verifyId( $this->mTicketId ) || $this->verifyId( $this->mContentId ) ) {
			// LibertyContent::load()assumes you have joined already, and will not execute any sql!
			// This is a significant performance optimization
			$lookupColumn = $this->verifyId( $this->mTicketId ) ? 'ticket_id' : 'content_id';
			$bindVars = array();
			$selectSql = $joinSql = $whereSql = '';
			array_push( $bindVars, $lookupId = @BitBase::verifyId( $this->mTicketId ) ? $this->mTicketId : $this->mContentId );
			$this->getServicesSql( 'content_load_sql_function', $selectSql, $joinSql, $whereSql, $bindVars );

			$query = "
				SELECT s.*, lc.*,
				uue.`login` AS modifier_user, uue.`real_name` AS modifier_real_name,
				uuc.`login` AS creator_user, uuc.`real_name` AS creator_real_name
				$selectSql
				FROM `".BIT_DB_PREFIX."tickets` s
					INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON( lc.`content_id` = s.`content_id` ) $joinSql
					LEFT JOIN `".BIT_DB_PREFIX."users_users` uue ON( uue.`user_id` = lc.`modifier_user_id` )
					LEFT JOIN `".BIT_DB_PREFIX."users_users` uuc ON( uuc.`user_id` = lc.`user_id` )
				WHERE s.`$lookupColumn`=? $whereSql";

            $attrQuery = "SELECT ta.*, tf.`def_id`, tf.`field_id`, tf.`field_value`, td.`title` AS `def_title`
                FROM `".BIT_DB_PREFIX."ticket_attributes` ta
                    LEFT JOIN `".BIT_DB_PREFIX."ticket_field_values tf ON( ta.`field_id` = tf.`field_id` )
					LEFT JOIN `".BIT_DB_PREFIX."ticket_field_defs td ON( tf.`def_id` = td.`def_id` )
                WHERE ta.`ticket_id`=?";

			$result = $this->mDb->query( $query, $bindVars );

			if( $result && $result->numRows() ) {
				$this->mInfo = $result->fields;
				$this->mContentId = $result->fields['content_id'];
				$this->mTicketId = $result->fields['ticket_id'];

				$this->mInfo['creator'] = ( !empty( $result->fields['creator_real_name'] ) ? $result->fields['creator_real_name'] : $result->fields['creator_user'] );
				$this->mInfo['editor'] = ( !empty( $result->fields['modifier_real_name'] ) ? $result->fields['modifier_real_name'] : $result->fields['modifier_user'] );
				$this->mInfo['display_name'] = BitUser::getTitle( $this->mInfo );
				$this->mInfo['display_url'] = $this->getDisplayUrl();
				$this->mInfo['parsed_data'] = $this->parseData();

                $attrResult = $this->mDb->query( $attrQuery, array ( $this->mTicketId ) );
                
                while ( $row = $attrResult->fetchRow() ) {
                    $this->mAttributes[$row["def_id"]] = $row;
                }

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
			$table = BIT_DB_PREFIX."tickets";
            $attrTable = BIT_DB_PREFIX."ticket_attributes";
            

			if( $this->mTicketId ) {
				$locId = array( "ticket_id" => $pParamHash['ticket_id'] );
				$result = $this->mDb->associateUpdate( $table, $pParamHash['ticket_store'], $locId );

                foreach( $pParamHash['ticket_store']['attributes'] as $attr) {
                    // if attributes has changed update row and store one in history
                    if ( !( array_has_key( $this->mAttributes, $attr['field_guid'] ) ) ) {
                        $locId ['field_id'] = $attr['field_id'];
                        $result = $this->mDB->associateInsert( $attrTable, array( 'ticket_id' => $this->mTicketId, $attr ) );
                    }
                    else if ( $this->mAttributes[$attr['field_guid']]['field_value'] != $attr['field_value'] ) {
                        $locId ['field_id'] = $attr['field_id'];
                        $result = $this->mDB->associateUpdate( $attrTable, array( 'ticket_id' => $this->mTicketId, $attr ) );
                    }
                }


			} else {
				$pParamHash['ticket_store']['content_id'] = $pParamHash['content_id'];
				if( @$this->verifyId( $pParamHash['ticket_id'] ) ) {
					// if pParamHash['ticket_id'] is set, some is requesting a particular ticket_id. Use with caution!
					$pParamHash['ticket_store']['ticket_id'] = $pParamHash['ticket_id'];
				} else {
					$pParamHash['ticket_store']['ticket_id'] = $this->mDb->GenID( 'tickets_ticket_id_seq' );
				}
				$this->mTicketId = $pParamHash['ticket_store']['ticket_id'];
				$result = $this->mDb->associateInsert( $table, $pParamHash['ticket_store'] );
				
                foreach( $pParamHash['attributes_store'] as $attr) {
                    $result = $this->mDb->associateInsert( $attrTable, array( 'ticket_id' => $this->mTicketId, 'field_id' => $attr ) );
                }
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
		
		// make sure we're all loaded up of we have a mTicketId
		if( $this->verifyId( $this->mTicketId ) && empty( $this->mInfo ) ) {
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
			$pParamHash['ticket_store']['content_id'] = $pParamHash['content_id'];
		}

		// check for name issues, first truncate length if too long
		if( !empty( $pParamHash['title'] ) ) {
			if( empty( $this->mTicketId ) ) {
				if( empty( $pParamHash['title'] ) ) {
					$this->mErrors['title'] = 'You must enter a name for this ticket.';
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
		
		if( !empty( $pParamHash['attributes'] ) ) {
			$pParamHash['attributes_store'] = $pParamHash['attributes'];
			unset( $pParamHash['attributes'] );
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
			$attrQuery = "DELETE FROM `".BIT_DB_PREFIX."ticket_attributes` WHERE `ticket_id` = ?";
			$query = "DELETE FROM `".BIT_DB_PREFIX."tickets` WHERE `content_id` = ?";
			
			// first delete attributes not to violate constraints
			$attrResult = $this->mDb->query( $attrQuery, array( $this->mTicketId) );
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
		return( @BitBase::verifyId( $this->mTicketId ) && @BitBase::verifyId( $this->mContentId ));
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
			SELECT ts.*, lc.`title`, lc.`data`, uuc.`login` AS creator_user, uuc.`real_name` AS creator_real_name $selectSql
			FROM `".BIT_DB_PREFIX."tickets` ts
				INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON( lc.`content_id` = ts.`content_id` ) $joinSql
				LEFT JOIN `".BIT_DB_PREFIX."users_users` uuc ON( uuc.`user_id` = lc.`user_id` )
			WHERE lc.`content_type_guid` = ? $whereSql
			ORDER BY ".$this->mDb->convertSortmode( $sort_mode );
		$query_cant = "
			SELECT COUNT(*)
			FROM `".BIT_DB_PREFIX."tickets` ts
				INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON( lc.`content_id` = ts.`content_id` ) $joinSql
			WHERE lc.`content_type_guid` = ? $whereSql";
		$result = $this->mDb->query( $query, $bindVars, $max_records, $offset );

        
		$ret = array();
		$ids = array();
		while( $res = $result->fetchRow() ) {
            $ids[] = $res['ticket_id'];
			$ret[$res['ticket_id']] = $res;
		}
		
		$pParamHash["cant"] = $this->mDb->getOne( $query_cant, $bindVars );
		
		if ( $pParamHash["cant"] > 0 ) {
			$in = implode(',', array_fill(0, $pParamHash["cant"], '?'));
			
			$query_attr = "SELECT ta.*, td.`def_id`, tf.`field_id`, tf.`field_value`
                FROM `".BIT_DB_PREFIX."ticket_attributes` ta
                    LEFT JOIN `".BIT_DB_PREFIX."ticket_field_values tf ON( ta.`field_id` = tf.`field_id` )
					LEFT JOIN `".BIT_DB_PREFIX."ticket_field_defs td ON( tf.`def_id` = td.`def_id` )
                WHERE ta.`ticket_id` IN ($in) $whereSql
				ORDER BY td.`sort_order`";
				
			$result = $this->mDb->query( $query_attr, $ids );
			
			while( $res = $result->fetchRow() ) 
				$ret[$res['ticket_id']]['attributes'][$res['def_id']] = $res;
		}
		
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
				$ret = TICKETS_PKG_URL.$this->mTicketId;
			} else {
				$ret = TICKETS_PKG_URL."index.php?ticket_id=".$this->mTicketId;
			}
		}
		return $ret;
	}

	static function getFieldValues () {
		global $gBitDb;
		
		$query = "SELECT * FROM `".BIT_DB_PREFIX."ticket_field_values` ORDER BY `sort_order`";
		
		$result = $gBitDb->query ($query);
		$ret = array ();
		
		while( $res = $result->fetchRow() ) {
			$ret[$res['def_id']][] = $res;
		}
		
		return $ret;
	}

	static function getFieldDefinitions () {
		global $gBitDb;
		
        // use group by rather then distinct
		$query = "SELECT * FROM `".BIT_DB_PREFIX."ticket_field_defs` WHERE `is_enabled`=1 ORDER BY `sort_order`";
		
		$result = $gBitDb->query ($query);

        return $result;
	}
}
?>
