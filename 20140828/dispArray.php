<?php
class dispArray {

	// 表示
	function disp($array) {
		// 画像差し替え
		function replaceIMG($arrayI, $html) {
			foreach ($arrayI as $image) {
/*
				// 画像デバック
				echo '<h1>画像デバッグ</h1>';
				echo '<img  src="data: image/jpg; base64,'.base64_encode($image).'">';
*/
				// 画像バイナリデータをエンコード
				$base64 = base64_encode($image);
				// 置換する文字列(スペース(\s)を二つ入れることで再置換を回避)
				$repla = '<img  src="data: image/jpg; base64,'.$base64.'" ';
				// 置換処理(第四引数のlimit(1)で、1度に1回しか置換させない)
				$html = preg_replace('/<img\s([a-zA-Z0-9])(\s|=|"|\/)*..../', $repla, $html, 1);
			}
			return $html;
		}

		// メイン処理
		$ix = 0;
		// 要素がないとdispArrayには入ってこないはずだからこれで大丈夫
		echo '<table border="1" align="center"><tbody>';
		foreach ($array as $value) {
			$html = $value['html'];

			if (isset($value['image'])) {
				$html = replaceIMG($value['image'], $html);
			}

			echo '
					<tr>
						<td>'.++$ix.'</br><button type="button"
							onclick="this.parentNode.parentNode.setAttribute(\'hidden\', \'hidden\');">×</button></td>
						<td>'.$html.'</td>
					</tr>';
		}
		echo '</tbody></table><br/>';
	}
	
}
?>