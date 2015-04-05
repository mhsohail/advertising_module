<?php
class Cws_Advertizement_IndexController extends Mage_Core_Controller_Front_Action {

/**
     *    Create session 
     * */
    protected function _getSession() {
        return Mage::getSingleton('advertizement/session');
    }

/**
     *    validate Customer Login and redirect previous page 
     * */
    protected function _validateCustomerLogin() {
        $session = Mage::getSingleton('customer/session');
        if (!$session->isLoggedIn()) {
            $session->setAfterAuthUrl(Mage::helper('core/url')->getCurrentUrl());
            $session->setBeforeAuthUrl(Mage::helper('core/url')->getCurrentUrl());
            $this->_redirect('customer/account/login/');
            return $this;
        }elseif(!Mage::helper('advertizement')->isMarketplaceActiveSellar()){
            $this->_redirect('customer/account/');
        }
    }


    public function indexAction() {
      $this->_validateCustomerLogin();
		$this->loadLayout();  
		$this->_initLayoutMessages('advertizement/session');  
       $this->renderLayout();
      // $this->loadLayout()->renderLayout();
     
    }
    public function addAction() {
		$this->_validateCustomerLogin();
		$this->loadLayout();    
		$this->_initLayoutMessages('advertizement/session');  
        $this->renderLayout();    
     
    }
	
	public function editAction() {
		$this->_validateCustomerLogin();
		$this->loadLayout();    
		$this->_initLayoutMessages('advertizement/session');  
        $this->renderLayout(); 
    }
	
	public function gettoreategoryAction() {
		//print_r($_POST);
		$this->_validateCustomerLogin();
		$store_id= (int) $_POST['store_id'];
		
		if($store_id == 1) {
			// main website store
			$category_name = "Distribution Center";
			$category_id = $this->getCategoryIdFor($category_name);
		} else if($store_id == 2) {
			// clikaroo
			$category_name = "Clikaroo";
			$category_id = $this->getCategoryIdFor($category_name);
		} else if($store_id == 3) {
			//cometothex
			$category_name = "Community Marketplace";
			$category_id = $this->getCategoryIdFor($category_name);
		} else {
			$root_category_id = Mage::app()->getStore($store_id)->getRootCategoryId();
			$category_id = $root_category_id;
			/*
			$categories = Mage::getModel('catalog/category')
				->getCollection()
				->addFieldToFilter('path', array('like'=> "1/$root_category_id/%"));
			echo $categories->count();
			*/
		}
		
		if($store_id) {
			//$html_ .='<select name="category_ids[]" id="category" class="required-entry select multiselect" multiple="multiple">';
			$html_ .='<select name="sub_category_1" id="sub_category_1" class="required-entry select multiselect" onchange="fill_subcategories(2, this.value);">';
			/*
			$categories = Mage::getModel('catalog/category')
			->getCollection()
			->setStoreId($store_id)
			->setOrder('position', 'asc')
			->addFieldToFilter('is_active', array('eq'=>'1'))
			->addAttributeToSelect('*');
			
			$categories = Mage::getModel('catalog/category')->getCollection()
			->addAttributeToSelect('*')//or you can just add some attributes
			->addAttributeToFilter('level', 1)//2 is actually the first level
			->addAttributeToFilter('is_active', 1); //if you want only active categories
			*/
			$categories = Mage::getModel('catalog/category')->getCategories($category_id);
			
			$html_ .='<option value="" selected>Select Category</option>';
			foreach ($categories as $cat) {
				$html_ .='<option value="'.$cat->getId().'">'.$cat->getName() . '</option>';
			}
			$html_ .='</select>';
			echo $html_ ;
		} else {
			echo '';
		}
	}
	
	function getCategoryIdFor($category_name) {
		$category = Mage::getResourceModel('catalog/category_collection')
			->addFieldToFilter('name', $category_name);
		
		// the below line gets the category data
		$catData = $category->getData();
		
		// the below line gets the category id
		$category_id = $catData[0]['entity_id'];
		
		return $category_id;
	}
	
