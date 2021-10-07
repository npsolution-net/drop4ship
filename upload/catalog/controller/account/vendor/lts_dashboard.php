<?php

class ControllerAccountVendorLtsDashboard extends Controller {

  public function index() {
    $data = [];

    $this->load->language('account/vendor/lts_dashboard');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('account/vendor/lts_order');

    $this->load->model('account/vendor/lts_product');

   

    if (!$this->customer->isLogged()) {
        $this->session->data['redirect'] = $this->url->link('account/account', '', true);

        $this->response->redirect($this->url->link('account/login', '', true));
    }

    if(!$this->config->get('module_lts_vendor_status')) {
        $this->response->redirect($this->url->link('account/account', '', true));
    }

    $this->load->model('account/vendor/lts_vendor');

    if($this->customer->isLogged()){
        $data['customer_id'] = $this->customer->getId();
    }
     
    $vednor_info = $this->model_account_vendor_lts_vendor->getVendorStoreInfo($this->customer->isLogged());

    if(!$vednor_info) {
        $this->response->redirect($this->url->link('account/account', '', true));
    }

    $filter_data = array(
        'vendor_id' => $vednor_info['vendor_id']
    );

    $data['order_total'] = $this->model_account_vendor_lts_order->getTotalOrders($filter_data);

    $data['product_total'] = $this->model_account_vendor_lts_product->getTotalProducts($filter_data);

    $data['breadcrumbs'] = array();

    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('text_home'),
        'href' => $this->url->link('account/vendor/lts_dashboard')
    );

    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('heading_title'),
        'href' => $this->url->link('account/vendor/lts_dashboard')
    );

    if (isset($this->session->data['success'])) {
        $data['success'] = $this->session->data['success'];

        unset($this->session->data['success']);
    } else {
        $data['success'] = '';
    }

    $data['add_product'] = $this->url->link('account/vendor/lts_product/add');
    $data['view_order'] = $this->url->link('account/vendor/lts_order');

    $this->load->controller('account/vendor/lts_header/script');
    $data['footer'] = $this->load->controller('common/footer');
    $data['header'] = $this->load->controller('common/header');
    $data['lts_column_left'] = $this->load->controller('account/vendor/lts_column_left');
    
    $this->response->setOutput($this->load->view('account/vendor/lts_dashboard', $data));
  }

}
