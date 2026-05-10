<?php

define('NO_KEEP_STATISTIC', true);
define('NO_AGENT_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', true);

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

use Bitrix\Main\Context;
use Bitrix\Main\Loader;
use Bitrix\Main\Web\Json;
use med\appointment\entity\skinHistory\SkinHistoryRepository;
use med\appointment\entity\skinHistory\SkinHistoryService;


header('Content-Type: application/json; charset=utf-8');

global $USER;

try {
	if (!check_bitrix_sessid()) {
		throw new Exception('Неверная сессия');
	}

	if (!$USER->IsAuthorized()) {
		throw new Exception('Пользователь не авторизован');
	}

	if (!Loader::includeModule('iblock')) {
		throw new Exception('Не удалось подключить модуль iblock');
	}

	if (!Loader::includeModule('med.appointment')) {
		throw new Exception('Не удалось подключить модуль med.appointment');
	}

	$request = Context::getCurrent()->getRequest();
	$action = (string) $request->getPost('action');

	if ($action !== 'save_skin_history') {
		throw new Exception('Неизвестное действие');
	}

	$responseJson = trim((string) $request->getPost('response'));
	$file = $request->getFile('photo');
	$iblockId = (int) $request->getPost('iblock_id');

	if ($responseJson === '') {
		throw new Exception('Пустой ответ нейросети');
	}

	if ($iblockId <= 0) {
		throw new Exception('Не задан ID инфоблока истории диагностики кожи');
	}

	if (empty($file) || empty($file['tmp_name'])) {
		throw new Exception('Не передан файл изображения');
	}

	try {
		$responseData = Json::decode($responseJson);
	} catch (Throwable $e) {
		throw new Exception('Некорректный JSON ответа');
	}

	$repository = new SkinHistoryRepository($iblockId);
	$service = new SkinHistoryService($repository);

	$id = $service->add(
		(int) $USER->GetID(),
		$file,
		$responseData
	);

	echo Json::encode(
		array(
			'success' => true,
			'id' => $id,
		),
		JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
	);
} catch (Throwable $e) {
	http_response_code(400);

	echo Json::encode(
		array(
			'success' => false,
			'error' => $e->getMessage(),
		),
		JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
	);
}