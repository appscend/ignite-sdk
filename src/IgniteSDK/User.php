<?php
namespace IgniteSDK;


class User extends ResponseObject {

	const API_ENDPOINT = 'https://dev.appscend.net/api/v2/user/';

	public static $cached = false;

	public function setFields($fields, $customFields = []) {
		$url = self::API_ENDPOINT.'setUser?appId='.Authorization::getAppId().'&timestamp='.time();
		if (self::$cached === false)
			$url .= '&expires_in=2';

		self::initCurl($url);
		if (!empty($customFields))
			$fields = array_merge($fields, ['customFields' => json_encode($customFields)]);
		$fields = array_merge($fields, ['idAppUser' => $this->content['idAppUser']]);
		curl_setopt(self::$curl, CURLOPT_POSTFIELDS, $fields);

		$result = curl_exec(self::$curl);
		$result = json_decode($result, true);
		if (curl_getinfo(self::$curl, CURLINFO_HTTP_CODE) >= 400)
			return null;
		if ($result['status'] >= 400)
			throw new \Exception($result['content']);

		if (!empty($customFields))
			$this->content['custom'] = is_array($this->content['custom']) ? array_merge($this->content['custom'], $customFields) : $this->content['custom'];

		$this->content = array_merge($this->content, $fields);
	}

	public function notify($text, $igniteAction = null, $param = null) {
		self::initCurl(self::API_ENDPOINT.'notify?appId='.Authorization::getAppId().'&timestamp='.time());
		$fields = ['idAppUser' => $this->content['idAppUser'], 'text' => $text];
		if ($igniteAction !== null) {
			$fields['ignite_action'] = $igniteAction;
			$fields['parameter'] = $param;
		}
		curl_setopt(self::$curl, CURLOPT_POSTFIELDS, $fields);

		$result = curl_exec(self::$curl);
		$result = json_decode($result, true);
		if (curl_getinfo(self::$curl, CURLINFO_HTTP_CODE) >= 400)
			return null;
		if ($result['status'] >= 400)
			throw new \Exception($result['content']);

		return $result;
	}

	public function relateTo($itemId, $relId) {
		self::relateUser($this->content['idAppUser'], $itemId, $relId);
	}

	public function unrelateTo($itemId, $relId) {
		self::unrelateUser($this->content['idAppUser'], $itemId, $relId);
	}

	public static function login($email, $pass, $appscendLogin = false) {
		$url = self::API_ENDPOINT.'login?appId='.Authorization::getAppId().'&timestamp='.time();

		if (self::$cached === false)
			$url .= '&no_cache='.microtime(true);
		if ($appscendLogin === true)
			$url .= '&appscendLogin=1';

		self::initCurl($url);
		curl_setopt(self::$curl, CURLOPT_POSTFIELDS, ['email' => $email, 'password' => $pass, 'udid' => $_POST['udid']]);

		$result = curl_exec(self::$curl);

		$result = json_decode($result, true);
		if (curl_getinfo(self::$curl, CURLINFO_HTTP_CODE) >= 400)
			return null;
		if ($result['status'] >= 400) {
			throw new \Exception($result['content']);
			return false;
		}

		return $result['content'];
	}

	public static function search(array $fields, $limit = 200, $offset = 0, $userLocation = null, $radius = null) {
		$url = self::API_ENDPOINT.'search?appId='.Authorization::getAppId().'&timestamp='.time();
		if (self::$cached === false)
			$url .= '&no_cache='.microtime(true);

		self::initCurl($url);
		$fields = array_merge($fields, ['limit' => $limit, 'offset' => $offset]);
		if ($userLocation) {
			$fields['userLocation'] = $userLocation;
			$fields['radius'] = $radius;
		}
		curl_setopt(self::$curl, CURLOPT_POSTFIELDS, $fields);

		$result = curl_exec(self::$curl);
		$result = json_decode($result, true);

		if (curl_getinfo(self::$curl, CURLINFO_HTTP_CODE) >= 400)
			throw new \Exception('HTTP Status code error: '.curl_getinfo(self::$curl, CURLINFO_HTTP_CODE));
		if ($result['status'] >= 400)
			throw new \Exception($result['content']);

		$objects = [];

		foreach ($result['content'] as $i) {
			$objects[] = new self($i);
		}

		return $objects;
	}

	public static function relateUser($id, $itemId, $relId) {
		self::initCurl(self::API_ENDPOINT.'relateUser?appId='.Authorization::getAppId().'&timestamp='.time());
		curl_setopt(self::$curl, CURLOPT_POSTFIELDS, ['idAppUser' => $id, 'itemId' => $itemId, 'rtld' => $relId]);

		$result = curl_exec(self::$curl);
		$result = json_decode($result, true);
		if (curl_getinfo(self::$curl, CURLINFO_HTTP_CODE) >= 400)
			return null;
		if ($result['status'] >= 400)
			throw new \Exception($result['content']);
	}

