<?php
class ModelExtensionGhtkGhtk extends Model {
	public function updateOrder($order_id, $order_status_id, $comment = '', $notify = false) {
		$this->load->model('checkout/order');

		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = " . (int)$order_status_id . " WHERE order_id = " . (int)$order_id);

		$this->model_checkout_order->addOrderHistory($order_id, $order_status_id, $comment, $notify);
	}
}