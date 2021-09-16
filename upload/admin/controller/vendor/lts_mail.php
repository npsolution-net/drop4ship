<?php
class ControllerVendorLtsMail extends Controller {

	private $error = array();

	public function index() {

		$this->load->language('vendor/lts_mail');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('vendor/lts_mail');

		$this->getList();

	}

	public function add() {
		$this->load->language('vendor/lts_mail');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('vendor/lts_mail');

		if(($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
	
			$this->model_vendor_lts_mail->addMail($this->request->post);

			if($this->request->post['status'] == '1') {

				$store_email = $this->config->get('config_email');

				$store_name  = $this->config->get('config_name');

				$message  = '<html dir="ltr" lang="en">' . "\n";
				$message .= '  <head>' . "\n";
				$message .= '    <title>' . $this->request->post['subject'] . '</title>' . "\n";
				$message .= '    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . "\n";
				$message .= '  </head>' . "\n";
				$message .= '  <body>' . html_entity_decode($this->request->post['message'], ENT_QUOTES, 'UTF-8') . '</body>' . "\n";
				$message .= '</html>' . "\n";
				// only approval vendor
				if($this->request->post['too_id'] == 3) {

					$emails = $this->model_vendor_lts_mail->approvalVendor();

					foreach ($emails as $email) {
						if (filter_var($email['email'], FILTER_VALIDATE_EMAIL)) {
							$mail = new Mail($this->config->get('config_mail_engine'));
							$mail->parameter = $this->config->get('config_mail_parameter');
							$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
							$mail->smtp_username = $this->config->get('config_mail_smtp_username');
							$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
							$mail->smtp_port = $this->config->get('config_mail_smtp_port');
							$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

							$mail->setTo($email['email']);
							$mail->setFrom($store_email);
							$mail->setSender(html_entity_decode($store_name, ENT_QUOTES, 'UTF-8'));
							$mail->setSubject(html_entity_decode($this->request->post['subject'], ENT_QUOTES, 'UTF-8'));
							$mail->setHtml($message);
							$mail->send();
						}
					}
					// only non approval vendor
				} elseif($this->request->post['too_id'] == 4) {

					$emails = $this->model_vendor_lts_mail->nonapprovalVendor();

					foreach ($emails as $email) {
						if (filter_var($email['email'], FILTER_VALIDATE_EMAIL)) {
							$mail = new Mail($this->config->get('config_mail_engine'));
							$mail->parameter = $this->config->get('config_mail_parameter');
							$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
							$mail->smtp_username = $this->config->get('config_mail_smtp_username');
							$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
							$mail->smtp_port = $this->config->get('config_mail_smtp_port');
							$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

							$mail->setTo($email['email']);
							$mail->setFrom($store_email);
							$mail->setSender(html_entity_decode($store_name, ENT_QUOTES, 'UTF-8'));
							$mail->setSubject(html_entity_decode($this->request->post['subject'], ENT_QUOTES, 'UTF-8'));
							$mail->setHtml($message);
							$mail->send();
						}
					}
					// all vendor
				} elseif($this->request->post['too_id'] == 2) {
					$emails = $this->model_vendor_lts_mail->allVendor();
                    
					foreach ($emails as $email) {
						if (filter_var($email['email'], FILTER_VALIDATE_EMAIL)) {
							$mail = new Mail($this->config->get('config_mail_engine'));
							$mail->parameter = $this->config->get('config_mail_parameter');
							$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
							$mail->smtp_username = $this->config->get('config_mail_smtp_username');
							$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
							$mail->smtp_port = $this->config->get('config_mail_smtp_port');
							$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

							$mail->setTo($email['email']);
							$mail->setFrom($store_email);
							$mail->setSender(html_entity_decode($store_name, ENT_QUOTES, 'UTF-8'));
							$mail->setSubject(html_entity_decode($this->request->post['subject'], ENT_QUOTES, 'UTF-8'));
							$mail->setHtml($message);
							$mail->send();
						}
					}
				}
			}
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('vendor/lts_mail', 'user_token=' . $this->session->data['user_token'], true));
		}

		$this->getForm();
	}