	public static function unrelateUser($id, $itemId, $relId) {
		self::initCurl(self::API_ENDPOINT.'unrelateUser?appId='.Authorization::getAppId().'&timestamp='.time());
		curl_setopt(self::$curl, CURLOPT_POSTFIELDS, ['idAppUser' => $id, 'itemId' => $itemId, 'rtld' => $relId]);

		$result = curl_exec(self::$curl);
		$result = json_decode($result, true);
		if (curl_getinfo(self::$curl, CURLINFO_HTTP_CODE) >= 400)
			return null;
		if ($result['status'] >= 400)
			throw new \Exception($result['content']);
	}

	public static function notifyAll($text, $igniteAction = null, $param = null) {
		self::initCurl(self::API_ENDPOINT.'notifyAllUsers?appId='.Authorization::getAppId().'&timestamp='.time());
		$fields = ['text' => $text];
		if ($igniteAction !== null) {
			$fields['ignite_action'] = $igniteAction;
			$fields['parameter'] = $param;
		}
		curl_setopt(self::$curl, CURLOPT_POSTFIELDS, $fields);

		$result = curl_exec(self::$curl);
		$result = json_decode($result, true);
		if (curl_getinfo(self::$curl, CURLINFO_HTTP_CODE) >= 400)
			return null;
		if ($result['status'] >= 400)
			throw new \Exception($result['content']);
	}

	public static function register($email, $pass, $udid) {
		self::initCurl(self::API_ENDPOINT.'registerUser?appId='.Authorization::getAppId().'&timestamp='.time());
		curl_setopt(self::$curl, CURLOPT_POSTFIELDS, ['email' => $email, 'pass' => $pass, 'udid' => $udid]);

		$result = curl_exec(self::$curl);
		$result = json_decode($result, true);
		if (curl_getinfo(self::$curl, CURLINFO_HTTP_CODE) >= 400)
			return null;
		if ($result['status'] >= 400)
			throw new \Exception($result['content']);
	}

	public static function getUsers($limit = 300, $offset = 0) {
		self::initCurl(self::API_ENDPOINT.'getUsers?appId='.Authorization::getAppId().'&timestamp='.time());
		curl_setopt(self::$curl, CURLOPT_POSTFIELDS, ['limit' => $limit, 'startFrom' => $offset]);

		$result = curl_exec(self::$curl);
		$result = json_decode($result, true);
		if (curl_getinfo(self::$curl, CURLINFO_HTTP_CODE) >= 400)
			return null;
		if ($result['status'] >= 400)
			throw new \Exception($result['content']);

		$objects = [];

		foreach ($result['content'] as $i) {
			$objects[] = new self($i);
		}

		return $objects;
	}

	public static function get($id, $udid = false) {
		$url = self::API_ENDPOINT.'getUser?appId='.Authorization::getAppId().'&timestamp='.time();

		if (self::$cached === false) {
			$url .= '&no_cache='.microtime(true);
		}

		self::initCurl($url);
		$fields = $udid == true ? ['udid' => $id] : ['idAppUser' => $id];
		curl_setopt(self::$curl, CURLOPT_POSTFIELDS, $fields);

		$result = curl_exec(self::$curl);
		$result = json_decode($result, true);
		if (curl_getinfo(self::$curl, CURLINFO_HTTP_CODE) >= 400) {
			return null;
		}
		if ($result['status'] >= 400)
			throw new \Exception($result['content']);

		if (empty($result['content']))
			return [];

		return new self($result['content']);
	}

	public static function create($udid, $commonFields = [], $customFields = [], $force = false, $noDuplicate = false) {
		self::initCurl(self::API_ENDPOINT.'createUser?appId='.Authorization::getAppId().'&timestamp='.time());
		$fields = ['udid' => $udid];

		if (!empty($customFields))
			$fields['customFields'] = json_encode($customFields);

		$fields = array_merge($fields, $commonFields);

		if ($force) $fields['force'] = 1;
		if ($noDuplicate) $fields['noDuplicateEmail'] = 1;

		curl_setopt(self::$curl, CURLOPT_POSTFIELDS, $fields);

		$result = curl_exec(self::$curl);
		$result = json_decode($result, true);
		if (curl_getinfo(self::$curl, CURLINFO_HTTP_CODE) >= 400) {
			return null;
		}
		if ($result['status'] >= 400)
			throw new \Exception($result['content']);

		$fields['customFields'] = $customFields;

		return new self($fields);
	}

} 