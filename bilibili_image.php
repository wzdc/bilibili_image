<?php
include('simple_html_dom.php');
header("Access-Control-Allow-Origin:*"); //接受所有访问
if(is_Mobile()&&$_REQUEST["mobile_search"])
$q=urlencode($_REQUEST["mobile_search"]);
else
$q=urlencode($_REQUEST["search"]);
if(!$q) exit;
$p=rand(1,30); //随机页数

$url = GET("https://api.bilibili.com/x/web-interface/wbi/search/type?__refresh__=true&_extra=&context=&page=$p&page_size=1&order=totalrank&duration=&from_source=&from_spmid=333.337&platform=pc&highlight=1&single_column=0&keyword=$q&qv_id=LQu75dQ0Mz3MbQwEQa3Sgv7vLFqYEq5l&ad_resource=&source_tag=3&category_id=0&search_type=article&w_rid=9fa7196ec35041e8c2b0d41bc0b273bd&wts=1673791435");
$arr = json_decode($url, true);
$id=$arr["data"]["result"][0]["id"];
$data = GET("https://www.bilibili.com/read/cv$id");
$size=rand(0,substr_count($data,"<img")-1); //随机截取一张图片
$html = new simple_html_dom();
$a="data-src";
$img='https:'.$html->load($data)->find("img",$size)->$a;

if(getimagesize($img)[1]>=400) //判断图片长度是否超过400（主要用来防装饰图片）
header("location:https://image.baidu.com/search/down?tn=download&ipn=dwnl&word=download&ie=utf8&fr=result&url=$img&thumburl=0"); //调用百度下载图片API绕过B站防盗链 header("location: $img");
else
header("location: ".$_SERVER["REQUEST_URI"]);


//GET
function GET($url) {
$headers[]  =  "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9";
$headers[]  =  "Accept-Encoding: gzip, deflate, br";
$headers[]  =  "Accept-Language: zh-CN,zh;q=0.9,zh-HK;q=0.8,zh-TW;q=0.7";
$headers[]  =  "Cache-Control: max-age=0";
$headers[]  =  "Connection: keep-alive";
$headers[]  =  'sec-ch-ua: "Not?A_Brand";v="8", "Chromium";v="108", "Google Chrome";v="108"';
$headers[]  =  "User-Agent:Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36";
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL,$url);
curl_setopt($curl, CURLOPT_HEADER,0);
curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
curl_setopt($curl,CURLOPT_HTTPHEADER,$headers);
curl_setopt($curl, CURLOPT_ENCODING, '');
curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($curl,CURLOPT_NOBODY,0);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$data = curl_exec($curl);
curl_close($curl);
    return $data;
}

//is Mobile
function is_Mobile()
{
    if (isset($_SERVER['HTTP_VIA']) && stristr($_SERVER['HTTP_VIA'], "wap")) {
        return true;
    } elseif (isset($_SERVER['HTTP_ACCEPT']) && strpos(strtoupper($_SERVER['HTTP_ACCEPT']), "VND.WAP.WML")) {
        return true;
    } elseif (isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE'])) {
        return true;
    } elseif (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/(blackberry|configuration\/cldc|hp |hp-|htc |htc_|htc-|iemobile|kindle|midp|mmp|motorola|mobile|nokia|opera mini|opera |Googlebot-Mobile|YahooSeeker\/M1A1-R2D2|android|iphone|ipod|mobi|palm|palmos|pocket|portalmmm|ppc;|smartphone|sonyericsson|sqh|spv|symbian|treo|up.browser|up.link|vodafone|windows ce|xda |xda_)/i',$_SERVER['HTTP_USER_AGENT'])) {
        return true;
    } else {
        return false;
    }
}
?>
