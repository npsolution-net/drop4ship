<?php
class ControllerVendorLtsProfile extends Controller {
	public function index() {
		$this->load->language('vendor/lts_profile');

		$this->load->model('vendor/lts_mvendor');

		$this->load->model('tool/image');

		
		// Text Display
		if($this->customer->isLogged()){
			$data['customer_is_loggedin'] = $this->language->get('customer_is_loggedin');
		} else {
			$data['customer_is_loggedin'] = $this->language->get('text_login_contact');
		}

		$data['name'] = $data['author'] = $this->customer->getFirstName() .' '. $this->customer->getLastName();
		$data['customer_email'] = $this->customer->getEmail();
		$data['customer_logged'] = $this->customer->isLogged();

		if (isset($this->request->get['vendor_id'])) {
			$data['vendor_id'] = $vendor_id = (int)$this->request->get['vendor_id'];
		} else {
			$data['vendor_id'] = $vendor_id = 0;
		}

		$vendor_info = $this->model_vendor_lts_mvendor->getVendorStoreInfo($vendor_id);
		

		if (!empty($vendor_info['status']) && !empty($vendor_info['approved'])) {
			$data['store_name'] = $vendor_info['store_name'];
			$data['email'] = $vendor_info['email'];
			$data['telephone'] = $vendor_info['telephone'];
			$data['address'] = $vendor_info['address'];

			if($vendor_info['logo']) {
				$data['logo'] = $this->model_tool_image->resize($vendor_info['logo'], 245, 166);
			} else {
				$data['logo'] = $this->model_tool_image->resize('no_image.png', 245, 166);
			}

			$data['facebook'] 	 = $vendor_info['facebook'];
			$data['twitter'] 	 = $vendor_info['twitter'];
			$data['pinterest'] 	 = $vendor_info['pinterest'];
			$data['youtube'] 	 = $vendor_info['youtube'];
			$data['instagram']   = $vendor_info['instagram'];

		   return $this->load->view('vendor/lts_profile', $data); 
		}
	}


}
