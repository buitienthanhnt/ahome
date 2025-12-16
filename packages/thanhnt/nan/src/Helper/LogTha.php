<?php

namespace Thanhnt\Nan\Helper;

use Illuminate\Log\LogManager;
use Illuminate\Support\Facades\Log;

class LogTha
{
	const LOG_PATH = "logs/tha/";

	const EVENT_TYPE = "event";
	const VIEW_COUNT = "viewCount";
	const SOURCE_URL_TYPE = "remoteSource";
	const ERROR_TYPE = 'error';
	const FIREBASE_TYPE = 'firebase';

	/**
	 * @var \Illuminate\Http\Request
	 */
	protected $request;

	/**
	 * @var \Illuminate\Log\LogManager
	 */
	protected $logger;

	public function __construct(
		\Illuminate\Http\Request $request,
		LogManager $logger
	) {
		$this->request = $request;
		$this->logger = $logger;
	}

	public function logEvent(string $type, string $message, array $params = []): void
	{
		$this->log(self::EVENT_TYPE, ...func_get_args());
	}

	public function logViewCount(string $type, string $message, array $params = []): void
	{
		$this->log(self::VIEW_COUNT, ...func_get_args());
	}

	public function logRemoteSource(string $type, string $message, array $params = []): void
	{
		$this->log(self::SOURCE_URL_TYPE, ...func_get_args());
	}

	function logError(string $type, string $message, array $params = []): void
	{
		$this->log(self::ERROR_TYPE, ...func_get_args());
	}

	function logFirebase(string $type, string $message, array $params = []): void
	{
		$this->log(self::FIREBASE_TYPE, ...func_get_args());
	}

	protected function log(string $logType, string $type = 'info', string $message = '', array $params = []): void
	{
		try {
			$channel = $this->logger->build([
				'driver' => 'single',
				'path' => storage_path(self::LOG_PATH . $logType . ".log"),
			]);
			$this->logger->stack([$channel])->{$type}("LogEvent:  " . $message, $params);
		} catch (\Throwable $throwable) {
			Log::alert($throwable->getMessage());
		}
		// $logger = $this->logger->{$type}("LogEvent:".$message, $params);
	}
}
