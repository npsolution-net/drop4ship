<?php

class ControllerAccountVendorLtsHeader extends Controller {
  public function script(){
    // if (!$this->customer->isLogged()) {
    //     $this->session->data['redirect'] = $this->url->link('account/account', '', true);

    //     $this->response->redirect($this->url->link('account/login', '', true));
    // }

    // if(!$this->config->get('module_lts_vendor_status')) {
    //     $this->response->redirect($this->url->link('account/account', '', true));
    // }

    // $this->load->model('account/vendor/lts_vendor');

    // if($this->customer->isLogged()){
    //     $data['customer_id'] = $this->customer->getId();
    // }
     
    // $vednor_info = $this->model_account_vendor_lts_vendor->getVendorStoreInfo($this->customer->isLogged());

    // if(!$vednor_info) {
    //     $this->response->redirect($this->url->link('account/account', '', true));
    // }
    
    $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/moment.min.js');
    $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/moment-with-locales.min.js');

    $this->document->addScript('catalog/view/javascript/vendor/lts-vendor.js');

    $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');

    $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/locales.js');

    $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/locales.min.js');

    $this->document->addStyle('catalog/view/javascript/vendor/lts-vendor.css');
    // $this->document->addStyle('catalog/view/javascript/vendor/bootstrap.css');
    $this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');
  }
  public function index() {


    $data['title'] = $this->document->getTitle();

    if ($this->request->server['HTTPS']) {
      $data['base'] = HTTPS_SERVER;
    } else {
      $data['base'] = HTTP_SERVER;
    }

    $this->load->model('vendor/lts_vendor');

    $this->load->model('tool/image');

    $vendor_info = $this->model_vendor_lts_vendor->getVendor($this->customer->isVendor());

    if ($vendor_info) {
      $data['firstname'] = $vendor_info['firstname'];
      $data['lastname'] = $vendor_info['lastname'];
    } else {
      $data['firstname'] = '';
      $data['lastname'] = '';
      $data['image'] = '';
    }

    if (!$this->vendor->isLogged()) {
      $data['logged'] = '';
      $data['home'] = $this->url->link('vendor/lts_dashboard', '', 'SSL');
    } else {
      $data['logged'] = 'SSL';

      $data['logout'] = $this->url->link('vendor/lts_logout', '', 'SSL');
      $data['myprofile'] = $this->url->link('vendor/lts_vendor_profile', 'vendor_id=' . $this->customer->isVendor());
    }

 

    return $this->load->view('vendor/lts_header', $data);
  }

}
