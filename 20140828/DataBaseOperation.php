<?php
define("DBSV", "localhost");	//サーバー名
define("DBUSER", "root");		//ユーザ名
define("DBPASS", "root");		//パスワード
define("DBNAME", "IPAhis3");		//データベース名
define("ENCDISP", "UTF-8");		//データベース側の文字コード

class DataBaseOperation {

	// -- コンストラクタ --
	// DB接続を行う
	function __construct() {
		$mysql = mysql_connect(DBSV, DBUSER, DBPASS);
		mysql_select_db(DBNAME);
		// 他の関数でも$mysqlを使えるようにする
		$_SESSION['mysql'] = $mysql;

		// 文字のエンコード設定
		mysql_query("set names utf8");

		// コネクションエラー確認
		if (!$mysql) {
			die('Invalid query: ' . mysql_error());
		}
	}
	//DB接続解除
	function db_close() {
		mysql_close($_SESSION['mysql']);
	}
	
	// -- 適当な細かいモジュール --
	// sql発行
	function myQuery($sql) {
		// SQL発行
		$result = mysql_query($sql);
		// 結果のチェック(debug)
		if (!$result) {
	    $message  = 'Invalid query: ' . mysql_error() . "\n";
			$message .= 'Whole query: ' . $sql;
			die($message);
		}
		return $result;
	}
	// SELECT文で抽出したデータを配列に格納
	function rowArray($result) {
		if (mysql_num_rows($result)) {
			// 抽出データを配列に格納
			while ($row = mysql_fetch_assoc($result)) {
				$array[] = $row;
			}
			return $array;
		} else return false;
	}

	// -- SELECT類 --
	// 指定されたデータ
	function selectQUE($str) {
		// question, an_id, htmlを出力
		$sql  = "SELECT H.hid, H.html ";
		$sql .= "FROM Htable as H, Ttable as T ";
		$sql .= "WHERE H.hid = T.hid AND T.tag LIKE '" . $str . "' ";
		$sql .= "GROUP BY H.hid ";
		// クエリ失敗or該当データ0行 はfalseが返ってきて、処理を終了する(呼び出し元に戻る)
		if ($flag = $this->rowArray($this->myQuery($sql))) $arrayH = $flag;
		else return $flag;

		// imageを出力
		$sql  = "SELECT I.hid, I.image ";
		$sql .= "FROM Htable as H, Itable as I, Ttable as T ";
		$sql .= "WHERE H.hid = I.hid AND H.hid = T.hid AND T.tag LIKE '".$str."'";
		// クエリ失敗or該当データ0行 はfalseが返ってきて、処理を終了する(呼び出し元に戻る)
		if ($flag = $this->rowArray($this->myQuery($sql))) $arrayI = $flag;
		else $arrayI = null;

		// QtableとItableの抽出結果を統合する
		for ($i=0; $i<count($arrayI); $i++) {
			for ($j=0; $j<count($arrayH); $j++) {
				// echo "Q: ".$arrayQ[$j]['q_id']."<br/>I: ".$arrayI[$i]['q_id']."<br/><br/>";
				if ($arrayH[$j]['hid'] == $arrayI[$i]['hid']) {
					$arrayH[$j]['image'][] = $arrayI[$i]['image'];
				}
			}
		}

/*
		// デバック用
		echo "<pre>";
		var_dump($arrayH);
		echo "</pre>";
*/

		return $arrayH;
	}
	// 全てのtagを抽出
	function selectTAG() {
		// question, an_id, htmlを出力
		$sql  = "SELECT tag FROM Ttable ";
		// クエリ失敗or該当データ0行 はfalseが返ってきて、処理を終了する(呼び出し元に戻る)
		if ($flag = $this->rowArray($this->myQuery($sql))) $arrayT = $flag;
		else return $flag;

		return $arrayT;
	}
	// 選択されたタグを抽出
	function selectSELTAG($str) {
		// question, an_id, htmlを出力
		$sql  = "SELECT tag FROM Ttable WHERE tag LIKE '".$str."%' ";
		// クエリ失敗or該当データ0行 はfalseが返ってきて、処理を終了する(呼び出し元に戻る)
		if ($flag = $this->rowArray($this->myQuery($sql))) $arrayT = $flag;
		else return $flag;

		return $arrayT;
	}
	//
	function selectBROTAG($str) {
		$sql = "SELECT hid FROM Ttable WHERE tag LIKE '".$str."' ";
		if ($flag = $this->rowArray($this->myQuery($sql))) $arrayNUM = $flag;
		else return $flag;

		foreach ($arrayNUM as $value) {
			$sql  = "SELECT tag FROM Ttable WHERE hid LIKE '".$value['hid']."' ";
			if ($flag = $this->rowArray($this->myQuery($sql))) $arrayTAG[] = $flag;
			else $arrayTAG[] = $flag;
		}
		
		return array($arrayTAG, $arrayNUM);
	}
	function selectBROTAG2($str) {
		$sql = "SELECT hid FROM Ttable WHERE tag LIKE '".$str."' ";
		if ($flag = $this->rowArray($this->myQuery($sql))) $arrayNUM = $flag;
		else return $flag;
		
		return $arrayNUM;
	}
	// 全てのデータを抽出
	function selectALL() {
		// question, an_id, htmlを出力
		$sql  = "SELECT * FROM Htable ";
		// クエリ失敗or該当データ0行 はfalseが返ってきて、処理を終了する(呼び出し元に戻る)
		if ($flag = $this->rowArray($this->myQuery($sql))) $arrayH = $flag;
		else return $flag;

		// imageを出力
		$sql  = "SELECT I.hid, I.image ";
		$sql .= "FROM Htable as H, Itable as I ";
		$sql .= "WHERE H.hid = I.hid ";
		// クエリ失敗or該当データ0行 はfalseが返ってきて、処理を終了する(呼び出し元に戻る)
		if ($flag = $this->rowArray($this->myQuery($sql))) $arrayI = $flag;
		else $arrayI = null;

		// QtableとItableの抽出結果を統合する
		for ($i=0; $i<count($arrayI); $i++) {
			for ($j=0; $j<count($arrayH); $j++) {
				// echo "Q: ".$arrayQ[$j]['q_id']."<br/>I: ".$arrayI[$i]['q_id']."<br/><br/>";
				if ($arrayH[$j]['hid'] == $arrayI[$i]['hid']) {
					$arrayH[$j]['image'][] = $arrayI[$i]['image'];
				}
			}
		}

/*
		// デバック用
		echo "<pre>";
		var_dump($arrayH);
		echo "</pre>";
*/

		return $arrayH;
	}
}
?>