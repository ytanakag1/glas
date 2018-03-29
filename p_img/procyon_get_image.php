<?php
/**
 
 */
// ************************************************************
// 設定値
// ************************************************************

// 画像取得先のベースURL
define('_BASE_URL', 'https://www.sscgm.com/sirius/files/');

// ************************************************************
//  これより下はプログラム本体ですので修正しないでください。
// ************************************************************
if(empty($_GET['url'])) exit();

$picture_url = $_GET['url'];

if(preg_match("/jpg$|jpeg$/i", $picture_url)) {
	header("Content-Type: image/jpg");
}elseif(preg_match("/gif$/i", $picture_url)) {
	header("Content-Type: image/gif");
}elseif(preg_match("/png$/i", $picture_url)) {
	header("Content-Type: image/png");
}else{
	exit();
}

@readfile(_BASE_URL . $picture_url);
exit;
