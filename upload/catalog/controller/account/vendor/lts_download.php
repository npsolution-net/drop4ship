<?php

class ControllerAccountVendorLtsDownload extends Controller {

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

    $this->load->language('account/vendor/lts_download');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('account/vendor/lts_download');

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
  
    $this->load->language('account/vendor/lts_download');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('account/vendor/lts_download');

    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      $this->model_account_vendor_lts_download->addDownload($vendor_info['vendor_id'], $this->request->post);

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

      $this->response->redirect($this->url->link('account/vendor/lts_download'));
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

    $this->load->language('account/vendor/lts_download');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('account/vendor/lts_download');

    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      $this->model_account_vendor_lts_download->editDownload($this->request->get['download_id'], $vendor_info['vendor_id'], $this->request->post);

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
      $this->load->controller('vendor/lts_header/script');
      $this->response->redirect($this->url->link('account/vendor/lts_download'));
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

    $this->load->language('account/vendor/lts_download');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('account/vendor/lts_download');

    if (isset($this->request->post['selected']) && $this->validateDelete()) {
      foreach ($this->request->post['selected'] as $download_id) {
        $this->model_account_vendor_lts_download->deleteDownload($download_id, $vendor_info['vendor_id']);
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

      $this->response->redirect($this->url->link('account/vendor/lts_download', $url, true));
    }

    $this->getList();
  }

