<?php
// DB処理
include_once('DataBaseOperation.php');
$dbo = new DataBaseOperation();
// タグを全て入手
$arrayTAG = $dbo->selectTAG();

// オントロジーのパスの読み込み
include_once('config.php');
// オントロジーデータを入手
$sxmlobj = simplexml_load_file(ontpath);
$arrayONT = get_object_vars($sxmlobj);
// 照合
foreach ($arrayONT['W_CONCEPTS']->CONCEPT as $concept) {
	// $arrayONT['W_CONCEPTS']->CONCEPT->LABELってできないからなあ…
	$label = $concept->LABEL;

	// 一致回数カウント用
	$count = 0;
	// タグの数だけループ
	foreach ($arrayTAG as $tag) {
		// もし一致していれば出力
		if ($label == $tag['tag']) {
			$count++;
			// 一回目の一致のみ出力
			if ($count == 1) {
				echo '<button onclick="extract(\''.$label.'\')">'.$label;
			}
		}
	}
	// 一致があったときだけ、閉じタグを出力
	if ($count != 0) {
		echo '('.$count.')</button><br/>';
	}
}
?>