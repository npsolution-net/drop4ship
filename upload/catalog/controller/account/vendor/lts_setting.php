<?php

class ControllerAccountVendorLtsSetting extends controller {

  private $error = [];

  public function index() {
    $this->load->language('account/vendor/lts_setting');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('account/vendor/lts_vendor');



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

    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
      $this->model_account_vendor_lts_vendor->addPayment($vendor_info['vendor_id'], $this->request->post);

      $this->session->data['success'] = $this->language->get('text_success');

      $this->response->redirect($this->url->link('account/vendor/lts_dashboard', '', true));
    }


    //error
    if (isset($this->error['warning'])) {
      $data['error_warning'] = $this->error['warning'];
    } else {
      $data['error_warning'] = '';
    }

    if (isset($this->error['paypal'])) {
      $data['error_paypal'] = $this->error['paypal'];
    } else {
      $data['error_paypal'] = '';
    }

    if (isset($this->error['account_holder'])) {
      $data['error_account_holder'] = $this->error['account_holder'];
    } else {
      $data['error_account_holder'] = '';
    }

    if (isset($this->error['bankname'])) {
      $data['error_bankname'] = $this->error['bankname'];
    } else {
      $data['error_bankname'] = '';
    }

     if (isset($this->error['accountno'])) {
        $data['error_accountno'] = $this->error['accountno'];
      } else {
        $data['error_accountno'] = '';
      }

    if (isset($this->error['ifsc'])) {
      $data['error_ifsc'] = $this->error['ifsc'];
    } else {
      $data['error_ifsc'] = '';
    }

    $data['breadcrumbs'] = array();

    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('text_home'),
        'href' => $this->url->link('common/home')
    );

    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('heading_title'),
        'href' => $this->url->link('account/vendor/lts_setting')
    );

    $data['action'] = $this->url->link('account/vendor/lts_setting', '', true);
    $data['cancel'] = $this->url->link('account/vendor/lts_dashboard', '', true);

    if ($this->request->server['REQUEST_METHOD'] != 'POST') {
      $payment_info = $this->model_account_vendor_lts_vendor->getPayment($vendor_info['vendor_id']);
    }

    if (isset($this->request->post['paypal'])) {
      $data['paypal'] = $this->request->post['paypal'];
    } elseif (isset($payment_info['paypal'])) {
      $data['paypal'] = $payment_info['paypal'];
    } else {
      $data['paypal'] = '';
    }

    if (isset($this->request->post['account_holder'])) {
      $data['account_holder'] = $this->request->post['account_holder'];
    } elseif (isset($payment_info['account_holder'])) {
      $data['account_holder'] = $payment_info['account_holder'];
    } else {
      $data['account_holder'] = '';
    } 

    if (isset($this->request->post['bankname'])) {
      $data['bankname'] = $this->request->post['bankname'];
    } elseif (isset($payment_info['bankname'])) {
      $data['bankname'] = $payment_info['bankname'];
    } else {
      $data['bankname'] = '';
    } 

    if (isset($this->request->post['accountno'])) {
      $data['accountno'] = $this->request->post['accountno'];
    } elseif (isset($payment_info['accountno'])) {
      $data['accountno'] = $payment_info['accountno'];
    } else {
      $data['accountno'] = '';
    }

    if (isset($this->request->post['ifsc'])) {
      $data['ifsc'] = $this->request->post['ifsc'];
    } elseif (isset($payment_info['ifsc'])) {
      $data['ifsc'] = $payment_info['ifsc'];
    } else {
      $data['ifsc'] = '';
    }

    $this->load->controller('account/vendor/lts_header/script');
    $data['header'] = $this->load->controller('common/header');
    $data['footer'] = $this->load->controller('common/footer');
    $data['lts_column_left'] = $this->load->controller('account/vendor/lts_column_left');
 
    $this->response->setOutput($this->load->view('account/vendor/lts_setting', $data));
  }

 protected function validate() {
    if ((utf8_strlen($this->request->post['paypal']) > 96) || !filter_var($this->request->post['paypal'], FILTER_VALIDATE_EMAIL)) {
      $this->error['paypal'] = $this->language->get('error_paypal');
    }

    if ((utf8_strlen($this->request->post['account_holder']) < 3) || (utf8_strlen($this->request->post['account_holder']) > 32)) {
      $this->error['account_holder'] = $this->language->get('error_account_holder');
    }  

    if ((utf8_strlen($this->request->post['bankname']) < 3) || (utf8_strlen($this->request->post['bankname']) > 32)) {
      $this->error['bankname'] = $this->language->get('error_bankname');
    }   

    if ((utf8_strlen($this->request->post['accountno']) < 9) || (utf8_strlen($this->request->post['accountno']) > 24)) {
      $this->error['accountno'] = $this->language->get('error_accountno');
    }  

    if ((utf8_strlen($this->request->post['ifsc']) < 3) || (utf8_strlen($this->request->post['ifsc']) > 9)) {
      $this->error['ifsc'] = $this->language->get('error_ifsc');
    }

    if ($this->error && !isset($this->error['warning'])) {
      $this->error['warning'] = $this->language->get('error_warning');
    }

    return !$this->error;
  }

}
