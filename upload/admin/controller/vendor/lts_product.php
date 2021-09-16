<?php

class ControllerVendorLtsProduct extends Controller {

    public function index() {

        $this->load->language('vendor/lts_product');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('vendor/lts_product');

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

         if (isset($this->request->get['filter_vendor_id'])) {
            $filter_vendor_id = $this->request->get['filter_vendor_id'];
        } else {
            $filter_vendor_id = null;
        }

        if (isset($this->request->get['filter_store_owner'])) {
            $filter_store_owner = $this->request->get['filter_store_owner'];
        } else {
            $filter_store_owner = null;
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

        if (isset($this->request->get['filter_vendor_id'])) {
            $url .= '&filter_vendor_id=' . $this->request->get['filter_vendor_id'];
        }

        if (isset($this->request->get['filter_store_owner'])) {
            $url .= '&filter_store_owner=' . $this->request->get['filter_store_owner'];
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
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('vendor/lts_product', 'user_token=' . $this->session->data['user_token'] . $url, true)
        );

        $data['add'] = $this->url->link('catalog/product/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
        $data['copy'] = $this->url->link('catalog/product/copy', 'user_token=' . $this->session->data['user_token'] . $url, true);
        $data['delete'] = $this->url->link('vendor/lts_product/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

        $data['products'] = array();

        $filter_data = array(
            'filter_name' => $filter_name,
            'filter_vendor_id' => $filter_vendor_id,
            'filter_store_owner' => $filter_store_owner,
            'filter_model' => $filter_model,
            'filter_price' => $filter_price,
            'filter_quantity' => $filter_quantity,
            'filter_status' => $filter_status,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );

        $this->load->model('tool/image');

        $this->load->model('vendor/lts_vendor');

        $product_total = $this->model_vendor_lts_product->getTotalProducts($filter_data);

        $results = $this->model_vendor_lts_product->getProducts($filter_data);
       
        foreach ($results as $result) {

            if (is_file(DIR_IMAGE . $result['image'])) {
                $image = $this->model_tool_image->resize($result['image'], 40, 40);
            } else {
                $image = $this->model_tool_image->resize('no_image.png', 40, 40);
            }

            $special = false;

            $product_specials = $this->model_vendor_lts_product->getProductSpecials($result['product_id']);

            foreach ($product_specials as $product_special) {
                if (($product_special['date_start'] == '0000-00-00' || strtotime($product_special['date_start']) < time()) && ($product_special['date_end'] == '0000-00-00' || strtotime($product_special['date_end']) > time())) {
                    $special = $this->currency->format($product_special['price'], $this->config->get('config_currency'));

                    break;
                }
            }


            $data['products'][] = array(
                'product_id'    => $result['product_id'],
                'image'         => $image,
                'name'          => $result['name'],
                'store_owner'   => $result['store_owner'],
                'model'         => $result['model'],
                'price'         => $this->currency->format($result['price'], $this->config->get('config_currency')),
                'special'       => $special,
                'quantity'      => $result['quantity'],
                'status'        => $result['approved'] ? $this->language->get('text_approve') : $this->language->get('text_pending'),
                'edit'          => $this->url->link('catalog/product/edit', 'user_token=' . $this->session->data['user_token'] . '&product_id=' . $result['product_id'] . $url, true),
                'approve'       => $this->url->link('vendor/lts_product/approve', 'user_token=' . $this->session->data['user_token'] . '&product_id=' . $result['product_id'], true),
                'disapprove'    => $this->url->link('vendor/lts_product/disapprove', 'user_token=' . $this->session->data['user_token'] . '&product_id=' . $result['product_id'], true),
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

        $data['sort_store_owner'] = $this->url->link('vendor/lts_product', 'user_token=' . $this->session->data['user_token'] . '&sort=lv.store_owner' . $url, true);

        $data['sort_name'] = $this->url->link('vendor/lts_product', 'user_token=' . $this->session->data['user_token'] . '&sort=pd.name' . $url, true);
        $data['sort_model'] = $this->url->link('vendor/lts_product', 'user_token=' . $this->session->data['user_token'] . '&sort=p.model' . $url, true);
        $data['sort_price'] = $this->url->link('vendor/lts_product', 'user_token=' . $this->session->data['user_token'] . '&sort=p.price' . $url, true);
        $data['sort_quantity'] = $this->url->link('vendor/lts_product', 'user_token=' . $this->session->data['user_token'] . '&sort=p.quantity' . $url, true);
        $data['sort_status'] = $this->url->link('vendor/lts_product', 'user_token=' . $this->session->data['user_token'] . '&sort=p.status' . $url, true);
        $data['sort_order'] = $this->url->link('vendor/lts_product', 'user_token=' . $this->session->data['user_token'] . '&sort=p.sort_order' . $url, true);

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
        $pagination->url = $this->url->link('vendor/lts_product', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));

        $data['filter_name'] = $filter_name;
        $data['filter_model'] = $filter_model;
        $data['filter_price'] = $filter_price;
        $data['filter_quantity'] = $filter_quantity;
        $data['filter_status'] = $filter_status;

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('vendor/lts_product_list', $data));
    }

    public function delete() {
        $this->load->language('vendor/lts_product');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('vendor/lts_product');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $product_id) {
                
                //$this->model_vendor_lts_product->deleteProduct($product_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('vendor/lts_product', 'user_token=' . $this->session->data['user_token'], true));
        }

        $this->getList();
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'vendor/lts_product')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    public function approve() {

        $this->load->model('vendor/lts_product');

        $this->model_vendor_lts_product->approveStatus($this->request->get['product_id']);

        $this->session->data['success'] = $this->language->get('text_success');

        $this->response->redirect($this->url->link('vendor/lts_product', 'user_token=' . $this->session->data['user_token'], true));
    }

    public function disapprove() {

        $this->load->model('vendor/lts_product');

        $this->model_vendor_lts_product->disapproveStatus($this->request->get['product_id']);

        $this->session->data['success'] = $this->language->get('text_success');

        $this->response->redirect($this->url->link('vendor/lts_product', 'user_token=' . $this->session->data['user_token'], true));
    }

}
