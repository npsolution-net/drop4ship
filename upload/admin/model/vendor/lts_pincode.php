<?php
class ModelVendorLtsPincode extends Model {
	public function addPincode($data) {

		$this->db->query("INSERT INTO ". DB_PREFIX ."lts_pincode SET name = '". $this->db->escape($data['name']) ."', country_id = '". (int)$data['country_id'] ."', zone_id='". (int)$data['zone_id'] ."', status = '". (int)$data['status'] ."'");

		$pincode_id = $this->db->getLastId();

		if(isset($data['pincode_checker'])) {
			foreach($data['pincode_checker'] as  $value) {

				$this->db->query("INSERT INTO ". DB_PREFIX ."lts_pincode_status SET pincode_id = '". (int)$pincode_id ."', pincode = '". (int)$value['pincode'] ."', status = '". (int)$value['status'] ."'");
			}
		}

	}

	public function getPincodeChecker($pincode_status_id) {
		$sql = "SELECT * FROM ". DB_PREFIX ."lts_pincode_status WHERE pincode_status_id = '". (int)$pincode_status_id ."'";

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getPincodeCheckers($pincode_id) {

		$pincode_checker_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "lts_pincode_status WHERE pincode_id = '" . (int)$pincode_id . "'");

		foreach ($query->rows as $result) {
			$pincode_checker_data[] = $result['pincode_status_id'];
		}

		return $pincode_checker_data;

	}

	public function editPincode($pincode_id, $data) {
		
		$this->db->query("UPDATE ". DB_PREFIX ."lts_pincode SET name = '". $this->db->escape($data['name']) ."', country_id = '". (int)$data['country_id'] ."', zone_id='". (int)$data['zone_id'] ."', status = '". (int)$data['status'] ."' WHERE pincode_id = '". (int)$pincode_id ."'");

		$this->db->query("DELETE FROM ". DB_PREFIX ."lts_pincode_status WHERE pincode_id='". (int)$pincode_id ."'");

		if(isset($data['pincode_checker'])) {
			foreach($data['pincode_checker'] as  $value) {

				$this->db->query("INSERT INTO ". DB_PREFIX ."lts_pincode_status SET pincode_id = '". (int)$pincode_id ."', pincode = '". (int)$value['pincode'] ."', status = '". (int)$value['status'] ."'");
			}
		}

	}

	public function getPincodes($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "lts_pincode";

		if (!empty($data['filter_name'])) {
			$sql .= " WHERE name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		
		$sort_data = array(
			'name',
			'country_id',
			'status',
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY name";
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
 
	public function deletePincode($pincode_id) {
		$this->db->query("DELETE FROM ". DB_PREFIX ."lts_pincode WHERE pincode_id='". (int)$pincode_id ."'");
		$this->db->query("DELETE FROM ". DB_PREFIX ."lts_pincode_status WHERE pincode_id='". (int)$pincode_id ."'");

		$this->cache->delete('lts_pincode');
	}

	public function getPincode($pincode_id) {
		$sql = "SELECT * FROM ". DB_PREFIX ."lts_pincode WHERE pincode_id = '". (int)$pincode_id ."'";

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getTotalPincodes($data = array()) {
		$sql = "SELECT COUNT(DISTINCT pincode_id) AS total FROM " . DB_PREFIX . "lts_pincode";
		
		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	//on product page

	public function addProductPincode($product_id, $data = array()) {
		foreach ($data as $pincode_id) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "lts_product_to_pincode SET product_id = '" . (int)$product_id . "', pincode_id = '" . (int)$pincode_id . "'");
        }

	}

	public function editProductPincode($product_id, $data = array()) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "lts_product_to_pincode WHERE product_id = '" . (int)$product_id . "'");

		foreach ($data as $pincode_id) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "lts_product_to_pincode SET product_id = '" . (int)$product_id . "', pincode_id = '" . (int)$pincode_id . "'");
        }
		
	}

	public function getProductPincodes($product_id) {
		$product_pincode_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "lts_product_to_pincode WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_pincode_data[] = $result['pincode_id'];
		}

		return $product_pincode_data;
	}
}