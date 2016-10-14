<?php
set_time_limit(600);
/*$curl_handle=curl_init();
curl_setopt($curl_handle, CURLOPT_URL,'https://www.youtube.com/results?sp=CAM%253D&q=site%3Ayoutube.com');
curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Your application name');
$query = curl_exec($curl_handle);
curl_close($curl_handle);
preg_match_all("|(<div class=\"yt-lockup.+yt-lockup-tile.+yt-lockup-video.+clearfix\")(.?)(data-context-item-id=\")(.{11})(\")|",$query, $matches, PREG_SET_ORDER );*/
require_once 'phpQuery.php';


$content_hour = 'https://www.youtube.com/results?q=site%3Ayoutube.com&sp=CAMSAggB';

$content_today = 'https://www.youtube.com/results?sp=CAMSAggC&q=site%3Ayoutube.com';

$content_week = 'https://www.youtube.com/results?q=site%3Ayoutube.com&sp=CAMSAggD';

$content_mounth = 'https://www.youtube.com/results?sp=CAMSAggE&q=site%3Ayoutube.com';

$content_year = 'https://www.youtube.com/results?sp=CAMSAggF&q=site%3Ayoutube.com';

$content_alltime = 'https://www.youtube.com/results?q=site%3Ayoutube.com&sp=CAMSAhAB';
$content_arr = [$content_hour, $content_today, $content_week, $content_mounth, $content_year, $content_alltime];
$name_arr = ['hour', 'today','week', 'mounth','year','alltime'];
function get_data($contents, $name){
    $arr='';
    $index=0;
    foreach ( $contents as $content) {
        $matches = [];

        $str = '';
        $str_item ='';
        if ($arr != "") {
            $arr .= ",";
        }
        $url = file_get_contents($content);
        $document = phpQuery::newDocument($url);
        $list_elements = $document->find('.yt-lockup-dismissable');
        foreach ($list_elements as $element) {
            $title = NULL;
            if ($title = pq($element)->find('img[width=196]')->attr('data-thumb')) {
                $title = pq($element)->find('img[width=196]')->attr('data-thumb');
            } else {
                $title = pq($element)->find('img[width=196]')->attr('src');
            }
            $author = pq($element)->find('.yt-lockup-byline a')->text();
            preg_match_all("|(vi/)(.{11})|", $title, $out, PREG_SET_ORDER);

            $pair = [$out[0][2], $author];
            $matches[] = $pair;

        }

        if($matches[0][0] == ''){
            do {
                echo ' wasin json';
                $json = file_get_contents('http://zhekavasil.by/ang/parser.php?type='.$name);
                $obj = json_decode($json);
                $arr = $obj->vid;
                $ind = 0;
                foreach ($arr as $item){
                    $matches[$ind][0] =  $item->id;
                    $matches[$ind][1] =  $item->author;
                    $ind++;
                }
            } while ($matches[0][0] == '');

        }
        foreach ($matches as $value) {
            if ($str != "") {
                $str .= ",";
            }

            $data = file_get_contents("https://www.googleapis.com/youtube/v3/videos?key=AIzaSyDKW1tr_PpkXur3m9X2y-jKEvUCJ2JurlY&part=snippet,contentDetails,statistics&id=" . $value[0]);
            $json = json_decode($data);
            $thumbnail = $json->items[0]->snippet->thumbnails->high->url;
            $title = $json->items[0]->snippet->title;
            $views = $json->items[0]->statistics->viewCount;


            $title = str_replace('"', ' ', $title);
            $author = str_replace('"', ' ', $value[1]);


            /* $authorId = $json->items[0]->snippet->channelId;
             $data2= file_get_contents("https://www.googleapis.com/youtube/v3/channels?part=id%2Csnippet%2Cstatistics%2CcontentDetails%2CtopicDetails&id=".$authorId."&key=AIzaSyDKW1tr_PpkXur3m9X2y-jKEvUCJ2JurlY");
             $json2 = json_decode($data2);
             $author = $json2->items[0]->snippet->title;*/
            $str .= '{"id":"' . $value[0] . '",';
            $str .= '"ico":"' . $thumbnail . '",';
            $str .= '"views":"' . $views . '",';
            $str .= '"author":"' . $author . '",';
            $str .= '"title":"' . $title . '"}';

        }

        $str_item = '"' . $name[$index] . '":[' . $str . ']';
       $arr .= $str_item;
        $index++;
    }
    return $arr;
}
$main_arr = get_data($content_arr, $name_arr);
$res ='{"vid":{'.$main_arr.'}}';
file_put_contents('json/parser.json', $res);
var_dump($res);

