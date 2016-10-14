<?php
set_time_limit(600);


$content_hour = 'http://zhekavasil.by/ang/parser.php?type=hour';

$content_today = 'http://zhekavasil.by/ang/parser.php?type=today';

$content_week = 'http://zhekavasil.by/ang/parser.php?type=week';

$content_mounth = 'http://zhekavasil.by/ang/parser.php?type=mounth';

$content_year = 'http://zhekavasil.by/ang/parser.php?type=year';

$content_alltime = 'http://zhekavasil.by/ang/parser.php?type=alltime';
$content_arr = [$content_hour, $content_today, $content_week, $content_mounth, $content_year, $content_alltime];
$name_arr = ['hour', 'today','week', 'mounth','year','alltime'];
function get_data($contents, $name, $double){
    $arr = '';
    /*$innerIndex ? $index = $innerIndex : $index = 0;*/
    $index = 0;
    foreach ( $contents as $content) {
        if ($arr != "") {
            $arr .= ",";
        }
        $str_item = '';
        $url = file_get_contents($content);
        $json = json_decode($url);
        $input = $json->vid;
        $str = '';
        if($input[0]->id == ''){
           /* echo 'In error ';*/
            $input = get_data([$content],[$name[$index]], true);
        } else {
            if($double) return $input;
        }
        foreach ($input as $element) {
            if ($str != "") {
                $str .= ",";
            }
            $str .= '{"id":"' . $element->id . '",';
            $str .= '"ico":"' . $element->ico . '",';
            $str .= '"views":"' . $element->views . '",';
            $str .= '"author":"' . $element->author . '",';
            $str .= '"title":"' . $element->title . '"}';
        }
        $str_item = '"' . $name[$index] . '":[' . $str . ']';
        $arr .= $str_item;
        $index++;
    }
    return $arr;
}
$main_arr = get_data($content_arr, $name_arr, false);
$res ='{"vid":{'.$main_arr.'}}';
file_put_contents('json/parser.json', $res);



