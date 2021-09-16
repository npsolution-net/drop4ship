<?php

class ModelVendorLtsReview extends Model {

    public function addReview($data) {
        $this->db->query("INSERT INTO  " . DB_PREFIX . "lts_review  set customer_id='" . (int) $data['customer_id'] . "', vendor_id='" . (int) $data['vendor_id'] . "', content='" . $this->db->escape($data['content']) . "', status = '" . (int) $data['status'] . "'");
    }

    public function getReview($review_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "lts_review WHERE review_id='" . (int) $review_id . "'");

        return $query->row;
    }

    public function getReviews($data = array()) {
        $sql = "SELECT r.review_id, (SELECT store_owner FROM ". DB_PREFIX ."lts_vendor WHERE vendor_id = lr.vendor_id ) AS store_owner, pd.name, lr.vendor_id, r.author, r.rating, r.status, r.date_added FROM " . DB_PREFIX . "review r LEFT JOIN " . DB_PREFIX . "product_description pd ON (r.product_id = pd.product_id) RIGHT JOIN " . DB_PREFIX . "lts_review lr ON(lr.review_id = r.review_id) WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "'";

        if (!empty($data['filter_product'])) {
            $sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_product']) . "%'";
        }

        if (!empty($data['filter_author'])) {
            $sql .= " AND r.author LIKE '" . $this->db->escape($data['filter_author']) . "%'";
        }

        if (isset($data['filter_status']) && $data['filter_status'] !== '') {
            $sql .= " AND r.status = '" . (int) $data['filter_status'] . "'";
        }

        if (!empty($data['filter_date_added'])) {
            $sql .= " AND DATE(r.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        $sort_data = array(
            'pd.name',
            'r.author',
            'r.rating',
            'r.status',
            'r.date_added',
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY r.date_added";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalReviews($data = array()) {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "lts_review lr LEFT JOIN " . DB_PREFIX . "product_description pd ON (pd.product_id = lr.product_id) WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "'";

         if (!empty($data['filter_product'])) {
            $sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_product']) . "%'";
        }

        if (!empty($data['filter_author'])) {
            $sql .= " AND r.author LIKE '" . $this->db->escape($data['filter_author']) . "%'";
        }

        if (isset($data['filter_status']) && $data['filter_status'] !== '') {
            $sql .= " AND r.status = '" . (int) $data['filter_status'] . "'";
        }

        if (!empty($data['filter_date_added'])) {
            $sql .= " AND DATE(r.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function approveStatus($review_id) {

        $this->db->query("UPDATE " . DB_PREFIX . "review SET status = '" . 1 . "' WHERE review_id = '" . (int) $review_id . "'");
    }

    public function disapproveStatus($review_id) {
        $this->db->query("UPDATE " . DB_PREFIX . "review SET status = '" . 0 . "' WHERE review_id = '" . (int) $review_id . "'");
    }

}
