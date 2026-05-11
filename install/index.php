<?php
defined('B_PROLOG_INCLUDED') || die;

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use CUserTypeEntity;
use med\appointment\helpers\TableDBHelper;


Loader::requireModule('highloadblock');
Loader::requireModule('iblock');


class med_appointment extends CModule {
    public function __construct() {
        $arModuleVersion = array();
        $this->MODULE_ID = Loc::getMessage('MODULE_ID');
        $this->MODULE_NAME = Loc::getMessage('MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('MODULE_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('PARTNER_URI');

        include(dirname(__FILE__) . '/version.php');

        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
    }

	public function doInstall() : void {
        registerModule($this->MODULE_ID);

        $db = Application::getConnection();
        $db->StartTransaction();

        $tableDbHelper = new TableDBHelper(new CUserTypeEntity());
        try {
            $tableDbHelper->install();
        } catch (Exception $e) {
            $db->rollbackTransaction();
            throw new RuntimeException("Не удалось таблицы сущностей: {$e->getMessage()}");
        }

        $db->commitTransaction();

        $this->installComponents();
	}

	public function doUninstall() : void {
        $db = Application::getConnection();
        $db->StartTransaction();

        $tableDbHelper = new TableDBHelper(new CUserTypeEntity());
        try {
            $tableDbHelper->unInstall();
        }
        catch (Exception $e) {
            $db->rollbackTransaction();
            throw new RuntimeException("Не удалось удалить таблицы сущностей: {$e->getMessage()}");
        }

        $db->commitTransaction();

        unRegisterModule($this->MODULE_ID);
    }


	private function installComponents() : void {
        $root = Application::getDocumentRoot();
        copyDirFiles(
            __DIR__ . '/components/lk.skinanalyse.history',
            $root . '/local/components/med.custom/lk.skinanalyse.history',
            true,
            true
        );
        copyDirFiles(
            __DIR__ . '/pages/cabinet/history/diagnosis-of-skin-diseases',
            $root . '/cabinet/history/diagnosis-of-skin-diseases',
            true,
            true
        );

        copyDirFiles(
            __DIR__ . '/components/lk.symptom.history',
            $root . '/local/components/med.custom/lk.symptom.history',
            true,
            true
        );
        copyDirFiles(
            __DIR__ . '/pages/cabinet/history/diagnosis-by-symptoms',
            $root . '/cabinet/history/diagnosis-by-symptoms',
            true,
            true
        );

        copyDirFiles(
            __DIR__ . '/components/skinanalyse.form',
            $root . '/local/components/med.custom/skinanalyse.form',
            true,
            true
        );
        copyDirFiles(
            __DIR__ . '/pages/skin-analysis',
            $root . '/skin-analysis',
            true,
            true
        );

        copyDirFiles(
            __DIR__ . '/components/symptomanalyse.form',
            $root . '/local/components/med.custom/symptomanalyse.form',
            true,
            true
        );
        copyDirFiles(
            __DIR__ . '/pages/skin-analysis',
            $root . '/analysis-of-symptoms',
            true,
            true
        );
	}
}
