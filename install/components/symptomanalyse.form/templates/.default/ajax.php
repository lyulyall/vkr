<?php
define('NO_KEEP_STATISTIC', true);
define('NO_AGENT_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', true);

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

use Bitrix\Main\Context;
use Bitrix\Main\Loader;
use Bitrix\Main\Web\Json;
use Bitrix\Highloadblock\HighloadBlockTable;
use med\appointment\helpers\HLBlockHelper;
use med\appointment\entity\symptomHistory\SymptomHistoryRepository;

header('Content-Type: application/json; charset=utf-8');

global $USER;

try {
	if (!check_bitrix_sessid()) {
		throw new Exception('Неверная сессия');
	}

	if (!$USER->IsAuthorized()) {
		throw new Exception('Пользователь не авторизован');
	}

	if (!Loader::includeModule('highloadblock')) {
		throw new Exception('Не удалось подключить модуль highloadblock');
	}

	if (!Loader::includeModule('med.appointment')) {
		throw new Exception('Не удалось подключить модуль med.appointment');
	}

	$request = Context::getCurrent()->getRequest();
	$action = (string)$request->getPost('action');

	if ($action !== 'save_symptom_history') {
		throw new Exception('Неизвестное действие');
	}

	$symptoms = trim((string)$request->getPost('symptoms'));
	$responseJson = trim((string)$request->getPost('response'));

	if ($symptoms === '') {
		throw new Exception('Пустое описание симптомов');
	}

	if ($responseJson === '') {
		throw new Exception('Пустой ответ нейросети');
	}

	try {
		$decoded = Json::decode($responseJson);
		$responseJson = Json::encode($decoded, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	} catch (Throwable $e) {
		throw new Exception('Некорректный JSON ответа');
	}

	$helper = new HLBlockHelper('SymptomHistory', new HighloadBlockTable());
	$repository = new SymptomHistoryRepository($helper);

	$id = $repository->addByData(
		(int)$USER->GetID(),
		$symptoms,
		$responseJson
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