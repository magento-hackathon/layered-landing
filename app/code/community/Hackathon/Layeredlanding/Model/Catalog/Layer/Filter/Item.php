<?php

class Hackathon_Layeredlanding_Model_Catalog_Layer_Filter_Item extends Mage_Catalog_Model_Layer_Filter_Item
{
    public function getRemoveUrl()
    {
        /** @var $category Mage_Catalog_Model_Category */
        $category = Mage::registry('current_category');

        /** @var $landingPage Hackathon_Layeredlanding_Model_Layeredlanding */
        $landingPage = Mage::registry('current_landingpage');

        if ($category->getId() && $landingPage && $landingPage->getId()) {

            $query = array($this->getFilter()->getRequestVar() => $this->getFilter()->getResetValue());
            $params['_current'] = true;
            $params['_use_rewrite'] = true;
            $params['_query'] = $query;
            $params['_escape'] = true;

            $attributeModel = Mage::getModel('eav/entity_attribute')
                ->loadByCode(10, $this->getFilter()->getRequestVar());

            $attributeIds = array();
            foreach ($landingPage->getAttributes() as $attribute) {
                $attributeIds[] = $attribute->getAttributeId();
            }

            if ($attributeModel->getId() && in_array($attributeModel->getAttributeId(), $attributeIds)) {
                $parameters = parse_url(Mage::getUrl('*/*/*', $params), PHP_URL_QUERY);
                $categoryUrl = parse_url($category->getUrl(), PHP_URL_PATH);

                return sprintf('%s?%s', $categoryUrl, $parameters);
            }

            return Mage::getUrl('*/*/*', $params);
        }

        return parent::getRemoveUrl();
    }

}