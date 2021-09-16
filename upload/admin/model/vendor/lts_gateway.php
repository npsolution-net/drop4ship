<?php
class ModelVendorLtsGateway extends Model {
	public function getGateway($code) {
		$setting_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "lts_gateway WHERE `code` = '" . $this->db->escape($code) . "'");

		foreach ($query->rows as $result) {
			if (!$result['serialized']) {
				$setting_data[$result['key']] = $result['value'];
			} else {
				$setting_data[$result['key']] = json_decode($result['value'], true);
			}
		}

		return $setting_data;
	}

	public function editGateway($code, $data) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "lts_gateway` WHERE `code` = '" . $this->db->escape($code) . "'");

		foreach ($data as $key => $value) {
			if (substr($key, 0, strlen($code)) == $code) {
				if (!is_array($value)) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "lts_gateway SET `code` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "'");
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "lts_gateway SET `code` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape(json_encode($value, true)) . "', serialized = '1'");
				}
			}
		}
	}

	public function deleteGateway($code) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "lts_gateway WHERE `code` = '" . $this->db->escape($code) . "'");
	}
	
	public function getGatewayValue($key) {
		$query = $this->db->query("SELECT value FROM " . DB_PREFIX . "lts_gateway WHERE `key` = '" . $this->db->escape($key) . "'");

		if ($query->num_rows) {
			return $query->row['value'];
		} else {
			return null;	
		}
	}
	
	public function editGatewayValue($code = '', $key = '', $value = '') {
		if (!is_array($value)) {
			$this->db->query("UPDATE " . DB_PREFIX . "lts_gateway SET `value` = '" . $this->db->escape($value) . "', serialized = '0'  WHERE `code` = '" . $this->db->escape($code) . "' AND `key` = '" . $this->db->escape($key) . "'");
		} else {
			$this->db->query("UPDATE " . DB_PREFIX . "lts_gateway SET `value` = '" . $this->db->escape(json_encode($value)) . "', serialized = '1' WHERE `code` = '" . $this->db->escape($code) . "' AND `key` = '" . $this->db->escape($key) . "'");
		}
	}
}
