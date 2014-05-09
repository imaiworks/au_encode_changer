<?php
//いまいまのシステムがUTF-8の場合、auの場合、文字化け必死なので
//コンテンツをSHIFT-JISに変換する
//(c)2014 imaiworks
//BSD licence

//再度webサーバに問い合わせるためmod_rewiteを通過させるための引数
$uri=$_SERVER["REQUEST_URI"];
if(!strstr($uri,"?"))
{
	$uri.="?changetrue=true";
}
else
{
	$uri.="&changetrue=true";
}

//元々のヘッダーを踏襲する
$requestheader=getallheaders();
foreach($requestheader as $key=>$data)
{
	$header_array[]=$key.": ".$data;
}
//print_r($header_array);

//POST以外はGETにしておく
//POST時は投げられたPOST値をSHIFT-JISからUTF-8に変換
if($_SERVER["REQUEST_METHOD"]!="POST")
{
	$context = stream_context_create(array(
		"http" => array(
			'method'  => 'GET',
			'header'  => implode("\r\n", $header_array),
		)
	));
}
else
{
	foreach($_POST as $key=>$data)
	{
		$postdata[$key]=mb_convert_encoding($data,"UTF-8","SHIFT-JIS");
	}
	$context = stream_context_create(array(
		"http" => array(
			'method'  => 'POST',
			'header'  => implode("\r\n", $header_array),
			'content' => http_build_query($postdata),
		)
	));
}

//揃えたデータを元にwebサーバに問い合わせる
$res = file_get_contents("http://localhost".$uri , false, $context);

//帰ってきたUTF-8のページデータをSHIFT-JISに変換
$res=str_replace("charset=UTF-8","charset=SHIFT-JIS",$res);
$res=mb_convert_encoding($res,"SHIFT-JIS","UTF-8");

//ブラウザに返す
header("Content-Type: text/html; charset=SHIFT-JIS");
header("Content-Length: ".strlen($res));

echo $res;

