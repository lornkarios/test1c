<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
/**
 * @var array $arResult
 */

?>
<div class="js-component" data-component-id="<?= $arResult['componentId'] ?>">
    <div class="js-import-container">
        <div class="row justify-content-end">
            <div class="col-md-2">
                <a href="#" class="js-import-document" data-type="csv" data-is-import="0">
                    <img src="<?= $this->GetFolder() ?>/img/csv.png">
                    <div class="progress" style="width:40px;height: 3px;margin-top:4px;display: none">
                        <div class="progress-bar" role="progressbar" style="width: 0%;" ></div>
                    </div>
                </a>


            </div>
            <div class="col-md-2">
                <a href="#" class="js-import-document" data-type="xml" data-is-import="0">
                    <img src="<?= $this->GetFolder() ?>/img/xml.png">
                    <div class="progress" style="width:40px;height: 3px;margin-top:4px;display: none">
                        <div class="progress-bar" role="progressbar" style="width: 0%;"></div>
                    </div>
                </a>

            </div>
        </div>
    </div>
    <div class="js-body-container">
        <div class="album py-5 bg-light">
            <div class="container">

                <div class="row">
                    <?php foreach ($arResult['users'] as $user): ?>
                        <div class="col-md-4">
                            <div class="card mb-4 shadow-sm">

                                <div class="card-body">
                                    <p class="card-text">
                                        <b>NAME:</b><?= $user['NAME'] ?>
                                    </p>
                                    <p class="card-text">
                                        <b>EMAIL:</b><?= $user['EMAIL'] ?>
                                    </p>

                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>


                </div>


                <!--        pagenavigation   -->
                <?
                $APPLICATION->IncludeComponent(
                    "bitrix:main.pagenavigation",
                    "main",
                    array(
                        "NAV_OBJECT" => $arResult['paginationObject'],
                        "SEF_MODE" => "Y",
                        "SHOW_COUNT" => "N",
                    ),
                    false
                );
                ?>

            </div>
        </div>

    </div>
</div>
<script>
    new UsersList("<?=$arResult['componentId']?>", document).init();

</script>