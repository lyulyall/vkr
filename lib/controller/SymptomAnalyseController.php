<?php

namespace vdc\custom\controller;


use Exception;
use vdc\custom\service\SymptomAnalyzeService;


class SymptomAnalyseController {
	public function __construct(protected SymptomAnalyzeService $service) { }

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