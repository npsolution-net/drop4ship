<?php
class ControllerVendorLtsBanner extends Controller {
	public function index() {


		$this->load->language('vendor/lts_visit');

		$this->load->model('vendor/lts_mvendor');

		$this->load->model('tool/image');
		
		if (isset($this->request->get['vendor_id'])) {
			$vendor_id = (int)$this->request->get['vendor_id'];
		} else {
			$vendor_id = 0; 
		} 

		$vendor_info = $this->model_vendor_lts_mvendor->getVendorStoreInfo($vendor_id);

		if (!empty($vendor_info['status']) && !empty($vendor_info['approved'])) {
			
			$data['store_name'] = $vendor_info['store_name'];
			$data['store_owner'] = $vendor_info['store_owner'];
			$data['email'] = $vendor_info['email'];
			$data['telephone'] = $vendor_info['telephone'];
			$data['address'] = $vendor_info['address'];

			if($vendor_info['banner']) {
				$data['banner'] = $this->model_tool_image->resize($vendor_info['banner'], 845, 220);
			} else {
				$data['banner'] = $this->model_tool_image->resize('no_image.png', 845, 220);
			}

			if($vendor_info['profile_image']) {
				$data['image'] = $this->model_tool_image->resize($vendor_info['profile_image'], 245, 166);
			} else {
				$data['image'] = $this->model_tool_image->resize('no_image.png', 245, 166);
			}

		   return $this->load->view('vendor/lts_banner', $data);
		
		}
	}
}
