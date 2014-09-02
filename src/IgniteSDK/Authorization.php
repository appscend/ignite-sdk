<?php

namespace IgniteSDK;


class Authorization {

	private static $appID = '';
	private static $apiKey = '';

	public static function set($appID, $apiKey) {
		self::$appID = $appID;
		self::$apiKey = $apiKey;
	}

	public static function getAppId() {
		return self::$appID;
	}

	public static function getApiKey() {
		return self::$apiKey;
	}
} 