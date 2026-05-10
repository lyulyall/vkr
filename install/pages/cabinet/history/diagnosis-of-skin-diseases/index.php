<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("История ИИ-диагностики кожных заболеваний");

global $USER;

$APPLICATION->IncludeComponent(
	"vdc.appointment:lk.skinanalyse.history",
	"",
	array(
		'AJAX_MODE' => 'Y',
		'IBLOCK_ID' => 94
	)
);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
