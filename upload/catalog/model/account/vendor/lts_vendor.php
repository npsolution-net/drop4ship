<?php
class ModelAccountVendorLtsVendor extends Model {
	public function addVendorStoreInfo($data) {
		$query = $this->db->query("SELECT * FROM ". DB_PREFIX ."lts_vendor WHERE customer_id = '". (int)$this->customer->isLogged() ."'");
 
		if($query->row) {

			$sql = "UPDATE ". DB_PREFIX ."lts_vendor SET description =  '". $this->db->escape($data['description']) ."' , meta_title = '". $this->db->escape($data['meta_title']) ."', meta_description = '". $this->db->escape($data['meta_description']) ."',  meta_keyword = '". $this->db->escape($data['meta_keyword']) ."', store_owner = '" . $this->db->escape($data['store_owner']) . "', store_name= '" . $this->db->escape($data["store_name"]) . "' , address = '" . $this->db->escape($data['address']) . "' , email= '" . $this->db->escape($data['email']) . "' , telephone= '" . $this->db->escape($data['telephone']) . "' ,  fax= '" . $this->db->escape($data['fax']) . "' ,  country_id= '" . (int)$data['country_id'] . "' ,   zone_id= '" . $this->db->escape($data['zone_id']) . "' ,  city= '" . $this->db->escape($data['city']) . "', logo= '" . $this->db->escape($data['logo']) . "',  banner= '" . $this->db->escape($data['banner']) . "',  profile_image= '" . $this->db->escape($data['profile_image']) . "',  facebook= '" . $this->db->escape($data['facebook']) . "', instagram= '" . $this->db->escape($data['instagram']) . "', youtube= '" . $this->db->escape($data['youtube']) . "', twitter= '" . $this->db->escape($data['twitter']) . "', pinterest= '" . $this->db->escape($data['pinterest']) . "' WHERE vendor_id = '". (int)$query->row['vendor_id'] ."' AND customer_id = '". (int)$this->customer->isLogged() ."' AND status = '1'";

			$this->db->query($sql);

			// SEO URL
			$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'vendor_id=" . (int)$query->row['vendor_id'] . "'");

			if (isset($data['vendor_seo_url'])) {

				foreach ($data['vendor_seo_url']as $store_id => $language) {
					foreach ($language as $language_id => $keyword) {
						if (!empty($keyword)) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', query = 'vendor_id=" . (int)$query->row['vendor_id'] . "', keyword = '" . $this->db->escape($keyword) . "'");
						}
					}
				}
			}

		} else {

			if($this->config->get('module_lts_vendor_approval')) {
				$approved = 1;
			} else {
				$approved = 0; 
			}  

			$sql = "INSERT INTO ". DB_PREFIX ."lts_vendor SET customer_id = '". (int)$this->customer->isLogged() ."', status = '1', approved = '". (int)$approved ."', description =  '". $this->db->escape($data['description']) ."' , meta_title = '". $this->db->escape($data['meta_title']) ."', meta_description = '". $this->db->escape($data['meta_description']) ."',  meta_keyword = '". $this->db->escape($data['meta_keyword']) ."', store_owner = '" . $this->db->escape($data['store_owner']) . "', store_name= '" . $this->db->escape($data["store_name"]) . "' , address = '" . $this->db->escape($data['address']) . "' , email= '" . $this->db->escape($data['email']) . "' , telephone= '" . $this->db->escape($data['telephone']) . "' ,  fax= '" . $this->db->escape($data['fax']) . "' ,  country_id= '" . (int)$data['country_id'] . "' ,   zone_id= '" . $this->db->escape($data['zone_id']) . "' ,  city= '" . $this->db->escape($data['city']) . "', logo= '" . $this->db->escape($data['logo']) . "',  banner= '" . $this->db->escape($data['banner']) . "', profile_image= '" . $this->db->escape($data['profile_image']) . "', facebook= '" . $this->db->escape($data['facebook']) . "', instagram= '" . $this->db->escape($data['instagram']) . "', youtube= '" . $this->db->escape($data['youtube']) . "', twitter= '" . $this->db->escape($data['twitter']) . "', pinterest= '" . $this->db->escape($data['pinterest']) . "' ";

			$this->db->query($sql);

			$vendor_id = $this->db->getLastId();


			// SEO URL
			if (isset($data['vendor_seo_url'])) {
				foreach ($data['vendor_seo_url']as $store_id => $language) {
					foreach ($language as $language_id => $keyword) {
						if (!empty($keyword)) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', query = 'vendor_id=" . (int)$vendor_id . "', keyword = '" . $this->db->escape($keyword) . "'");
						}
					}
				}
			}

