<?php
$site = getenv('POPCLIP_OPTION_SITE');
$query_ip = getenv('POPCLIP_TEXT');

switch ($site) {
    case 'haoip.cn(s)':
        haoip($query_ip);
        break;
    case 'ipinfo.io(s)':
        ipinfo($query_ip);
        break;
    case 'cip.cc':
        cipcc($query_ip);
        break;
    case 'freeapi.ipip.net(s)':
        ipip($query_ip);
        break;
    default:
        echo "wrong site";
        break;
}

function get($url){
    $ua = 'curl/7.54.0';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_USERAGENT, $ua);
    curl_setopt($ch, CURLOPT_TIMEOUT, 2);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $response = curl_exec($ch);
    return $response;
}

function haoip($query_ip){
    $url = sprintf('https://haoip.cn/ip/%s',$query_ip);
    $data = get($url);
    $match = [];
    $rs = preg_match_all('/纯真:(.*)/i', $data, $match);
    if($rs > 0){
        echo trim($match[1][0]);
    }
}


function ipinfo($query_ip){
    $url = sprintf('https://ipinfo.io/%s',$query_ip);
    $data = get($url);
    $data = json_decode($data, 1);
    if($data){
        echo $data['country'] . ',' . $data['region'] . ',' . $data['city'];
    }
}

function cipcc($query_ip){
    $url = sprintf('http://cip.cc/%s',$query_ip);
    $data = get($url);
    $match = [];
    $rs = preg_match_all('/数据二	:(.*)/i', $data, $match);
    if($rs > 0){
        echo trim($match[1][0]);
    }
}

function ipip($query_ip){
    $url = sprintf('https://freeapi.ipip.net/%s',$query_ip);
    $data = get($url);
    $data = json_decode($data, 1);
    foreach($data as $k => $v){
        if(empty($v)){
            unset($data[$k]);
        }
    }
    if($data){
        echo implode(',', $data);
    }
}

exit(0);