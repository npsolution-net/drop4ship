<?php

class ControllerVendorLtsVendorCategory extends Controller {

    public function index() {
        $this->load->language('vendor/lts_vendor_category');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('vendor/lts_category');


        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('vendor/lts_vendor_category', 'user_token=' . $this->session->data['user_token'], true)
        );

        $category_info = $this->model_vendor_lts_category->getVendorCategories();
       
        $this->load->model('vendor/lts_vendor');

        if (!empty($category_info)) {
            foreach ($category_info as $category) {
                $data['categories'][] = array(
                    'category_id' => $category['category_id'],
                    'store_owner' => $category['store_owner'],
                    'name' => $category['name'],
                    'sort_order' => $category['sort_order'],
                    'status' => $category['approved'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
                    'edit' => $this->url->link('catalog/category/edit', 'user_token=' . $this->session->data['user_token'] . '&category_id=' . $category['category_id'], true),
                    'approve' => $this->url->link('vendor/lts_vendor_category/approve', 'user_token=' . $this->session->data['user_token'] . '&category_id=' . $category['category_id'], true),
                    'disapprove' => $this->url->link('vendor/lts_vendor_category/disapprove', 'user_token=' . $this->session->data['user_token'] . '&category_id=' . $category['category_id'], true),
                );
            }
        }

        $data['add'] = $this->url->link('catalog/category/add', 'user_token=' . $this->session->data['user_token'], true);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');


        $this->response->setOutput($this->load->view('vendor/lts_vendor_category', $data));
    }

    public function approve() {

        $this->load->model('vendor/lts_category');

        $this->model_vendor_lts_category->approveStatus($this->request->get['category_id']);

        $this->response->redirect($this->url->link('vendor/lts_vendor_category', 'user_token=' . $this->session->data['user_token'], true));
    }

    public function disapprove() {

        $this->load->model('vendor/lts_category');

        $this->model_vendor_lts_category->disapproveStatus($this->request->get['category_id']);

        $this->response->redirect($this->url->link('vendor/lts_vendor_category', 'user_token=' . $this->session->data['user_token'], true));
    }

}
