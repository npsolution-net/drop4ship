<?php

class ControllerAccountVendorLtsCoupon extends Controller {

  private $error = array();

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

    $this->load->language('account/vendor/lts_coupon');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('account/vendor/lts_coupon');

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

    $this->load->language('account/vendor/lts_coupon');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('account/vendor/lts_coupon');

    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      $this->model_account_vendor_lts_coupon->addCoupon($vendor_info['vendor_id'], $this->request->post);

      $this->session->data['success'] = $this->language->get('text_success');

      $url = '';

      if (isset($this->request->get['sort'])) {
        $url .= '&sort=' . $this->request->get['sort'];
      }

      if (isset($this->request->get['order'])) {
        $url .= '&order=' . $this->request->get['order'];
      }

      if (isset($this->request->get['page'])) {
        $url .= '&page=' . $this->request->get['page'];
      }

      $this->response->redirect($this->url->link('account/vendor/lts_coupon', $url, true));
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
    $this->load->language('account/vendor/lts_coupon');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('account/vendor/lts_coupon');

    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      $this->model_account_vendor_lts_coupon->editCoupon($this->request->get['coupon_id'], $vendor_info['vendor_id'], $this->request->post);

      $this->session->data['success'] = $this->language->get('text_success');

      $url = '';

      if (isset($this->request->get['sort'])) {
        $url .= '&sort=' . $this->request->get['sort'];
      }

      if (isset($this->request->get['order'])) {
        $url .= '&order=' . $this->request->get['order'];
      }

      if (isset($this->request->get['page'])) {
        $url .= '&page=' . $this->request->get['page'];
      }

      $this->response->redirect($this->url->link('account/vendor/lts_coupon', $url, true));
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

    $this->load->language('account/vendor/lts_coupon');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('account/vendor/lts_coupon');

    if (isset($this->request->post['selected'])) {
      foreach ($this->request->post['selected'] as $coupon_id) {
        $this->model_account_vendor_lts_coupon->deleteCoupon($coupon_id, $vendor_info['vendor_id']);
      }

      $this->session->data['success'] = $this->language->get('text_success');

      $url = '';

      if (isset($this->request->get['sort'])) {
        $url .= '&sort=' . $this->request->get['sort'];
      }

      if (isset($this->request->get['order'])) {
        $url .= '&order=' . $this->request->get['order'];
      }

      if (isset($this->request->get['page'])) {
        $url .= '&page=' . $this->request->get['page'];
      }

      $this->response->redirect($this->url->link('account/vendor/lts_coupon', $url, true));
    }

