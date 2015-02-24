<?php

class Hackathon_Layeredlanding_Block_Adminhtml_Layeredlanding_Edit_Tab_Conditions extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
		
        $fieldset = $form->addFieldset('layeredlanding_form', array(
			'legend' => Mage::helper('layeredlanding')->__('Landingpage Conditions'),
			'class' => 'fieldset-wide'
		));

        $fieldset->addField('category_ids', 'multiselect', array(
			'label' => Mage::helper('layeredlanding')->__('Categories'),
			'class' => 'required-entry',
			'required' => true,
			'name' => 'category_ids',
			'onchange' => '_estimate_product_count();',
			'values' => Mage::getSingleton('layeredlanding/options_categories')->toOptionArray(),
            'style' => 'width:80%'
		));

        /**
         * Check is single store mode
         */
        if (!Mage::app()->isSingleStoreMode()) {
            $field = $fieldset->addField('store_ids', 'multiselect', array(
				'name' => 'store_ids',
				'label' => Mage::helper('cms')->__('Store View'),
				'title' => Mage::helper('cms')->__('Store View'),
				'required' => true,
				'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
				'onchange' => '_estimate_product_count();',
			));
			
            $renderer = $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element');
            $field->setRenderer($renderer);
        } else {
            $fieldset->addField('store_ids', 'hidden', array(
				'name' => 'store_ids',
				'value' => Mage::app()->getStore(true)->getId(),
				'onchange' => '_estimate_product_count();',
			));
        }

        $fieldset->addField('attributes', 'text', array(
			'name' => 'attributes',
			'label' => Mage::helper('layeredlanding')->__('Attributes'),
			'required' => false,
		));

        $attributes = $form->getElement('attributes');

        $attributes->setRenderer(
            $this->getLayout()->createBlock('layeredlanding/adminhtml_layeredlanding_edit_renderer_attributes')
        );

        if (Mage::getSingleton('adminhtml/session')->getLayeredlandingData()) {
            $data = Mage::getSingleton('adminhtml/session')->getLayeredlandingData();
            Mage::getSingleton('adminhtml/session')->setLayeredlandingData(null);
        } elseif (Mage::registry('layeredlanding_data')) {
            $data = Mage::registry('layeredlanding_data')->getData();
        }

        // modify multiselect values
        $data['store_ids'] = explode(',', $data['store_ids']);
        $data['category_ids'] = explode(',', $data['category_ids']);

        $form->setValues($data);

        return parent::_prepareForm();
    }
}