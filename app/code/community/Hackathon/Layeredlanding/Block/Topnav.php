<?php

class Hackathon_Layeredlanding_Block_Topnav extends Mage_Core_Block_Template
{

    protected function _toHtml()
    {
        $collection = Mage::getModel('layeredlanding/layeredlanding')
            ->getCollection()
            ->addFieldToFilter('display_in_top_navigation', 1);

        $html = '';

        /** @var $landingpage Hackathon_Layeredlanding_Model_Layeredlanding */
        foreach ($collection as $landingpage)
        {
            $html .= '<li><a href="'.$landingpage->getUrl().'">'.$this->escapeHtml($landingpage->getPageTitle()).'</a></li>';
        }

        return $html;
    }

}