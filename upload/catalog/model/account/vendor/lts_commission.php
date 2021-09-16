<?php
class ModelAccountVendorLtsCommission extends Model {
	public function getCommissions($data = array()) {
	$sql = "SELECT lc.*, (SELECT lv.store_name FROM " . DB_PREFIX . "lts_vendor lv WHERE lv.vendor_id = lc.vendor_id) AS store_name,  (SELECT lv.store_owner FROM " . DB_PREFIX . "lts_vendor lv WHERE lv.vendor_id = lc.vendor_id) AS store_owner, (SELECT o.currency_code FROM " . DB_PREFIX . "order o WHERE o.order_id = lc.order_id) AS currency_code, (SELECT o.currency_value FROM " . DB_PREFIX . "order o WHERE o.order_id = lc.order_id) AS currency_value, (SELECT lop.price FROM " . DB_PREFIX . "lts_order_product lop WHERE lop.order_product_id = lc.order_product_id) AS price, (SELECT op.name FROM " . DB_PREFIX . "order_product op WHERE op.order_product_id = lc.order_product_id) AS name, (SELECT lop.quantity FROM " . DB_PREFIX . "lts_order_product lop WHERE lop.order_product_id = lc.order_product_id) AS quantity,  (SELECT lop.total FROM " . DB_PREFIX . "lts_order_product lop WHERE lop.order_product_id = lc.order_product_id) AS total FROM " . DB_PREFIX . "lts_commission lc WHERE lc.vendor_commission_id > 0 AND type = 'sale' AND lc.status = '1' AND vendor_id='".$data['vendor_id']."'";

		 $sort_data = array(
		 	'store_owner',
		 	'store_name',
		 	'price',
		 	'name',
		 	'quantity',
		 	'total',
		 	'mc.order_id',
		 	'mc.amount',
		 	'mc.date_added',
		 );

		 if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
		 	$sql .= " ORDER BY " . $data['sort'];
		 } else {
		 	$sql .= " ORDER BY mc.date_added";
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

		 	$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		 }

		 $query = $this->db->query($sql);

		 return $query->rows;
	}


	public function getTotalCommissions($data = array()) {
		$sql = "SELECT COUNT(DISTINCT vendor_commission_id) AS total FROM " . DB_PREFIX . "lts_commission WHERE vendor_id = '". (int)$data['vendor_id'] ."' ";
		
		$query = $this->db->query($sql);

		return $query->row['total'];
	}
}  