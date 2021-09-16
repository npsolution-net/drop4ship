<?php
class ControllerVendorLtsCommission extends controller {
	public function index() {
		$this->load->language('vendor/lts_commission');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('vendor/lts_commission');

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
 
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('vendor/lts_commission', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);


		$filter_data = array(
			// 'filter_name'	  => $filter_name,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => $this->config->get('config_limit_admin')
		);

		$commission_report_total = $this->model_vendor_lts_commission->getTotalCommissions($filter_data);

		$results = $this->model_vendor_lts_commission->getCommissions($filter_data);

		$this->load->model('vendor/lts_vendor');

		$data['vendor_commissions'] = array();

		foreach($results as $result) {
			$data['vendor_commissions'][] = array(
				'vendor_commission_id'	=> $result['vendor_commission_id'],
				'vendor_id'				=> $result['vendor_id'],
				'order_id'				=> $result['order_id'],
				'store_name'				=> $result['store_name'],
				'store_owner'				=> $result['store_owner'],
				'name'				=> $result['name'],
				'price'				=> $result['price'],
				'amount'				=> $result['amount'],
				'quantity'				=> $result['quantity'],
				'amount'				=> $result['amount'],
				'total'				=> $result['total'],
				'date_added'				=> $result['date_added'],
			);

		} 

		
		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$url = '';

		

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
			
		$data['sort_name'] = $this->url->link('vendor/lts_commission_report', 'user_token=' . $this->session->data['user_token'] . '&sort=v.vname' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}




    $pagination = new Pagination();
    $pagination->total = $commission_report_total;
    $pagination->page = $page;
    $pagination->limit = $this->config->get('config_limit_admin');
    $pagination->url = $this->url->link('account/vendor/lts_product', '&page={page}', true);

    $data['pagination'] = $pagination->render();

    $data['results'] = sprintf($this->language->get('text_pagination'), ($commission_report_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($commission_report_total - $this->config->get('config_limit_admin'))) ? $commission_report_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $commission_report_total, ceil($commission_report_total / $this->config->get('config_limit_admin')));

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer']		 = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('vendor/lts_commission', $data));

	}    

	public function pay() {
		$this->load->language('vendor/lts_commission_report');

		$this->document->setTitle($this->language->get('heading_title'));

		// $this->load->model('catalog/product');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_product->addProduct($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			$this->response->redirect($this->url->link('catalog/product', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	

}   