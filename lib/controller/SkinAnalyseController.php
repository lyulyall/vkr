<?php

namespace med\custom\controller;


use Exception;
use med\custom\service\SkinAnalyseService;


class SkinAnalyseController {
	public function __construct(protected SkinAnalyseService $service) { }

	/**
	 * @throws Exception
	 */
	public function add(int $userId, array $file, array $responseData): int {
		return $this->service->add(
			$userId,
			$file,
			$responseData
		);
	}

}