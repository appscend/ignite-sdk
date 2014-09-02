<?php
namespace IgniteSDK;


class CloudServices extends ResponseObject {

	const API_ENDPOINT = 'https://dev.appscend.net/api/v2/app/';

	public function __construct() {}

	public static function sendMail($to, $subject, $body) {
		self::initCurl(self::API_ENDPOINT.'sendMail?appId='.Authorization::getAppId().'&timestamp='.time());
		curl_setopt(self::$curl, CURLOPT_POSTFIELDS, ['to' => $to, 'subject' => $subject, 'contents' => $body]);

		$result = curl_exec(self::$curl);
		$result = json_decode($result, true);
		if (curl_getinfo(self::$curl, CURLINFO_HTTP_CODE) >= 400)
			return null;
		if ($result['status'] >= 400)
			throw new \Exception($result['content']);
	}

} 