<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("1С тест на 2ю часть собеседования");
?>

<?php
$APPLICATION->IncludeComponent(
    "test1c:users.list",
    "",
    [
            'countOnPage'=>10,
            'componentId'=>'userlist1'
    ]
);
?>

<?php
$APPLICATION->IncludeComponent(
    "test1c:users.list",
    "",
    [
        'countOnPage'=>4,
        'componentId'=>'userlist2'
    ]
);
?>
<?php
$APPLICATION->IncludeComponent(
    "test1c:users.list",
    "",
    [
        'countOnPage'=>8,
        'componentId'=>'userlist3'
    ]
);
?>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>