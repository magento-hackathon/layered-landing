<?php
 
class Hackathon_Layeredlanding_Model_Mysql4_Layeredlanding_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        //parent::__construct();
        $this->_init('layeredlanding/layeredlanding');
    }

    public function addStoreFilter($store_id = null)
    {
        if (is_null($store_id)) {
            $store_id = Mage::app()->getStore()->getId();
        }

        $this->getSelect()->where(" (CONCAT(',', `store_ids`, ',') LIKE '%,{$store_id},%' OR `store_ids` = 0)");

        return $this;
    }
}