<?php
// DB
include_once('DataBaseOperation.php');
$dbo = new DataBaseOperation();
$array = $dbo->selectQUE($_POST['keyword']);

echo '<div id="'.$_POST['keyword'].'" align="center"><h3>「<b>'.$_POST['keyword'].'</b>」での抽出結果です
<button type="button" onclick="cancelHidden(this);">◯</button>
<button type="button" onclick="this.parentNode.parentNode.remove();">×</button>
<button type="button" onclick="nodeUp(this);">△</button>
<button type="button" onclick="nodeDown(this);">▽</button></h3>';
if ($array) {
	// 表示
	include_once('dispArray.php');
	$disp = new dispArray();
	$disp->disp($array);
} else {
	echo '<p align="center">DBで一致しませんでした</p>';
}
echo '</div>';
/*
// デバッグ
echo "<pre>";
var_dump($array);
echo "</pre>";
*/


?>