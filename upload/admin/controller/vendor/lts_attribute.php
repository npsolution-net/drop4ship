<?php

class ControllerVendorLtsAttribute extends Controller {

    private $error;

    public function index() {
        $this->load->language('vendor/lts_attribute');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('vendor/lts_attribute');

        $this->getList();
    }
 
    public function add() {

        $this->load->language('vendor/lts_attribute');

        $this->load->model('vendor/lts_attribute');

        $this->document->setTitle($this->language->get('heading_title'));

        if (($this->request->server['REQUEST_METHOD'] == 'POST' ) && $this->validateForm()) {

            $this->model_vendor_lts_attribute->addAttributeMapping($this->request->post);

            $this->response->redirect($this->url->link('vendor/lts_attribute', 'user_token=' . $this->session->data['user_token'], true));
        }

        $this->getForm();
    }

    public function delete() {
        
        $this->load->language('vendor/lts_attribute');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('vendor/lts_attribute');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $attribute_mapping_id) {

                $this->model_vendor_lts_attribute->deleteAttributeMapping($attribute_mapping_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('vendor/lts_attribute', 'user_token=' . $this->session->data['user_token'], true));
        } 

        $this->getList();
    }

    public function edit() {
        $this->load->language('vendor/lts_attribute');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('vendor/lts_attribute');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_vendor_lts_attribute->editAttributeMapping($this->request->get['attribute_mapping_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('vendor/lts_attribute', 'user_token=' . $this->session->data['user_token'], true));
        }

        $this->getForm();
    }

    protected function getList() {
        $data['add'] = $this->url->link('vendor/lts_attribute/add', 'user_token=' . $this->session->data['user_token'], true);

        $this->load->model('vendor/lts_attribute');

        $this->load->model('catalog/attribute');

        $this->load->model('catalog/category');

        $data['user_token'] = $this->session->data['user_token'];

        $attribute_info = $this->model_vendor_lts_attribute->getAttributeMapping();

        foreach ($attribute_info as $attribute) {
            $e_attributes = explode(',', $attribute['attribute']);

            $att_names = array();
            
            $category_name = $this->model_catalog_category->getCategory($attribute['category_id']);

            foreach ($e_attributes as $attribute_id) {
                $att_name = $this->model_catalog_attribute->getAttribute($attribute_id);

                $att_names[] = $att_name;
            }

            $data['attributes'][] = array(
                'attribute_mapping_id'  => $attribute['attribute_mapping_id'] ,
                'name'                  => $category_name['name'],
                'att_names'             => $att_names,
                'edit'                  => $this->url->link('vendor/lts_attribute/edit', 'user_token=' . $this->session->data['user_token'] . '&attribute_mapping_id=' . $attribute['attribute_mapping_id'], true)
            );
        }

         $data['delete'] = $this->url->link('vendor/lts_attribute/delete', 'user_token=' . $this->session->data['user_token'], true);

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

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('vendor/lts_attribute_list', $data));
    }

    protected function getForm() {

        $data['text_form'] = !isset($this->request->get['attribute_mapping_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['category'])) {
            $data['error_category'] = $this->error['category'];
        } else {
            $data['error_category'] = '';
        }

        if (isset($this->error['attributes'])) {
            $data['error_attributes'] = $this->error['attributes'];
        } else {
            $data['error_attributes'] = '';
        }

        $data['user_token'] = $this->session->data['user_token'];

        if (!isset($this->request->get['attribute_mapping_id'])) {
            $data['action'] = $this->url->link('vendor/lts_attribute/add', 'user_token=' . $this->session->data['user_token'], true);
        } else {
            $data['action'] = $this->url->link('vendor/lts_attribute/edit', 'user_token=' . $this->session->data['user_token'] . '&attribute_mapping_id=' . $this->request->get['attribute_mapping_id'], true);
        }

        $data['cancel'] = $this->url->link('vendor/lts_attribute', 'user_token=' . $this->session->data['user_token'], true);

        if (isset($this->request->get['attribute_mapping_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $attribute_mapping_info = $this->model_vendor_lts_attribute->getAttributeMappingById($this->request->get['attribute_mapping_id']);
        }

        $this->load->model('catalog/category');

        if (isset($this->request->post['category_id'])) {
            $data['category_id'] = $this->request->post['category_id'];
        } elseif (!empty($attribute_mapping_info)) {
            $data['category_id'] = $attribute_mapping_info['category_id'];
        } else {
            $data['category_id'] = array();
        }

        if (isset($this->request->post['category'])) {
            $data['category'] = $this->request->post['category'];
        } elseif (!empty($attribute_mapping_info)) {
            $category = $this->model_catalog_category->getCategory($data['category_id']);   
            $data['category'] = $category['name'];
        } else {
            $data['category'] = '';
        }

        if (isset($this->request->post['attributes'])) {
            $attributes = $this->request->post['attributes'];
        } elseif (!empty($attribute_mapping_info)) {
            $attributes = explode(',', $attribute_mapping_info['attribute']);
        } else {
            $attributes = array();
        }

        $this->load->model('catalog/attribute');

        $data['attributes'] = array();

        foreach ($attributes as $attribute_id) {
            $att_name = $this->model_catalog_attribute->getAttribute($attribute_id);
            if ($att_name) {
                $data['attributes'][] = array(
                    'attribute_id' => $att_name['attribute_id'],
                    'name' => $att_name['name']
                );
            }
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('vendor/lts_attribute_form', $data));
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'vendor/lts_attribute')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['category_id']) {
            $this->error['category'] = $this->language->get('error_category');
        }

        if (!isset($this->request->post['attributes'])) {
            $this->error['attributes'] = $this->language->get('error_attributes');
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

    public function validateDelete() {
        if (!$this->user->hasPermission('modify', 'vendor/lts_attribute')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    public function attributeMapping() {
        $json = array();

        if (isset($this->request->get['category_id'])) {
            $category_id = $this->request->get['category_id'];
        } else {
            $category_id = 0;
        }

        $this->load->model("vendor/lts_attribute");

        $attr_info = $this->model_vendor_lts_attribute->getAttibuteMappingByCategoryId($this->request->get['category_id']);

        if (!empty($attr_info)) {
            $attributes = explode(',', $attr_info['attribute']);

            $this->load->model('catalog/attribute');

            foreach ($attributes as $attribute_id) {
                $att_name = $this->model_catalog_attribute->getAttribute($attribute_id);
                if ($att_name) {
                    $json[] = array(
                        'attribute_id' => $att_name['attribute_id'],
                        'name' => $att_name['name']
                    );
                }
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}