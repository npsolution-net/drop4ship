<?php

namespace Vendor;
class Vendor {
	private $data = array();
	private $vendor_id;
	private $firstname;
	private $lastname;
	private $vendor_group_id;
	private $email;
	private $telephone;
	private $address_id;

	public function __construct($registry) {
		$this->config = $registry->get('config');
		$this->db = $registry->get('db');
		$this->request = $registry->get('request');
		$this->session = $registry->get('session');

		if (isset($this->session->data['vendor_id'])) {
			$vendor_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "lts_vendor WHERE vendor_id = '" . (int)$this->session->data['vendor_id'] . "' AND status = '1'");

			if ($vendor_query->num_rows) {
				$this->vendor_id = $vendor_query->row['vendor_id'];
				$this->firstname = $vendor_query->row['firstname'];
				$this->lastname = $vendor_query->row['lastname'];
				$this->email = $vendor_query->row['email'];
				$this->telephone = $vendor_query->row['telephone'];
			} else {
				$this->logout();
			}
		}
	}

	public function login($email, $password, $override = false) {

		if ($override) {
			$vendor_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "lts_vendor WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "' AND status = '1'");
		} else {
			$vendor_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "lts_vendor WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "' AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->db->escape($password) . "'))))) OR password = '" . $this->db->escape(md5($password)) . "') AND status = '1'");
		}

		if ($vendor_query->num_rows) {
			$this->session->data['vendor_id'] = $vendor_query->row['vendor_id'];

			$this->vendor_id = $vendor_query->row['vendor_id'];
			$this->firstname = $vendor_query->row['firstname'];
			$this->lastname  = $vendor_query->row['lastname'];
			$this->email = $vendor_query->row['email'];
			$this->telephone = $vendor_query->row['telephone'];
	
			return true;
		} else {
			return false;
		} 
	}

	public function getId() { 
		return $this->vendor_id;
	}

	public function logout() {
		unset($this->session->data['vendor_id']);

		$this->vendor_id = '';
		$this->firstname = '';
		$this->lastname = '';
		$this->email = '';
		$this->telephone = '';
	}

	public function isLogged() {
		return $this->vendor_id;
	}

	public function getFirstName() {
		return $this->firstname;
	}

	public function getLastName() {
		return $this->lastname;
	}

	public function getEmail() {
		return $this->email;
	}
	
} 
?>