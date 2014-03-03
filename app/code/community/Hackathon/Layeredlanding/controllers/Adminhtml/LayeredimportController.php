<?php
 
class Hackathon_Layeredlanding_Adminhtml_LayeredimportController extends Mage_Adminhtml_Controller_Action
{

	protected function _initAction()
	{
		$this->loadLayout()
			->_setActiveMenu('catalog/attributes/layeredlandingimport')
			->_addBreadcrumb(Mage::helper('layeredlanding')->__('Landingpage Import tool'), Mage::helper('layeredlanding')->__('Landingpage Import tool'));
		return $this;
	}   
   
	public function indexAction() {
		die('till here');
        $this->_initAction();
		$this->_addContent($this->getLayout()->createBlock('layeredlanding/adminhtml_layeredimport'));
		$this->renderLayout();
	}
}