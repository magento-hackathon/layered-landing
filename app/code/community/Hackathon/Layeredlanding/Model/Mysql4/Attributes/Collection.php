<?php
 
class Hackathon_Layeredlanding_Model_Mysql4_Attributes_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        //parent::__construct();
        $this->_init('layeredlanding/attributes');
    }
}