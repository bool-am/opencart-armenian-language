<?php
namespace Opencart\Admin\Controller\Extension\BoolLanguageArmenian\Language;
class Armenian extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('extension/bool_language_armenian/language/armenian');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=language')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/bool_language_armenian/language/armenian', 'user_token=' . $this->session->data['user_token'])
		];

		$data['save'] = $this->url->link('extension/bool_language_armenian/language/armenian.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=language');

		$data['language_armenian_status'] = $this->config->get('language_armenian_status');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/bool_language_armenian/language/armenian', $data));
	}

	public function save(): void {
		$this->load->language('extension/bool_language_armenian/language/armenian');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/bool_language_armenian/language/armenian')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('language_armenian', $this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function install(): void {
		if ($this->user->hasPermission('modify', 'extension/language')) {
			$language_info = $this->model_localisation_language->getLanguageByCode('hy-am');

			if (!$language_info) {
				// Add language
				$language_data = [
					'name'       => 'Armenian',
					'code'       => 'hy-am',
					'locale'     => 'hy-AM,hy_AM.UTF-8,hy_AM,hy-am,armenian',
					'extension'  => 'bool_language_armenian',
					'status'     => 1,
					'sort_order' => 1
				];

				$this->load->model('localisation/language');

				$this->model_localisation_language->addLanguage($language_data);
			} else {
				// Edit language
				$this->load->model('localisation/language');

				$this->model_localisation_language->editLanguage($language_info['language_id'], $language_info + ['extension' => 'bool_language_armenian']);
			}
		}
	}

	public function uninstall(): void {
		if ($this->user->hasPermission('modify', 'extension/language')) {
			$this->load->model('localisation/language');

			$language_info = $this->model_localisation_language->getLanguageByCode('hy-am');

			if ($language_info) {
				$this->model_localisation_language->deleteLanguage($language_info['language_id']);
			}
		}
	}
}
