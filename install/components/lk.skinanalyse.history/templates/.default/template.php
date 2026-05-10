<?php

defined('B_PROLOG_INCLUDED') || die;

/**
 * @var array $arResult
 */
?>

<div id="skin-history-container" class="skin-history container mt-5 mb-5">
    <h2 class="mb-4">История диагностики кожных заболеваний</h2>

    <?php if (!$arResult['IS_AUTHORIZED']): ?>
        <div class="alert alert-info">
            Чтобы посмотреть историю диагностики кожи, пожалуйста, авторизуйтесь.
        </div>
        <?php return; ?>
    <?php endif; ?>

    <div class="skin-history__controls mb-4">
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

    <div id="skin-history-loader" class="skin-history__loader" style="display:none;">
        <div class="skin-history__loader-backdrop"></div>
        <div class="skin-history__loader-content">
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
        <div class="skin-history__item-wrap mb-4">
            <div class="skin-history__item card shadow-sm">
                <div class="card-body skin-history__card-body">
                    <div class="mb-3">
                        <strong>Дата:</strong>
                        <?=htmlspecialcharsbx($item['DATE'])?>
                    </div>

                    <?php if (!empty($item['PHOTO_SRC'])): ?>
                        <div class="mb-3">
                            <strong>Изображение:</strong><br>
                            <a href="<?=htmlspecialcharsbx($item['PHOTO_SRC'])?>" target="_blank">
                                <img
                                        src="<?=htmlspecialcharsbx($item['PHOTO_SRC'])?>"
                                        alt="<?=htmlspecialcharsbx($item['NAME'])?>"
                                        class="skin-history__image mt-2"
                                >
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($item['RESPONSE']) && !empty($item['RESPONSE']['predictions']) && is_array($item['RESPONSE']['predictions'])): ?>
                        <div class="mb-3">
                            <strong>Результаты анализа:</strong>

                            <div class="skin-history__predictions mt-3">
                                <?php foreach ($item['RESPONSE']['predictions'] as $index => $prediction): ?>
                                    <?php
                                    $title = '';

                                    if (!empty($prediction['clean_name'])) {
                                        $title = (string)$prediction['clean_name'];
                                    } elseif (!empty($prediction['class_name'])) {
                                        $title = (string)$prediction['class_name'];
                                    } else {
                                        $title = 'Не указано';
                                    }

                                    $confidence = isset($prediction['confidence']) ? (string)$prediction['confidence'] : '';
                                    $isTop = ($index === 0);
                                    ?>
                                    <div class="skin-history__prediction <?=$isTop ? 'skin-history__prediction--top' : ''?>">
                                        <div class="skin-history__prediction-title">
                                            <?=$isTop ? '🏆 ' : ($index + 1) . '. '?>
                                            <?=htmlspecialcharsbx($title)?>
                                        </div>

                                        <?php if ($confidence !== ''): ?>
                                            <div class="skin-history__prediction-confidence">
                                                Уверенность: <?=htmlspecialcharsbx($confidence)?>%
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
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
        <div class="skin-history__pagination d-flex flex-wrap align-items-center gap-2 mt-4">
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