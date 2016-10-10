<?php

require_once 'phpQuery.php';

// Загрузим страницу
$content = file_get_contents('https://www.youtube.com/results?q=site%3Ayoutube.com&sp=CAM%253D');

// Покормим phpQuery кодом страницы
$document = phpQuery::newDocument($content);

// Выберем списочные элементы в которых есть заголовок и текст новости
$list_elements = $document->find('img[width=196]');


// Пробегаем по найденым элементам и делаем с ними что угодно.
$out =[];
foreach ($list_elements as $element)
{
    $title = NULL;
    if($title = pq($element)->attr('data-thumb')){
        $title = pq($element)->attr('data-thumb');
    } else {
        $title = pq($element)->attr('src');
    }

    preg_match_all("|(vi/)(.{11})|",$title, $matches, PREG_SET_ORDER);
    $out[] = $matches[0][2];
}
var_dump($out);