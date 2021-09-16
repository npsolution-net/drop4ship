<?php

class ControllerAccountVendorLtsProfile extends controller {

  private $error = [];

  public function index() {
    $this->load->language('vendor/lts_profile');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('account/customer');
    
    $this->load->model('vendor/lts_vendor');

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
     
    $vendor_info = $this->model_account_vendor_lts_vendor->getVendorStoreInfo($this->customer->isLogged());

    if(!$vendor_info) {
        $this->response->redirect($this->url->link('account/account', '', true));
    }

    
    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

     $this->model_account_customer->editCustomer($this->customer->getId(), $this->request->post);

      $this->session->data['success'] = $this->language->get('text_success');

      $this->response->redirect($this->url->link('vendor/lts_dashboard')); 
    }

    //error
    if (isset($this->error['warning'])) {
      $data['error_warning'] = $this->error['warning'];
    } else {
      $data['error_warning'] = '';
    }

    if (isset($this->error['firstname'])) {
      $data['error_firstname'] = $this->error['firstname'];
    } else {
      $data['error_firstname'] = '';
    }

    if (isset($this->error['lastname'])) {
      $data['error_lastname'] = $this->error['lastname'];
    } else {
      $data['error_lastname'] = '';
    }

     if (isset($this->error['email'])) {
        $data['error_email'] = $this->error['email'];
      } else {
        $data['error_email'] = '';
      }

    if (isset($this->error['telephone'])) {
        $data['error_telephone'] = $this->error['telephone'];
      } else {
        $data['error_telephone'] = '';
      }
 
    $data['breadcrumbs'] = array();

    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('text_home'),
        'href' => $this->url->link('vendor/lts_dashboard')
    );

    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('heading_title'),
        'href' => $this->url->link('vendor/lts_profile')
    );

    if ($this->request->server['REQUEST_METHOD'] != 'POST') {
      $vendor_info = $this->model_vendor_lts_vendor->getVendor($this->customer->isVendor());
    }


    if (isset($this->request->post['firstname'])) {
      $data['firstname'] = $this->request->post['firstname'];
    } elseif (isset($vendor_info['firstname'])) {
      $data['firstname'] = $vendor_info['firstname'];
    } else {
      $data['firstname'] = '';
    }

    if (isset($this->request->post['lastname'])) {
      $data['lastname'] = $this->request->post['lastname'];
    } elseif (isset($vendor_info['lastname'])) {
      $data['lastname'] = $vendor_info['lastname'];
    } else {
      $data['lastname'] = '';
    } 

    if (isset($this->request->post['email'])) {
      $data['email'] = $this->request->post['email'];
    } elseif (isset($vendor_info['email'])) {
      $data['email'] = $vendor_info['email'];
    } else {
      $data['email'] = '';
    } 

    if (isset($this->request->post['telephone'])) {
      $data['telephone'] = $this->request->post['telephone'];
    } elseif (isset($vendor_info['telephone'])) {
      $data['telephone'] = $vendor_info['telephone'];
    } else {
      $data['telephone'] = '';
    }

    $data['action'] = $this->url->link('vendor/lts_profile', '', true);
    $data['cancel'] = $this->url->link('vendor/lts_dashboard', '', true);

    $this->load->controller('vendor/lts_header/script');

    $this->load->controller('vendor/lts_header/script');
    $data['footer'] = $this->load->controller('common/footer');
    $data['header'] = $this->load->controller('common/header');
    $data['lts_column_left'] = $this->load->controller('vendor/lts_column_left');

    $this->response->setOutput($this->load->view('vendor/lts_profile', $data));
  }

  protected function validateForm() {
    if (!$this->request->post['firstname']) {
      $this->error['firstname'] = $this->language->get('error_firstname');
    }

    if (!$this->request->post['lastname']) {
      $this->error['lastname'] = $this->language->get('error_lastname');
    }

    if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
      $this->error['email'] = $this->language->get('error_email');
    }

    if (($this->customer->getEmail() != $this->request->post['email']) && $this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
      $this->error['warning'] = $this->language->get('error_exists');
    }

    if (!$this->request->post['telephone']) {
      $this->error['telephone'] = $this->language->get('error_telephone');
    }

    if ($this->error && !isset($this->error['warning'])) {
      $this->error['warning'] = $this->language->get('error_warning');
    }

    return !$this->error;
  }

}
