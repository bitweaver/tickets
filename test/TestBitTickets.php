<?php
require_once('../../kernel/setup_inc.php');
require_once(TICKETS_PKG_PATH.'BitTicket.php');

class TestBitTicket extends Test {
    
    var $test;
    var $id;
    var $count;
    
    function TestBitTicket()
    {
        global $gBitSystem;
        Assert::equalsTrue($gBitSystem->isPackageActive( 'tickets' ), 'Package not active');
    }

    function testCreateItem()
    {
        $this->test = new BitTicket();
        Assert::equalsTrue($this->test != NULL, 'Error during initialisation');
    }

    function testGetItems()
    {
	$filter = array();
        $list = $this->test->getList($filter);
        $this->count = count($list);
        Assert::equalsTrue(is_array($list));
    }

    function testStoreItem()
    {
	$newItemHash = array(
		"title" => "Test Title",
		"description" => "Test Description",
		"data" => "Test Text"
	);
        Assert::equalsTrue($this->test->store($newItemHash));
    }
    
    function testIsValidItem()
    {
        Assert::equalsTrue($this->test->isValid());
    }
    
    function testNullItem()
    {
	$this->id = $this->test->mTicketId;
        $this->test = NULL;
        Assert::equals($this->test, NULL);
    }
    
    function testLoadItem()
    {
        $this->test = new BitTicket($this->id);
        Assert::equals($this->test->load(), 23);
    }

    function testUrlItem()
    {
        Assert::equalsTrue($this->test->getDisplayUrl() != "");
    }

    function testExpungeItem()
    {
        Assert::equalsTrue($this->test->expunge());
    }

    function testCountItems()
    {
	$filter = array();
        $count = count($this->test->getList($filter));
        Assert::equals($this->count, $count);
    }

}
?>
