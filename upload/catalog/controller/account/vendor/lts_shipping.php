<?php

class ControllerVendorLtsShipping extends Controller {

  private $error = array();

  public function index() {

    $this->load->language('vendor/lts_shipping');

    $this->document->setTitle($this->language->get('heading_title'));

    // $this->load->model('vendor/lts_shipping');

    $this->getList();
      
  }

  public function add() {
    
    $this->load->language('vendor/lts_shipping');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('vendor/lts_shipping');

    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

      //$this->model_vendor_lts_shipping->addProduct($this->request->post);

      $this->session->data['success'] = $this->language->get('text_success');

      $this->response->redirect($this->url->link('vendor/lts_shipping'));
    }
    $this->getForm();
  }

  public function getList() {

    $data['breadcrumbs'] = array();

    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('text_home'),
        'href' => $this->url->link('vendor/lts_dashboard')
    );

    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('heading_title'),
        'href' => $this->url->link('vendor/lts_shipping')
    );

    $data['add'] = $this->url->link('vendor/lts_shipping/add');
    $data['delete'] = $this->url->link('vendor/lts_shipping/delete');

    $this->load->controller('vendor/lts_header/script');
    $data['header'] = $this->load->controller('common/header');
    $data['lts_column_left'] = $this->load->controller('vendor/lts_column_left');
    $data['footer'] = $this->load->controller('common/footer');

    $this->response->setOutput($this->load->view('vendor/lts_shipping_list', $data));
  }

  public function getForm() {
     $this->load->controller('vendor/lts_header/script');

    if (!$this->config->get('module_lts_vendor_status')) {
      $this->response->redirect($this->url->link('error/not_found', '', true));
    }

    if (!$this->customer->getId() || !$this->customer->isVendor()) {
      $this->response->redirect($this->url->link('vendor/lts_login', '', true));
    }
   
    $data['text_form'] = !isset($this->request->get['product_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');


    if (isset($this->error['warning'])) {
      $data['error_warning'] = $this->error['warning'];
    } else {
      $data['error_warning'] = '';
    }

    if (isset($this->error['country'])) {
      $data['error_country'] = $this->error['country'];
    } else {
      $data['error_country'] = '';
    }
    
    if (isset($this->error['zone'])) {
      $data['error_zone'] = $this->error['zone'];
    } else {
      $data['error_zone'] = '';
    }

    if (isset($this->error['zip_from'])) {
      $data['error_zip_from'] = $this->error['zip_from'];
    } else {
      $data['error_zip_from'] = '';
    }

    if (isset($this->error['zip_to'])) {
      $data['error_zip_to'] = $this->error['zip_to'];
    } else {
      $data['error_zip_to'] = '';
    }

    if (isset($this->error['weight_from'])) {
      $data['error_weight_from'] = $this->error['weight_from'];
    } else {
      $data['error_weight_from'] = '';
    }

    if (isset($this->error['weight_to'])) {
      $data['error_weight_to'] = $this->error['weight_to'];
    } else {
      $data['error_weight_to'] = '';
    }


    $data['breadcrumbs'] = array();

    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('text_home'),
        'href' => $this->url->link('vendor/lts_dashboard')
    );

    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('heading_title'),
        'href' => $this->url->link('vendor/lts_shipping')
    );


    if (!isset($this->request->get['shipping_id'])) {
      $data['action'] = $this->url->link('vendor/lts_shipping/add');
    } else {
      $data['action'] = $this->url->link('vendor/lts_shipping/edit', '&shipping_id=' . $this->request->get['shipping_id'], true);
    }
    
    $data['cancel'] = $this->url->link('vendor/lts_shipping');

    if (isset($this->request->get['shipping_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      $shipping_info = $this->model_vendor_lts_shipping->getShipping($this->request->get['shipping_id']);
    }


    $this->load->model('localisation/country');

    $data['countries'] = $this->model_localisation_country->getCountries();

     if (isset($this->request->post['country_id'])) {
      $data['country_id'] = $this->request->post['country_id'];
    } elseif (!empty($store_info)) {
      $data['country_id'] = $store_info['country_id'];
    } else {
      $data['country_id'] = '';
    }

    if (isset($this->request->post['zone_id'])) {
      $data['zone_id'] = $this->request->post['zone_id'];
    } elseif (!empty($store_info)) {
      $data['zone_id'] = $store_info['zone_id'];
    } else {
      $data['zone_id'] = '';
    }

    if (isset($this->request->post['zip_from'])) {
      $data['zip_from'] = $this->request->post['zip_from'];
    } elseif (!empty($shipping_info)) {
      $data['zip_from'] = $shipping_info['zip_from'];
    } else {
      $data['zip_from'] = '';
    }

    if (isset($this->request->post['zip_to'])) {
      $data['zip_to'] = $this->request->post['zip_to'];
    } elseif (!empty($shipping_info)) {
      $data['zip_to'] = $shipping_info['zip_to'];
    } else {
      $data['zip_to'] = '';
    }

    if (isset($this->request->post['weight_to'])) {
      $data['weight_to'] = $this->request->post['weight_to'];
    } elseif (!empty($shipping_info)) {
      $data['weight_to'] = $shipping_info['weight_to'];
    } else {
      $data['weight_to'] = '';
    }

    if (isset($this->request->post['weight_from'])) {
      $data['weight_from'] = $this->request->post['weight_from'];
    } elseif (!empty($shipping_info)) {
      $data['weight_from'] = $shipping_info['weight_from'];
    } else {
      $data['weight_from'] = '';
    }

    $data['action'] = $this->url->link("vendor/lts_shipping/add", '');  

    $this->load->controller('vendor/lts_header/script');
    $data['header'] = $this->load->controller('common/header');
    $data['lts_column_left'] = $this->load->controller('vendor/lts_column_left');
    $data['footer'] = $this->load->controller('common/footer');

    $this->response->setOutput($this->load->view('vendor/lts_shipping_form', $data));
  }

  protected function validateForm() {

    if ($this->request->post['country_id'] == '') {
      $this->error['country'] = $this->language->get('error_country');
    }

    if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '' || !is_numeric($this->request->post['zone_id'])) {
      $this->error['zone'] = $this->language->get('error_zone');
    }

    if ((utf8_strlen(trim($this->request->post['zip_from'])) < 5) || (utf8_strlen(trim($this->request->post['zip_from'])) > 6)) {
      $this->error['zip_from'] = $this->language->get('error_zip_from');
    }

    if ((utf8_strlen(trim($this->request->post['zip_to'])) < 5) || (utf8_strlen(trim($this->request->post['zip_to'])) > 6)) {
      $this->error['zip_to'] = $this->language->get('error_zip_to');
    }

    if ($this->request->post['weight_from'] == '') {
      $this->error['weight_from'] = $this->language->get('error_weight_from');
    }

    if ($this->request->post['weight_to'] == '') {
      $this->error['weight_to'] = $this->language->get('error_weight_to');
    }

    if ($this->error && !isset($this->error['warning'])) {
      $this->error['warning'] = $this->language->get('error_warning');
    }

    return !$this->error;
  }
}