	public function saveAction() {
		$this->_validateCustomerLogin();
		$customer_id = Mage::getSingleton('customer/session')->getId(); // Get Current User id
		
		if(isset($_FILES['advertizement-image']['name']) and (file_exists($_FILES['advertizement-image']['tmp_name']))) {
		try {
		$uploader = new Varien_File_Uploader('advertizement-image');
		//print_r($uploader);
		$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png')); // or pdf or anything


		$uploader->setAllowRenameFiles(false);
		$new_file_name=time().$customer_id;
		// setAllowRenameFiles(true) -> move your file in a folder the magento way
		// setAllowRenameFiles(true) -> move your file directly in the $path folder
		$uploader->setFilesDispersion(false);

		$path = Mage::getBaseDir('media') . DS."advertizement/" ;
				   
				   $uplaoedFilename=$new_file_name.$_FILES['advertizement-image']['name'];
					$uploader->save($path, $new_file_name.$_FILES['advertizement-image']['name']);
		//$uploader->save($path, $new_file_name);

					$data['advertizement-image'] = $new_file_name.$_FILES['advertizement-image']['name'];
		
		//$data['advertizement-image'] = $new_file_name;
		
		/* SAVE POSTED DATA **/
		$data               = Mage::app()->getRequest()->getPost();
		$all_categories=@implode(',', $data['category_ids']);		
		$contactproModel = Mage::getModel('advertizement/advertizement');
                 $contactproModel->load($this->getRequest()->getParam('advertizement_id'));
               if(!empty($data) and $contactproModel->getId() ){
                $contactproModel
                    ->setMerchant_id($customer_id)
                    ->setStore_id($data['stores'])
                    ->setCategories($all_categories)
                    ->setAddvertizement_banner($uplaoedFilename)
					->setIp_address($this->getUserIpAddress())
					->setPage_location($data['page_location'])
                    ->save();
               }
               elseif(!empty($data)){
                    Mage::getModel('advertizement/advertizement')
                    ->setMerchant_id($customer_id)
                    ->setStore_id($data['stores'])
                    ->setCategories($all_categories)
                    ->setAddvertizement_banner($uplaoedFilename)
					->setIp_address($this->getUserIpAddress())
					->setPage_location($data['page_location'])                    
                    ->save();
               }
                    Mage::getSingleton('core/session')->addSuccess('successfully saved');
                    Mage::getSingleton('core/session')->settestData(false);
		//print_r($data);
		//exit;
		/* ENd SAVE POSTED DATA */
		
		
		
		}catch(Exception $e) {
Mage::getSingleton('core/session')->addSuccess($e);
		}
}
	 $this->_redirect("*/*/");
	}
	
	public function updateAction() {
		$this->_validateCustomerLogin();
		$customer_id = Mage::getSingleton('customer/session')->getId(); // Get Current User id
		
		if(isset($_FILES['advertizement-image']['name']) and (file_exists($_FILES['advertizement-image']['tmp_name']))) {
			try {
				$uploader = new Varien_File_Uploader('advertizement-image');
				//print_r($uploader);
				$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png')); // or pdf or anything


				$uploader->setAllowRenameFiles(false);
				$new_file_name=time().$customer_id;
				// setAllowRenameFiles(true) -> move your file in a folder the magento way
				// setAllowRenameFiles(true) -> move your file directly in the $path folder
				$uploader->setFilesDispersion(false);

				$path = Mage::getBaseDir('media') . DS."advertizement/" ;
						   
						   $uplaoedFilename=$new_file_name.$_FILES['advertizement-image']['name'];
							$uploader->save($path, $new_file_name.$_FILES['advertizement-image']['name']);
				//$uploader->save($path, $new_file_name);

				$post['advertizement-image'] = $new_file_name.$_FILES['advertizement-image']['name'];
				
				//$post['advertizement-image'] = $new_file_name;
				
				$post = Mage::app()->getRequest()->getPost();
				$all_categories=@implode(',', $post['category_ids']);
				
				$data = array(
					'merchant_id' => $customer_id,
					'store_id' => $post['stores'],
					'categories' => $all_categories,
					'addvertizement_banner' => $uplaoedFilename,
					'active_status' => '0',
					'ip_address' => $this->getUserIpAddress(),
					'page_location' => $post['page_location']
				);
				$model = Mage::getModel('advertizement/advertizement')->load($ad_id)->addData($data);
				$ad_id = $post['advertizement_id'];
				try {
					$model->setId($ad_id)->save();
					// in order to see the below message printed, remove $this->_redirect("*/*/") statement below.
					//echo "Data updated successfully.";
				} catch (Exception $e){
					echo $e->getMessage(); 
				}
			} catch(Exception $e) {
				Mage::getSingleton('core/session')->addSuccess($e);
			}
			$this->_redirect("*/*/");
		}
	}
	
	public function deleteAction()
	{
		$id = $this->getRequest()->getParam('id');
		//$id = 30;
		$model = Mage::getModel('advertizement/advertizement');
		try {
			$model->setId($id)->delete();
			//echo "Data deleted successfully.";
			Mage::getSingleton('core/session')->addSuccess('Data deleted successfully.');

		} catch (Exception $e){
			//echo $e->getMessage(); 
			Mage::getSingleton('core/session')->addSuccess($e->getMessage());
		}
		$this->_redirect("*/*/");
	}
	
	
	public function statusAction()
	{
		 $id = $this->getRequest()->getParam('id');
		 $status = $this->getRequest()->getParam('status');
		//$id = 30;
		if($status=='active')
		{
			$arrcustData = array('active_status'=>'1');
		}
		else if($status=='in-active')
		{
			$arrcustData = array('active_status'=>'0');
		}
		
		$model = Mage::getModel('advertizement/advertizement')->load($id)->addData($arrcustData );  
		try {
			$model->setId($id)->save();
			//echo "Data deleted successfully.";
			Mage::getSingleton('core/session')->addSuccess('Record has been updated successfully.');

		} catch (Exception $e){
			//echo $e->getMessage(); 
			Mage::getSingleton('core/session')->addSuccess($e->getMessage());
		}
		$this->_redirect("*/*/");
	}
	
	private function getUserIpAddress() {
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
	
	
	
	
}

