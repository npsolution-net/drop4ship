<?php

class ControllerVendorLtsAssignedCategory extends Controller {

    private $error = array();

    public function index() {
        $this->load->language('vendor/lts_assigned_category');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('vendor/lts_category');

        $this->getList();
    }

    public function add() {

        $this->load->language('vendor/lts_assigned_category');
 
        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('vendor/lts_category');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

            $this->model_vendor_lts_category->addAssignedCategory($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('vendor/lts_assigned_category', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }
        $this->getForm();
    } 

    public function edit() {
        $this->load->language('vendor/lts_assigned_category');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('vendor/lts_category');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_vendor_lts_category->editAssignedCategory($this->request->get['vendor_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('vendor/lts_assigned_category', 'user_token=' . $this->session->data['user_token'], true));
        }

        $this->getForm();
    }

    public function delete() {
        $this->load->language('vendor/lts_category');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('vendor/lts_category');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {

            foreach ($this->request->post['selected'] as $vendor_id) {
                $this->model_vendor_lts_category->deleteAssignedCategory($vendor_id);
            }

            $this->response->redirect($this->url->link('vendor/lts_assigned_category', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->getList();
    }

    public function getForm() {
        if (isset($this->error['vendor'])) {
            $data['error_vendor'] = $this->error['vendor'];
        } else {
            $data['error_vendor'] = '';
        }

        if (isset($this->error['vendor-category'])) {
            $data['error_vendor_category'] = $this->error['vendor-category'];
        } else {
            $data['error_vendor_category'] = '';
        }

        if (isset($this->error['warning'])) {
            $data['warning'] = $this->error['warning'];
        } else {
            $data['warning'] = '';
        }

        $this->load->model('vendor/lts_vendor');

        $data['user_token'] = $this->session->data['user_token'];

        if (!isset($this->request->get['vendor_id'])) {
            $data['action'] = $this->url->link('vendor/lts_assigned_category/add', 'user_token=' . $this->session->data['user_token'], true);
        } else {
            $data['action'] = $this->url->link('vendor/lts_assigned_category/edit', 'user_token=' . $this->session->data['user_token'] . '&vendor_id=' . $this->request->get['vendor_id'], true);
        }

        $data['cancel'] = $this->url->link('vendor/lts_assigned_category', 'user_token=' . $this->session->data['user_token'], true);

        if (isset($this->request->get['vendor_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $category_info = $this->model_vendor_lts_category->getAssignedCategory($this->request->get['vendor_id']);
        }

        if (isset($this->request->post['vendor_id'])) {
            $data['vendor_id'] = $this->request->post['vendor_id'];
        } elseif (!empty($category_info)) {
            $data['vendor_id'] = $category_info['vendor_id'];
        } else {
            $data['vendor_id'] = '';
        }


        if (isset($this->request->post['vendor'])) {
            $data['vendor'] = $this->request->post['vendor'];
        } elseif (!empty($category_info)) {
            $store_owner = $this->model_vendor_lts_vendor->getStoreOwner($data['vendor_id']);
            $data['vendor'] = $store_owner['store_owner'];
        } else {
            $data['vendor'] = '';
        }


        // print_r($data['vendor']);

        // die;

        if (isset($this->request->post['vendor_category'])) {
            $categories = $this->request->post['vendor_category'];
        } elseif (!empty($category_info)) {
            $categories = explode(',', $category_info['category_id']);
        } else {
            $categories = array();
        }

        $this->load->model('catalog/category');

        $data['vendor_categories'] = array();

        foreach ($categories as $category_id) {
            $category_info = $this->model_catalog_category->getCategory($category_id);
            if ($category_info) {
                $data['vendor_category'][] = array(
                    'category_id' => $category_info['category_id'],
                    'name' => $category_info['name']
                );
            }
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('vendor/lts_assigned_category_form', $data));
    }

    protected function getList() {

        $data['add'] = $this->url->link('vendor/lts_assigned_category/add', 'user_token=' . $this->session->data['user_token'], true);

        $data['delete'] = $this->url->link('vendor/lts_assigned_category/delete', 'user_token=' . $this->session->data['user_token'], true);

        $category_info = $this->model_vendor_lts_category->getAssignedCategories();

        $this->load->model('vendor/lts_vendor');

        $this->load->model('catalog/category');

         $categories=array();

        foreach ($category_info as $category) {
          
            $vendor_category = explode(',', $category['category_id']);
            $categories[]=array( 
                                'vendor_id'=>$category['vendor_id'],
                                'category'=>$vendor_category
            );
            foreach ($categories as $category_id) {
                $category_name = $this->model_catalog_category->getCategory($category_id);

                $categories_name[$category['vendor_id']][] = $category_name;
            }
        }
            
            if($categories){
            foreach($categories as $category){
                $store_data=$this->model_vendor_lts_vendor->getStoreOwner($category['vendor_id']);
                if($category['category']){
                    foreach($category['category'] as $cat){
                        $category_names[]= $this->model_catalog_category->getCategory($cat);
                    }
                }
                 $data['categories'][] = array(
                'vendor_id' => $category['vendor_id'],
                'store_owner' =>$store_data['store_owner'],
                'categories_name' => $category_names,
                'edit' => $this->url->link('vendor/lts_assigned_category/edit', 'user_token=' . $this->session->data['user_token'] . '&vendor_id=' . $category['vendor_id'], true)
             );
               unset($category_names)  ;
            }
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

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array)$this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('vendor/lts_assigned_category_list', $data));
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'vendor/lts_assigned_category')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function validateForm() {

        if (!$this->user->hasPermission('modify', 'vendor/lts_assigned_category')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['vendor_id']) {
            $this->error['vendor'] = $this->language->get('error_vendor');
        }

        // if (!isset($this->request->get['vendor_id'])) {
        //     if ($this->model_vendor_lts_category->getTotalVendorAssignById($this->request->post['vendor_id'])) {
        //         $this->error['warning'] = $this->language->get('error_vendor_exists');
        //     }
        // }

        if (!isset($this->request->post['vendor_category'])) {
            $this->error['vendor-category'] = $this->language->get('error_vendor_category');
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

    public function autocomplete() {
        $json = array();

        if (isset($this->request->get['filter_name'])) {
            $this->load->model('vendor/lts_category');
            $this->load->model('catalog/category');

            $filter_data = array(
                'filter_name' => $this->request->get['filter_name'],
                'sort' => 'name',
                'order' => 'ASC',
                'start' => 0,
                'limit' => 5
            );

            $results = $this->model_catalog_category->getCategories($filter_data);

            foreach ($results as $result) {
                $vendor_id = $this->model_vendor_lts_category->getVendorCategory($result['category_id']);
                if (!empty($vendor_id)) {
                    // if($vendor_id['vendor_id'] = $)
                } else {
                    $json[] = array(
                        'category_id' => $result['category_id'],
                        'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
                    );
                }
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
