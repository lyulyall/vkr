<?php

defined('B_PROLOG_INCLUDED') || die;

/**
 * @var array $arResult
 */
?>

<div id="symptom-history-container"  class="symptom-history container mt-5 mb-5">
    <h2 class="mb-4">История диагностики симптомов</h2>

    <?php if (!$arResult['IS_AUTHORIZED']): ?>
        <div class="alert alert-info">
            Чтобы посмотреть историю диагностики симптомов, пожалуйста, авторизуйтесь.
        </div>
        <?php return; ?>
    <?php endif; ?>

    <div class="symptom-history__controls mb-4">
        <form method="get" action="">
            <label for="page_size" class="me-2">
                <strong>Показывать на странице:</strong>
            </label>

            <select name="page_size" id="page_size" class="form-select d-inline-block w-auto" onchange="this.form.submit()">
                <?php foreach ($arResult['PAGINATION']['PAGE_SIZE_OPTIONS'] as $size): ?>
                    <option value="<?=$size?>" <?=$size == $arResult['PAGINATION']['PAGE_SIZE'] ? 'selected' : ''?>>
                        <?=$size?>
                    </option>
                <?php endforeach; ?>
            </select>

            <input type="hidden" name="page" value="1">
        </form>
    </div>

    <div id="symptom-history-loader" class="symptom-history__loader" style="display:none;">
        <div class="symptom-history__loader-backdrop"></div>
        <div class="symptom-history__loader-content">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Загрузка...</span>
            </div>
            <div class="mt-2">Загрузка истории...</div>
        </div>
    </div>

    <?php if (empty($arResult['ITEMS'])): ?>
        <div class="alert alert-secondary">
            История диагностики пока пуста.
        </div>
        <?php return; ?>
    <?php endif; ?>

    <?php foreach ($arResult['ITEMS'] as $item): ?>
        <div class="symptom-history__item-wrap mb-4">
            <div class="symptom-history__item card shadow-sm">
                <div class="card-body symptom-history__card-body">
                    <div class="mb-3">
                        <strong>Дата:</strong>
                        <?=htmlspecialcharsbx($item['DATE'])?>
                    </div>

                    <div class="mb-3">
                        <strong>Симптомы:</strong><br>
                        <?=nl2br(htmlspecialcharsbx($item['REQUEST']))?>
                    </div>

                    <?php if (!empty($item['RESPONSE'])): ?>
                        <div class="mb-3">
                            <strong>К какому врачу обратиться:</strong><br>
                            <?=htmlspecialcharsbx((string)($item['RESPONSE']['doctor'] ?? 'Не указано'))?>
                        </div>

                        <div class="mb-3">
                            <strong>Срочность:</strong><br>
                            <span class="badge bg-warning text-dark">
                                <?=htmlspecialcharsbx((string)($item['RESPONSE']['urgency'] ?? 'Не указано'))?>
                            </span>
                        </div>

                        <div class="mb-3">
                            <strong>Возможные причины:</strong>
                            <ul class="mt-2 mb-0 ps-4">
                                <?php
                                $possibleCauses = isset($item['RESPONSE']['possible_causes']) && is_array($item['RESPONSE']['possible_causes'])
                                        ? $item['RESPONSE']['possible_causes']
                                        : array();
                                ?>
                                <?php if (!empty($possibleCauses)): ?>
                                    <?php foreach ($possibleCauses as $cause): ?>
                                        <li class="mb-1"><?=htmlspecialcharsbx((string)$cause)?></li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li>Не указано</li>
                                <?php endif; ?>
                            </ul>
                        </div>

                        <div class="mb-0">
                            <strong>Рекомендации:</strong>
                            <ul class="mt-2 mb-0 ps-4">
                                <?php
                                $recommendations = isset($item['RESPONSE']['recommendations']) && is_array($item['RESPONSE']['recommendations'])
                                        ? $item['RESPONSE']['recommendations']
                                        : array();
                                ?>
                                <?php if (!empty($recommendations)): ?>
                                    <?php foreach ($recommendations as $recommendation): ?>
                                        <li class="mb-1"><?=htmlspecialcharsbx((string)$recommendation)?></li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li>Не указано</li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning mb-0">
                            Не удалось разобрать сохранённый ответ.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <?php if (!empty($arResult['PAGINATION']) && $arResult['PAGINATION']['TOTAL_PAGES'] > 1): ?>
        <div class="symptom-history__pagination d-flex flex-wrap align-items-center gap-2 mt-4">
            <?php if ($arResult['PAGINATION']['HAS_PREV']): ?>
                <a
                        class="btn btn-outline-primary"
                        href="<?=htmlspecialcharsbx($arResult['PAGINATION']['PREV_URL'])?>"
                >
                    Назад
                </a>
            <?php endif; ?>

            <?php foreach ($arResult['PAGINATION']['PAGE_URLS'] as $pageItem): ?>
                <?php if ($pageItem['TYPE'] === 'dots'): ?>
                    <span class="btn btn-light disabled">…</span>
                <?php elseif ($pageItem['IS_CURRENT']): ?>
                    <span class="btn btn-primary"><?=$pageItem['NUMBER']?></span>
                <?php else: ?>
                    <a
                            class="btn btn-outline-primary"
                            href="<?=htmlspecialcharsbx($pageItem['URL'])?>"
                    >
                        <?=$pageItem['NUMBER']?>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>

            <?php if ($arResult['PAGINATION']['HAS_NEXT']): ?>
                <a
                        class="btn btn-outline-primary"
                        href="<?=htmlspecialcharsbx($arResult['PAGINATION']['NEXT_URL'])?>"
                >
                    Вперёд
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>