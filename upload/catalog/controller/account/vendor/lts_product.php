<?php

class ControllerAccountVendorLtsProduct extends Controller {

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

    $this->load->language('account/vendor/lts_product');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('account/vendor/lts_product');

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
      // echo '<pre>'; print_r($this->request->post);die;
     
      $product_id=$this->model_account_vendor_lts_product->addProduct($this->request->post, $vendor_info['vendor_id']);

      $this->session->data['success'] = $this->language->get('text_success');
      if($this->config->get('module_lts_vendor_admin_receive_mail_product_add')){
          $this->send_mail_to_admin($product_id,$vendor_info);
          }
      $url = '';

      if (isset($this->request->get['filter_name'])) {
        $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
      }

      if (isset($this->request->get['filter_model'])) {
        $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
      }

      if (isset($this->request->get['filter_price'])) {
        $url .= '&filter_price=' . $this->request->get['filter_price'];
      }

      if (isset($this->request->get['filter_quantity'])) {
        $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
      }

      if (isset($this->request->get['filter_status'])) {
        $url .= '&filter_status=' . $this->request->get['filter_status'];
      }

      if (isset($this->request->get['sort'])) {
        $url .= '&sort=' . $this->request->get['sort'];
      }

      if (isset($this->request->get['order'])) {
        $url .= '&order=' . $this->request->get['order'];
      }

      if (isset($this->request->get['page'])) {
        $url .= '&page=' . $this->request->get['page'];
      }

