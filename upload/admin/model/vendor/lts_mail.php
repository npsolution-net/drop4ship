<?php
class ModelVendorLtsMail extends Model {
	public function addMail($data) {

		$this->db->query("INSERT INTO ". DB_PREFIX ."lts_mail SET too_id= '". (int)$data['too_id'] ."', subject='". $this->db->escape($data['subject']) ."', message='". $this->db->escape($data['message']) ."', status='". (int)$data['status'] ."', date_added = NOW(), date_modified = NOW()");

	}

	public function editMail($mail_id, $data) {
		$this->db->query("UPDATE ". DB_PREFIX ."lts_mail SET too_id= '". (int)$data['too_id'] ."', subject='". $this->db->escape($data['subject']) ."', message='". $this->db->escape($data['message']) ."', status='". (int)$data['status'] ."' , date_modified = NOW() WHERE mail_id='". (int)$mail_id ."'");

	}

	public function deleteMail($mail_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "lts_mail` WHERE mail_id = '" . (int)$mail_id . "'");
	
		$this->cache->delete('mail');
	}

	public function getMail($mail_id) {
		$query = $this->db->query("SELECT * FROM ". DB_PREFIX ."lts_mail WHERE mail_id='". (int)$mail_id ."'");

		return $query->row;
	} 

	public function approvalVendor() {
		$query = $this->db->query("SELECT email, CONCAT(store_owner) AS name FROM ". DB_PREFIX ."lts_vendor WHERE status='". (int)1 ."'");

		return $query->rows;
	}

	public function nonapprovalVendor() {
		$query = $this->db->query("SELECT email,  CONCAT(store_owner) AS name FROM ". DB_PREFIX ."lts_vendor WHERE status='". (int)1 ."'");

		return $query->rows;
	}

	public function allVendor() {
		$query = $this->db->query("SELECT email, CONCAT(store_owner) AS name FROM ". DB_PREFIX ."lts_vendor");

		return $query->rows;
	}

	public function getMails() {
		$query = $this->db->query("SELECT *, (SELECT name FROM ". DB_PREFIX ."lts_too WHERE too_id= m.too_id) AS too FROM ". DB_PREFIX ."lts_mail m ORDER BY m.mail_id DESC");

		return $query->rows;
	}

	public function getToo() {
		$query = $this->db->query("SELECT * FROM ". DB_PREFIX ."lts_too");

		return $query->rows;
	}


	public function getTooName($too_id) {
		$query = $this->db->query("SELECT name FROM ". DB_PREFIX ."lts_too WHERE too_id='". (int)$too_id ."' ");

		return $query->row;
	}
}