<?php

class ControllerDropshipDropship extends Controller {

  private $error = [];

  public function index() {
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

    $this->load->language('dropship/dropship');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('dropship/dropship');

     $this->getList();
  }

  public function add() {
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
    
    $this->load->language('account/vendor/lts_product');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('account/vendor/lts_product');

    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      $this->load->model('dropship/dropship');
      $data = array(
        'email' => $this->request->post['email'],
        'customer_group_id' => $this->customer->getGroupId()
      );
      $this->model_dropship_dropship->addDropshipGroup($data);
      $this->response->redirect($this->url->link('dropship/dropship'));
    }
    $this->getForm();
  }

  public function delete() {
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
    
    $this->load->model('dropship/dropship');    

    if (isset($this->request->post['selected'])) {
      $data = array(
        'selected' => $this->request->post['selected'],
        'customer_group_id' => $this->customer->getGroupId()
      );    
      $this->model_dropship_dropship->deleteDropshipGroup($data);

      $this->response->redirect($this->url->link('dropship/dropship'));
    }

    $this->getList();
  }

  protected function getList() {
   
    $data['breadcrumbs'] = array();

    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('text_home'),
        'href' => $this->url->link('account/vendor/lts_dashboard')
    );

    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('heading_title'),
        'href' => $this->url->link('dropship/dropship')
    );
    
    $data['dropships'] = array();

    $filter_data = array(
		'customer_group_id' => $this->customer->getGroupId(),
        'customer_id' => $this->customer->getId(),
    );

    $results = $this->model_dropship_dropship->getDropships($filter_data);

    foreach ($results as $result) {
      $data['dropships'][] = array(
          'customer_id' => $result['customer_id'],
		      // 'owner_id' => $result['owner_id'],
          'name' => $result['firstname'] . ' ' . $result['lastname'], 
          'email' => $result['email'],
          'telephone' => $result['telephone'],
          'dropship_status' => $result['dropship_status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),  
      );
    }

	  $data['add'] = $this->url->link('dropship/dropship/add');
    $data['delete'] = $this->url->link('dropship/dropship/delete');

    $this->load->controller('account/vendor/lts_header/script');
    $data['footer'] = $this->load->controller('common/footer');
    $data['header'] = $this->load->controller('common/header');
    $data['lts_column_left'] = $this->load->controller('account/vendor/lts_column_left');

    $this->response->setOutput($this->load->view('dropship/dropship', $data));
  }

  protected function getForm() {

	$this->load->language('dropship/dropship_add');

    $data['text_form'] = $this->language->get('text_add');

    $data['breadcrumbs'] = array();

    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('text_home'),
        'href' => $this->url->link('account/vendor/lts_dashboard')
    );

    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('heading_title'),
        'href' => $this->url->link('dropship/dropship')
    );

	$data['save'] = $this->url->link('dropship/dropship/add');

    $data['cancel'] = $this->url->link('dropship/dropship');

   
    $this->load->model('dropship/dropship');

    $this->load->controller('account/vendor/lts_header/script');
    $data['footer'] = $this->load->controller('common/footer');
    $data['header'] = $this->load->controller('common/header');
    $data['lts_column_left'] = $this->load->controller('account/vendor/lts_column_left');

    $this->response->setOutput($this->load->view('dropship/dropship_add', $data));
  }

  protected function validateForm() {
    return $this->request->post['email'];
  }

  public function searchByEmail(){
    $this->load->model('dropship/dropship');
    $customer = $this->model_dropship_dropship->searchByEmail($this->request->post);
    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($customer));
  }

}
