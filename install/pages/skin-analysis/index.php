<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Диагностика кожных заболеваний");

global $USER;


$APPLICATION->IncludeComponent(
	"vdc.appointment:skinanalyse.form",
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