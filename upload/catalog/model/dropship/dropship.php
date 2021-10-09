<?php

class ModelDropshipDropship extends Model {

  public function searchByEmail($data = array()){
    $sql = "SELECT * FROM " . DB_PREFIX . "customer WHERE email = '" . $data['email'] . "' AND customer_group_id = 1";
    $query = $this->db->query($sql);

    return $query->rows;
  }

  public function getDropshipGroup($data = array()){
    $sql = "SELECT * FROM " . DB_PREFIX . "dropship_group WHERE customer_id = '" . $this->db->escape($data['customer_id']) . "'";
    $query = $this->db->query($sql);

    return $query->rows;
  }

  public function addDropshipGroup($data = array()){
    $customer = $this->db->query("SELECT * FROM ". DB_PREFIX . "customer WHERE email = '" . $this->db->escape($data['email']) . "'");
    if(count($customer->rows) > 0 ){
      $customer = $customer->rows[0];          
      $this->db->query("DELETE FROM ". DB_PREFIX . "dropship_group WHERE customer_id = '" . $this->db->escape($customer['customer_id']) . "' AND customer_group_id = '" . $this->db->escape($data['customer_group_id']) . "'");
      $this->db->query("INSERT INTO `". DB_PREFIX . "dropship_group`(`customer_id`,`customer_group_id`,`status`) VALUES ('" . $this->db->escape($customer['customer_id']) . "', '" . $this->db->escape($data['customer_group_id']) . "',1)");
    }   
  }

  public function deleteDropshipGroup($data = array()){
    $this->db->query("DELETE FROM ". DB_PREFIX . "dropship_group WHERE customer_id IN (" . $this->db->escape(implode(",", $data['selected'])) . ") AND customer_group_id = '" . $this->db->escape($data['customer_group_id']) . "'");
  }

  public function getDropships($data = array()){  
    $sql = "SELECT c.*, dg.customer_group_id dropship_group_id, dg.status dropship_status FROM " . DB_PREFIX . "customer c INNER JOIN " . DB_PREFIX . "dropship_group dg ON c.customer_id = dg.customer_id WHERE dg.customer_group_id = '" . $this->db->escape($data['customer_group_id']) . "'";
    $query = $this->db->query($sql);

    return $query->rows;
  }

  public function deleteDropships($data = array()){
    $this->db->query("UPDATE " . DB_PREFIX . "customer SET customer_group_id = 1 WHERE customer_id IN (" . $this->db->escape(implode(",", $data['selected'])) . ")");
  }

}

