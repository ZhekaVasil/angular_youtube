<?php
/*$curl_handle=curl_init();
curl_setopt($curl_handle, CURLOPT_URL,'https://www.youtube.com/results?sp=CAM%253D&q=site%3Ayoutube.com');
curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Your application name');
$query = curl_exec($curl_handle);
curl_close($curl_handle);
preg_match_all("|(<div class=\"yt-lockup.+yt-lockup-tile.+yt-lockup-video.+clearfix\")(.?)(data-context-item-id=\")(.{11})(\")|",$query, $matches, PREG_SET_ORDER );*/
require_once 'phpQuery.php';
$content = NULL;
if(isset($_GET['type'])){
    switch ($_GET['type']){
        case 'hour':
            $content = file_get_contents('https://www.youtube.com/results?q=site%3Ayoutube.com&sp=CAMSAggB');
            break;
        case 'today':
            $content = file_get_contents('https://www.youtube.com/results?sp=CAMSAggC&q=site%3Ayoutube.com');
            break;
        case 'week':
            $content = file_get_contents('https://www.youtube.com/results?q=site%3Ayoutube.com&sp=CAMSAggD');
            break;
        case 'mounth':
            $content = file_get_contents('https://www.youtube.com/results?sp=CAMSAggE&q=site%3Ayoutube.com');
            break;
        case 'year':
            $content = file_get_contents('https://www.youtube.com/results?sp=CAMSAggF&q=site%3Ayoutube.com');
            break;
        case 'alltime':
            $content = file_get_contents('https://www.youtube.com/results?q=site%3Ayoutube.com&sp=CAM%253D');
            break;
    }
} else {
    $content = file_get_contents('https://www.youtube.com/results?q=site%3Ayoutube.com&sp=CAM%253D');
}

$document = phpQuery::newDocument($content);
$list_elements = $document->find('img[width=196]');
$matches =[];
foreach ($list_elements as $element)
{
    $title = NULL;
    if($title = pq($element)->attr('data-thumb')){
        $title = pq($element)->attr('data-thumb');
    } else {
        $title = pq($element)->attr('src');
    }

    preg_match_all("|(vi/)(.{11})|",$title, $out,PREG_SET_ORDER);
    $matches[] = $out[0][2];

}
$str='';
foreach ($matches as $value)
{
    if ($str != "") {$str .= ",";}

    $data = file_get_contents("https://www.googleapis.com/youtube/v3/videos?key=AIzaSyDKW1tr_PpkXur3m9X2y-jKEvUCJ2JurlY&part=snippet,statistics&id=".$value);
    $json = json_decode($data);
    $thumbnail = $json->items[0]->snippet->thumbnails->high->url;
    $title = $json->items[0]->snippet->title;
    $title = str_replace('"',' ', $title);
    $views = $json->items[0]->statistics->viewCount;
    $str .= '{"id":"'  . $value . '",';
    $str .= '"ico":"'. $thumbnail. '",';
    $str .= '"views":"'. $views. '",';
    $str .= '"title":"'. $title. '"}';

}

$str ='{"vid":['.$str.']}';
echo $str;