	public function edit() {

		$this->load->language('vendor/lts_mail');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('vendor/lts_mail');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

			$this->model_vendor_lts_mail->editMail($this->request->get['mail_id'], $this->request->post);

			if($this->request->post['status'] == '1') {

				$store_email = $this->config->get('config_email');

				$store_name  = $this->config->get('config_name');

				$message  = '<html dir="ltr" lang="en">' . "\n";
				$message .= '  <head>' . "\n";
				$message .= '    <title>' . $this->request->post['subject'] . '</title>' . "\n";
				$message .= '    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . "\n";
				$message .= '  </head>' . "\n";
				$message .= '  <body>' . html_entity_decode($this->request->post['message'], ENT_QUOTES, 'UTF-8') . '</body>' . "\n";
				$message .= '</html>' . "\n";

				// only approval vendor
				if($this->request->post['too_id'] == 3) {

					$emails = $this->model_vendor_lts_mail->approvalVendor();

					foreach ($emails as $email) {
						if (filter_var($email['email'], FILTER_VALIDATE_EMAIL)) {
							$mail = new Mail($this->config->get('config_mail_engine'));
							$mail->parameter = $this->config->get('config_mail_parameter');
							$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
							$mail->smtp_username = $this->config->get('config_mail_smtp_username');
							$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
							$mail->smtp_port = $this->config->get('config_mail_smtp_port');
							$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

							$mail->setTo($email['email']);
							$mail->setFrom($store_email);
							$mail->setSender(html_entity_decode($store_name, ENT_QUOTES, 'UTF-8'));
							$mail->setSubject(html_entity_decode($this->request->post['subject'], ENT_QUOTES, 'UTF-8'));
							$mail->setHtml($message);
							$mail->send();
						}
					}
					// only non approval vendor
				} elseif($this->request->post['too_id'] == 4) {

					$emails = $this->model_vendor_lts_mail->nonapprovalVendor();

					foreach ($emails as $email) {
						if (filter_var($email['email'], FILTER_VALIDATE_EMAIL)) {
							$mail = new Mail($this->config->get('config_mail_engine'));
							$mail->parameter = $this->config->get('config_mail_parameter');
							$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
							$mail->smtp_username = $this->config->get('config_mail_smtp_username');
							$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
							$mail->smtp_port = $this->config->get('config_mail_smtp_port');
							$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

							$mail->setTo($email['email']);
							$mail->setFrom($store_email);
							$mail->setSender(html_entity_decode($store_name, ENT_QUOTES, 'UTF-8'));
							$mail->setSubject(html_entity_decode($this->request->post['subject'], ENT_QUOTES, 'UTF-8'));
							$mail->setHtml($message);
							$mail->send();
						}
					}
					// all vendor
				} elseif($this->request->post['too_id'] == 2) {
					$emails = $this->model_vendor_lts_mail->allVendor();

					foreach ($emails as $email) {
						if (filter_var($email['email'], FILTER_VALIDATE_EMAIL)) {
							$mail = new Mail($this->config->get('config_mail_engine'));
							$mail->parameter = $this->config->get('config_mail_parameter');
							$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
							$mail->smtp_username = $this->config->get('config_mail_smtp_username');
							$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
							$mail->smtp_port = $this->config->get('config_mail_smtp_port');
							$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

							$mail->setTo($email['email']);
							$mail->setFrom($store_email);
							$mail->setSender(html_entity_decode($store_name, ENT_QUOTES, 'UTF-8'));
							$mail->setSubject(html_entity_decode($this->request->post['subject'], ENT_QUOTES, 'UTF-8'));
							$mail->setHtml($message);
							$mail->send();
						}
					}
				}
			}
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('vendor/lts_mail', 'user_token=' . $this->session->data['user_token'], true));
		}

		$this->getForm();
	}

	public function delete() {

		$this->load->language('vendor/lts_mail');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('vendor/lts_mail');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateDelete()) {

			foreach ($this->request->post['selected'] as $mail_id) {
				$this->model_vendor_lts_mail->deleteMail($mail_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('vendor/lts_mail', 'user_token=' . $this->session->data['user_token'], true));
		}

		$this->getForm();
	}


	protected function getList() {

		$mails = $this->model_vendor_lts_mail->getMails();



		foreach ($mails as $value) {
			$data['mails'][] = array(
				'mail_id'			=> $value['mail_id'],
				'too' 			    => $value['too'],
				'subject'			=> $value['subject'],
				'message'			=> $value['message'],
				'date_added'		=> $value['date_added'],
				'edit'       => $this->url->link('vendor/lts_mail/edit', 'user_token=' . $this->session->data['user_token'] . '&mail_id=' . $value['mail_id'], true)
			);
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('vendor/lts_mail', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['add'] = $this->url->link('vendor/lts_mail/add', 'user_token=' . $this->session->data['user_token'], true);

		$data['delete'] = $this->url->link('vendor/lts_mail/delete', 'user_token=' . $this->session->data['user_token'], true);

		$data['header'] 	 = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer']      = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('vendor/lts_mail_list', $data));
	}

	public function getForm() {
		if (isset($this->error['subject'])) {
			$data['error_subject'] = $this->error['subject'];
		} else {
			$data['error_subject'] = '';
		}

		if (isset($this->error['message'])) {
			$data['error_message'] = $this->error['message'];
		} else {
			$data['error_message'] = '';
		}

		if (isset($this->request->get['mail_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$mail_info = $this->model_vendor_lts_mail->getMail($this->request->get['mail_id']);
		}


		//print_r($mail_info);

		if (isset($this->request->post['too_id'])) {
			$data['too_id'] = $this->request->post['too_id'];
		} elseif (!empty($mail_info)) {
			$data['too_id'] = $mail_info['too_id'];
		} else {
			$data['too_id'] = '';
		}


		if (isset($this->request->post['subject'])) {
			$data['subject'] = $this->request->post['subject'];
		} elseif (!empty($mail_info)) {
			$data['subject'] = $mail_info['subject'];
		} else {
			$data['subject'] = '';
		}

		if (isset($this->request->post['message'])) {
			$data['message'] = $this->request->post['message'];
		} elseif (!empty($mail_info)) {
			$data['message'] = $mail_info['message'];
		} else {
			$data['message'] = '';
		}


		$too = $this->model_vendor_lts_mail->getToo();

		foreach ($too as $value) {
			$data['too'][] = array(
				'too_id'	=> $value['too_id'],
				'name'		=> $value['name']
			);
		}

		$data['cancel'] = $this->url->link('vendor/lts_mail', 'user_token=' . $this->session->data['user_token'], true);

		if (!isset($this->request->get['mail_id'])) {
			$data['action'] = $this->url->link('vendor/lts_mail/add', 'user_token=' . $this->session->data['user_token'], true);
		} else {
			$data['action'] = $this->url->link('vendor/lts_mail/edit', 'user_token=' . $this->session->data['user_token'] . '&mail_id=' . $this->request->get['mail_id'], true);
		}

		$data['header'] 	 = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer']      = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('vendor/lts_mail_form', $data));
	}

	public function validateForm() {
		if (!$this->user->hasPermission('modify', 'vendor/lts_mail')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['subject']) {
			$this->error['subject'] = $this->language->get('error_subject');
		}

		if (!$this->request->post['message']) {
			$this->error['message'] = $this->language->get('error_message');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/attribute')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

}        