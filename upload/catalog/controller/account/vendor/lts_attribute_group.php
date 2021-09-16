<?php

class ControllerAccountVendorLtsAttributeGroup extends Controller {

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

    $this->load->language('account/vendor/lts_attribute_group');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('account/vendor/lts_attribute_group');

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

    $this->load->language('account/vendor/lts_attribute_group');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('account/vendor/lts_attribute_group');

    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      $this->model_account_vendor_lts_attribute_group->addAttributeGroup($vendor_info['vendor_id'], $this->request->post);

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

      $this->response->redirect($this->url->link('account/vendor/lts_attribute_group'));
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

    $this->load->language('account/vendor/lts_attribute_group');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('account/vendor/lts_attribute_group');

    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      $this->model_account_vendor_lts_attribute_group->editAttributeGroup($this->request->get['attribute_group_id'], $vendor_info['vendor_id'], $this->request->post);

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

      $this->response->redirect($this->url->link('account/vendor/lts_attribute_group'));
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

    $this->load->language('account/vendor/lts_attribute_group');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('account/vendor/lts_attribute_group');

    if (isset($this->request->post['selected']) && $this->validateDelete()) {
      foreach ($this->request->post['selected'] as $attribute_group_id) {
        $this->model_account_vendor_lts_attribute_group->deleteAttributeGroup($attribute_group_id, $vendor_info['vendor_id']);
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

      $this->response->redirect($this->url->link('account/vendor/lts_attribute_group'));
    }

    $this->getList();
  }

  protected function getList() {


    if (isset($this->request->get['sort'])) {
      $sort = $this->request->get['sort'];
    } else {
      $sort = 'agd.name';
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
        'href' => $this->url->link('vendor/lts_dashboard')
    );

    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('heading_title'),
        'href' => $this->url->link('account/vendor/lts_attribute_group')
    );

    $data['add'] = $this->url->link('account/vendor/lts_attribute_group/add');
    $data['delete'] = $this->url->link('account/vendor/lts_attribute_group/delete');

    $this->load->model('account/vendor/lts_vendor');

   $vendor_info = $this->model_account_vendor_lts_vendor->getVendorStoreInfo($this->customer->isLogged());

    $data['attribute_groups'] = array();

    $filter_data = array(
        'sort' => $sort,
        'order' => $order,
        'start' => ($page - 1) * $this->config->get('config_limit_admin'),
        'limit' => $this->config->get('config_limit_admin'),
        'vendor_id' => $vendor_info['vendor_id']
    );

    $attribute_group_total = $this->model_account_vendor_lts_attribute_group->getTotalAttributeGroups($filter_data);

    $results = $this->model_account_vendor_lts_attribute_group->getAttributeGroups($filter_data);

    foreach ($results as $result) {
      $data['attribute_groups'][] = array(
          'attribute_group_id' => $result['attribute_group_id'],
          'name' => $result['name'],
          'sort_order' => $result['sort_order'],
          'edit' => $this->url->link('account/vendor/lts_attribute_group/edit', '&attribute_group_id=' . $result['attribute_group_id'] . $url, true)
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

    $data['sort_name'] = $this->url->link('account/vendor/lts_attribute_group', '&sort=agd.name' . $url, true);
    $data['sort_sort_order'] = $this->url->link('account/vendor/lts_attribute_group', '&sort=ag.sort_order' . $url, true);

    $url = '';

    if (isset($this->request->get['sort'])) {
      $url .= '&sort=' . $this->request->get['sort'];
    }

    if (isset($this->request->get['order'])) {
      $url .= '&order=' . $this->request->get['order'];
    }

    $pagination = new Pagination();
    $pagination->total = $attribute_group_total;
    $pagination->page = $page;
    $pagination->limit = $this->config->get('config_limit_admin');
    $pagination->url = $this->url->link('account/vendor/lts_attribute_group', $url . '&page={page}', true);

    $data['pagination'] = $pagination->render();

    $data['results'] = sprintf($this->language->get('text_pagination'), ($attribute_group_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($attribute_group_total - $this->config->get('config_limit_admin'))) ? $attribute_group_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $attribute_group_total, ceil($attribute_group_total / $this->config->get('config_limit_admin')));

    $data['sort'] = $sort;
    $data['order'] = $order;

    $this->load->controller('account/vendor/lts_header/script');
    $data['footer'] = $this->load->controller('common/footer');
    $data['header'] = $this->load->controller('common/header');
    $data['lts_column_left'] = $this->load->controller('account/vendor/lts_column_left');

    $this->response->setOutput($this->load->view('account/vendor/lts_attribute_group_list', $data));
  }

  protected function getForm() {
    $data['text_form'] = !isset($this->request->get['attribute_group_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

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
        'href' => $this->url->link('vendor/lts_dashboard')
    );

    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('heading_title'),
        'href' => $this->url->link('account/vendor/lts_attribute_group')
    );

    if (!isset($this->request->get['attribute_group_id'])) {
      $data['action'] = $this->url->link('account/vendor/lts_attribute_group/add', $url);
    } else {
      $data['action'] = $this->url->link('account/vendor/lts_attribute_group/edit', '&attribute_group_id=' . $this->request->get['attribute_group_id'] . $url, true);
    }

    $data['cancel'] = $this->url->link('account/vendor/lts_attribute_group');

    if (isset($this->request->get['attribute_group_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      $attribute_group_info = $this->model_account_vendor_lts_attribute_group->getAttributeGroup($this->request->get['attribute_group_id']);
    }

    $this->load->model('localisation/language');

    $data['languages'] = $this->model_localisation_language->getLanguages();

    if (isset($this->request->post['attribute_group_description'])) {
      $data['attribute_group_description'] = $this->request->post['attribute_group_description'];
    } elseif (isset($this->request->get['attribute_group_id'])) {
      $data['attribute_group_description'] = $this->model_account_vendor_lts_attribute_group->getAttributeGroupDescriptions($this->request->get['attribute_group_id']);
    } else {
      $data['attribute_group_description'] = array();
    }

    if (isset($this->request->post['sort_order'])) {
      $data['sort_order'] = $this->request->post['sort_order'];
    } elseif (!empty($attribute_group_info)) {
      $data['sort_order'] = $attribute_group_info['sort_order'];
    } else {
      $data['sort_order'] = '';
    }

    $this->load->controller('account/vendor/lts_header/script');
    $data['footer'] = $this->load->controller('common/footer');
    $data['header'] = $this->load->controller('common/header');
    $data['lts_column_left'] = $this->load->controller('account/vendor/lts_column_left');

    $this->response->setOutput($this->load->view('account/vendor/lts_attribute_group_form', $data));
  }

  protected function validateForm() {

    foreach ($this->request->post['attribute_group_description'] as $language_id => $value) {
      if ((utf8_strlen($value['name']) < 1) || (utf8_strlen($value['name']) > 64)) {
        $this->error['name'][$language_id] = $this->language->get('error_name');
      }
    }

    return !$this->error;
  }

  protected function validateDelete() {

    $this->load->model('account/vendor/lts_attribute');

    foreach ($this->request->post['selected'] as $attribute_group_id) {
      $attribute_total = $this->model_vendor_lts_attribute->getTotalAttributesByAttributeGroupId($attribute_group_id);

      if ($attribute_total) {
        $this->error['warning'] = sprintf($this->language->get('error_attribute'), $attribute_total);
      }
    }

    return !$this->error;
  }

}
