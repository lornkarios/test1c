<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/** @var array $arParams */
/** @var array $arResult */
/** @var CBitrixComponentTemplate $this */

/** @var PageNavigationComponent $component */
$component = $this->getComponent();

$this->setFrameMode(true);



$pagesCount = $arResult['PAGE_COUNT'];
$curPageNum = $arResult['CURRENT_PAGE'];
if ($pagesCount > 1) {
    $prevHtml = ' <li class="page-item ' . (($curPageNum == 1) ? 'disabled' : '') . '">
                                <a href="#" class="page-link"  ' . (($curPageNum == 1) ? 'tabindex="-1" aria-disabled="true"' : '') . ' data-action="prevPage" data-cur-page="' . $curPageNum . '">Previous</a>
                            </li>';
    $nextHtml = ' <li class="page-item ' . (($curPageNum == $pagesCount) ? 'disabled' : '') . '">
                                <a href="#" class="page-link"  ' . (($curPageNum == $pagesCount) ? 'tabindex="-1" aria-disabled="true"' : '') . ' data-action="nextPage" data-cur-page="' . $curPageNum . '">Next</a>
                            </li>';
    $paginationString = '';
    switch ($curPageNum) {
        case 1:

        case 2:
            $startPageNum = 1;
            if ($pagesCount < 5) {
                $endPageNum = $pagesCount;
            } else {
                $endPageNum = 5;
            }
            break;

        case $pagesCount - 1:
        case $pagesCount:
            if ($pagesCount < 5) {
                $startPageNum = 1;
                $endPageNum = $pagesCount;
            } else {
                $startPageNum = $pagesCount - 4;
                $endPageNum = $pagesCount;
            }
            break;
        default:
            if ($pagesCount < 5) {
                $startPageNum = 1;
                $endPageNum = $pagesCount;
            } else {
                $startPageNum = $curPageNum - 2;
                $endPageNum = $curPageNum + 2;
            }
            break;
    }

    for ($pageNum = $startPageNum; $pageNum <= $endPageNum; $pageNum++) {
        $paginationString .= '<li class="page-item ' . (($curPageNum == $pageNum) ? 'active' : '') . '">
                                <a href="#" class="page-link" data-action="linkPage" data-cur-page="' . $pageNum . '">' . $pageNum . '</a>
                            </li>';
    }
    echo
        '<div class="row justify-content-center">
                    <div class="col-md-4">
                        <nav aria-label="...">
                            <ul class="pagination">' .
        $prevHtml . $paginationString . $nextHtml . '
                            </ul>
                        </nav>
                    </div>
                </div>';

}
