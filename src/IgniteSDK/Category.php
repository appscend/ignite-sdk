<?php
namespace IgniteSDK;


class Category extends ResponseObject {

	const API_ENDPOINT = 'https://dev.appscend.net/api/v2/category/';

	public function delete() {
		self::deleteById($this->content['categoryId']);
	}

	public function putMetadata($data) {
		self::initCurl(self::API_ENDPOINT.'putMetadata?appId='.Authorization::getAppId().'&timestamp='.time());
		curl_setopt(self::$curl, CURLOPT_POSTFIELDS, ['categoryId' => $this->content['categoryId']]);

		$result = curl_exec(self::$curl);
		$result = json_decode($result, true);
		if (curl_getinfo(self::$curl, CURLINFO_HTTP_CODE) >= 400) {
			return null;
		}
		if ($result['status'] >= 400)
			throw new \Exception($result['content']);

		$this->content['metadata'] = $data;
	}

	public function getAllTags() {
		return self::getAllTagsForCategory($this->content['categoryId']);
	}

	public function addComment($text) {
		self::initCurl(self::API_ENDPOINT.'addCategoryComment?appId='.Authorization::getAppId().'&timestamp='.time());
		curl_setopt(self::$curl, CURLOPT_POSTFIELDS, ['categoryId' => $this->content['categoryId'], 'udid' => $_POST['udid'], 'text' => $text]);

		$result = curl_exec(self::$curl);
		$result = json_decode($result, true);
		if (curl_getinfo(self::$curl, CURLINFO_HTTP_CODE) >= 400) {
			return null;
		}
		if ($result['status'] >= 400)
			throw new \Exception($result['content']);

		return $result['content'];
	}

	public function getComments($extended = 0) {
		self::initCurl(self::API_ENDPOINT.'getCategoryComments?appId='.Authorization::getAppId().'&timestamp='.time());
		curl_setopt(self::$curl, CURLOPT_POSTFIELDS, ['catId' => $this->content['categoryId'], 'extended' => $extended]);

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
		self::initCurl(self::API_ENDPOINT.'addCategoryRating?appId='.Authorization::getAppId().'&timestamp='.time());
		curl_setopt(self::$curl, CURLOPT_POSTFIELDS, ['categoryId' => $this->content['categoryId'], 'udid' => $_POST['udid'], 'rating' => $r]);

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
		self::initCurl(self::API_ENDPOINT.'getCategoryRatings?appId='.Authorization::getAppId().'&timestamp='.time());
		curl_setopt(self::$curl, CURLOPT_POSTFIELDS, ['categoryId' => $this->content['categoryId']]);

		$result = curl_exec(self::$curl);
		$result = json_decode($result, true);
		if (curl_getinfo(self::$curl, CURLINFO_HTTP_CODE) >= 400) {
			return null;
		}
		if ($result['status'] >= 400)
			throw new \Exception($result['content']);

		return $result['content'];
	}

	public static function searchSubcategoriesByLevel($id, $level, $search) {
		self::initCurl(self::API_ENDPOINT.'searchSubcategoriesByLevel?appId='.Authorization::getAppId().'&timestamp='.time());
		curl_setopt(self::$curl, CURLOPT_POSTFIELDS, ['categoryId' => $id, 'level' => $level, 'search' => $search]);

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

	public static function get($id) {
		self::initCurl(self::API_ENDPOINT.'getCategory?appId='.Authorization::getAppId().'&timestamp='.time());
		curl_setopt(self::$curl, CURLOPT_POSTFIELDS, ['categoryId' => $id]);

		$result = curl_exec(self::$curl);
		$result = json_decode($result, true);
		if (curl_getinfo(self::$curl, CURLINFO_HTTP_CODE) >= 400) {
			return null;
		}
		if ($result['status'] >= 400)
			throw new \Exception($result['content']);

		return new self($result['content']);
	}

	public static function getAllTagsForCategory($id) {
		self::initCurl(self::API_ENDPOINT.'getAllTagsForCategory?appId='.Authorization::getAppId().'&timestamp='.time());
		curl_setopt(self::$curl, CURLOPT_POSTFIELDS, ['categoryId' => $id]);

		$result = curl_exec(self::$curl);
		$result = json_decode($result, true);
		if (curl_getinfo(self::$curl, CURLINFO_HTTP_CODE) >= 400) {
			return null;
		}
		if ($result['status'] >= 400)
			throw new \Exception($result['content']);

		return $result['content'];

	}

	public static function getCategoriesForParent($parentID) {
		self::initCurl(self::API_ENDPOINT.'getCategoriesForParent?appId='.Authorization::getAppId().'&timestamp='.time());
		curl_setopt(self::$curl, CURLOPT_POSTFIELDS, ['parentId' => $parentID]);

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

	public static function getCategoriesForTag($tag) {
		self::initCurl(self::API_ENDPOINT.'getCategoriesForTag?appId='.Authorization::getAppId().'&timestamp='.time());
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

	public static function deleteById($id) {
		self::initCurl(self::API_ENDPOINT.'deleteCategory?appId='.Authorization::getAppId().'&timestamp='.time());
		curl_setopt(self::$curl, CURLOPT_POSTFIELDS, ['categoryId' => $id]);

		$result = curl_exec(self::$curl);
		$result = json_decode($result, true);
		if (curl_getinfo(self::$curl, CURLINFO_HTTP_CODE) >= 400) {
			return null;
		}
		if ($result['status'] >= 400)
			throw new \Exception($result['content']);
	}

	/**
	 * @return Category[]
	 * @throws \Exception
	 */
	public static function getAll() {
		self::initCurl(self::API_ENDPOINT.'getCategories?appId='.Authorization::getAppId().'&timestamp='.time());

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

	public static function addCategory($content) {
		self::initCurl(self::API_ENDPOINT.'putCategory?appId='.Authorization::getAppId().'&timestamp='.time());
		curl_setopt(self::$curl, CURLOPT_POSTFIELDS, $content);

		$result = curl_exec(self::$curl);
		$result = json_decode($result, true);
		if (curl_getinfo(self::$curl, CURLINFO_HTTP_CODE) >= 400)
			return null;
		if ($result['status'] >= 400)
			throw new \Exception($result['content']);

		return new self($content);
	}

} 