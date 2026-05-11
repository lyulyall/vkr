<?php

use Bitrix\Main\Context;
use Bitrix\Main\Text\HtmlFilter;
use Bitrix\Main\Web\Uri;


/**
 * @var string $mid
 */

global $APPLICATION;
global $USER;

$tabs = array(
    array(
        'DIV' => 'integration',
        'TAB' => 'Интеграции',
        'TITLE' => 'Интеграции',
    )
);

$options = array(
    'integration' => array(
        '1C',
        array(
            'URL_FOR_GET_FREE_TIME_FROM_1C',
            'Адрес для получения слотов',
            '',
            array('text')
        ),
        array(
            'URL_FOR_WRITE_RECEPTION',
            'Адрес для отправки данных о записи',
            '',
            array('text')
        ),
        array(
            'PATH_TO_LOG_IMPORT_FILES',
            'Путь к папке с лог файлами импорта справочников относительно корня сайта',
            '',
            array('text')
        ),
        array(
            'PATH_TO_LOG_JOURNAL_FILES',
            'Путь к папке с журналом иморта относительно корня сайта',
            '',
            array('text')
        ),
        array(
            'PATH_TO_DIRECTORY_FILES',
            'Путь к файлам-справочникам, приходящим из 1C относительно корня сайта',
            '',
            array('text')
        ),
        array(
            'LOGIN_FOR_1C',
            'Логин для отправки запросов в 1C',
            '',
            array('text')
        ),
        array(
            'PASSWORD_FOR_1C',
            'Пароль для отправки запросов в 1C',
            '',
            array('text')
        )
    )
);

if ($USER->IsAdmin() && check_bitrix_sessid() && !empty($_POST['save'])) {
    foreach ($options as $option) {
        __AdmSettingsSaveOptions($mid, $option);
    }
    LocalRedirect($APPLICATION->GetCurPageParam());
}

$requestUrl = Context::getCurrent()->getRequest()->getRequestUri();
$action = (new Uri($requestUrl))->addParams(array('mid' => $mid, 'lang' => LANGUAGE_ID));

$tabControl = new CAdminTabControl('tabControl', $tabs);
$tabControl->Begin();
?>

<form method="POST" action="<?= HtmlFilter::encode((string)$action) ?>">
    <?php $tabControl->BeginNextTab(); ?>
    <?php __AdmSettingsDrawList($mid, $options['integration']); ?>
    <?php $tabControl->Buttons(
            array(
                    'btnApply' => true,
            ));
    ?>
    <?= bitrix_sessid_post(); ?>
    <?php $tabControl->End(); ?>
</form>