  protected function getList() {
    
    if (isset($this->request->get['sort'])) {
      $sort = $this->request->get['sort'];
    } else {
      $sort = 'dd.name';
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
        'href' => $this->url->link('account/vendor/lts_download')
    );

    $data['add'] = $this->url->link('account/vendor/lts_download/add');
    $data['delete'] = $this->url->link('account/vendor/lts_download/delete');


    $this->load->model('account/vendor/lts_vendor');

    $vendor_info = $this->model_account_vendor_lts_vendor->getVendorStoreInfo($this->customer->isLogged());

    $data['downloads'] = array();

    $filter_data = array(
        'sort' => $sort,
        'order' => $order,
        'start' => ($page - 1) * $this->config->get('config_limit_admin'),
        'limit' => $this->config->get('config_limit_admin'),
        'vendor_id' => $vendor_info['vendor_id']
    );

    $download_total = $this->model_account_vendor_lts_download->getTotalDownloads($filter_data);

    $results = $this->model_account_vendor_lts_download->getDownloads($filter_data);

    foreach ($results as $result) {
      $data['downloads'][] = array(
          'download_id' => $result['download_id'],
          'name' => $result['name'],
          'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
          'edit' => $this->url->link('account/vendor/lts_download/edit', '&download_id=' . $result['download_id'] . $url, true)
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

    $data['sort_name'] = $this->url->link('account/vendor/lts_download', '&sort=dd.name' . $url, true);
    $data['sort_date_added'] = $this->url->link('account/vendor/lts_download', '&sort=d.date_added' . $url, true);

    $url = '';

    if (isset($this->request->get['sort'])) {
      $url .= '&sort=' . $this->request->get['sort'];
    }

    if (isset($this->request->get['order'])) {
      $url .= '&order=' . $this->request->get['order'];
    }

    $pagination = new Pagination();
    $pagination->total = $download_total;
    $pagination->page = $page;
    $pagination->limit = $this->config->get('config_limit_admin');
    $pagination->url = $this->url->link('account/vendor/lts_download', $url . '&page={page}', true);

    $data['pagination'] = $pagination->render();

    $data['results'] = sprintf($this->language->get('text_pagination'), ($download_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($download_total - $this->config->get('config_limit_admin'))) ? $download_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $download_total, ceil($download_total / $this->config->get('config_limit_admin')));

    $data['sort'] = $sort;
    $data['order'] = $order;

    $this->load->controller('account/vendor/lts_header/script');
    $data['footer'] = $this->load->controller('common/footer');
    $data['header'] = $this->load->controller('common/header');
    $data['lts_column_left'] = $this->load->controller('account/vendor/lts_column_left');

    $this->response->setOutput($this->load->view('account/vendor/lts_download_list', $data));
  }

  protected function getForm() {
     
    $data['text_form'] = !isset($this->request->get['download_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

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

    if (isset($this->error['filename'])) {
      $data['error_filename'] = $this->error['filename'];
    } else {
      $data['error_filename'] = '';
    }

    if (isset($this->error['mask'])) {
      $data['error_mask'] = $this->error['mask'];
    } else {
      $data['error_mask'] = '';
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
        'href' => $this->url->link('vendor/lts_dashboard', true)
    );

    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('heading_title'),
        'href' => $this->url->link('account/vendor/lts_download', $url, true)
    );

    if (!isset($this->request->get['download_id'])) {
      $data['action'] = $this->url->link('account/vendor/lts_download/add', $url, true);
    } else {
      $data['action'] = $this->url->link('account/vendor/lts_download/edit', '&download_id=' . $this->request->get['download_id'] . $url, true);
    }

    $data['cancel'] = $this->url->link('account/vendor/lts_download', $url, true);

    $this->load->model('localisation/language');

    $data['languages'] = $this->model_localisation_language->getLanguages();

    if (isset($this->request->get['download_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      $download_info = $this->model_account_vendor_lts_download->getDownload($this->request->get['download_id']);
    }

    if (isset($this->request->get['download_id'])) {
      $data['download_id'] = $this->request->get['download_id'];
    } else {
      $data['download_id'] = 0;
    }

    if (isset($this->request->post['download_description'])) {
      $data['download_description'] = $this->request->post['download_description'];
    } elseif (isset($this->request->get['download_id'])) {
      $data['download_description'] = $this->model_account_vendor_lts_download->getDownloadDescriptions($this->request->get['download_id']);
    } else {
      $data['download_description'] = array();
    }

    if (isset($this->request->post['filename'])) {
      $data['filename'] = $this->request->post['filename'];
    } elseif (!empty($download_info)) {
      $data['filename'] = $download_info['filename'];
    } else {
      $data['filename'] = '';
    }

    if (isset($this->request->post['mask'])) {
      $data['mask'] = $this->request->post['mask'];
    } elseif (!empty($download_info)) {
      $data['mask'] = $download_info['mask'];
    } else {
      $data['mask'] = '';
    }

    $this->load->controller('account/vendor/lts_header/script');
    $data['header'] = $this->load->controller('common/header');
    $data['lts_column_left'] = $this->load->controller('account/vendor/lts_column_left');
    $data['footer'] = $this->load->controller('common/footer');

    $this->response->setOutput($this->load->view('account/vendor/lts_download_form', $data));
  }

  protected function validateForm() {

    foreach ($this->request->post['download_description'] as $language_id => $value) {
      if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 64)) {
        $this->error['name'][$language_id] = $this->language->get('error_name');
      }
    }

    if ((utf8_strlen($this->request->post['filename']) < 3) || (utf8_strlen($this->request->post['filename']) > 128)) {
      $this->error['filename'] = $this->language->get('error_filename');
    }

    if (!is_file(DIR_DOWNLOAD . $this->request->post['filename'])) {
      $this->error['filename'] = $this->language->get('error_exists');
    }

    if ((utf8_strlen($this->request->post['mask']) < 3) || (utf8_strlen($this->request->post['mask']) > 128)) {
      $this->error['mask'] = $this->language->get('error_mask');
    }

    return !$this->error;
  }

  protected function validateDelete() {
    $this->load->model('account/vendor/lts_product');

    foreach ($this->request->post['selected'] as $download_id) {
      $product_total = $this->model_account_vendor_lts_product->getTotalProductsByDownloadId($download_id);

      if ($product_total) {
        $this->error['warning'] = sprintf($this->language->get('error_product'), $product_total);
      }
    }

    return !$this->error;
  }

  public function upload() {
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

    $this->load->language('account/vendor/lts_download');

    $json = array();

    if (!$json) {
      if (!empty($this->request->files['file']['name']) && is_file($this->request->files['file']['tmp_name'])) {
        // Sanitize the filename
        $filename = basename(html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8'));

        // Validate the filename length
        if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 128)) {
          $json['error'] = $this->language->get('error_filename');
        }

        // Allowed file extension types
        $allowed = array();

        $extension_allowed = preg_replace('~\r?\n~', "\n", $this->config->get('config_file_ext_allowed'));

        $filetypes = explode("\n", $extension_allowed);

        foreach ($filetypes as $filetype) {
          $allowed[] = trim($filetype);
        }

        if (!in_array(strtolower(substr(strrchr($filename, '.'), 1)), $allowed)) {
          $json['error'] = $this->language->get('error_filetype');
        }

        // Allowed file mime types
        $allowed = array();

        $mime_allowed = preg_replace('~\r?\n~', "\n", $this->config->get('config_file_mime_allowed'));

        $filetypes = explode("\n", $mime_allowed);

        foreach ($filetypes as $filetype) {
          $allowed[] = trim($filetype);
        }

        if (!in_array($this->request->files['file']['type'], $allowed)) {
          $json['error'] = $this->language->get('error_filetype');
        }

        // Check to see if any PHP files are trying to be uploaded
        $content = file_get_contents($this->request->files['file']['tmp_name']);

        if (preg_match('/\<\?php/i', $content)) {
          $json['error'] = $this->language->get('error_filetype');
        }

        // Return any upload error
        if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
          $json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
        }
      } else {
        $json['error'] = $this->language->get('error_upload');
      }
    }

    if (!$json) {
      $file = $filename . '.' . token(32);

      move_uploaded_file($this->request->files['file']['tmp_name'], DIR_DOWNLOAD . $file);

      $json['filename'] = $file;
      $json['mask'] = $filename;

      $json['success'] = $this->language->get('text_upload');
    }

    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
  }

  public function autocomplete() {
    $this->load->controller('account/vendor/lts_header/script');

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
    
    $json = array();

    if (isset($this->request->get['filter_name'])) {
      $this->load->model('account/vendor/lts_download');

      $filter_data = array(
          'filter_name' => $this->request->get['filter_name'],
          'start' => 0,
          'limit' => 5
      );

      $results = $this->model_account_vendor_lts_download->getDownloads($filter_data);

      foreach ($results as $result) {
        $json[] = array(
            'download_id' => $result['download_id'],
            'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
        );
      }
    }

    $sort_order = array();

    foreach ($json as $key => $value) {
      $sort_order[$key] = $value['name'];
    }

    array_multisort($sort_order, SORT_ASC, $json);

    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
  }
}