			// $mail = new Mail();
			// $mail->protocol = $this->config->get('config_mail_protocol');
			// $mail->parameter = $this->config->get('config_mail_parameter');
			// $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			// $mail->smtp_username = $this->config->get('config_mail_smtp_username');
			// $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			// $mail->smtp_port = $this->config->get('config_mail_smtp_port');
			// $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
			

			// $mail->setTo($this->config->get('config_email'));
			// $mail->setFrom($this->config->get('config_email'));
			// $mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			// $mail->setSubject(html_entity_decode($this->language->get('text_new_seller'), ENT_QUOTES, 'UTF-8'));
			// $mail->setText($this->load->view('account/vendor/register_alert', $data));
			// $mail->send();

		}
	}



	public function getVendorSeoUrls($vendor_id) {
		$vendor_seo_url_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE query = 'vendor_id=" . (int)$vendor_id . "'");

		foreach ($query->rows as $result) {
			$vendor_seo_url_data[$result['store_id']][$result['language_id']] = $result['keyword'];
		}

		return $vendor_seo_url_data;
	}

	public function getVendorInfo($customer_id) {
		$query = $this->db->query("SELECT * FROM ". DB_PREFIX ."lts_vendor WHERE customer_id = '". (int)$customer_id ."' AND status = '1' AND approved = '1'");

		return $query->row;
	}

	public function addPayment($vendor_id, $data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "lts_payment WHERE vendor_id = '" . (int)$vendor_id . "'");

		$this->db->query("INSERT INTO " . DB_PREFIX . "lts_payment SET vendor_id = '". (int)$vendor_id ."', paypal =  '". $this->db->escape($data['paypal']) ."', account_holder =  '". $this->db->escape($data['account_holder']) ."', bankname = '". $this->db->escape($data['bankname']) ."', accountno = '". $this->db->escape($data['accountno']) ."', ifsc = '". $this->db->escape($data['ifsc']) ."'");
	}

	
	public function getVendor($vendor_id) {
	  
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "lts_vendor` v LEFT JOIN `". DB_PREFIX ."customer` c ON (c.customer_id = v.customer_id)  WHERE  v.vendor_id = '" . (int)$vendor_id . "'");
      
		return $query->row;
	}

	public function getVendorStoreInfo($customer_id) {
		$query = $this->db->query("SELECT * FROM ". DB_PREFIX ."lts_vendor WHERE customer_id = '". (int)$customer_id ."' AND status = '1' AND approved = '1'");

		return $query->row;
	}

	public function getVendorApplyInfo($customer_id) {
		$query = $this->db->query("SELECT * FROM ". DB_PREFIX ."lts_vendor WHERE customer_id = '". (int)$customer_id ."' AND status = '1' AND approved = '0'");

		return $query->row;
	}
	
	
	public function getPayment($vendor_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "lts_payment`WHERE  vendor_id = '" . (int)$vendor_id . "'");

		return $query->row;
	}

	public function getVendorName($vendor_id) {
		$query = $this->db->query("SELECT store_owner FROM " . DB_PREFIX . "lts_vendor WHERE vendor_id = '" . (int)$vendor_id . "'");

		return $query->row;
	}
   public function getVendorStatus($customer_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "lts_vendor WHERE customer_id = '" . (int)$customer_id . "'");
        if($query->row){
		   return $query->row['status'];
	    }else{
	    	return false;
	    }
	}


	public function getStoreInformation($vendor_id) {
	
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "lts_vendor`  WHERE  vendor_id = '" . (int)$vendor_id . "'");
        
		return $query->row;
	}

	public function isVendor($customer_id){
 
         $query = $this->db->query("SELECT vendor_id FROM `" . DB_PREFIX . "lts_vendor`  WHERE  customer_id = '" . (int)$customer_id . "'");
        
		  return $query->row['vendor_id']?$query->row['vendor_id']:0;
	}
}