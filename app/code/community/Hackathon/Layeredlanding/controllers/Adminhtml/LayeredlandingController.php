<?php
 
class Hackathon_Layeredlanding_Adminhtml_LayeredlandingController extends Mage_Adminhtml_Controller_Action
{

	protected function _initAction()
	{
		$this->loadLayout()
			->_setActiveMenu('catalog/attributes/layeredlanding')
			->_addBreadcrumb(Mage::helper('layeredlanding')->__('Landingpage Manager'), Mage::helper('layeredlanding')->__('Landingpage Manager'));
		return $this;
	}   
   
	public function indexAction() {
		$this->_initAction();       
		$this->_addContent($this->getLayout()->createBlock('layeredlanding/adminhtml_layeredlanding'));
		$this->renderLayout();
	}
 
	public function editAction()
	{
		$layeredlandingId		= $this->getRequest()->getParam('id');
		$layeredlandingModel	= Mage::getModel('layeredlanding/layeredlanding')->load($layeredlandingId);

		if ($layeredlandingModel->getId() || $layeredlandingId == 0) {

			Mage::register('layeredlanding_data', $layeredlandingModel);

			$this->loadLayout();
			$this->_setActiveMenu('layeredlanding/items');

			$this->_addBreadcrumb(Mage::helper('layeredlanding')->__('Landingpage Manager'), Mage::helper('layeredlanding')->__('Landingpage Manager'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('layeredlanding/adminhtml_layeredlanding_edit'))
				->_addLeft($this->getLayout()->createBlock('layeredlanding/adminhtml_layeredlanding_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('layeredlanding')->__('Landingpage does not exist'));
			$this->_redirect('*/*/');
		}
	}
   
	public function newAction()
	{
		$this->_forward('edit');
	}

    public function saveAction() {
        if ($this->getRequest()->getPost()) {
            try {
                $post_data = $this->getRequest()->getPost();

                $post_data['store_ids'] = implode(',', $post_data['store_ids']);
		    
                if (is_array($post_data['category_ids'])) {
                    $post_data['category_ids'] = implode(',', $post_data['category_ids']);
                }

                $model = Mage::getModel('layeredlanding/layeredlanding');

                $model->setId($this->getRequest()->getParam('id'))
                    ->setData('meta_title', $post_data['meta_title'])
                    ->setData('meta_keywords', $post_data['meta_keywords'])
                    ->setData('meta_description', $post_data['meta_description'])
                    ->setData('page_title', $post_data['page_title'])
                    ->setData('page_description', $post_data['page_description'])
                    ->setData('page_url', $post_data['page_url'])
                    ->setData('display_layered_navigation', $post_data['display_layered_navigation'])
                    ->setData('display_in_top_navigation', $post_data['display_in_top_navigation'])
                    ->setData('custom_layout_template', $post_data['custom_layout_template'])
                    ->setData('custom_layout_update', $post_data['custom_layout_update'])
                    ->setData('store_ids', $post_data['store_ids'])
                    ->setData('category_ids', $post_data['category_ids']);

                if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
                    try {
                        $uploader = new Varien_File_Uploader('image');

                        $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                        $uploader->setAllowRenameFiles(false);
                        $uploader->setFilesDispersion(false);

                        // Set media as the upload dir
                        $media_path = Mage::getBaseDir('media') . '/landingpages/';
                        $imgFilename = $media_path . $_FILES['image']['name'];

                        while (file_exists($imgFilename)) {
                            $pieces = array();

                            $res = preg_match('/^(.+)_(\d+)$/', $imgFilename, $pieces);

                            if (!$res) {
                                $imgFilename .= '_1';
                            } else {
                                $imgFilename .= '_' . strval(intval($pieces[2]) + 1);
                            }
                        }

                        // Upload the image
                        $uploader->save($media_path, $_FILES['image']['name']);
                        $model->setData('image', '/landingpages/' . $_FILES['image']['name']);
                    } catch (Exception $e) {
                        Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                        $this->_redirect('*/*/');
                    }
                } else {
                    $model->unsetData('image');
                }

                if (isset($post_data['image']) && $post_data['image']['delete']) {
                    $model->setData('image', '');
                }

                $model->save();

                $attributelanding_id = $model->getId();

                // save opening hours
                if (isset($post_data['attributes'])) {
                    foreach ($post_data['attributes']['delete'] as $_key => $_row) {
                        $delete = (int)$_row;
                        $object_data = $post_data['attributes']['value'][$_key];

                        $attributes_object = Mage::getModel('layeredlanding/attributes')->load((int)$object_data['id']);

                        if ($delete && 0 < (int)$attributes_object->getId()) // exists & required to delete
                        {
                            $attributes_object->delete();
                            continue;
                        }

                        // Check if the attribute and value values are set
                        $can_save = true;
                        if (0 == (int)$attributes_object->getId() && (empty($object_data['value']) || empty($object_data['attribute']))) // new item but no values
                        {
                            $can_save = false;
                        } elseif (0 < (int)$attributes_object->getId() && empty($object_data['value'])) // existing item but no value
                        {
                            $can_save = false;
                        }

                        if (!$delete && $can_save) // save if not deleted and data checks out
                        {
                            $attributes_object->setData('layeredlanding_id', $attributelanding_id);
                            $attributes_object->setData('attribute_id', $object_data['attribute']);
                            $attributes_object->setData('value', $object_data['value']);

                            $attributes_object->save();
                        }
                    }
                }

                // And wrap up the transaction
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('layeredlanding')->__('Landingpage was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setLayeredlandingData(false);

                if ($this->getRequest()->getParam("back")) {
                    $this->_redirect("*/*/edit", array("id" => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setLayeredlandingData($this->getRequest()->getPost());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        $this->_redirect('*/*/');
    }
   
	public function deleteAction()
	{
		if( $this->getRequest()->getParam('id') > 0 )
		{
			try {
				$model = Mage::getModel('layeredlanding/layeredlanding');

				$model->setId($this->getRequest()->getParam('id'))
					->delete();

				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('layeredlanding')->__('Landingpage was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}
	
	/**
	* Product grid for AJAX request.
	* Sort and filter result for example.
	*/
	public function gridAction()
	{
		$this->loadLayout();
		$this->getResponse()->setBody(
			$this->getLayout()->createBlock('layeredlanding/adminhtml_layeredlanding_grid')->toHtml()
		);
	}

    public function ajaxValuesAction()
    {
        $request = Mage::app()->getRequest();

        $attribute_id = (int)$request->getParam('attributeid', false);
        $store_id = $request->getParam('storeid', 0);
        $input_name = $request->getParam('inputname');
		
		echo Mage::getModel('layeredlanding/attributes')->getGridOptionsHtml($attribute_id, $store_id, 0, $input_name);
    }

    public function ajaxCountResultAction()
    {
        $request = Mage::app()->getRequest()->getParams();
		
		$category = $request['category'];
		$stores = explode(',', trim($request['store'], ','));
		
		unset($request['isAjax'], $request['form_key'], $request['category'], $request['store']);
		
		foreach ($stores as $store)
		{
			$collection = Mage::getModel('catalog/product')->getCollection();
			
			// get not only this cat, but also it's childrens products
			$categories = Mage::getModel('catalog/category')->load((int)$category)->getAllChildren(true);
			$collection->joinField('category_id', 'catalog/category_product', 'category_id', 'product_id=entity_id', null, 'left');
			$collection->addAttributeToFilter('category_id', array('in' => $categories));
						
			$collection->setStoreId((int)$store);
			
			foreach ($request as $attribute_id => $value)
			{
				$attribute_code = Mage::helper('layeredlanding')->attributeIdToCode($attribute_id);
				if ($attribute_code == 'price')
				{
					$price_range = explode('-', $value);
					$collection->addFieldToFilter('price', array('from'=>$price_range[0],'to'=>$price_range[1]));
				}
				else
				{
					$collection->addAttributeToFilter($attribute_code, $value);
				}
			}
			
			Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
			
			//var_dump((string)$collection->getSelect());exit;
			
			echo Mage::helper('layeredlanding')->__('Estimated product count for store \'%s\' is %d', Mage::app()->getStore($store)->getName(), $collection->getSize()) . "<br/>";
		}
    }

}
