<?php
class ModelVendorLtsMvendor extends Model {
	
	public function getVendorStoreInfo($vendor_id) {
		$query = $this->db->query("SELECT * FROM ". DB_PREFIX ."lts_vendor WHERE vendor_id = '". (int)$vendor_id ."' AND status = '1' AND approved = '1'");

		return $query->row;
	}

	public function getVendorStores($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "lts_vendor lv WHERE lv.vendor_id > 0 AND lv.status = '1' AND lv.approved = '1'";

		$sort_data = array(
			'lv.vendor_id',
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY lv.vendor_id";
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

	public function getTotalVendorStores($data = array()) {
		$sql = "SELECT COUNT(*) as total FROM " . DB_PREFIX . "lts_vendor lv WHERE lv.vendor_id > 0 AND lv.status = '1' AND lv.approved = '1'";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

}