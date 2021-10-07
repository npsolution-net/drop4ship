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
		$this->model_dropship_dropship->setGroupId($data);

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

    // $this->load->language('account/vendor/lts_product');

    // $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('dropship/dropship');

    // $vendor_id = $this->customer->getId();

    if (isset($this->request->post['selected'])) {
    //   foreach ($this->request->post['selected'] as $product_id) {
		$this->model_dropship_dropship->deleteDropships($this->request->post);
    //   }

      //$this->session->data['success'] = $this->language->get('text_success');

    //   $url = '';

    //   if (isset($this->request->get['filter_name'])) {
    //     $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
    //   }

    //   if (isset($this->request->get['filter_model'])) {
    //     $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
    //   }

    //   if (isset($this->request->get['filter_price'])) {
    //     $url .= '&filter_price=' . $this->request->get['filter_price'];
    //   }

    //   if (isset($this->request->get['filter_quantity'])) {
    //     $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
    //   }

    //   if (isset($this->request->get['filter_status'])) {
    //     $url .= '&filter_status=' . $this->request->get['filter_status'];
    //   }

    //   if (isset($this->request->get['sort'])) {
    //     $url .= '&sort=' . $this->request->get['sort'];
    //   }

    //   if (isset($this->request->get['order'])) {
    //     $url .= '&order=' . $this->request->get['order'];
    //   }

    //   if (isset($this->request->get['page'])) {
    //     $url .= '&page=' . $this->request->get['page'];
    //   }

      	$this->response->redirect($this->url->link('dropship/dropship'));
    }

    $this->getList();
  }

  protected function getList() {
   
    // if (isset($this->request->get['filter_name'])) {
    //   $filter_name = $this->request->get['filter_name'];
    // } else {
    //   $filter_name = '';
    // }

    // if (isset($this->request->get['filter_model'])) {
    //   $filter_model = $this->request->get['filter_model'];
    // } else {
    //   $filter_model = '';
    // }

    // if (isset($this->request->get['filter_price'])) {
    //   $filter_price = $this->request->get['filter_price'];
    // } else {
    //   $filter_price = '';
    // }

    // if (isset($this->request->get['filter_quantity'])) {
    //   $filter_quantity = $this->request->get['filter_quantity'];
    // } else {
    //   $filter_quantity = '';
    // }

    // if (isset($this->request->get['filter_status'])) {
    //   $filter_status = $this->request->get['filter_status'];
    // } else {
    //   $filter_status = '';
    // }

    // if (isset($this->request->get['sort'])) {
    //   $sort = $this->request->get['sort'];
    // } else {
    //   $sort = 'pd.name';
    // }

    // if (isset($this->request->get['order'])) {
    //   $order = $this->request->get['order'];
    // } else {
    //   $order = 'ASC';
    // }

    // if (isset($this->request->get['page'])) {
    //   $page = $this->request->get['page'];
    // } else {
    //   $page = 1;
    // }

    // $url = '';

    // if (isset($this->request->get['filter_name'])) {
    //   $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
    // }

    // if (isset($this->request->get['filter_model'])) {
    //   $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
    // }

    // if (isset($this->request->get['filter_price'])) {
    //   $url .= '&filter_price=' . $this->request->get['filter_price'];
    // }

    // if (isset($this->request->get['filter_quantity'])) {
    //   $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
    // }

    // if (isset($this->request->get['filter_status'])) {
    //   $url .= '&filter_status=' . $this->request->get['filter_status'];
    // }

    // if (isset($this->request->get['order'])) {
    //   $url .= '&order=' . $this->request->get['order'];
    // }

    // if (isset($this->request->get['page'])) {
    //   $url .= '&page=' . $this->request->get['page'];
    // }

    $data['breadcrumbs'] = array();

    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('text_home'),
        'href' => $this->url->link('account/vendor/lts_dashboard')
    );

    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('heading_title'),
        'href' => $this->url->link('dropship/dropship')
    );
    // $products=$this->model_account_vendor_lts_product->get_vendor_product_count();
   
    // $can_add_multi_product=$this->config->get('module_lts_vendor_multi_product');
    // if($can_add_multi_product){
    //     $data['add'] = $this->url->link('account/vendor/lts_product/add');
    //     $data['copy'] = $this->url->link('account/vendor/lts_product/copy');
     
    // }else{
    //    if($products==0){
    //     $data['add'] = $this->url->link('account/vendor/lts_product/add');
    //     $data['copy'] = $this->url->link('account/vendor/lts_product/copy');
    //    }else{
    //      $data['add'] = '';
    //      $data['copy'] ='';
    //   }
    // }
    
    // $data['delete'] = $this->url->link('dropship/dropship/delete');

    // $this->load->model('account/vendor/lts_vendor');

    // if($this->customer->isLogged()){
    //     $data['customer_id'] = $this->customer->getId();
    // }
     
    // $vendor_info = $this->model_account_vendor_lts_vendor->getVendorStoreInfo($this->customer->isLogged());

    // $vendor_id = $vendor_info['vendor_id'];


    $data['dropships'] = array();

    $filter_data = array(
		'customer_group_id' => $this->customer->getGroupId(),
        'customer_id' => $this->customer->getId(),
        // 'filter_model' => $filter_model,
        // 'filter_price' => $filter_price,
        // 'filter_quantity' => $filter_quantity,
        // 'filter_status' => $filter_status,
        // 'sort' => $sort,
        // 'order' => $order,
        // 'start' => ($page - 1) * $this->config->get('config_limit_admin'),
        // 'limit' => $this->config->get('config_limit_admin'),
        // 'vendor_id' => $vendor_id
    );

    // $this->load->model('account/vendor/lts_image');

    // $product_total = $this->model_account_vendor_lts_product->getTotalProducts($filter_data);

    $results = $this->model_dropship_dropship->getDropships($filter_data);

    foreach ($results as $result) {
    //   if (is_file(DIR_IMAGE . $result['image'])) {
    //     $image = $this->model_account_vendor_lts_image->resize($result['image'], 40, 40);
    //   } else {
    //     $image = $this->model_account_vendor_lts_image->resize('no_image.png', 40, 40);
    //   }

    //   $special = false;

    //   $product_specials = $this->model_account_vendor_lts_product->getProductSpecials($result['product_id']);
    //   $product_status = $this->model_account_vendor_lts_product->getVendorProductById($result['product_id']);
    //   foreach ($product_specials as $product_special) {
    //     if (($product_special['date_start'] == '0000-00-00' || strtotime($product_special['date_start']) < time()) && ($product_special['date_end'] == '0000-00-00' || strtotime($product_special['date_end']) > time())) {
    //       $special = $this->currency->format($product_special['price'], $this->config->get('config_currency'));

    //       break;
    //     }
    //   }

      $data['dropships'][] = array(
          'customer_id' => $result['customer_id'],
		  'owner_id' => $result['owner_id'],
          'name' => $result['firstname'] . ' ' . $result['lastname'], 
          'email' => $result['email'],
          'telephone' => $result['telephone'],
          'status' => $result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
        //   'edit' => $this->url->link('dropship/dropship/edit', '&product_id=' . $result['product_id'] . $url, true)
      );
    }

	$data['add'] = $this->url->link('dropship/dropship/add');
    $data['delete'] = $this->url->link('dropship/dropship/delete');

    // if ($this->config->get('module_lts_vendor_status') && $this->config->get('module_lts_vendor_delete_product')) {

    //   $data['module_lts_vendor_delete_product'] = $this->config->get('module_lts_vendor_delete_product');
    // }


    // if (isset($this->error['warning'])) {
    //   $data['error_warning'] = $this->error['warning'];
    // } else {
    //   $data['error_warning'] = '';
    // }

    // if (isset($this->session->data['success'])) {
    //   $data['success'] = $this->session->data['success'];

    //   unset($this->session->data['success']);
    // } else {
    //   $data['success'] = '';
    // }

    // if (isset($this->request->post['selected'])) {
    //   $data['selected'] = (array) $this->request->post['selected'];
    // } else {
    //   $data['selected'] = array();
    // }

    // $url = '';

    // if (isset($this->request->get['filter_name'])) {
    //   $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
    // }

    // if (isset($this->request->get['filter_model'])) {
    //   $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
    // }

    // if (isset($this->request->get['filter_price'])) {
    //   $url .= '&filter_price=' . $this->request->get['filter_price'];
    // }

    // if (isset($this->request->get['filter_quantity'])) {
    //   $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
    // }

    // if (isset($this->request->get['filter_status'])) {
    //   $url .= '&filter_status=' . $this->request->get['filter_status'];
    // }

    // if ($order == 'ASC') {
    //   $url .= '&order=DESC';
    // } else {
    //   $url .= '&order=ASC';
    // }

    // if (isset($this->request->get['page'])) {
    //   $url .= '&page=' . $this->request->get['page'];
    // }

    // $data['sort_name'] = $this->url->link('account/vendor/lts_product');
    // $data['sort_model'] = $this->url->link('account/vendor/lts_product');
    // $data['sort_price'] = $this->url->link('account/vendor/lts_product');
    // $data['sort_quantity'] = $this->url->link('account/vendor/lts_product');
    // $data['sort_status'] = $this->url->link('account/vendor/lts_product');
    // $data['sort_order'] = $this->url->link('account/vendor/lts_product');

    // $url = '';

    // if (isset($this->request->get['filter_name'])) {
    //   $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
    // }

    // if (isset($this->request->get['filter_model'])) {
    //   $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
    // }

    // if (isset($this->request->get['filter_price'])) {
    //   $url .= '&filter_price=' . $this->request->get['filter_price'];
    // }

    // if (isset($this->request->get['filter_quantity'])) {
    //   $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
    // }

    // if (isset($this->request->get['filter_status'])) {
    //   $url .= '&filter_status=' . $this->request->get['filter_status'];
    // }

    // if (isset($this->request->get['sort'])) {
    //   $url .= '&sort=' . $this->request->get['sort'];
    // }

    // if (isset($this->request->get['order'])) {
    //   $url .= '&order=' . $this->request->get['order'];
    // }

    // $pagination = new Pagination();
    // $pagination->total = $product_total;
    // $pagination->page = $page;
    // $pagination->limit = $this->config->get('config_limit_admin');
    // $pagination->url = $this->url->link('account/vendor/lts_product', '&page={page}');

    // $data['pagination'] = $pagination->render();

    // $data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));

    // $data['filter_name'] = $filter_name;
    // $data['filter_model'] = $filter_model;
    // $data['filter_price'] = $filter_price;
    // $data['filter_quantity'] = $filter_quantity;
    // $data['filter_status'] = $filter_status;

    // $data['sort'] = $sort;
    // $data['order'] = $order;

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
