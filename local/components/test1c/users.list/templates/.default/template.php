<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
/**
 * @var array $arResult
 */

?>
<div class="js-component" data-component-id="<?= $arResult['componentId'] ?>">
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
            <?=$arResult['paginationHtml']?>

        </div>
    </div>


</div>
<script>
  new UsersList("<?=$arResult['componentId']?>", document).init();

</script>