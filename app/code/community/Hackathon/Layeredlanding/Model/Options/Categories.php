<?php

class Hackathon_Layeredlanding_Model_Options_Categories extends Mage_Core_Model_Abstract
{

    public function toOptionArray()
    {
        $categories = array();

        $allCategoriesCollection = Mage::getModel('catalog/category')
            ->getCollection()
            ->addAttributeToSelect('name')
            ->addFieldToFilter('level', array('gt'=>'0'));

        $allCategoriesArray = $allCategoriesCollection->load()->toArray();

        $categoriesArray = $allCategoriesCollection
            ->addAttributeToSelect('level')
            ->addAttributeToSort('path', 'asc')
            ->addFieldToFilter('is_active', array('eq'=>'1'))
            ->addFieldToFilter('level', array('gt'=>'1'))
            ->load()
            ->toArray();

        foreach ($categoriesArray as $categoryId => $category)
        {
            if (!isset($category['name'])) {
                continue;
            }
            $categoryIds = explode('/', $category['path']);
            $nameParts = array();
            foreach($categoryIds as $catId) {
                if($catId == 1) {
                    continue;
                }
                $nameParts[] = $allCategoriesArray[$catId]['name'];
            }
            $categories[$categoryId] = array(
                'value' => $categoryId,
                'label' => implode(' / ', $nameParts)
            );
        }

        // If landingpage already exists, move chosen categories to top of list for clarity
        $landingpageId = Mage::app()->getRequest()->getParam('id');
        if($landingpageId) {
            $landingpage = Mage::getModel('layeredlanding/layeredlanding')->load($landingpageId);
            $categoryIds = explode(',', $landingpage->getCategoryIds());
            foreach($categoryIds as $catId) {
                $this->moveToTop($categories, $catId);
            }
        }

        return $categories;
    }

    private function moveToTop(&$array, $key) {
        $temp = array($key => array('value' => $array[$key]['value'], 'label' => $array[$key]['label']));
        unset($array[$key]);
        $array = $temp + $array;
    }
}