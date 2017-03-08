<?php

class Hackathon_Layeredlanding_Controller_Router extends Mage_Core_Controller_Varien_Router_Standard
{
    /**
     * @param Zend_Controller_Request_Http $request
     * @return bool
     */
    public function match(Zend_Controller_Request_Http $request) {
        if (!Mage::isInstalled()) {
            Mage::app()->getFrontController()->getResponse()
                ->setRedirect(Mage::getUrl('install'))
                ->sendResponse();
            exit;
        }

        $identifier = trim($request->getPathInfo(), '/');

        /* @var $parser Hackathon_Layeredlanding_Model_Layeredlanding */
        $landingPage = Mage::getModel('layeredlanding/layeredlanding')
            ->loadByUrl($identifier);

        if (!$landingPage->getId()) {
            return false;
        }

        Mage::register('current_landingpage', $landingPage);

        Mage::app()->getStore()->setConfig(Mage_Catalog_Helper_Category::XML_PATH_USE_CATEGORY_CANONICAL_TAG, 0); // disable canonical tag

        // if successfully gained url parameters, use them and dispatch ActionController action
        $categoryIdsValue = $landingPage->getCategoryIds();
        $categoryIds = explode(',', $categoryIdsValue);
        $firstCategoryId = $categoryIds[0];
        $request->setRouteName('catalog')
            ->setModuleName('catalog')
            ->setControllerName('category')
            ->setActionName('view')
            ->setParam('id', $firstCategoryId);

        if (Mage::helper('core')->isModuleEnabled('Emico_Tweakwise')) {
            require_once Mage::getModuleDir('controllers', 'Emico_Tweakwise') . DS . 'Catalog' . DS . 'CategoryController.php';

            $filters = array();
            foreach ($landingPage->getAttributes()->getData() as $filterArray) {
                $attributeModel = Mage::getModel('eav/entity_attribute')->load($filterArray['attribute_id']);
                $value = $attributeModel->getSource()->getOptionText($filterArray['value']);

                if (!isset($filters[$attributeModel->getAttributeCode()])) {
                    $filters[$attributeModel->getAttributeCode()] = [$value];
                } else {
                    $filters[$attributeModel->getAttributeCode()][] = $value;
                }
            }

            $query = array();
            foreach ($filters as $attributeCode => $values) {
                $query[$attributeCode] = implode('|',  $values);
            }

            $controllerClassName = 'Emico_Tweakwise_Catalog_CategoryController';

            $request->setRequestUri($request->getRequestUri() . '?' . http_build_query($query));
        } else {
            /** @var $attribute Hackathon_Layeredlanding_Model_Attributes */
            foreach ($landingPage->getAttributes() as $attribute) {
                $attr = Mage::getModel('eav/entity_attribute')->load($attribute->getAttributeId());
                $request->setParam($attr->getAttributeCode(), $attribute->getValue());
            }
            $controllerClassName = $this->_validateControllerClassName('Mage_Catalog', 'category');
        }

        $controllerInstance = Mage::getControllerInstance($controllerClassName, $request, $this->getFront()->getResponse());

        $request->setAlias(
            Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS,
            $identifier
        );

        // dispatch action
        $request->setDispatched(true);
        $controllerInstance->dispatch('view');

        return true;
    }
}
