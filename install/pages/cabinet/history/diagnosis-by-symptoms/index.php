<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("История анализа симптомов");

global $USER;

$APPLICATION->IncludeComponent(
	"vdc.appointment:lk.symptom.history",
	"",
	array(
		'AJAX_MODE' => 'Y'
	)
);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
