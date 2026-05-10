<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Анализ состояния по симптомам");

global $USER;

$APPLICATION->IncludeComponent(
	"vdc.appointment:symptomanalyse.form",
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600000",
		"USER_AUTHORIZED" => $USER->IsAuthorized(),
	),
	false
);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");