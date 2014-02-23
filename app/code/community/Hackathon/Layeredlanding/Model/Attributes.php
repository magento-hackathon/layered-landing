<?php
 
class Hackathon_Layeredlanding_Model_Attributes extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('layeredlanding/attributes');
    }
}