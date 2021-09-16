<?php

class ControllerAccountVendorLtsReview extends Controller {

  private $error = array();

  public function index() {

  
    if (!$this->customer->isLogged()) {
        $this->session->data['redirect'] = $this->url->link('account/account', '', true);

        $this->response->redirect($this->url->link('account/login', '', true));
    }

    if(!$this->config->get('module_lts_vendor_status') ) {
        $this->response->redirect($this->url->link('account/account', '', true));
    }

     if($this->config->get('module_lts_vendor_status') &&  !$this->config->get('module_lts_vendor_review_action')) {
        $this->response->redirect($this->url->link('account/vendor/lts_dashboard', '', true));
    }

    $this->load->model('account/vendor/lts_vendor');

    if($this->customer->isLogged()){
        $data['customer_id'] = $this->customer->getId();
    }
     
    $vendor_info = $this->model_account_vendor_lts_vendor->getVendorStoreInfo($this->customer->isLogged());

    if(!$vendor_info) {
        $this->response->redirect($this->url->link('account/account', '', true));
    }

    $this->load->language('account/vendor/lts_review');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('account/vendor/lts_review');
 
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

   
    $this->load->language('account/vendor/lts_review');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('account/vendor/lts_review');

    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

      $this->model_account_vendor_lts_review->addReview($vendor_info['vendor_id'], $this->request->post);


      $this->session->data['success'] = $this->language->get('text_success');

      $url = '';

      if (isset($this->request->get['filter_product'])) {
        $url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
      }

      if (isset($this->request->get['filter_author'])) {
        $url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
      }

      if (isset($this->request->get['filter_status'])) {
        $url .= '&filter_status=' . $this->request->get['filter_status'];
      }

      if (isset($this->request->get['filter_date_added'])) {
        $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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

      $this->response->redirect($this->url->link('account/vendor/lts_review', $url));
    }

    $this->getForm();
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

    $this->load->language('account/vendor/lts_review');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('account/vendor/lts_review');

    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      $this->model_account_vendor_lts_review->editReview($this->request->get['review_id'], $vendor_info['vendor_id'], $this->request->post);

      $this->session->data['success'] = $this->language->get('text_success');

      $url = '';

      if (isset($this->request->get['filter_product'])) {
        $url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
      }

      if (isset($this->request->get['filter_author'])) {
        $url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
      }

      if (isset($this->request->get['filter_status'])) {
        $url .= '&filter_status=' . $this->request->get['filter_status'];
      }

      if (isset($this->request->get['filter_date_added'])) {
        $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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

      $this->response->redirect($this->url->link('account/vendor/lts_review', $url, true));
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

    $this->load->language('account/vendor/lts_review');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('account/vendor/lts_review');

    if (isset($this->request->post['selected'])) {
      foreach ($this->request->post['selected'] as $review_id) {
        $this->model_account_vendor_lts_review->deleteReview($review_id, $vendor_info['vendor_id']);
      }

      $this->session->data['success'] = $this->language->get('text_success');

      $url = '';

      if (isset($this->request->get['filter_product'])) {
        $url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
      }

      if (isset($this->request->get['filter_author'])) {
        $url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
      }

      if (isset($this->request->get['filter_status'])) {
        $url .= '&filter_status=' . $this->request->get['filter_status'];
      }

      if (isset($this->request->get['filter_date_added'])) {
        $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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

      $this->response->redirect($this->url->link('account/vendor/lts_review', $url, true));
    }

    $this->getList();
  }

  protected function getList() {
  
    if (isset($this->request->get['filter_product'])) {
      $filter_product = $this->request->get['filter_product'];
    } else {
      $filter_product = '';
    }

    if (isset($this->request->get['filter_author'])) {
      $filter_author = $this->request->get['filter_author'];
    } else {
      $filter_author = '';
    }

    if (isset($this->request->get['filter_status'])) {
      $filter_status = $this->request->get['filter_status'];
    } else {
      $filter_status = '';
    }

    if (isset($this->request->get['filter_date_added'])) {
      $filter_date_added = $this->request->get['filter_date_added'];
    } else {
      $filter_date_added = '';
    }

    if (isset($this->request->get['order'])) {
      $order = $this->request->get['order'];
    } else {
      $order = 'DESC';
    }

    if (isset($this->request->get['sort'])) {
      $sort = $this->request->get['sort'];
    } else {
      $sort = 'r.date_added';
    }

    if (isset($this->request->get['page'])) {
      $page = $this->request->get['page'];
    } else {
      $page = 1;
    }

    $url = '';

    if (isset($this->request->get['filter_product'])) {
      $url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
    }

    if (isset($this->request->get['filter_author'])) {
      $url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
    }

    if (isset($this->request->get['filter_status'])) {
      $url .= '&filter_status=' . $this->request->get['filter_status'];
    }

    if (isset($this->request->get['filter_date_added'])) {
      $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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

    $data['breadcrumbs'] = array();

    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('text_home'),
        'href' => $this->url->link('account/vendor/lts_dashboard', true)
    );

    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('heading_title'),
        'href' => $this->url->link('account/vendor/lts_review', $url, true)
    );

    $data['add'] = $this->url->link('account/vendor/lts_review/add');
    $data['delete'] = $this->url->link('account/vendor/lts_review/delete');

    $this->load->model('account/vendor/lts_review');

     $vendor_info = $this->model_account_vendor_lts_vendor->getVendorStoreInfo($this->customer->isLogged());


    $data['reviews'] = array();

    $filter_data = array(
        'filter_product' => $filter_product,
        'filter_author' => $filter_author,
        'filter_status' => $filter_status,
        'filter_date_added' => $filter_date_added,
        'sort' => $sort,
        'order' => $order,
        'start' => ($page - 1) * $this->config->get('config_limit_admin'),
        'limit' => $this->config->get('config_limit_admin'),
        'vendor_id' => $vendor_info['vendor_id']
    );

    $review_total = $this->model_account_vendor_lts_review->getTotalReviews($filter_data);

    $results = $this->model_account_vendor_lts_review->getReviews($filter_data);

    foreach ($results as $result) {
      $data['reviews'][] = array(
          'review_id' => $result['review_id'],
          'name' => $result['name'],
          'author' => $result['author'],
          'rating' => $result['rating'],
          'status' => ($result['status']) ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
          'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
          'edit' => $this->url->link('account/vendor/lts_review/edit', '&review_id=' . $result['review_id'] . $url, true)
      );
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

    if (isset($this->request->get['filter_product'])) {
      $url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
    }

    if (isset($this->request->get['filter_author'])) {
      $url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
    }

    if (isset($this->request->get['filter_status'])) {
      $url .= '&filter_status=' . $this->request->get['filter_status'];
    }

    if (isset($this->request->get['filter_date_added'])) {
      $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
    }

    if ($order == 'ASC') {
      $url .= '&order=DESC';
    } else {
      $url .= '&order=ASC';
    }

    if (isset($this->request->get['page'])) {
      $url .= '&page=' . $this->request->get['page'];
    }

    $data['sort_product'] = $this->url->link('account/vendor/lts_review', '&sort=pd.name' . $url, true);
    $data['sort_author'] = $this->url->link('account/vendor/lts_review', '&sort=r.author' . $url, true);
    $data['sort_rating'] = $this->url->link('account/vendor/lts_review', '&sort=r.rating' . $url, true);
    $data['sort_status'] = $this->url->link('account/vendor/lts_review', '&sort=r.status' . $url, true);
    $data['sort_date_added'] = $this->url->link('account/vendor/lts_review', '&sort=r.date_added' . $url, true);

    $url = '';

    if (isset($this->request->get['filter_product'])) {
      $url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
    }

    if (isset($this->request->get['filter_author'])) {
      $url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
    }

    if (isset($this->request->get['filter_status'])) {
      $url .= '&filter_status=' . $this->request->get['filter_status'];
    }

    if (isset($this->request->get['filter_date_added'])) {
      $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
    }

    if (isset($this->request->get['sort'])) {
      $url .= '&sort=' . $this->request->get['sort'];
    }

    if (isset($this->request->get['order'])) {
      $url .= '&order=' . $this->request->get['order'];
    }

    $pagination = new Pagination();
    $pagination->total = $review_total;
    $pagination->page = $page;
    $pagination->limit = $this->config->get('config_limit_admin');
    $pagination->url = $this->url->link('account/vendor/lts_review', $url . '&page={page}', true);

    $data['pagination'] = $pagination->render();

    $data['results'] = sprintf($this->language->get('text_pagination'), ($review_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($review_total - $this->config->get('config_limit_admin'))) ? $review_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $review_total, ceil($review_total / $this->config->get('config_limit_admin')));

    $data['filter_product'] = $filter_product;
    $data['filter_author'] = $filter_author;
    $data['filter_status'] = $filter_status;
    $data['filter_date_added'] = $filter_date_added;

    $data['sort'] = $sort;
    $data['order'] = $order;

    $this->load->controller('account/vendor/lts_header/script');
    $data['footer'] = $this->load->controller('common/footer');
    $data['header'] = $this->load->controller('common/header');
    $data['lts_column_left'] = $this->load->controller('account/vendor/lts_column_left');

    $this->response->setOutput($this->load->view('account/vendor/lts_review_list', $data));
  }

  protected function getForm() {

    if($this->customer->isLogged()){
     $data['customer_id'] = $this->customer->getId();
    }

    $this->load->model('account/vendor/lts_vendor');

    $vendor_info=$this->model_account_vendor_lts_vendor->getVendorInfo($data['customer_id']);

    $data['text_form'] = !isset($this->request->get['review_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

    if (isset($this->error['warning'])) {
      $data['error_warning'] = $this->error['warning'];
    } else {
      $data['error_warning'] = '';
    }

    if (isset($this->error['product'])) {
      $data['error_product'] = $this->error['product'];
    } else {
      $data['error_product'] = '';
    }

    if (isset($this->error['author'])) {
      $data['error_author'] = $this->error['author'];
    } else {
      $data['error_author'] = '';
    }

    if (isset($this->error['text'])) {
      $data['error_text'] = $this->error['text'];
    } else {
      $data['error_text'] = '';
    }

    if (isset($this->error['rating'])) {
      $data['error_rating'] = $this->error['rating'];
    } else {
      $data['error_rating'] = '';
    }

    $url = '';

    if (isset($this->request->get['filter_product'])) {
      $url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
    }

    if (isset($this->request->get['filter_author'])) {
      $url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
    }

    if (isset($this->request->get['filter_status'])) {
      $url .= '&filter_status=' . $this->request->get['filter_status'];
    }

    if (isset($this->request->get['filter_date_added'])) {
      $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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

    $data['breadcrumbs'] = array();

    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('text_home'),
        'href' => $this->url->link('account/vendor/lts_dashboard')
    );

    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('heading_title'),
        'href' => $this->url->link('account/vendor/lts_review', $url)
    );

    if (!isset($this->request->get['review_id'])) {
      $data['action'] = $this->url->link('account/vendor/lts_review/add', $url);
    } else {
      $data['action'] = $this->url->link('account/vendor/lts_review/edit', '&review_id=' . $this->request->get['review_id'] . $url);
    }

    $data['cancel'] = $this->url->link('account/vendor/lts_review');

    if (isset($this->request->get['review_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      $review_info = $this->model_account_vendor_lts_review->getReview($this->request->get['review_id'],$vendor_info['vendor_id']);
    }

    $this->load->model('account/vendor/lts_product');

    if (isset($this->request->post['product_id'])) {
      $data['product_id'] = $this->request->post['product_id'];
    } elseif (!empty($review_info)) {
      $data['product_id'] = $review_info['product_id'];
    } else {
      $data['product_id'] = '';
    }

    if (isset($this->request->post['product'])) {
      $data['product'] = $this->request->post['product'];
    } elseif (!empty($review_info)) {
      $data['product'] = $review_info['product'];
    } else {
      $data['product'] = '';
    }

    if (isset($this->request->post['author'])) {
      $data['author'] = $this->request->post['author'];
    } elseif (!empty($review_info)) {
      $data['author'] = $review_info['author'];
    } else {
      $data['author'] = '';
    }

    if (isset($this->request->post['text'])) {
      $data['text'] = $this->request->post['text'];
    } elseif (!empty($review_info)) {
      $data['text'] = $review_info['text'];
    } else {
      $data['text'] = '';
    }

    if (isset($this->request->post['rating'])) {
      $data['rating'] = $this->request->post['rating'];
    } elseif (!empty($review_info)) {
      $data['rating'] = $review_info['rating'];
    } else {
      $data['rating'] = '';
    }

    if (isset($this->request->post['date_added'])) {
      $data['date_added'] = $this->request->post['date_added'];
    } elseif (!empty($review_info)) {
      $data['date_added'] = ($review_info['date_added'] != '0000-00-00 00:00' ? $review_info['date_added'] : '');
    } else {
      $data['date_added'] = '';
    }

    if (isset($this->request->post['status'])) {
      $data['status'] = $this->request->post['status'];
    } elseif (!empty($review_info)) {
      $data['status'] = $review_info['status'];
    } else {
      $data['status'] = '';
    }
   
    $this->load->controller('account/vendor/lts_header/script');
    $data['footer'] = $this->load->controller('common/footer');
    $data['header'] = $this->load->controller('common/header');
    $data['lts_column_left'] = $this->load->controller('account/vendor/lts_column_left');

    $this->response->setOutput($this->load->view('account/vendor/lts_review_form', $data));
  }

  protected function validateForm() {
    if (!$this->request->post['product_id']) {
      $this->error['product'] = $this->language->get('error_product');
    }

    if ((utf8_strlen($this->request->post['author']) < 3) || (utf8_strlen($this->request->post['author']) > 64)) {
      $this->error['author'] = $this->language->get('error_author');
    }

    if (utf8_strlen($this->request->post['text']) < 1) {
      $this->error['text'] = $this->language->get('error_text');
    }

    if (!isset($this->request->post['rating']) || $this->request->post['rating'] < 0 || $this->request->post['rating'] > 5) {
      $this->error['rating'] = $this->language->get('error_rating');
    }

    if ($this->error && !isset($this->error['warning'])) {
      $this->error['warning'] = $this->language->get('error_warning');
    }

    return !$this->error;
  }

}
 