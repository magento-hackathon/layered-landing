<?php

class Hackathon_Layeredlanding_Test_ConfigTest extends EcomDev_PHPUnit_Test_Case_Config
{
    /**
     * @test
     */
    public function checkRegisteredObsevers()
    {
        $this->assertEventObserverDefined(
            'global',
            'controller_front_init_routers',
            'layeredlanding/observer',
            'addLayeredRouter'
        );
    }
}