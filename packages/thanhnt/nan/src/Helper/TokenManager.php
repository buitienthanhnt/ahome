<?php

namespace Thanhnt\Nan\Helper;

require_once('vendor/autoload.php');

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

final class TokenManager
{
	const DEFAULT_TYPE = 'HS256';

	private $exp_time = 150 * 60;
	private $exp_time_refresh = 60 * 60 * 24 * 30;
	private $serect_key = 'laravel1.com';
    private $time_token_key = 'timeSecurityLaravel';

	protected $request;

	function __construct(Request $request)
	{
		$this->request = $request;
	}

	/**
	 * @param array|null $data
	 * @param int $iat
	 * @param int $nbf
	 * @param int $exp
	 * @param string $type
	 * @return array
	 */
	function getToken(array $data = null, int $iat = 0, int $nbf = 0, int $exp = 0, $type = self::DEFAULT_TYPE): array
	{
		$iat = $iat ?: time();
		$exp = $exp ?: $iat + $this->exp_time;
		try {
			$payload = array(
				'iss' => $data, // data cần mã hóa lưu trữ.
				'iat' => $iat,  // thời gian bắt đầu token.
				'nbf' => $nbf,  // thời gian token bắt đầu có thể decode(có hiệu lực so với iat).
				'exp' => $exp,  // thời gian token hết hạn.
				// 'uId' => $UiD
			);
			$jwt = JWT::encode($payload, $this->get_serect_key(), $type);
			return [
				'status' => true,
				'type' => $type,
				'value' => $jwt
			];
		} catch (\Throwable $th) {
			// echo ($th->getMessage());
		}
		return [
			'status' => false,
			'type' => $type,
			'value' => null
		];
	}

	/**
	 * @return array|null
	 */
	function getRefreshToken(array $data = null, array $config = null, $type = self::DEFAULT_TYPE)
	{
		$iat = $config['iat'] ?? time();
		$exp = $config['exp'] ?? $iat + $this->exp_time_refresh;
		try {
			$payload = array(
				'iss' => $data, // data cần mã hóa lưu trữ.
				'iat' => $iat,  // thời gian bắt đầu token.
				'nbf' => $config['nbf'] ?? null,  // thời gian token bắt đầu có thể decode(có hiệu lực so với iat).
				'exp' => $exp,  // thời gian token hết hạn.
				// 'uId' => $UiD
			);
			$jwt = JWT::encode($payload, $this->get_serect_key(), $type);
			return [
				'status' => true,
				'type' => $type,
				'value' => $jwt
			];
		} catch (\Throwable $th) {
			throw new Exception("Error Processing Request: " . $th->getMessage(), 1);
		}
		return null;
	}

	/**
	 * @param string $token
	 * @param string $type
	 * @return array
	 */
	function getTokenData(string $token = null, $type = self::DEFAULT_TYPE): array
	{
		$token = $token ?: $this->getTokenAuthor();
		if ($token) {
			try {
				$decode = JWT::decode($token, new Key($this->get_serect_key(), $type));
				if (is_object($decode) && $data = $decode->iss) {
					return (array) $decode;
					// return (array) $data;
				}
			} catch (\Throwable $th) {
			}
		}
		return [];
	}

	/**
	 * @return string
	 */
	public function get_serect_key(): string
	{
		return $this->serect_key;
	}

    function getTimeStartTokenData(string $token = null, $type = self::DEFAULT_TYPE) {
        $token = $token ?: $this->getTokenTimeStart();
		if ($token) {
			try {
				$decode = JWT::decode($token, new Key($this->time_token_key, $type));
				if (is_object($decode)) {
					return (array) $decode;
				}
			} catch (\Throwable $th) {}
		}
		return null;
    }

    public function getTokenTimeStart(): string
	{
		return $this->request->header('time-Token', '');
	}

	public function getTokenAuthor(): string
	{
		return $this->request->header('authorization', $this->defaultAuth());
	}

	function defaultAuth(): string
	{
		$headerData = apache_request_headers();
		if (isset($headerData['Authorization']) && !empty($headerData['Authorization'])) {
			return $headerData['Authorization'];
		}

		if (isset($headerData['authorization']) && !empty($headerData['authorization'])) {
			return $headerData['authorization'];
		}

		if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION']) && !empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
			return $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
		}

		return '';
	}
}
