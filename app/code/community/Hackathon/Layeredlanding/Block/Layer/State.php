<?php

class Hackathon_Layeredlanding_Block_Layer_State extends Mage_Catalog_Block_Layer_State
{
    public function getClearUrl()
    {
        $filterState = array();
        foreach ($this->getActiveFilters() as $item) {
            $filterState[$item->getFilter()->getRequestVar()] = $item->getFilter()->getCleanValue();
        }

        $params['_current']     = true;
        $params['_use_rewrite'] = true;
        $params['_query']       = $filterState;
        $params['_escape']      = true;

        $category = Mage::registry('current_category');
        if ($category) {
            return Mage::getModel('catalog/category')->load($category->getId())->getUrl();
        }

        return Mage::getUrl('*/*/*', $params);
    }

}