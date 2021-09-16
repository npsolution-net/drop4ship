<?php

class ControllerVendorLtsReview extends Controller {

    private $error = array();

    public function index() {
        $this->load->language('vendor/lts_review');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('vendor/lts_review');

        $this->load->model('vendor/lts_vendor');

        $this->getList();
    }

    protected function getList() {
        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'vname';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'DESC';
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
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('vendor/lts_review', 'user_token=' . $this->session->data['user_token'] . $url, true)
        );

        $filter_data = array(
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );

        $review_total = $this->model_vendor_lts_review->getTotalReviews($filter_data);

        $results = $this->model_vendor_lts_review->getReviews($filter_data);

        foreach ($results as $result) {

            $data['reviews'][] = array(
                
                'store_owner' => $result['store_owner'],
                'author' => $result['author'],
                'name' => $result['name'],
                'rating' => $result['rating'],
                'status' => $result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
                'date_added' => $result['date_added'],
                'approve' => $this->url->link('vendor/lts_review/approve', 'user_token=' . $this->session->data['user_token'] . '&review_id=' . $result['review_id'], true),
                'disapprove' => $this->url->link('vendor/lts_review/disapprove', 'user_token=' . $this->session->data['user_token'] . '&review_id=' . $result['review_id'], true),
                'view' => $this->url->link('catalog/review/edit', 'user_token=' . $this->session->data['user_token'] . '&review_id=' . $result['review_id'], true)
            );
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $url = '';

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        $data['sort_name'] = $this->url->link('vendor/lts_review', 'user_token=' . $this->session->data['user_token'] . '&sort=pd.name' . $url, true);

        $data['sort_vendor'] = $this->url->link('vendor/lts_review', 'user_token=' . $this->session->data['user_token'] . '&sort=vname' . $url, true);

        $data['sort_customer'] = $this->url->link('vendor/lts_review', 'user_token=' . $this->session->data['user_token'] . '&sort=r.author' . $url, true);

        $data['sort_rating'] = $this->url->link('vendor/lts_review', 'user_token=' . $this->session->data['user_token'] . '&sort=r.rating' . $url, true);

        $data['sort_date'] = $this->url->link('vendor/lts_review', 'user_token=' . $this->session->data['user_token'] . '&sort=r.date_added' . $url, true);

        $pagination = new Pagination();
        $pagination->total = $review_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('vendor/lts_review', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($review_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($review_total - $this->config->get('config_limit_admin'))) ? $review_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $review_total, ceil($review_total / $this->config->get('config_limit_admin')));

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('vendor/lts_review_list', $data));
    }

    public function approve() {

        $this->load->model('vendor/lts_review');

        $this->model_vendor_lts_review->approveStatus($this->request->get['review_id']);

        $this->response->redirect($this->url->link('vendor/lts_review', 'user_token=' . $this->session->data['user_token'], true));
    }

    public function disapprove() {

        $this->load->model('vendor/lts_review');

        $this->model_vendor_lts_review->disapproveStatus($this->request->get['review_id']);

        $this->response->redirect($this->url->link('vendor/lts_review', 'user_token=' . $this->session->data['user_token'], true));
    }

}