      $this->response->redirect($this->url->link('account/vendor/lts_product'));
    }
    $this->getForm();
  }

  public function copy() {
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

    if (isset($this->request->post['selected'])) {
      foreach ($this->request->post['selected'] as $product_id) {
        $this->model_account_vendor_lts_product->copyProduct($product_id, $vendor_info['vendor_id']);
      }

      $this->session->data['success'] = $this->language->get('text_success');

      $url = '';

      if (isset($this->request->get['filter_name'])) {
        $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
      }

      if (isset($this->request->get['filter_model'])) {
        $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
      }

      if (isset($this->request->get['filter_price'])) {
        $url .= '&filter_price=' . $this->request->get['filter_price'];
      }

      if (isset($this->request->get['filter_quantity'])) {
        $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
      }

      if (isset($this->request->get['filter_status'])) {
        $url .= '&filter_status=' . $this->request->get['filter_status'];
      }

      if (isset($this->request->get['sort'])) {
        $url .= '&sort=' . $this->request->get['sort'];
      }

      if (isset($this->request->get['order'])) {
        $url .= '&order=' . $this->request->get['order'];
      }

      if (isset($this->request->get['page'])) {
        $url .= '&page=' . $this->request->get['page'];
      }

      $this->response->redirect($this->url->link('account/vendor/lts_product'));
    }

    $this->getList();
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

    $this->load->language('account/vendor/lts_product');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('account/vendor/lts_product');

    $vendor_id = $this->customer->getId();

    if (isset($this->request->post['selected'])) {
      foreach ($this->request->post['selected'] as $product_id) {
        $this->model_account_vendor_lts_product->deleteProduct($product_id, $vendor_id);
      }

      $this->session->data['success'] = $this->language->get('text_success');

      $url = '';

      if (isset($this->request->get['filter_name'])) {
        $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
      }

      if (isset($this->request->get['filter_model'])) {
        $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
      }

      if (isset($this->request->get['filter_price'])) {
        $url .= '&filter_price=' . $this->request->get['filter_price'];
      }

      if (isset($this->request->get['filter_quantity'])) {
        $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
      }

      if (isset($this->request->get['filter_status'])) {
        $url .= '&filter_status=' . $this->request->get['filter_status'];
      }

      if (isset($this->request->get['sort'])) {
        $url .= '&sort=' . $this->request->get['sort'];
      }

      if (isset($this->request->get['order'])) {
        $url .= '&order=' . $this->request->get['order'];
      }

      if (isset($this->request->get['page'])) {
        $url .= '&page=' . $this->request->get['page'];
      }

      $this->response->redirect($this->url->link('account/vendor/lts_product'));
    }

    $this->getList();
  }

  protected function getList() {
   
    if (isset($this->request->get['filter_name'])) {
      $filter_name = $this->request->get['filter_name'];
    } else {
      $filter_name = '';
    }

    if (isset($this->request->get['filter_model'])) {
      $filter_model = $this->request->get['filter_model'];
    } else {
      $filter_model = '';
    }

    if (isset($this->request->get['filter_price'])) {
      $filter_price = $this->request->get['filter_price'];
    } else {
      $filter_price = '';
    }

    if (isset($this->request->get['filter_quantity'])) {
      $filter_quantity = $this->request->get['filter_quantity'];
    } else {
      $filter_quantity = '';
    }

    if (isset($this->request->get['filter_status'])) {
      $filter_status = $this->request->get['filter_status'];
    } else {
      $filter_status = '';
    }

    if (isset($this->request->get['sort'])) {
      $sort = $this->request->get['sort'];
    } else {
      $sort = 'pd.name';
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

    if (isset($this->request->get['filter_name'])) {
      $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
    }

    if (isset($this->request->get['filter_model'])) {
      $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
    }

    if (isset($this->request->get['filter_price'])) {
      $url .= '&filter_price=' . $this->request->get['filter_price'];
    }

    if (isset($this->request->get['filter_quantity'])) {
      $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
    }

    if (isset($this->request->get['filter_status'])) {
      $url .= '&filter_status=' . $this->request->get['filter_status'];
    }

    if (isset($this->request->get['order'])) {
      $url .= '&order=' . $this->request->get['order'];
    }

    if (isset($this->request->get['page'])) {
      $url .= '&page=' . $this->request->get['page'];
    }

    $data['breadcrumbs'] = array();

    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('text_home'),
        'href' => $this->url->link('account/vendor/lts_dashboard')
    );

    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('heading_title'),
        'href' => $this->url->link('account/vendor/lts_product')
    );
    $products=$this->model_account_vendor_lts_product->get_vendor_product_count();
   
    $can_add_multi_product=$this->config->get('module_lts_vendor_multi_product');
    if($can_add_multi_product){
        $data['add'] = $this->url->link('account/vendor/lts_product/add');
        $data['copy'] = $this->url->link('account/vendor/lts_product/copy');
     
    }else{
       if($products==0){
        $data['add'] = $this->url->link('account/vendor/lts_product/add');
        $data['copy'] = $this->url->link('account/vendor/lts_product/copy');
       }else{
         $data['add'] = '';
         $data['copy'] ='';
      }
    }
    
    $data['delete'] = $this->url->link('account/vendor/lts_product/delete');

     $this->load->model('account/vendor/lts_vendor');

    if($this->customer->isLogged()){
        $data['customer_id'] = $this->customer->getId();
    }
     
    $vendor_info = $this->model_account_vendor_lts_vendor->getVendorStoreInfo($this->customer->isLogged());

    $vendor_id = $vendor_info['vendor_id'];


    $data['products'] = array();

    $filter_data = array(
        'filter_name' => $filter_name,
        'filter_model' => $filter_model,
        'filter_price' => $filter_price,
        'filter_quantity' => $filter_quantity,
        'filter_status' => $filter_status,
        'sort' => $sort,
        'order' => $order,
        'start' => ($page - 1) * $this->config->get('config_limit_admin'),
        'limit' => $this->config->get('config_limit_admin'),
        'vendor_id' => $vendor_id
    );

    $this->load->model('account/vendor/lts_image');

    $product_total = $this->model_account_vendor_lts_product->getTotalProducts($filter_data);

    $results = $this->model_account_vendor_lts_product->getProducts($filter_data);

    foreach ($results as $result) {
      if (is_file(DIR_IMAGE . $result['image'])) {
        $image = $this->model_account_vendor_lts_image->resize($result['image'], 40, 40);
      } else {
        $image = $this->model_account_vendor_lts_image->resize('no_image.png', 40, 40);
      }

      $special = false;

      $product_specials = $this->model_account_vendor_lts_product->getProductSpecials($result['product_id']);
      $product_status = $this->model_account_vendor_lts_product->getVendorProductById($result['product_id']);
      foreach ($product_specials as $product_special) {
        if (($product_special['date_start'] == '0000-00-00' || strtotime($product_special['date_start']) < time()) && ($product_special['date_end'] == '0000-00-00' || strtotime($product_special['date_end']) > time())) {
          $special = $this->currency->format($product_special['price'], $this->config->get('config_currency'));

          break;
        }
      }

      $data['products'][] = array(
          'product_id' => $result['product_id'],
          'image' => $image,
          'name' => $result['name'], 
          'model' => $result['model'],
          'price' => $this->currency->format($result['price'], $this->config->get('config_currency')),
          'special' => $special,
          'quantity' => $result['quantity'],
          'status' => $result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
          'edit' => $this->url->link('account/vendor/lts_product/edit', '&product_id=' . $result['product_id'] . $url, true)
      );
    }

    if ($this->config->get('module_lts_vendor_status') && $this->config->get('module_lts_vendor_delete_product')) {

      $data['module_lts_vendor_delete_product'] = $this->config->get('module_lts_vendor_delete_product');
    }


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

    if (isset($this->request->post['selected'])) {
      $data['selected'] = (array) $this->request->post['selected'];
    } else {
      $data['selected'] = array();
    }

    $url = '';

    if (isset($this->request->get['filter_name'])) {
      $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
    }

    if (isset($this->request->get['filter_model'])) {
      $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
    }

    if (isset($this->request->get['filter_price'])) {
      $url .= '&filter_price=' . $this->request->get['filter_price'];
    }

    if (isset($this->request->get['filter_quantity'])) {
      $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
    }

    if (isset($this->request->get['filter_status'])) {
      $url .= '&filter_status=' . $this->request->get['filter_status'];
    }

    if ($order == 'ASC') {
      $url .= '&order=DESC';
    } else {
      $url .= '&order=ASC';
    }

    if (isset($this->request->get['page'])) {
      $url .= '&page=' . $this->request->get['page'];
    }

    $data['sort_name'] = $this->url->link('account/vendor/lts_product');
    $data['sort_model'] = $this->url->link('account/vendor/lts_product');
    $data['sort_price'] = $this->url->link('account/vendor/lts_product');
    $data['sort_quantity'] = $this->url->link('account/vendor/lts_product');
    $data['sort_status'] = $this->url->link('account/vendor/lts_product');
    $data['sort_order'] = $this->url->link('account/vendor/lts_product');

    $url = '';

    if (isset($this->request->get['filter_name'])) {
      $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
    }

    if (isset($this->request->get['filter_model'])) {
      $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
    }

    if (isset($this->request->get['filter_price'])) {
      $url .= '&filter_price=' . $this->request->get['filter_price'];
    }

    if (isset($this->request->get['filter_quantity'])) {
      $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
    }

    if (isset($this->request->get['filter_status'])) {
      $url .= '&filter_status=' . $this->request->get['filter_status'];
    }

    if (isset($this->request->get['sort'])) {
      $url .= '&sort=' . $this->request->get['sort'];
    }

    if (isset($this->request->get['order'])) {
      $url .= '&order=' . $this->request->get['order'];
    }

    $pagination = new Pagination();
    $pagination->total = $product_total;
    $pagination->page = $page;
    $pagination->limit = $this->config->get('config_limit_admin');
    $pagination->url = $this->url->link('account/vendor/lts_product', '&page={page}');

    $data['pagination'] = $pagination->render();

    $data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));

    $data['filter_name'] = $filter_name;
    $data['filter_model'] = $filter_model;
    $data['filter_price'] = $filter_price;
    $data['filter_quantity'] = $filter_quantity;
    $data['filter_status'] = $filter_status;

    $data['sort'] = $sort;
    $data['order'] = $order;

    $this->load->controller('account/vendor/lts_header/script');
    $data['footer'] = $this->load->controller('common/footer');
    $data['header'] = $this->load->controller('common/header');
    $data['lts_column_left'] = $this->load->controller('account/vendor/lts_column_left');

    $this->response->setOutput($this->load->view('account/vendor/lts_product_list', $data));
  }

  public function edit() {
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

    $this->load->controller('account/vendor/lts_header/script');

    $this->load->language('account/vendor/lts_product');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('account/vendor/lts_product');
    // echo '<pre>'; print_r($this->request->post);die;

    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

      
      $this->model_account_vendor_lts_product->editProduct($this->request->get['product_id'], $vendor_info['vendor_id'],  $this->request->post);

      $this->session->data['success'] = $this->language->get('text_success');

      $url = '';

      if (isset($this->request->get['filter_name'])) {
        $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
      }

      if (isset($this->request->get['filter_model'])) {
        $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
      }

      if (isset($this->request->get['filter_price'])) {
        $url .= '&filter_price=' . $this->request->get['filter_price'];
      }

      if (isset($this->request->get['filter_quantity'])) {
        $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
      }

      if (isset($this->request->get['filter_status'])) {
        $url .= '&filter_status=' . $this->request->get['filter_status'];
      }

      if (isset($this->request->get['sort'])) {
        $url .= '&sort=' . $this->request->get['sort'];
      }

      if (isset($this->request->get['order'])) {
        $url .= '&order=' . $this->request->get['order'];
      }

      if (isset($this->request->get['page'])) {
        $url .= '&page=' . $this->request->get['page'];
      }

      $this->response->redirect($this->url->link('account/vendor/lts_product'));
    }

    $this->getForm();
  }

  protected function getForm() {
    $data['text_form'] = !isset($this->request->get['product_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

    if (isset($this->error['warning'])) {
      $data['error_warning'] = $this->error['warning'];
    } else {
      $data['error_warning'] = '';
    }

    if (isset($this->error['name'])) {
      $data['error_name'] = $this->error['name'];
    } else {
      $data['error_name'] = array();
    }

    if (isset($this->error['meta_title'])) {
      $data['error_meta_title'] = $this->error['meta_title'];
    } else {
      $data['error_meta_title'] = array();
    }

    if (isset($this->error['model'])) {
      $data['error_model'] = $this->error['model'];
    } else {
      $data['error_model'] = '';
    }

    if ($this->config->get('module_lts_vendor_status') && $this->config->get('module_lts_vendor_category_required')) {
      if (isset($this->error['error_product_category'])) {
        $data['error_product_category'] = $this->error['error_product_category'];
      } else {
        $data['error_product_category'] = '';
      }
    }

    if (isset($this->error['keyword'])) {
      $data['error_keyword'] = $this->error['keyword'];
    } else {
      $data['error_keyword'] = '';
    }

    $data['breadcrumbs'] = array();

    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('text_home'),
        'href' => $this->url->link('account/vendor/lts_dashboard')
    );

    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('heading_title'),
        'href' => $this->url->link('account/vendor/lts_product')
    );

    if (!isset($this->request->get['product_id'])) {
      $data['action'] = $this->url->link('account/vendor/lts_product/add');
    } else {
      $data['action'] = $this->url->link('account/vendor/lts_product/edit', '&product_id=' . $this->request->get['product_id'], true);
    }

    if (isset($this->request->get['product_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      $product_info = $this->model_account_vendor_lts_product->getProduct($this->request->get['product_id']);
    }

    $data['cancel'] = $this->url->link('account/vendor/lts_product');

   
    $this->load->model('account/vendor/lts_language');

    $data['languages'] = $this->model_account_vendor_lts_language->getLanguages();

    if (isset($this->request->post['product_description'])) {
      $data['product_description'] = $this->request->post['product_description'];
    } elseif (isset($this->request->get['product_id'])) {
      $data['product_description'] = $this->model_account_vendor_lts_product->getProductDescriptions($this->request->get['product_id']);
    } else {
      $data['product_description'] = array();
    }

    if (isset($this->request->post['model'])) {
      $data['model'] = $this->request->post['model'];
    } elseif (!empty($product_info)) {
      $data['model'] = $product_info['model'];
    } else {
      $data['model'] = '';
    }

    if (isset($this->request->post['sku'])) {
      $data['sku'] = $this->request->post['sku'];
    } elseif (!empty($product_info)) {
      $data['sku'] = $product_info['sku'];
    } else {
      $data['sku'] = '';
    }

    if (isset($this->request->post['upc'])) {
      $data['upc'] = $this->request->post['upc'];
    } elseif (!empty($product_info)) {
      $data['upc'] = $product_info['upc'];
    } else {
      $data['upc'] = '';
    }

    if (isset($this->request->post['ean'])) {
      $data['ean'] = $this->request->post['ean'];
    } elseif (!empty($product_info)) {
      $data['ean'] = $product_info['ean'];
    } else {
      $data['ean'] = '';
    }

    if (isset($this->request->post['jan'])) {
      $data['jan'] = $this->request->post['jan'];
    } elseif (!empty($product_info)) {
      $data['jan'] = $product_info['jan'];
    } else {
      $data['jan'] = '';
    }

    if (isset($this->request->post['isbn'])) {
      $data['isbn'] = $this->request->post['isbn'];
    } elseif (!empty($product_info)) {
      $data['isbn'] = $product_info['isbn'];
    } else {
      $data['isbn'] = '';
    }

    if (isset($this->request->post['mpn'])) {
      $data['mpn'] = $this->request->post['mpn'];
    } elseif (!empty($product_info)) {
      $data['mpn'] = $product_info['mpn'];
    } else {
      $data['mpn'] = '';
    }

    if (isset($this->request->post['location'])) {
      $data['location'] = $this->request->post['location'];
    } elseif (!empty($product_info)) {
      $data['location'] = $product_info['location'];
    } else {
      $data['location'] = '';
    }

    $this->load->model('account/vendor/lts_store');

    $data['stores'] = array();

    $data['stores'][] = array(
        'store_id' => 0,
        'name' => $this->language->get('text_default')
    );

    $stores = $this->model_account_vendor_lts_store->getStores();

    foreach ($stores as $store) {
      $data['stores'][] = array(
          'store_id' => $store['store_id'],
          'name' => $store['name']
      );
    }

    if (isset($this->request->post['product_store'])) {
      $data['product_store'] = $this->request->post['product_store'];
    } elseif (isset($this->request->get['product_id'])) {
      $data['product_store'] = $this->model_account_vendor_lts_product->getProductStores($this->request->get['product_id']);
    } else {
      $data['product_store'] = array(0);
    }

    if (isset($this->request->post['shipping'])) {
      $data['shipping'] = $this->request->post['shipping'];
    } elseif (!empty($product_info)) {
      $data['shipping'] = $product_info['shipping'];
    } else {
      $data['shipping'] = 1;
    }

    if (isset($this->request->post['price'])) {
      $data['price'] = $this->request->post['price'];
    } elseif (!empty($product_info)) {
      $data['price'] = $product_info['price'];
    } else {
      $data['price'] = '';
    }

    $this->load->model('account/vendor/lts_tax_class');

    $data['tax_classes'] = $this->model_account_vendor_lts_tax_class->getTaxClasses();

    if (isset($this->request->post['tax_class_id'])) {
      $data['tax_class_id'] = $this->request->post['tax_class_id'];
    } elseif (!empty($product_info)) {
      $data['tax_class_id'] = $product_info['tax_class_id'];
    } else {
      $data['tax_class_id'] = 0;
    }

    if (isset($this->request->post['date_available'])) {
      $data['date_available'] = $this->request->post['date_available'];
    } elseif (!empty($product_info)) {
      $data['date_available'] = ($product_info['date_available'] != '0000-00-00') ? $product_info['date_available'] : '';
    } else {
      $data['date_available'] = date('Y-m-d');
    }

    if (isset($this->request->post['quantity'])) {
      $data['quantity'] = $this->request->post['quantity'];
    } elseif (!empty($product_info)) {
      $data['quantity'] = $product_info['quantity'];
    } else {
      $data['quantity'] = 1;
    }

    if (isset($this->request->post['minimum'])) {
      $data['minimum'] = $this->request->post['minimum'];
    } elseif (!empty($product_info)) {
      $data['minimum'] = $product_info['minimum'];
    } else {
      $data['minimum'] = 1;
    }

    if (isset($this->request->post['subtract'])) {
      $data['subtract'] = $this->request->post['subtract'];
    } elseif (!empty($product_info)) {
      $data['subtract'] = $product_info['subtract'];
    } else {
      $data['subtract'] = 1;
    }

    if (isset($this->request->post['sort_order'])) {
      $data['sort_order'] = $this->request->post['sort_order'];
    } elseif (!empty($product_info)) {
      $data['sort_order'] = $product_info['sort_order'];
    } else {
      $data['sort_order'] = 1;
    }

    $this->load->model('account/vendor/lts_stock_status');

    $data['stock_statuses'] = $this->model_account_vendor_lts_stock_status->getStockStatuses();

    if (isset($this->request->post['stock_status_id'])) {
      $data['stock_status_id'] = $this->request->post['stock_status_id'];
    } elseif (!empty($product_info)) {
      $data['stock_status_id'] = $product_info['stock_status_id'];
    } else {
      $data['stock_status_id'] = 0;
    }

    if (isset($this->request->post['status'])) {
      $data['status'] = $this->request->post['status'];
    } elseif (!empty($product_info)) {
      $product_status = $this->model_account_vendor_lts_product->getVendorProductById($product_info['product_id']);
      $data['status'] = $product_status['status'];
    } else {
      $data['status'] = true;
    }

    if (isset($this->request->post['weight'])) {
      $data['weight'] = $this->request->post['weight'];
    } elseif (!empty($product_info)) {
      $data['weight'] = $product_info['weight'];
    } else {
      $data['weight'] = '';
    }

    $this->load->model('account/vendor/lts_weight_class');

    $data['weight_classes'] = $this->model_account_vendor_lts_weight_class->getWeightClasses();

    if (isset($this->request->post['weight_class_id'])) {
      $data['weight_class_id'] = $this->request->post['weight_class_id'];
    } elseif (!empty($product_info)) {
      $data['weight_class_id'] = $product_info['weight_class_id'];
    } else {
      $data['weight_class_id'] = $this->config->get('config_weight_class_id');
    }

    if (isset($this->request->post['length'])) {
      $data['length'] = $this->request->post['length'];
    } elseif (!empty($product_info)) {
      $data['length'] = $product_info['length'];
    } else {
      $data['length'] = '';
    }

    if (isset($this->request->post['width'])) {
      $data['width'] = $this->request->post['width'];
    } elseif (!empty($product_info)) {
      $data['width'] = $product_info['width'];
    } else {
      $data['width'] = '';
    }

    if (isset($this->request->post['height'])) {
      $data['height'] = $this->request->post['height'];
    } elseif (!empty($product_info)) {
      $data['height'] = $product_info['height'];
    } else {
      $data['height'] = '';
    }

    $this->load->model('account/vendor/lts_length_class');

    $data['length_classes'] = $this->model_account_vendor_lts_length_class->getLengthClasses();

    if (isset($this->request->post['length_class_id'])) {
      $data['length_class_id'] = $this->request->post['length_class_id'];
    } elseif (!empty($product_info)) {
      $data['length_class_id'] = $product_info['length_class_id'];
    } else {
      $data['length_class_id'] = $this->config->get('config_length_class_id');
    }

    $this->load->model('account/vendor/lts_manufacturer');

    if (isset($this->request->post['manufacturer_id'])) {
      $data['manufacturer_id'] = $this->request->post['manufacturer_id'];
    } elseif (!empty($product_info)) {
      $data['manufacturer_id'] = $product_info['manufacturer_id'];
    } else {
      $data['manufacturer_id'] = 0;
    }

    if (isset($this->request->post['manufacturer'])) {
      $data['manufacturer'] = $this->request->post['manufacturer'];
    } elseif (!empty($product_info)) {
      $manufacturer_info = $this->model_account_vendor_lts_manufacturer->getManufacturer($product_info['manufacturer_id']);

      if ($manufacturer_info) {
        $data['manufacturer'] = $manufacturer_info['name'];
      } else {
        $data['manufacturer'] = '';
      }
    } else {
      $data['manufacturer'] = '';
    }

    // Categories
    $this->load->model('account/vendor/lts_category');

    if (isset($this->request->post['product_category'])) {
      $categories = $this->request->post['product_category'];
    } elseif (isset($this->request->get['product_id'])) {
      $categories = $this->model_account_vendor_lts_product->getProductCategories($this->request->get['product_id']);
    } else {
      $categories = array();
    }

    $data['product_categories'] = array();

    foreach ($categories as $category_id) {
      $category_info = $this->model_account_vendor_lts_category->getCategory($category_id);

      if ($category_info) {
        $data['product_categories'][] = array(
            'category_id' => $category_info['category_id'],
            'name' => ($category_info['path']) ? $category_info['path'] . ' &gt; ' . $category_info['name'] : $category_info['name']
        );
      }
    }

    // Filters
    $this->load->model('account/vendor/lts_filter');

    if (isset($this->request->post['product_filter'])) {
      $filters = $this->request->post['product_filter'];
    } elseif (isset($this->request->get['product_id'])) {
      $filters = $this->model_account_vendor_lts_product->getProductFilters($this->request->get['product_id']);
    } else {
      $filters = array();
    }

    $data['product_filters'] = array();

    foreach ($filters as $filter_id) {
      $filter_info = $this->model_account_vendor_lts_filter->getFilter($filter_id);

      if ($filter_info) {
        $data['product_filters'][] = array(
            'filter_id' => $filter_info['filter_id'],
            'name' => $filter_info['group'] . ' &gt; ' . $filter_info['name']
        );
      }
    }

    // Attributes
    $this->load->model('account/vendor/lts_attribute');

    if (isset($this->request->post['product_attribute'])) {
      $product_attributes = $this->request->post['product_attribute'];
    } elseif (isset($this->request->get['product_id'])) {
      $product_attributes = $this->model_account_vendor_lts_product->getProductAttributes($this->request->get['product_id']);
    } else {
      $product_attributes = array();
    }

    $data['product_attributes'] = array();

    foreach ($product_attributes as $product_attribute) {
      $attribute_info = $this->model_account_vendor_lts_attribute->getAttribute($product_attribute['attribute_id']);

      if ($attribute_info) {
        $data['product_attributes'][] = array(
            'attribute_id' => $product_attribute['attribute_id'],
            'name' => $attribute_info['name'],
            'product_attribute_description' => $product_attribute['product_attribute_description']
        );
      }
    }

    // Options
    $this->load->model('account/vendor/lts_option');

    if (isset($this->request->post['product_option'])) {
      $product_options = $this->request->post['product_option'];
    } elseif (isset($this->request->get['product_id'])) {
      $product_options = $this->model_account_vendor_lts_product->getProductOptions($this->request->get['product_id']);
    } else {
      $product_options = array();
    }

    $data['product_options'] = array();

    foreach ($product_options as $product_option) {
      $product_option_value_data = array();

      if (isset($product_option['product_option_value'])) {
        foreach ($product_option['product_option_value'] as $product_option_value) {
          $product_option_value_data[] = array(
              'product_option_value_id' => $product_option_value['product_option_value_id'],
              'option_value_id' => $product_option_value['option_value_id'],
              'quantity' => $product_option_value['quantity'],
              'subtract' => $product_option_value['subtract'],
              'price' => $product_option_value['price'],
              'price_prefix' => $product_option_value['price_prefix'],
              'points' => $product_option_value['points'],
              'points_prefix' => $product_option_value['points_prefix'],
              'weight' => $product_option_value['weight'],
              'weight_prefix' => $product_option_value['weight_prefix']
          );
        }
      }

      $data['product_options'][] = array(
          'product_option_id' => $product_option['product_option_id'],
          'product_option_value' => $product_option_value_data,
          'option_id' => $product_option['option_id'],
          'name' => $product_option['name'],
          'type' => $product_option['type'],
          'value' => isset($product_option['value']) ? $product_option['value'] : '',
          'required' => $product_option['required']
      );
    }

    $data['option_values'] = array();

    foreach ($data['product_options'] as $product_option) {
      if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
        if (!isset($data['option_values'][$product_option['option_id']])) {
          $data['option_values'][$product_option['option_id']] = $this->model_account_vendor_lts_option->getOptionValues($product_option['option_id']);
        }
      }
    }

    $this->load->model('account/vendor/lts_customer_group');

    $data['customer_groups'] = $this->model_account_vendor_lts_customer_group->getCustomerGroups();

    if (isset($this->request->post['product_discount'])) {
      $product_discounts = $this->request->post['product_discount'];
    } elseif (isset($this->request->get['product_id'])) {
      $product_discounts = $this->model_account_vendor_lts_product->getProductDiscounts($this->request->get['product_id']);
    } else {
      $product_discounts = array();
    }

    $data['product_discounts'] = array();

    foreach ($product_discounts as $product_discount) {
      $data['product_discounts'][] = array(
          'customer_group_id' => $product_discount['customer_group_id'],
          'quantity' => $product_discount['quantity'],
          'priority' => $product_discount['priority'],
          'price' => $product_discount['price'],
          'date_start' => ($product_discount['date_start'] != '0000-00-00') ? $product_discount['date_start'] : '',
          'date_end' => ($product_discount['date_end'] != '0000-00-00') ? $product_discount['date_end'] : ''
      );
    }

    if (isset($this->request->post['product_special'])) {
      $product_specials = $this->request->post['product_special'];
    } elseif (isset($this->request->get['product_id'])) {
      $product_specials = $this->model_account_vendor_lts_product->getProductSpecials($this->request->get['product_id']);
    } else {
      $product_specials = array();
    }

    $data['product_specials'] = array();

    foreach ($product_specials as $product_special) {
      $data['product_specials'][] = array(
          'customer_group_id' => $product_special['customer_group_id'],
          'priority' => $product_special['priority'],
          'price' => $product_special['price'],
          'date_start' => ($product_special['date_start'] != '0000-00-00') ? $product_special['date_start'] : '',
          'date_end' => ($product_special['date_end'] != '0000-00-00') ? $product_special['date_end'] : ''
      );
    }
    // Image
    if (isset($this->request->post['image'])) {
      $data['image'] = $this->request->post['image'];
    } elseif (!empty($product_info)) {
      $data['image'] = $product_info['image'];
    } else {
      $data['image'] = '';
    }

    //$this->load->model('vendor/image');
    $this->load->model('tool/image');

    if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
      $data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
    } elseif (!empty($product_info) && is_file(DIR_IMAGE . $product_info['image'])) {
      $data['thumb'] = $this->model_tool_image->resize($product_info['image'], 100, 100);
    } else {
      $data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
    }

    $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

    // Images
    if (isset($this->request->post['product_image'])) {
      $product_images = $this->request->post['product_image'];
    } elseif (isset($this->request->get['product_id'])) {
      $product_images = $this->model_account_vendor_lts_product->getProductImages($this->request->get['product_id']);
    } else {
      $product_images = array();
    }

    $data['product_images'] = array();

    foreach ($product_images as $product_image) {
      if (is_file(DIR_IMAGE . $product_image['image'])) {
        $image = $product_image['image'];
        $thumb = $product_image['image'];
      } else {
        $image = '';
        $thumb = 'no_image.png';
      }

      $data['product_images'][] = array(
          'image' => $image,
          'thumb' => $this->model_tool_image->resize($thumb, 100, 100),
          'sort_order' => $product_image['sort_order']
      );
    }

    // Downloads
    $this->load->model('account/vendor/lts_download');

    if (isset($this->request->post['product_download'])) {
      $product_downloads = $this->request->post['product_download'];
    } elseif (isset($this->request->get['product_id'])) {
      $product_downloads = $this->model_account_vendor_lts_product->getProductDownloads($this->request->get['product_id']);
    } else {
      $product_downloads = array();
    }

    $data['product_downloads'] = array();

    foreach ($product_downloads as $download_id) {
      $download_info = $this->model_account_vendor_lts_download->getDownload($download_id);

      if ($download_info) {
        $data['product_downloads'][] = array(
            'download_id' => $download_info['download_id'],
            'name' => $download_info['name']
        );
      }
    }

    if (isset($this->request->post['product_related'])) {
      $products = $this->request->post['product_related'];
    } elseif (isset($this->request->get['product_id'])) {
      $products = $this->model_account_vendor_lts_product->getProductRelated($this->request->get['product_id']);
    } else {
      $products = array();
    }

    $data['product_relateds'] = array();

    foreach ($products as $product_id) {
      $related_info = $this->model_account_vendor_lts_product->getProduct($product_id);

      if ($related_info) {
        $data['product_relateds'][] = array(
            'product_id' => $related_info['product_id'],
            'name' => $related_info['name']
        );
      }
    }

    if (isset($this->request->post['points'])) {
      $data['points'] = $this->request->post['points'];
    } elseif (!empty($product_info)) {
      $data['points'] = $product_info['points'];
    } else {
      $data['points'] = '';
    }

    if (isset($this->request->post['product_reward'])) {
      $data['product_reward'] = $this->request->post['product_reward'];
    } elseif (isset($this->request->get['product_id'])) {
      $data['product_reward'] = $this->model_account_vendor_lts_product->getProductRewards($this->request->get['product_id']);
    } else {
      $data['product_reward'] = array();
    }

    if (isset($this->request->post['product_seo_url'])) {
      $data['product_seo_url'] = $this->request->post['product_seo_url'];
    } elseif (isset($this->request->get['product_id'])) {
      $data['product_seo_url'] = $this->model_account_vendor_lts_product->getProductSeoUrls($this->request->get['product_id']);
    } else {
      $data['product_seo_url'] = array();
    }

    if (isset($this->request->post['product_layout'])) {
      $data['product_layout'] = $this->request->post['product_layout'];
    } elseif (isset($this->request->get['product_id'])) {
      $data['product_layout'] = $this->model_account_vendor_lts_product->getProductLayouts($this->request->get['product_id']);
    } else {
      $data['product_layout'] = array();
    }

    $this->load->model('account/vendor/lts_layout');
    $data['layouts'] = $this->model_account_vendor_lts_layout->getLayouts();

    if ($this->config->get("module_lts_vendor_status") && $this->config->get('module_lts_vendor_product_tab')) {
      $tabs = $this->config->get('module_lts_vendor_product_tab');

      if (isset($tabs['links'])) {
        $data['links'] = $tabs['links'];
      }

      if (isset($tabs['attribute'])) {
        $data['attribute'] = $tabs['attribute'];
      }

      if (isset($tabs['option'])) {
        $data['option'] = $tabs['option'];
      }

      if (isset($tabs['discount'])) {
        $data['discount'] = $tabs['discount'];
      }

      if (isset($tabs['special'])) {
        $data['special'] = $tabs['special'];
      }

      if (isset($tabs['image'])) {
        $data['images'] = $tabs['image'];
      }

      if (isset($tabs['reward'])) {
        $data['reward'] = $tabs['reward'];
      }

      if (isset($tabs['seo'])) {
        $data['seo'] = $tabs['seo'];
      }

      if (isset($tabs['design'])) {
        $data['design'] = $tabs['design'];
      }
    }

    // $data['module_lts_vendor_status'] = $this->config->get('module_lts_vendor_status');



    if ($this->config->get("module_lts_vendor_status") && $this->config->get('module_lts_vendor_category_required')) {

      $data['module_lts_vendor_category_required'] = $this->config->get('module_lts_vendor_category_required');
    }


    if ($this->config->get("module_lts_vendor_status") && $this->config->get('module_lts_vendor_pincode_checker')) {

      $pincode_checker = $this->config->get('module_lts_vendor_pincode_checker');

      if (isset($pincode_checker['vendor'])) {
        $data['vendor_pincode'] = $pincode_checker['vendor'];
      }
    }

    $this->load->controller('account/vendor/lts_header/script');
    $data['footer'] = $this->load->controller('common/footer');
    $data['header'] = $this->load->controller('common/header');
    $data['lts_column_left'] = $this->load->controller('account/vendor/lts_column_left');

    $this->response->setOutput($this->load->view('account/vendor/lts_product_form', $data));
  }

  protected function validateForm() {
    foreach ($this->request->post['product_description'] as $language_id => $value) {
      if ((utf8_strlen($value['name']) < 1) || (utf8_strlen($value['name']) > 255)) {
        $this->error['name'][$language_id] = $this->language->get('error_name');
      }

      if ((utf8_strlen($value['meta_title']) < 1) || (utf8_strlen($value['meta_title']) > 255)) {
        $this->error['meta_title'][$language_id] = $this->language->get('error_meta_title');
      }
    }

    if ((utf8_strlen($this->request->post['model']) < 1) || (utf8_strlen($this->request->post['model']) > 64)) {
      $this->error['model'] = $this->language->get('error_model');
    }

    if ($this->config->get('module_lts_vendor_status') && $this->config->get('module_lts_vendor_category_required')) {
      if (!isset($this->request->post['product_category'])) {
        $this->error['error_product_category'] = $this->language->get('error_product_category');
      }
    }

   

    if ($this->request->post['product_seo_url']) {
      $this->load->model('account/vendor/lts_seo_url');

      foreach ($this->request->post['product_seo_url'] as $store_id => $language) {
        foreach ($language as $language_id => $keyword) {
          if (!empty($keyword)) {
            if (count(array_keys($language, $keyword)) > 1) {
              $this->error['keyword'][$store_id][$language_id] = $this->language->get('error_unique');
            }

            $seo_urls = $this->model_account_vendor_lts_seo_url->getSeoUrlsByKeyword($keyword);

            foreach ($seo_urls as $seo_url) {
              if (($seo_url['store_id'] == $store_id) && (!isset($this->request->get['product_id']) || (($seo_url['query'] != 'product_id=' . $this->request->get['product_id'])))) {
                $this->error['keyword'][$store_id][$language_id] = $this->language->get('error_keyword');

                break;
              }
            }
          }
        }
      }
    }

    if ($this->error && !isset($this->error['warning'])) {
      $this->error['warning'] = $this->language->get('error_warning');
    }

    $this->load->model('account/vendor/lts_product');
    $this->load->model('account/vendor/lts_subscription');
    $vednor_info = $this->model_account_vendor_lts_vendor->getVendorStoreInfo($this->customer->getId());
    $plan_info = $this->model_account_vendor_lts_subscription->getVendonActivePlan($vednor_info['vendor_id']);
    
    $total = $this->model_account_vendor_lts_product->get_vendor_product_count();

    if(!empty($plan_info) ) {
       $plan_info['no_of_product'] = $plan_info['no_of_product'];
    } else {
      $plan_info['no_of_product'] = 0;
    }

    if ($total >= $plan_info['no_of_product']) {
      $this->error['warning'] = $this->language->get('error_limit');
    }
    
    return !$this->error;
  }

  public function autocomplete() {
    
    $json = array();

    if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_model'])) {
      $this->load->model('account/vendor/lts_product');
      $this->load->model('account/vendor/lts_option');

      $this->load->model('account/vendor/lts_vendor');
         
      $vendor_info = $this->model_account_vendor_lts_vendor->getVendorStoreInfo($this->customer->isLogged());

      if(!$vendor_info) {
        $this->response->redirect($this->url->link('account/account', '', true));
      }


      if (isset($this->request->get['filter_name'])) {
        $filter_name = $this->request->get['filter_name'];
      } else {
        $filter_name = '';
      }

      if (isset($this->request->get['filter_model'])) {
        $filter_model = $this->request->get['filter_model'];
      } else {
        $filter_model = '';
      }

      if (isset($this->request->get['limit'])) {
        $limit = $this->request->get['limit'];
      } else {
        $limit = 5;
      } 

      $filter_data = array(
          'filter_name' => $filter_name,
          'filter_model' => $filter_model,
          'vendor_id'   => $vendor_info['vendor_id'],
          'start' => 0,
          'limit' => $limit
      );

      $results = $this->model_account_vendor_lts_product->getProducts($filter_data);

      foreach ($results as $result) {
        $option_data = array();

        $product_options = $this->model_account_vendor_lts_product->getProductOptions($result['product_id']);

        foreach ($product_options as $product_option) {
          $option_info = $this->model_account_vendor_lts_option->getOption($product_option['option_id']);

          if ($option_info) {
            $product_option_value_data = array();

            foreach ($product_option['product_option_value'] as $product_option_value) {
              $option_value_info = $this->model_account_vendor_lts_option->getOptionValue($product_option_value['option_value_id']);

              if ($option_value_info) {
                $product_option_value_data[] = array(
                    'product_option_value_id' => $product_option_value['product_option_value_id'],
                    'option_value_id' => $product_option_value['option_value_id'],
                    'name' => $option_value_info['name'],
                    'price' => (float) $product_option_value['price'] ? $this->currency->format($product_option_value['price'], $this->config->get('config_currency')) : false,
                    'price_prefix' => $product_option_value['price_prefix']
                );
              }
            }

            $option_data[] = array(
                'product_option_id' => $product_option['product_option_id'],
                'product_option_value' => $product_option_value_data,
                'option_id' => $product_option['option_id'],
                'name' => $option_info['name'],
                'type' => $option_info['type'],
                'value' => $product_option['value'],
                'required' => $product_option['required']
            );
          }
        }

        $json[] = array(
            'product_id' => $result['product_id'],
            'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
            'model' => $result['model'],
            'option' => $option_data,
            'price' => $result['price']
        );
      }
    }

    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
  }

public function send_mail_to_admin($product_id,$vendor){
    $this->load->model('catalog/product');
    $this->load->model('vendor/lts_product');

    $this->load->language('account/vendor/lts_product');

    $data['product']= $this->model_vendor_lts_product->getProduct($product_id);
  
    $data['vendor'] =$vendor;
    $data['store'] = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');
    $mail_to=$this->config->get('config_mail');
    $mail = new Mail($this->config->get('config_mail_engine'));
    $mail->parameter = $this->config->get('config_mail_parameter');
    $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
    $mail->smtp_username = $this->config->get('config_mail_smtp_username');
    $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
    $mail->smtp_port = $this->config->get('config_mail_smtp_port');
    $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

    $mail->setTo($this->config->get('config_email'));
    $mail->setFrom($this->config->get('config_email'));
    $mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
    $mail->setSubject(sprintf($this->language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8')));
    $mail->setText($this->load->view('account/vendor/product_mail', $data));
    $mail->send(); 
}



}
