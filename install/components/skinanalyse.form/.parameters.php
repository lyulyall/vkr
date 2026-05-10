<?php

defined('B_PROLOG_INCLUDED') || die;

global $USER;

\Bitrix\Main\Diag\Debug::dumpToFile($USER->IsAuthorized(), '$USER->IsAuthorized()');

$arComponentParameters = array(
    'PARAMETERS' => array(
        'CACHE_TIME' => array(),
        'USER_AUTHORIZED' => $USER->IsAuthorized(),
    )
);
