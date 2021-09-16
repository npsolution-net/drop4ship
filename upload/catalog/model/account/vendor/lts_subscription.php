<?php
class ModelAccountVendorLtsSubscription extends Model {
	public function addVendorPlan($expire_date, $data, $vendor_id) { 

		$this->db->query("DELETE FROM `" . DB_PREFIX . "lts_plan` WHERE vendor_id = '" . (int)$vendor_id . "'");

		$this->db->query("INSERT INTO " . DB_PREFIX . "lts_plan SET vendor_id = '". (int)$vendor_id ."', subscription_id = '" . (int)$data['subscription_id'] . "', name = '". $this->db->escape($data['name']) ."', no_of_product = '". (int)$data['no_of_product'] ."', join_fee = '". $data['join_fee'] ."', subscription_fee = '". $data['subscription_fee'] ."', validity = '". (int)$data['validity'] ."', date_added = NOW(), date_expire ='". $expire_date ."' ");

		$plan_id = $this->db->getLastId();

		return $plan_id;
	}


	public function getVendonPlan($subscription_id, $vendor_id)	{
		$sql = "SELECT * FROM " . DB_PREFIX . "lts_plan  WHERE subscription_id = '" . (int)$subscription_id . "' AND vendor_id = '". (int)$vendor_id ."'";

		$query = $this->db->query($sql);
 
		return $query->row; 
	}

	public function getVendonActivePlan($vendor_id)	{
		$sql = "SELECT * FROM " . DB_PREFIX . "lts_plan  WHERE vendor_id = '". (int)$vendor_id ."'";
 
		$query = $this->db->query($sql);

		return $query->row; 
	}

	public function getSubscriptionIdByProductId($product_id)	{
		$sql = "SELECT subscription_id FROM " . DB_PREFIX . "lts_subscription  WHERE product_id = '". (int)$product_id ."'";
		$query = $this->db->query($sql);

		return $query->row['subscription_id']; 
	}

	public function getSubscriptions() {
		$sql = "SELECT * FROM " . DB_PREFIX . "lts_subscription s LEFT JOIN " . DB_PREFIX . "lts_subscription_description sd ON (s.subscription_id = sd.subscription_id) WHERE sd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		$query = $this->db->query($sql);

		return $query->rows; 
	}

	public function getSubscriptionDescription($subscription_id) {
 
	}

	public function getSubscription($subscription_id) {
		$sql = "SELECT * FROM " . DB_PREFIX . "lts_subscription s LEFT JOIN " . DB_PREFIX . "lts_subscription_description sd ON (s.subscription_id = sd.subscription_id) WHERE sd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND s.subscription_id = '". (int)$subscription_id ."'";
       
		$query = $this->db->query($sql);

		return $query->row;
	}
}


?>