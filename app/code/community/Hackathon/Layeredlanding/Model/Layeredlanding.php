<?php
 
class Hackathon_Layeredlanding_Model_Layeredlanding extends Mage_Core_Model_Abstract
{
    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'layered_landing';

    public function _construct()
    {
        parent::_construct();
        $this->_init('layeredlanding/layeredlanding');
    }
	
	public function getAttributes()
	{
		return Mage::getModel('layeredlanding/attributes')->getCollection()
					->addFieldToFilter('layeredlanding_id', $this->getId());
	}

    public function loadByUrl($url)
    {
        $collection = $this->getCollection()
            ->addFieldToSelect('layeredlanding_id')
            ->addFieldToSelect('store_ids')
            ->addFieldToFilter('page_url', array('eq' => $url));

        if ($collection->getSize()) 
		{
			$store_ids = explode(',', $collection->getFirstItem()->getStoreIds());
			if (in_array(Mage::app()->getStore()->getId(), $store_ids) || in_array('0', $store_ids)) // check if the item applies to the store or to system level
			{
				$this->load($collection->getFirstItem()->getId());
			}
        }

        return $this;
    }

    public function getUrl()
    {
        return Mage::getUrl().$this->getPageUrl();
    }
	
	public function addStoreFilter(&$collection, $store_id = null)
	{
		if (is_null($store_id)) $store_id = Mage::app()->getStore()->getId();
		
		$select = $collection->getSelect()->where(" CONCAT(',', `store_ids`, ',') LIKE '%,{$store_id},%'");
	}
}