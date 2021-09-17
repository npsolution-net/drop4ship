<?php
class ModelAccountCustomerGroup extends Model {
	public function getCustomerGroup($customer_group_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer_group cg LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (cg.customer_group_id = cgd.customer_group_id) WHERE cg.customer_group_id = '" . (int)$customer_group_id . "' AND cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getCustomerGroups() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_group cg LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (cg.customer_group_id = cgd.customer_group_id) WHERE cgd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY cg.sort_order ASC, cgd.name ASC");

		return $query->rows;
	}

	public function addCustomerGroup($data) {
		$option = $data['owner_id'] ? ", owner_id = '" . $data['owner_id'] . "'" : '';
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer_group SET approval = '" . (int)$data['approval'] . "', sort_order = '" . (int)$data['sort_order'] . "'" . $option);

		$customer_group_id = $this->db->getLastId();

		foreach ($data['customer_group_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "customer_group_description SET customer_group_id = '" . (int)$customer_group_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}
		
		return $customer_group_id;
	}

	public function editGroup($customer_id, $customer_group_id) {
		$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET customer_group_id = '" . $this->db->escape($customer_group_id) . "' WHERE LCASE(customer_id) = '" . $this->db->escape(utf8_strtolower($customer_id)) . "'");
	}
}