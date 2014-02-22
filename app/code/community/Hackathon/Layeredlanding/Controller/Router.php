<?php

class Hackathon_Layeredlanding_Controller_Router extends Mage_Core_Controller_Varien_Router_Standard
{
    public function match(Zend_Controller_Request_Http $request)
    {
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

        // if successfully gained url parameters, use them and dispatch ActionController action
        $request->setRouteName('catalog')
            ->setModuleName('catalog')
            ->setControllerName('category')
            ->setActionName('view')
            ->setParam('id', $landingPage->getCategoryIds());
        $pathInfo = 'catalog/category/view/id/' . $landingPage->getCategoryIds();
        $requestUri = '/' . $pathInfo . '?';

        /** @var $attribute Hackathon_Layeredlanding_Model_Attributes */
        foreach ($landingPage->getAttributes() as $attribute) {
            $attr = Mage::getModel('eav/entity_attribute')->load($attribute->getAttributeId());
            $request->setParam($attr->getAttributeCode(), $attribute->getValue());
        }

        $controllerClassName = $this->_validateControllerClassName('Mage_Catalog', 'category');
        $controllerInstance = Mage::getControllerInstance($controllerClassName, $request, $this->getFront()->getResponse());

        $request->setRequestUri(substr($requestUri, 0, -1));
        $request->setPathInfo($pathInfo);
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