<?php


use Bitrix\Main\UserTable;

class UsersList extends CBitrixComponent
{
    private
        $countOnPage = 4;


    public function onPrepareComponentParams($arParams)
    {
        CModule::IncludeModule('iblock');


        return parent::onPrepareComponentParams($arParams);
    }


    public function executeComponent()
    {
        if ($this->arParams['countOnPage']) {
            $this->setCountOnPage($this->arParams['countOnPage']);
        }
        $this->arResult['componentId'] = $this->arParams['componentId'];

        if (isset($_POST['componentId']) && $_POST['componentId'] == $this->arResult['componentId']) {
            global $APPLICATION;
            $APPLICATION->RestartBuffer();


            $usersCount = UserTable::getCount();
            $pagesCount = (int)($usersCount / $this->countOnPage);
            if ($usersCount % $this->countOnPage != 0) {
                $pagesCount++;
            }

            $curPageNum = $_POST['pageNum'];
            switch ($_POST['action']) {
                case 'prevPage':
                    if ($curPageNum != 1) {
                        $linkPageNum = $curPageNum - 1;
                    }
                    break;
                case 'nextPage':
                    if ($curPageNum != $pagesCount) {
                        $linkPageNum = $curPageNum + 1;
                    }
                    break;
                case 'linkPage':
                    if (($curPageNum >= 1)&&($curPageNum <= $pagesCount)) {
                        $linkPageNum = $curPageNum;
                    }
                    break;
            }
            if($linkPageNum) {
                $this->arResult['users'] = $this->prepareUsers($linkPageNum);
                $this->arResult['paginationHtml'] = $this->preparePaginationString($linkPageNum, UserTable::getCount());
                $this->IncludeComponentTemplate();
            }
            die();

        } else {

            $this->arResult['users'] = $this->prepareUsers(1);
            $this->arResult['paginationHtml'] = $this->preparePaginationString(1, UserTable::getCount());

            $this->IncludeComponentTemplate();
        }

    }


    /**
     * @param int $pageNum
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    private function prepareUsers(int $pageNum): array
    {
        $userDb = UserTable::getList(
            [
                'order' => ['ID'],
                'select' => ['NAME', 'EMAIL'],
                'filter' => ['ACTIVE' => 'Y'],
                'limit' => $this->countOnPage,
                'offset' => $this->countOnPage * ($pageNum - 1)
            ]
        );

        return $userDb->fetchAll();


    }

    private function preparePaginationString(int $curPageNum, int $usersCount): string
    {
        $pagesCount = (int)($usersCount / $this->countOnPage);
        if ($usersCount % $this->countOnPage != 0) {
            $pagesCount++;
        }

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
            return
                '<div class="row justify-content-center">
                    <div class="col-md-4">
                        <nav aria-label="...">
                            <ul class="pagination">' .
                $prevHtml . $paginationString . $nextHtml . '
                            </ul>
                        </nav>
                    </div>
                </div>';

        } else {
            return '';
        }

    }


    /**
     * @return int
     */
    private function getCountOnPage(): int
    {
        return $this->countOnPage;
    }

    /**
     * @param int $countOnPage
     */
    private function setCountOnPage(int $countOnPage): void
    {
        $this->countOnPage = $countOnPage;
    }


}
