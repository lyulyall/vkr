<?php

namespace vdc\custom\service;


use Exception;
use vdc\custom\repository\SymptomAnalyseRepository;


class SymptomAnalyzeService {
	public function __construct(protected SymptomAnalyseRepository $repository) {
	}

	/**
	 * @throws Exception
	 */
	public function add(int $userId, string $requestText, array $responseData): int {
		$responseJson = json_encode(
			$responseData,
			JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
		);

		if ($responseJson === false) {
			throw new Exception('Не удалось сериализовать ответ нейросети');
		}

		return $this->repository->addByData(
			$userId,
			$requestText,
			$responseJson
		);
	}

	public function getUserHistory(int $userId, int $limit = 20, int $offset = 0): array {
		return $this->repository->getByUserId($userId, $limit, $offset);
	}

	public function getUserHistoryCount(int $userId): int {
		return $this->repository->getCountByUserId($userId);
	}

	public function getById(int $id): ?array {
		return $this->repository->getById($id);
	}
}