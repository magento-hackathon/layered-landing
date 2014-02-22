<?php

class Hackathon_Layeredlanding_Model_Observer extends Mage_Core_Model_Abstract
{
    public function addLayeredRouter($observer)
    {
        $front = $observer->getEvent()->getFront();

        $router = new Hackathon_Layeredlanding_Controller_Router();
        $front->addRouter('hackathon_layeredlanding', $router);
    }
}