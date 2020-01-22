<?php


use Bitrix\Main\UI\PageNavigation;
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
        $usersCount = UserTable::getCount();

        if (isset($_POST['componentId']) && $_POST['componentId'] == $this->arResult['componentId']) {

            $oneStepIteration = 2000;//сколько пользователей мы будем выгружать за одну итерацию импорта


            if (isset($_POST['documentType']) && ($documentType = $_POST['documentType'])) {


                $curStep = (int)$_POST['curStep'];
                $maxStepCount = (int)($usersCount / $oneStepIteration);
                if ($usersCount % $oneStepIteration != 0) {
                    $maxStepCount++;
                }

                if ($curStep == 0) {

                    $jsonResult['maxStepCount'] = $maxStepCount;


                    $documentPath =  '/upload/importFiles/';
                    if (!is_dir($_SERVER['DOCUMENT_ROOT'] .$documentPath)) {
                        mkdir($_SERVER['DOCUMENT_ROOT'] .$documentPath);
                    }
                    $documentPath .= uniqid() . '/';
                    mkdir($_SERVER['DOCUMENT_ROOT'] .$documentPath);

                    $documentPath .= uniqid() . '.' . $documentType;
                    if ($documentType == 'xml') {
                        file_put_contents(
                            $_SERVER['DOCUMENT_ROOT'] .$documentPath,
                            '<?xml version="1.0" encoding="UTF-8"?>
                                <users>'
                        );

                    } else {
                        file_put_contents($_SERVER['DOCUMENT_ROOT'] .$documentPath, '');

                    }

                    $jsonResult['documentName'] = $documentPath;
                } else {
                    $documentPath = $_SERVER['DOCUMENT_ROOT'] .$_POST['documentName'];
                }

                $users = $this->prepareUsersForImport($curStep, $oneStepIteration);

                switch ($documentType) {
                    case 'xml':
                        $xmlUsers = $this->convertUsersToXml($users);
                        file_put_contents($documentPath, $xmlUsers, FILE_APPEND);
                        break;
                    case 'csv':
                        $csvUsers = $this->convertUsersToCsv($users);
                        file_put_contents($documentPath, $csvUsers, FILE_APPEND);
                        break;
                }

                if (($curStep == ($maxStepCount - 1)) && ($documentType == 'xml')) {

                    file_put_contents(
                        $documentPath,
                        '</users>', FILE_APPEND
                    );


                }
                $jsonResult['success'] = true;

                $curStep++;
                $jsonResult['curStep'] = $curStep;

                global $APPLICATION;
                $APPLICATION->RestartBuffer();

                echo json_encode($jsonResult, JSON_UNESCAPED_UNICODE);

                die();
            } else {

                global $APPLICATION;
                $APPLICATION->RestartBuffer();


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
                        if (($curPageNum >= 1) && ($curPageNum <= $pagesCount)) {
                            $linkPageNum = $curPageNum;
                        }
                        break;
                }
                if ($linkPageNum) {
                    $this->arResult['users'] = $this->prepareUsers($linkPageNum);
                    $this->arResult['paginationObject'] = $this->preparePaginationObject($usersCount,$linkPageNum);
                    $this->IncludeComponentTemplate();
                }
                die();
            }
        } else {

            $this->arResult['users'] = $this->prepareUsers(1);
            $this->arResult['paginationObject'] = $this->preparePaginationObject($usersCount,1);

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


        return $this->prepareUsersForImport($pageNum - 1, $this->countOnPage);


    }

    /**
     * @param int $curStep
     * @param int $oneStepIteration
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    private function prepareUsersForImport(int $curStep, int $oneStepIteration): array
    {
        $userDb = UserTable::getList(
            [
                'order' => ['ID'],
                'select' => ['NAME', 'EMAIL'],
                'filter' => ['ACTIVE' => 'Y'],
                'limit' => $oneStepIteration,
                'offset' => $oneStepIteration * $curStep,
                'cache'=>["ttl"=>3600]
            ]
        );

        return $userDb->fetchAll();


    }


    private function preparePaginationObject(int $usersCount, int $curPage): PageNavigation
    {
        $nav = new \Bitrix\Main\UI\PageNavigation("nav-less-news");
        $nav->allowAllRecords(false)
            ->setPageSize($this->countOnPage)
            ->setCurrentPage($curPage)
            ->initFromUri();
        $nav->setRecordCount($usersCount);
        return $nav;

    }

    /**
     * @param int $countOnPage
     */
    private function setCountOnPage(int $countOnPage): void
    {
        $this->countOnPage = $countOnPage;
    }


    private function convertUsersToCsv(array $users): string
    {
        $csvUsers = '';
        foreach ($users as $user) {
            $csvUsers .= $user['NAME'] . '|' . $user['EMAIL'] . PHP_EOL;
        }
        return $csvUsers;
    }

    private function convertUsersToXml(array $users): string
    {
        $xmlUsers = '';
        foreach ($users as $user) {
            $xmlUsers .=
                '<user>
                    <email>' . $user['EMAIL'] . '</email>
                    <name>' . $user['NAME'] . '</name>
                </user>';
        }
        return $xmlUsers;
    }
}
