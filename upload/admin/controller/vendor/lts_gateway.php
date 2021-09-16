<?php
class ControllerVendorLtsGateway extends controller {
	private $error;

	public function index() {
		$this->load->language('vendor/lts_gateway');
		
		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('vendor/lts_gateway');
		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

			$this->model_setting_setting->editSetting('lts_gateway', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('vendor/lts_gateway', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/lts_vendor', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('vendor/lts_gateway', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('vendor/lts_gateway', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->post['lts_gateway_status'])) {
			$data['lts_gateway_status'] = $this->request->post['lts_gateway_status'];
		} else {
			$data['lts_gateway_status'] = $this->config->get('lts_gateway_status');
		}
		if (isset($this->request->post['lts_gateway_sender'])) {
			$data['lts_gateway_sender'] = $this->request->post['lts_gateway_sender'];
		} else {
			$data['lts_gateway_sender'] = $this->config->get('lts_gateway_sender');
		}

		if (isset($this->request->post['lts_gateway_apikey'])) {
			$data['lts_gateway_apikey'] = $this->request->post['lts_gateway_apikey'];
		} else {
			$data['lts_gateway_apikey'] = $this->config->get('lts_gateway_apikey');
		}

		if (isset($this->request->post['lts_gateway_order'])) {
			$data['lts_gateway_order'] = $this->request->post['lts_gateway_order'];
		} else {
			$data['lts_gateway_order'] = $this->config->get('lts_gateway_order');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		
		$data['header']	= $this->load->controller('common/header');
		$data['column_left']	= $this->load->controller('common/column_left');
		$data['footer']	= $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('vendor/lts_gateway', $data));
	}


	protected function validate() {
		if (!$this->user->hasPermission('modify', 'vendor/lts_gateway')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}



?>