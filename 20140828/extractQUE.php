<?php
include_once('DataBaseOperation.php');
$dbo = new DataBaseOperation();
$array = $dbo->selectALL();

// 表示
echo '<div align="center"><h3><b>全過去問</b>の抽出結果です
<button type="button" onclick="cancelHidden(this);">◯</button>
<button type="button" onclick="this.parentNode.parentNode.remove();">×</button></h3>';
include_once('dispArray.php');
$disp = new dispArray();
$disp->disp($array);

/*
// デバッグ
echo "<pre>";
var_dump($array);
echo "</pre>";
*/

?>