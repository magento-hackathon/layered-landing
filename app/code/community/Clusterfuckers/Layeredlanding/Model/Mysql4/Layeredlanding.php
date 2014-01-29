<?php
 
class Clusterfuckers_Layeredlanding_Model_Mysql4_Layeredlanding extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {   
        $this->_init('layeredlanding/layeredlanding', 'layeredlanding_id');
    }
}