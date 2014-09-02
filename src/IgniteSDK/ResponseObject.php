<?php
namespace IgniteSDK;


abstract class ResponseObject implements \ArrayAccess {

	protected static $curl = null;

	protected $content = [];

	public function __construct($values) {
		$this->content = $values;
	}

	protected static function initCurl($url) {
		if (self::$curl === null) {
			self::$curl = curl_init($url);
			curl_setopt_array(self::$curl, [
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_PROTOCOLS => CURLPROTO_HTTPS,
				CURLOPT_HTTPHEADER => [
					'Authorization: Token token="'.openssl_digest(Authorization::getApiKey().time(), 'sha512').'"'
				],
				CURLOPT_RETURNTRANSFER => true
			]);
		} else {
			curl_setopt_array(self::$curl, [
				CURLOPT_HTTPHEADER => [
					'Authorization: Token token="'.openssl_digest(Authorization::getApiKey().time(), 'sha512').'"'
				],
				CURLOPT_URL => $url
			]);
		}

	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Whether a offset exists
	 *
	 * @link http://php.net/manual/en/arrayaccess.offsetexists.php
	 * @param mixed $offset <p>
	 * An offset to check for.
	 * </p>
	 * @return boolean true on success or false on failure.
	 * </p>
	 * <p>
	 * The return value will be casted to boolean if non-boolean was returned.
	 */
	public function offsetExists($offset) {
		return isset($this->content[$offset]);
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Offset to retrieve
	 *
	 * @link http://php.net/manual/en/arrayaccess.offsetget.php
	 * @param mixed $offset <p>
	 * The offset to retrieve.
	 * </p>
	 * @return mixed Can return all value types.
	 */
	public function offsetGet($offset) {
		return $this->content[$offset];
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Offset to set
	 *
	 * @link http://php.net/manual/en/arrayaccess.offsetset.php
	 * @param mixed $offset <p>
	 * The offset to assign the value to.
	 * </p>
	 * @param mixed $value <p>
	 * The value to set.
	 * </p>
	 * @return void
	 */
	public function offsetSet($offset, $value) {
		return ;
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Offset to unset
	 *
	 * @link http://php.net/manual/en/arrayaccess.offsetunset.php
	 * @param mixed $offset <p>
	 * The offset to unset.
	 * </p>
	 * @return void
	 */
	public function offsetUnset($offset) {
		return ;
	}

} 