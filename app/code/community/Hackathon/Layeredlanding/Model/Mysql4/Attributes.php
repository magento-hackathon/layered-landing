<?php
 
class Hackathon_Layeredlanding_Model_Mysql4_Attributes extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {   
        $this->_init('layeredlanding/attributes', 'layeredlanding_attributes_id');
    }
}