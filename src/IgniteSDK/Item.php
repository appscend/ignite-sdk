<?php

namespace IgniteSDK;

class Item extends ResponseObject {

	const API_ENDPOINT = 'https://dev.appscend.net/api/v2/item/';

	public static $cached = false;

	public function edit(array $content) {
		self::initCurl(self::API_ENDPOINT.'editItem?appId='.Authorization::getAppId().'&timestamp='.time());
		$content['itemId'] = $this->content['itemId'];
		curl_setopt(self::$curl, CURLOPT_POSTFIELDS, $content);

		$result = curl_exec(self::$curl);
		$result = json_decode($result, true);
		error_log(print_r($result, true));
		if (curl_getinfo(self::$curl, CURLINFO_HTTP_CODE) >= 400) {
			return null;
		}
		if ($result['status'] >= 400)
			throw new \Exception($result['content']);

		$this->content = array_merge($this->content, $content);
	}

	public function relateTo($item, $relID, $q1 = null, $q2 = null) {
		return self::relateItems($this->content['itemId'], $item, $relID, $q1, $q2);
	}

	public function addComment($text) {
		self::initCurl(self::API_ENDPOINT.'addComment?appId='.Authorization::getAppId().'&timestamp='.time());
		curl_setopt(self::$curl, CURLOPT_POSTFIELDS, ['itemId' => $this->content['itemId'], 'udid' => $_POST['udid'], 'text' => $text]);

		$result = curl_exec(self::$curl);
		$result = json_decode($result, true);
		if (curl_getinfo(self::$curl, CURLINFO_HTTP_CODE) >= 400) {
			return null;
		}
		if ($result['status'] >= 400)
			throw new \Exception($result['content']);

		return $result['content'];
	}

	public function delete() {
		self::deleteById($this->content['itemId']);
	}

	public function getComments() {
		self::initCurl(self::API_ENDPOINT.'getComments?appId='.Authorization::getAppId().'&timestamp='.time());
		curl_setopt(self::$curl, CURLOPT_POSTFIELDS, ['itemId' => $this->content['itemId']]);

		$result = curl_exec(self::$curl);
		$result = json_decode($result, true);
		if (curl_getinfo(self::$curl, CURLINFO_HTTP_CODE) >= 400) {
			return null;
		}
		if ($result['status'] >= 400)
			throw new \Exception($result['content']);

		return $result['content'];
	}

	public function addRating($r) {
		self::initCurl(self::API_ENDPOINT.'addRating?appId='.Authorization::getAppId().'&timestamp='.time());
		curl_setopt(self::$curl, CURLOPT_POSTFIELDS, ['itemId' => $this->content['itemId'], 'udid' => $_POST['udid'], 'rating' => $r]);

		$result = curl_exec(self::$curl);
		$result = json_decode($result, true);
		if (curl_getinfo(self::$curl, CURLINFO_HTTP_CODE) >= 400) {
			return null;
		}
		if ($result['status'] >= 400)
			throw new \Exception($result['content']);

		return $result['content'];
	}

	public function getRatings() {
		self::initCurl(self::API_ENDPOINT.'getRatings?appId='.Authorization::getAppId().'&timestamp='.time());
		curl_setopt(self::$curl, CURLOPT_POSTFIELDS, ['itemId' => $this->content['itemId']]);

		$result = curl_exec(self::$curl);
		$result = json_decode($result, true);
		if (curl_getinfo(self::$curl, CURLINFO_HTTP_CODE) >= 400) {
			return null;
		}
		if ($result['status'] >= 400)
			throw new \Exception($result['content']);

		return $result['content'];
	}

	public static function getAllItemsByRating() {
		self::initCurl(self::API_ENDPOINT.'getAllItemsByRating?appId='.Authorization::getAppId().'&timestamp='.time());

		$result = curl_exec(self::$curl);
		$result = json_decode($result, true);
		if (curl_getinfo(self::$curl, CURLINFO_HTTP_CODE) >= 400) {
			return null;
		}
		if ($result['status'] >= 400)
			throw new \Exception($result['content']);

		$objects = [];

		foreach ($result['content'] as $i) {
			$objects[] = new self($i);
		}

		return $objects;
	}

	public static function deleteById($id) {
		self::initCurl(self::API_ENDPOINT.'deleteItem?appId='.Authorization::getAppId().'&timestamp='.time());
		curl_setopt(self::$curl, CURLOPT_POSTFIELDS, ['itemId' => $id]);

		$result = curl_exec(self::$curl);
		$result = json_decode($result, true);
		if (curl_getinfo(self::$curl, CURLINFO_HTTP_CODE) >= 400) {
			return null;
		}
		if ($result['status'] >= 400)
			throw new \Exception($result['content']);
	}

