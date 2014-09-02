<?php
// オントロジーのパスの読み込み
include_once('config.php');
// オントロジーデータを取得
$sxmlobj = simplexml_load_file(ontpath);
// 配列に変換
$array = get_object_vars($sxmlobj);

// 表示
// idカウント用
$ix = 0;
foreach ($array['W_CONCEPTS']->CONCEPT as $value) {
	echo '<button type="button" onclick="extract(\''.$value->LABEL.'\')">'.$value->LABEL.'</button><br/>';
}
?>