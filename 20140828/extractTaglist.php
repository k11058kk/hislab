<?php
// DB処理
include_once('DataBaseOperation.php');
$dbo = new DataBaseOperation();
// 指定したタグを入手
$NUM = $dbo->selectBROTAG2($_POST['keyword']);

echo $_POST['keyword'].',';
if ($NUM) {
	foreach ($NUM as $n) {
		echo $n['hid'].',';
//	echo 'FINISH,';
	}
} else {
	echo 'false';
}
?>