	public static function relateItems($item1, $item2, $relID, $q1 = null, $q2 = null) {
		self::initCurl(self::API_ENDPOINT.'relateItem?appId='.Authorization::getAppId().'&timestamp='.time());
		curl_setopt(self::$curl, CURLOPT_POSTFIELDS, ['item1' => $item1, 'item2' => $item2, 'rtld' => $relID,
			'qualifier' => $q1, 'qualifier2' => $q2]);

		$result = curl_exec(self::$curl);
		$result = json_decode($result, true);
		if (curl_getinfo(self::$curl, CURLINFO_HTTP_CODE) >= 400) {
			return null;
		}
		if ($result['status'] >= 400)
			throw new \Exception($result['content']);

		return $result;
	}

	public static function createRelation($name) {
		self::initCurl(self::API_ENDPOINT.'createRelation?appId='.Authorization::getAppId().'&timestamp='.time());
		curl_setopt(self::$curl, CURLOPT_POSTFIELDS, ['name' => $name]);

		$result = curl_exec(self::$curl);
		$result = json_decode($result, true);
		if (curl_getinfo(self::$curl, CURLINFO_HTTP_CODE) >= 400) {
			return null;
		}
		if ($result['status'] >= 400)
			throw new \Exception($result['content']);

		return $result;
	}

	public static function addItem(array $content) {
		self::initCurl(self::API_ENDPOINT.'putItem?appId='.Authorization::getAppId().'&timestamp='.time());
		curl_setopt(self::$curl, CURLOPT_POSTFIELDS, $content);

		$result = curl_exec(self::$curl);
		$result = json_decode($result, true);
		if (curl_getinfo(self::$curl, CURLINFO_HTTP_CODE) >= 400) {
			return null;
		}
		if ($result['status'] >= 400)
			throw new \Exception($result['content']);

		return new self($content);
	}

	public static function get($id) {
		$url = self::API_ENDPOINT.'getItem?appId='.Authorization::getAppId().'&timestamp='.time();
		if (self::$cached == false)
			$url .= '&no_cache='.microtime(true);

		self::initCurl($url);
		curl_setopt(self::$curl, CURLOPT_POSTFIELDS, ['itemId' => $id]);

		$result = curl_exec(self::$curl);
		$result = json_decode($result, true);
		if (curl_getinfo(self::$curl, CURLINFO_HTTP_CODE) >= 400) {
			return null;
		}
		if ($result['status'] >= 400)
			throw new \Exception($result['content']);

		return new self($result['content']);
	}

	public static function getCategoryItems($categoryId, array $params) {
		$url = self::API_ENDPOINT.'getItems?appId='.Authorization::getAppId().'&timestamp='.time();

		if (self::$cached == false)
			$url .= '&no_cache='.microtime(true);

		self::initCurl($url);
		$params['categoryId'] = $categoryId;
		curl_setopt(self::$curl, CURLOPT_POSTFIELDS, $params);

		$result = curl_exec(self::$curl);
		$result = json_decode($result, true);
		if (curl_getinfo(self::$curl, CURLINFO_HTTP_CODE) >= 400)
			throw new \Exception('Error HTTP Status code: '.curl_getinfo(self::$curl, CURLINFO_HTTP_CODE));
		if ($result['status'] >= 400)
			throw new \Exception($result['content']);

		$objects = [];

		foreach ($result['content'] as $i) {
			$objects[] = new self($i);
		}

		return $objects;
	}

	public static function getItemsForTag($tag) {
		self::initCurl(self::API_ENDPOINT.'getItemsForTag?appId='.Authorization::getAppId().'&timestamp='.time());
		curl_setopt(self::$curl, CURLOPT_POSTFIELDS, ['tag' => $tag]);

		$result = curl_exec(self::$curl);
		$result = json_decode($result, true);
		if (curl_getinfo(self::$curl, CURLINFO_HTTP_CODE) >= 400) {
			return null;
		}
		if ($result['status'] >= 400)
			throw new \Exception($result['content']);

		$objects = [];

		foreach ($result['content'] as $i) {
			$objects[] = new self($i);
		}

		return $objects;
	}

	public static function getAllTags() {
		self::initCurl(self::API_ENDPOINT.'getAllTags?appId='.Authorization::getAppId().'&timestamp='.time());

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

}