    $this->getList();
  }

  protected function getList() {

    if (isset($this->request->get['sort'])) {
      $sort = $this->request->get['sort'];
    } else {
      $sort = 'name';
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
        'href' => $this->url->link('account/vendor/lts_coupon')
    );

    $data['add'] = $this->url->link('account/vendor/lts_coupon/add');
    $data['delete'] = $this->url->link('account/vendor/lts_coupon/delete', $url, true);

    $this->load->model('account/vendor/lts_vendor');
     $vendor_info = $this->model_account_vendor_lts_vendor->getVendorStoreInfo($this->customer->isLogged());

    $data['coupons'] = array();

    $filter_data = array(
        'sort' => $sort,
        'order' => $order,
        'start' => ($page - 1) * $this->config->get('config_limit_admin'),
        'limit' => $this->config->get('config_limit_admin'),
        'vendor_id' => $vendor_info['vendor_id']
    );

    $coupon_total = $this->model_account_vendor_lts_coupon->getTotalCoupons($filter_data);

    $results = $this->model_account_vendor_lts_coupon->getCoupons($filter_data);

    foreach ($results as $result) {
      $data['coupons'][] = array(
          'coupon_id' => $result['coupon_id'],
          'name' => $result['name'],
          'code' => $result['code'],
          'discount' => $result['discount'],
          'date_start' => date($this->language->get('date_format_short'), strtotime($result['date_start'])),
          'date_end' => date($this->language->get('date_format_short'), strtotime($result['date_end'])),
          'status' => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
          'edit' => $this->url->link('account/vendor/lts_coupon/edit', '&coupon_id=' . $result['coupon_id'] . $url, true)
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

    if ($order == 'ASC') {
      $url .= '&order=DESC';
    } else {
      $url .= '&order=ASC';
    }

    if (isset($this->request->get['page'])) {
      $url .= '&page=' . $this->request->get['page'];
    }

    $data['sort_name'] = $this->url->link('account/vendor/lts_coupon', '&sort=name' . $url, true);
    $data['sort_code'] = $this->url->link('account/vendor/lts_coupon', '&sort=code' . $url, true);
    $data['sort_discount'] = $this->url->link('account/vendor/lts_coupon', '&sort=discount' . $url, true);
    $data['sort_date_start'] = $this->url->link('account/vendor/lts_coupon', '&sort=date_start' . $url, true);
    $data['sort_date_end'] = $this->url->link('account/vendor/lts_coupon', '&sort=date_end' . $url, true);
    $data['sort_status'] = $this->url->link('account/vendor/lts_coupon', '&sort=status' . $url, true);

    $url = '';

    if (isset($this->request->get['sort'])) {
      $url .= '&sort=' . $this->request->get['sort'];
    }

    if (isset($this->request->get['order'])) {
      $url .= '&order=' . $this->request->get['order'];
    }

    $pagination = new Pagination();
    $pagination->total = $coupon_total;
    $pagination->page = $page;
    $pagination->limit = $this->config->get('config_limit_admin');
    $pagination->url = $this->url->link('account/vendor/lts_coupon', $url . '&page={page}', true);

    $data['pagination'] = $pagination->render();

    $data['results'] = sprintf($this->language->get('text_pagination'), ($coupon_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($coupon_total - $this->config->get('config_limit_admin'))) ? $coupon_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $coupon_total, ceil($coupon_total / $this->config->get('config_limit_admin')));

    $data['sort'] = $sort;
    $data['order'] = $order;

   $this->load->controller('account/vendor/lts_header/script');
    $data['footer'] = $this->load->controller('common/footer');
    $data['header'] = $this->load->controller('common/header');
    $data['lts_column_left'] = $this->load->controller('account/vendor/lts_column_left');

    $this->response->setOutput($this->load->view('account/vendor/lts_coupon_list', $data));
  }

  protected function getForm() {
    $data['text_form'] = !isset($this->request->get['coupon_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

    $this->load->controller('account/vendor/lts_header/script');

    if (isset($this->request->get['coupon_id'])) {
      $data['coupon_id'] = $this->request->get['coupon_id'];
    } else {
      $data['coupon_id'] = 0;
    }

    if (isset($this->error['warning'])) {
      $data['error_warning'] = $this->error['warning'];
    } else {
      $data['error_warning'] = '';
    }

    if (isset($this->error['name'])) {
      $data['error_name'] = $this->error['name'];
    } else {
      $data['error_name'] = '';
    }

    if (isset($this->error['code'])) {
      $data['error_code'] = $this->error['code'];
    } else {
      $data['error_code'] = '';
    }

    if (isset($this->error['date_start'])) {
      $data['error_date_start'] = $this->error['date_start'];
    } else {
      $data['error_date_start'] = '';
    }

    if (isset($this->error['date_end'])) {
      $data['error_date_end'] = $this->error['date_end'];
    } else {
      $data['error_date_end'] = '';
    }

    $url = '';

    if (isset($this->request->get['page'])) {
      $url .= '&page=' . $this->request->get['page'];
    }

    if (isset($this->request->get['sort'])) {
      $url .= '&sort=' . $this->request->get['sort'];
    }

    if (isset($this->request->get['order'])) {
      $url .= '&order=' . $this->request->get['order'];
    }

    $data['breadcrumbs'] = array();

    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('text_home'),
        'href' => $this->url->link('account/vendor/lts_dashboard')
    );

    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('heading_title'),
        'href' => $this->url->link('account/vendor/lts_coupon', $url, true)
    );

    if (!isset($this->request->get['coupon_id'])) {
      $data['action'] = $this->url->link('account/vendor/lts_coupon/add', $url, true);
    } else {
      $data['action'] = $this->url->link('account/vendor/lts_coupon/edit', '&coupon_id=' . $this->request->get['coupon_id'] . $url, true);
    }

    $data['cancel'] = $this->url->link('account/vendor/lts_coupon', $url, true);

    if (isset($this->request->get['coupon_id']) && (!$this->request->server['REQUEST_METHOD'] != 'POST')) {
      $coupon_info = $this->model_account_vendor_lts_coupon->getCoupon($this->request->get['coupon_id']);
    }

    if (isset($this->request->post['name'])) {
      $data['name'] = $this->request->post['name'];
    } elseif (!empty($coupon_info)) {
      $data['name'] = $coupon_info['name'];
    } else {
      $data['name'] = '';
    }

    if (isset($this->request->post['code'])) {
      $data['code'] = $this->request->post['code'];
    } elseif (!empty($coupon_info)) {
      $data['code'] = $coupon_info['code'];
    } else {
      $data['code'] = '';
    }

    if (isset($this->request->post['type'])) {
      $data['type'] = $this->request->post['type'];
    } elseif (!empty($coupon_info)) {
      $data['type'] = $coupon_info['type'];
    } else {
      $data['type'] = '';
    }

    if (isset($this->request->post['discount'])) {
      $data['discount'] = $this->request->post['discount'];
    } elseif (!empty($coupon_info)) {
      $data['discount'] = $coupon_info['discount'];
    } else {
      $data['discount'] = '';
    }

    if (isset($this->request->post['logged'])) {
      $data['logged'] = $this->request->post['logged'];
    } elseif (!empty($coupon_info)) {
      $data['logged'] = $coupon_info['logged'];
    } else {
      $data['logged'] = '';
    }

    if (isset($this->request->post['shipping'])) {
      $data['shipping'] = $this->request->post['shipping'];
    } elseif (!empty($coupon_info)) {
      $data['shipping'] = $coupon_info['shipping'];
    } else {
      $data['shipping'] = '';
    }

    if (isset($this->request->post['total'])) {
      $data['total'] = $this->request->post['total'];
    } elseif (!empty($coupon_info)) {
      $data['total'] = $coupon_info['total'];
    } else {
      $data['total'] = '';
    }

    if (isset($this->request->post['coupon_product'])) {
      $products = $this->request->post['coupon_product'];
    } elseif (isset($this->request->get['coupon_id'])) {
      $products = $this->model_account_vendor_lts_coupon->getCouponProducts($this->request->get['coupon_id']);
    } else {
      $products = array();
    }

    $this->load->model('account/vendor/lts_product');

    $data['coupon_product'] = array();

    foreach ($products as $product_id) {
      $product_info = $this->model_account_vendor_lts_product->getProduct($product_id);

      if ($product_info) {
        $data['coupon_product'][] = array(
            'product_id' => $product_info['product_id'],
            'name' => $product_info['name']
        );
      }
    }

    if (isset($this->request->post['coupon_category'])) {
      $categories = $this->request->post['coupon_category'];
    } elseif (isset($this->request->get['coupon_id'])) {
      $categories = $this->model_account_vendor_lts_coupon->getCouponCategories($this->request->get['coupon_id']);
    } else {
      $categories = array();
    }

    $this->load->model('account/vendor/lts_category');

    $data['coupon_category'] = array();

    foreach ($categories as $category_id) {
      $category_info = $this->model_account_vendor_lts_category->getCategory($category_id);

      if ($category_info) {
        $data['coupon_category'][] = array(
            'category_id' => $category_info['category_id'],
            'name' => ($category_info['path'] ? $category_info['path'] . ' &gt; ' : '') . $category_info['name']
        );
      }
    }

    if (isset($this->request->post['date_start'])) {
      $data['date_start'] = $this->request->post['date_start'];
    } elseif (!empty($coupon_info)) {
      $data['date_start'] = ($coupon_info['date_start'] != '0000-00-00' ? $coupon_info['date_start'] : '');
    } else {
      $data['date_start'] = date('Y-m-d', time());
    }

    if (isset($this->request->post['date_end'])) {
      $data['date_end'] = $this->request->post['date_end'];
    } elseif (!empty($coupon_info)) {
      $data['date_end'] = ($coupon_info['date_end'] != '0000-00-00' ? $coupon_info['date_end'] : '');
    } else {
      $data['date_end'] = date('Y-m-d', strtotime('+1 month'));
    }

    if (isset($this->request->post['uses_total'])) {
      $data['uses_total'] = $this->request->post['uses_total'];
    } elseif (!empty($coupon_info)) {
      $data['uses_total'] = $coupon_info['uses_total'];
    } else {
      $data['uses_total'] = 1;
    }

    if (isset($this->request->post['uses_customer'])) {
      $data['uses_customer'] = $this->request->post['uses_customer'];
    } elseif (!empty($coupon_info)) {
      $data['uses_customer'] = $coupon_info['uses_customer'];
    } else {
      $data['uses_customer'] = 1;
    }

    if (isset($this->request->post['status'])) {
      $data['status'] = $this->request->post['status'];
    } elseif (!empty($coupon_info)) {
      $data['status'] = $coupon_info['status'];
    } else {
      $data['status'] = true;
    }

   $this->load->controller('account/vendor/lts_header/script');
    $data['footer'] = $this->load->controller('common/footer');
    $data['header'] = $this->load->controller('common/header');
    $data['lts_column_left'] = $this->load->controller('account/vendor/lts_column_left');

    $this->response->setOutput($this->load->view('account/vendor/lts_coupon_form', $data));
  }

  protected function validateForm() {
    if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 128)) {
      $this->error['name'] = $this->language->get('error_name');
    }

    if ((utf8_strlen($this->request->post['code']) < 3) || (utf8_strlen($this->request->post['code']) > 10)) {
      $this->error['code'] = $this->language->get('error_code');
    }

    $coupon_info = $this->model_account_vendor_lts_coupon->getCouponByCode($this->request->post['code']);

    if ($coupon_info) {
      if (!isset($this->request->get['coupon_id'])) {
        $this->error['warning'] = $this->language->get('error_exists');
      } elseif ($coupon_info['coupon_id'] != $this->request->get['coupon_id']) {
        $this->error['warning'] = $this->language->get('error_exists');
      }
    }

    return !$this->error;
  }

  public function history() {

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

    $this->load->model('account/vendor/lts_coupon');

    if (isset($this->request->get['page'])) {
      $page = $this->request->get['page'];
    } else {
      $page = 1;
    }

    $data['histories'] = array();

    $results = $this->model_account_vendor_lts_coupon->getCouponHistories($this->request->get['coupon_id'], ($page - 1) * 10, 10);

    foreach ($results as $result) {
      $data['histories'][] = array(
          'order_id' => $result['order_id'],
          'customer' => $result['customer'],
          'amount' => $result['amount'],
          'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
      );
    }

    $history_total = $this->model_account_vendor_lts_coupon->getTotalCouponHistories($this->request->get['coupon_id']);

    $pagination = new Pagination();
    $pagination->total = $history_total;
    $pagination->page = $page;
    $pagination->limit = 10;
    $pagination->url = $this->url->link('account/vendor/lts_coupon/history', '&coupon_id=' . $this->request->get['coupon_id'] . '&page={page}', true);

    $data['pagination'] = $pagination->render();

    $data['results'] = sprintf('Showing %d to %d of %d (%d Pages)', ($history_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($history_total - 10)) ? $history_total : ((($page - 1) * 10) + 10), $history_total, ceil($history_total / 10));

    $this->response->setOutput($this->load->view('account/vendor/lts_coupon_history', $data));
  }
}