<?php

class ControllerVendorLtsPincode extends Controller {

    private $error;

    public function index() {
        $this->load->language('vendor/lts_pincode');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('vendor/lts_pincode');

        $this->getList();
    }
 
    public function add() {

        $this->load->language('vendor/lts_pincode');

        $this->load->model('vendor/lts_pincode');

        $this->document->setTitle($this->language->get('heading_title'));

        if (($this->request->server['REQUEST_METHOD'] == 'POST' ) && $this->validateForm()) {

            $this->model_vendor_lts_pincode->addPincode($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('vendor/lts_pincode', 'user_token=' . $this->session->data['user_token'], true));
        }

        $this->getForm();
    }

    public function delete() {
        
        $this->load->language('vendor/lts_pincode');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('vendor/lts_pincode');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $pincode_id) {

                $this->model_vendor_lts_pincode->deletePincode($pincode_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('vendor/lts_pincode', 'user_token=' . $this->session->data['user_token'], true));
        } 

        $this->getList();
    }

    public function edit() {
        $this->load->language('vendor/lts_pincode');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('vendor/lts_pincode');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_vendor_lts_pincode->editPincode($this->request->get['pincode_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('vendor/lts_pincode', 'user_token=' . $this->session->data['user_token'], true));
        }

        $this->getForm();
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


        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('exetension/module/lts_vendor', 'user_token=' . $this->session->data['user_token'] . $url, true)
        );

        $data['add'] = $this->url->link('vendor/lts_pincode/add', 'user_token=' . $this->session->data['user_token'] . $url, true);

        $data['delete'] = $this->url->link('vendor/lts_pincode/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

        $data['pincodes'] = array();

        $filter_data = array(
            'sort'            => $sort,
            'order'           => $order,
            'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit'           => $this->config->get('config_limit_admin')
        );

        $pincode_total = $this->model_vendor_lts_pincode->getTotalPincodes($filter_data);

        $results = $this->model_vendor_lts_pincode->getPincodes($filter_data);

        $this->load->model('localisation/country');

        $this->load->model('localisation/zone');

        
        foreach ($results as $result) {

            $country =  $this->model_localisation_country->getCountry($result['country_id']);
            $zone =  $this->model_localisation_zone->getZone($result['zone_id']);


            $data['pincodes'][] = array(
                'pincode_id'    => $result['pincode_id'],
                'name'          => $result['name'],
                'country'       => $country['name'],
                'state'       => $zone['name'],
                'status'     => $result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
                'edit'       => $this->url->link('vendor/lts_pincode/edit', 'user_token=' . $this->session->data['user_token'] . '&pincode_id=' . $result['pincode_id'] . $url, true)
            );
        }

        $data['user_token'] = $this->session->data['user_token'];

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


        $url = '';

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_name'] = $this->url->link('vendor/lts_pincode', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url, true);
        $data['sort_country'] = $this->url->link('vendor/lts_pincode', 'user_token=' . $this->session->data['user_token'] . '&sort=country' . $url, true);
        $data['sort_status'] = $this->url->link('catalog/product', 'user_token=' . $this->session->data['user_token'] . '&sort=status' . $url, true);

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $pincode_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('vendor/lts_pincode', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($pincode_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($pincode_total - $this->config->get('config_limit_admin'))) ? $pincode_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $pincode_total, ceil($pincode_total / $this->config->get('config_limit_admin')));

        $data['sort'] = $sort;
        $data['order'] = $order;


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('vendor/lts_pincode_list', $data));
    }

    protected function getForm() {

        $data['text_form'] = !isset($this->request->get['pincode_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['country_id'])) {
            $data['error_country_id'] = $this->error['country_id'];
        } else {
            $data['error_zone'] = '';
        }

        if (isset($this->error['zone_id'])) {
            $data['error_zone_id'] = $this->error['zone_id'];
        } else {
            $data['error_zone_id'] = '';
        }

        $data['user_token'] = $this->session->data['user_token'];

        if (!isset($this->request->get['pincode_id'])) {
            $data['action'] = $this->url->link('vendor/lts_pincode/add', 'user_token=' . $this->session->data['user_token'], true);
        } else {
            $data['action'] = $this->url->link('vendor/lts_pincode/edit', 'user_token=' . $this->session->data['user_token'] . '&pincode_id=' . $this->request->get['pincode_id'], true);
        }

        $data['cancel'] = $this->url->link('vendor/lts_pincode', 'user_token=' . $this->session->data['user_token'], true);

        if (isset($this->request->get['pincode_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $pincode_info = $this->model_vendor_lts_pincode->getPincode($this->request->get['pincode_id']);
        }

        if (isset($this->request->post['name'])) {
          $data['name'] = $this->request->post['name'];
        } elseif (!empty($pincode_info)) {
          $data['name'] = $pincode_info['name'];
        } else {
          $data['name'] = '';
        }

        $this->load->model('localisation/country');

        $data['countries'] = $this->model_localisation_country->getCountries();

        if (isset($this->request->post['country_id'])) {
          $data['country_id'] = $this->request->post['country_id'];
        } elseif (!empty($pincode_info)) {
          $data['country_id'] = $pincode_info['country_id'];
        } else {
          $data['country_id'] = '';
        }

        if (isset($this->request->post['zone_id'])) {
          $data['zone_id'] = $this->request->post['zone_id'];
        } elseif (!empty($pincode_info)) {
          $data['zone_id'] = $pincode_info['zone_id'];
        } else {
          $data['zone_id'] = '';
        }

        if (isset($this->request->post['status'])) {
          $data['status'] = $this->request->post['status'];
        } elseif (!empty($pincode_info)) {
          $data['status'] = $pincode_info['status'];
        } else {
          $data['status'] = '';
        }

        $this->load->model('catalog/category');

        if (isset($this->request->post['pincode_checker'])) {
            $pincode_checkers = $this->request->post['pincode_checker'];
        } elseif (!empty($pincode_info)) {
            $pincode_checkers = $this->model_vendor_lts_pincode->getPincodeCheckers($this->request->get['pincode_id']);
        } else {
            $pincode_checkers = array();
        }

        $data['pincode_checkers'] = array();

        foreach ($pincode_checkers as $pincode_status_id) {
            $pincode_checker_info = $this->model_vendor_lts_pincode->getPincodeChecker($pincode_status_id);

            if ($pincode_checker_info) {
                $data['pincode_checkers'][] = array(
                    'pincode' => $pincode_checker_info['pincode']
                );
            }
        }






       

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('vendor/lts_pincode_form', $data));
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'vendor/lts_pincode')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

         if (!isset($this->request->post['name'])) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if ($this->request->post['country_id'] == '') {
          $this->error['country'] = $this->language->get('error_country');
        }

        if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '' || !is_numeric($this->request->post['zone_id'])) {
          $this->error['zone'] = $this->language->get('error_zone');
        }

       
        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

    public function validateDelete() {
        if (!$this->user->hasPermission('modify', 'vendor/lts_pincode')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
    
    public function autocomplete() {

        $json = array();

        if (isset($this->request->get['filter_name'])) {

            $this->load->model('vendor/lts_pincode');

            if (isset($this->request->get['filter_name'])) {
                $filter_name = $this->request->get['filter_name'];
            } else {
                $filter_name = '';
            }

            $limit = 5;

            $filter_data = array(
                'filter_name'  => $filter_name,
                'start'        => 0,
                'limit'        => $limit
            );

            $results = $this->model_vendor_lts_pincode->getPincodes($filter_data);

            foreach ($results as $result) {

                $json[] = array(
                    'pincode_id' => $result['pincode_id'],
                    'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
                );
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}