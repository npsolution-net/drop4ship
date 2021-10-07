<?php

class ModelDropshipDropship extends Model {

  public function searchByEmail($data = array()){
    $sql = "SELECT * FROM " . DB_PREFIX . "customer WHERE email = '" . $data['email'] . "' AND customer_group_id = 1";
    $query = $this->db->query($sql);

    return $query->rows;
  }

  public function setGroupId($data = array()){
    $this->db->query("UPDATE " . DB_PREFIX . "customer SET customer_group_id = '" . (int)$this->db->escape($data['customer_group_id']) . "' WHERE email = '" . $data['email'] . "'");
  }

  public function getDropships($data = array()){
    $sql = "SELECT * FROM " . DB_PREFIX . "customer c INNER JOIN " . DB_PREFIX . "customer_group cg ON c.customer_group_id = cg.customer_group_id WHERE c.customer_group_id = '" . (int)$this->db->escape($data['customer_group_id']) . "' AND c.customer_id != '" . (int)$this->db->escape($data['customer_id']) . "'";

    if (!empty($data['customer_group_id'])) {
        $sql .= " AND c.customer_group_id = '" . (int)$this->db->escape($data['customer_group_id']) . "'";
    }
  
    $query = $this->db->query($sql);

    return $query->rows;
  }

  public function deleteDropships($data = array()){
    $this->db->query("UPDATE " . DB_PREFIX . "customer SET customer_group_id = 1 WHERE customer_id IN (" . $this->db->escape(implode(",", $data['selected'])) . ")");
  }

}

