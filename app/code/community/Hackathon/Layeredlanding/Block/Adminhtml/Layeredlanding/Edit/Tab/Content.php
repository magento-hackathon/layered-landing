<?php

class Hackathon_Layeredlanding_Block_Adminhtml_Layeredlanding_Edit_Tab_Content extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareLayout()
    {
        $return = parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        return $return;
    }

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);

        $fieldset = $form->addFieldset('layeredlanding_form', array(
            'legend' => Mage::helper('layeredlanding')->__('Landingpage Content'),
            'class' => 'fieldset-wide'
        ));

        $fieldset->addField('page_url', 'text', array(
            'label' => Mage::helper('layeredlanding')->__('Page URL'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'page_url',
        ));

        $fieldset->addField('page_title', 'text', array(
            'label' => Mage::helper('layeredlanding')->__('Page title'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'page_title',
        ));

        $fieldset->addField('page_description', 'editor', array(
            'label' => Mage::helper('layeredlanding')->__('Page description'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'page_description',
            'style' => "width:500px;height:350px;",
            'config' => Mage::getSingleton('cms/wysiwyg_config')->getConfig(),
        ));

        $fieldset->addField('image', 'image', array(
            'label' => Mage::helper('layeredlanding')->__('Image'),
            'required' => false,
            'name' => 'image',
        ));

        $fieldset->addField('meta_title', 'text', array(
            'label' => Mage::helper('layeredlanding')->__('Meta title'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'meta_title',
        ));

        $fieldset->addField('meta_keywords', 'text', array(
            'label' => Mage::helper('layeredlanding')->__('Meta keywords'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'meta_keywords',
        ));

        $fieldset->addField('meta_description', 'textarea', array(
            'label' => Mage::helper('layeredlanding')->__('Meta description'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'meta_description',
            'style' => "width:500px;",
        ));

        if (Mage::getSingleton('adminhtml/session')->getLayeredlandingData()) {
            $data = Mage::getSingleton('adminhtml/session')->getLayeredlandingData();
            Mage::getSingleton('adminhtml/session')->setLayeredlandingData(null);
        } elseif (Mage::registry('layeredlanding_data')) {
            $data = Mage::registry('layeredlanding_data')->getData();
        }

        // modify multiselect values
        if (isset($data['store_ids'])) {
            $data['store_ids'] = explode(',', $data['store_ids']);
        } else {
            $data['store_ids'] = array(1);
        }
        if (isset($data['category_ids'])) {
            $data['category_ids'] = explode(',', $data['category_ids']);
        } else {
            $data['category_ids'] = array();
        }
        $form->setValues($data);

        return parent::_prepareForm();
    }
}
