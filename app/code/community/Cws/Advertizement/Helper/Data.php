<?php
/**
 * Cws Advertizement Plugin
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * It is available on the World Wide Web at:
 * http://opensource.org/licenses/osl-3.0.php
 * If you are unable to access it on the World Wide Web, please send an email
 * To: javed.alam@cwsinfotech.com.  We will send you a copy of the source file.
 *
 * @category   Advertizement Plugin
 * @package    Cws_Advertizement
 * @copyright  Copyright (c) 2014 Cws Technology Pvt. Ltd., India
 *             http://www.cwstechnology.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Javed Alam <javed.alam@cwsinfotech.com>
 */

class Cws_Advertizement_Helper_Data extends Mage_Core_Helper_Abstract
{

const MARKETPLACE_ENABLE = "marketplace/marketplace/enable";
const MARKETPLACE_STATUS_APPROVED = "marketplace/status/approved";




		public function isMarketplaceEnabled() {
				//return (bool) Mage::getStoreConfig(self::MARKETPLACE_ENABLE, Mage::app()->getStore());
				return true;
		}

		public function isMarketplaceActiveSellar()
		{
			return true;
			/*if ($this->isMarketplaceEnabled()) {
			$customer = Mage::getSingleton('customer/session')->getCustomer();
			if ($customer->getStatus() == Mage::getStoreConfig(self::MARKETPLACE_STATUS_APPROVED) && (bool) $customer->getSellerSubscriber()) {
			return true;
			} else {
			return false;
			}
			} else {
			return false;
			}*/
		}


		public function getAllstoreColletion()
		{
			return Mage::app()->getStores();
		}
		public function getAllCategories()
		{
			$category=Mage::getModel('catalog/category')
			->getCollection()
			->setStoreId($store_id)
			->setOrder('position', 'asc')
			->addFieldToFilter('is_active', array('eq'=>'1'))
			->addAttributeToSelect('*');
			return $category;
		}



	public function getStoreData(){
		$store_info=array();	
		$allStores  = Mage::helper('advertizement')->getAllstoreColletion();
		foreach ($allStores as $_eachStoreId => $val) 
		{			
		$catname = Mage::app()->getStore($_eachStoreId)->getName();
		$catid = Mage::app()->getStore($_eachStoreId)->getId();
		$store_info[$catid]	=  $catname;
		}
		return $store_info;
	}
	
	public function getAllCategoriesDATA(){
		$category_info=array();	
		$category  = $this->getAllCategories();
		foreach ($category as $cat)
		{				
		$category_info[$cat->getId()]= $cat->getName();
		}
		return $category_info;
	}


}
