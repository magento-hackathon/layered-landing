<?php

class Hackathon_Layeredlanding_Controller_Router extends Mage_Core_Controller_Varien_Router_Standard
{
    /**
     * Helper function to register the current router at the front controller. 
     * 
     * @param Varien_Event_Observer $observer The event observer for the controller_front_init_routers event
     * @event controller_front_init_routers
     */
    public function addLayeredRouter($observer)
    {
        $front = $observer->getEvent()->getFront();
        
        $router = new Hackathon_Layeredlanding_Controller_Router();
        $front->addRouter('layeredlanding', $router);
    }

    public function match(Zend_Controller_Request_Http $request)
    {
        var_dump('test');
		exit;
    }
}