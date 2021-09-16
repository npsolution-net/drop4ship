<?php
class ControllerVendorLtsMvendor extends Controller {
	public function index() {
	
		$this->load->language('vendor/lts_mvendor');

		$this->load->model('vendor/lts_mvendor');

		$this->load->model('tool/image');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('vendor/lts_mvendor', '', true)
		);


		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_empty'] = $this->language->get('text_empty');


		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$filter_data = array(
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => 10
		);


		// die;

		$vendor_store_total = $this->model_vendor_lts_mvendor->getTotalVendorStores($filter_data);

		$vendors = $this->model_vendor_lts_mvendor->getVendorStores($filter_data);


		// print_r($vendors);

		// die;


		$data['vendors'] = array();

		foreach($vendors as $vendor) {
			if($vendor['profile_image']) {
				$profile_image = $this->model_tool_image->resize($vendor['profile_image'], 385, 100);
			} else {
				$profile_image = $this->model_tool_image->resize('no_image.png', 385, 100);
			}


			// die;

			if($vendor['banner']) {
				$banner = $this->model_tool_image->resize($vendor['banner'], 385, 100);
			} else {
				$banner = $this->model_tool_image->resize('no_image.png', 385, 100);
			}

			
			$data['vendors'][] = array(
				'vendor_id'	=> $vendor['vendor_id'],
				'store_name'	=> $vendor['store_name'],
				'image'			=> $profile_image,
				'banner'		=> $banner,
				'store_owner'	=> $vendor['store_owner'],
				'email'			=> $vendor['email'],
				'telephone'		=> $vendor['telephone'],
				'address'		=> $vendor['address'],
				'href'			=> $this->url->link('vendor/lts_visit', 'vendor_id='. $vendor['vendor_id'], true),
			);
		}



		$url = '';

		$pagination = new Pagination();
		$pagination->total = $vendor_store_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('vendor/lts_mvendor', '' . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($vendor_store_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($vendor_store_total - $this->config->get('config_limit_admin'))) ? $vendor_store_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $vendor_store_total, ceil($vendor_store_total / $this->config->get('config_limit_admin')));

		$this->load->controller('account/vendor/lts_header/script');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('vendor/lts_mvendor', $data));
	}
}
