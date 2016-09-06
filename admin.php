<?php
session_start();

/* 設定ファイルを読み込む */
require_once("set_up.php");

// ログイン済みかどうかの変数チェックを行う
if ($_SESSION["petter_user_name"] != PETTER_USER_NAME || $_SESSION["petter_password"] != PETTER_PASSWORD) {

// ユーザー名とパスワードがセッションにない場合は不正な処理と判断し、ログイン画面へリダイレクトさせる
$no_login_url = "index.php";
header("Location: {$no_login_url}");
exit;
}




    if(isset($_POST['logout'])){
     
    // セッション変数を全て解除する
    $_SESSION = array();
     
    // セッションを切断するにはセッションクッキーも削除する。
    // Note: セッション情報だけでなくセッションを破壊する。
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
     
    //セッションを破壊してリダイレクト
    session_destroy();
    header("Location:index.php");
    }
?>




<?php
require_once("function.php");
?>

<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD
  XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta http-equiv="content-language" content="ja" />
<meta http-equiv="content-style-type" content="text/css" />
<link rel="stylesheet" href="css/style.css" media="all" /> 
<meta http-equiv="content-script-type" content="text/javascript" />
<title>PETTER　管理画面</title>




</head>
<body>

<div id="wrapper">

<div id="contents">

<div id="main">


<div id="header" class="clearfix">

<h1><img src="images/title_petter.jpg" alt="Petter" border="0" usemap="#Map" />
<map name="Map" id="Map"><area shape="rect" coords="213,6,247,39" href="http://www.love.co.jp/" alt="rss2.0" target="_blank" />
<area shape="rect" coords="2,0,178,39" href="admin.php" alt="PETTER" />
</map></h1>


<div class="logout">
<form action="admin.php" method="post">
<p><button type="submit" name="logout">ログアウト</button></p>
</form>
</div>

</div><!-- header -->





<?php
if($_SERVER["REQUEST_METHOD"] == "POST") {

	/* エラーメッセージを格納する */
	$error_message = array();




	/******************************************************/
	/* 管理データベース処理 */
	/******************************************************/

	/* インストール後の管理内容書き込み処理 */
	if($_POST["entrance"]) {

		if($_POST["title"] == "") {
			$error_message[] = "管理する内容を入力してください。";
		}
		/* エラーがあったらメッセージを表示 */
		if (count($error_message)) {
			require_once("template/entrance.php");
		} 
		else {
			$fp = fopen(DATABASE, "wb");
			fclose($fp);
			writeDataDB(DATABASE);
			require_once("template/default.php");
		}

	}




	/* 管理データベース新規作成ページを表示 */
	if($_POST["create_db_page"]) {
		require_once("template/create_db.php");
	}
	/* 管理データベース新規作成ページの処理 */
	if($_POST["create_db"]) {
		if($_POST["title"] == "") {
			$error_message[] = "管理する内容を入力してください。";
		}
		if($_POST["article_number_set"] == "0" && $_POST["article_number"] != "") {
			$error_message[] = "表示件数が無制限の時には件数を入力しないでください。<br/>
					件数を指定する時には、件数指定をチェックして件数を入力してください。";
		}
		if($_POST["article_number_set"] == "1") {
			$article_number = mb_convert_kana($_POST["article_number"], "a", "UTF-8"); 
			if ($_POST["article_number"] == "" || is_numeric($article_number) == false) {
				$error_message[] = "件数指定は数字で入力してください。";
			}
			elseif ($article_number < 0) {
				$error_message[] = "件数指定は0以上の数字で入力してください。";
			}
		}
		$url_no_space = changeSpace($_POST["url"]);
		if($url_no_space != "") {
			if (! preg_match('/^(ht|f)tp(s)?:\/\/[\w]/', $url_no_space)) {
				$error_message[] = "正しいURLを入力してください。";
			}
		}
		/* エラーがあったらメッセージを表示 */
		if (count($error_message)) {
			require_once("template/create_db_error.php");
		} 
		else {
			writeDataDB(DATABASE);
			require_once("template/default.php");
		}
	}




	/* 管理データベース削除 */
	if($_POST["delete_db"]) {
		if($_POST["id_array"]) {
			/* 削除するデータベースIDの配列を引数として渡す */
			deleteDataDB(DATABASE, $_POST["id_array"]);
			require_once("template/default.php");
		}
		else {
			$error_message[] = "削除する内容を選択してください。";
			/* エラーがあったらメッセージを表示 */
			require_once("template/default.php");
		}
	}




	/* 管理データベース編集ページを表示 */
	if($_POST["edit_db_page"]) {
		if($_POST["id_array"]) {
			if( count($_POST["id_array"]) != 1 ) {
				$error_message[] = "設定を変更する内容は1つだけ選択してください。";
			}
		}
		else {
			$error_message[] = "設定を変更する内容を選択してください。";
		}
		/* エラーがあったらメッセージを表示 */
		if (count($error_message)) {
			require_once("template/default.php");
		} 
		else {
			foreach($_POST["id_array"] as $edit_id) {
				/* 【管理データベースクラス作成】　指定した管理データベースIDの内容を操作するクラス*/
				$database_obj = new DatabaseManage($edit_id);
			}
			require_once("template/edit_db.php");
		}	
	}
	/* 管理データベース編集ページの処理 */
	elseif($_POST["edit_db"]) {
		if($_POST["title"] == "") {
			$error_message[] = "管理内容を入力してください。";
		}
		if($_POST["article_number_set"] == "0" && $_POST["article_number"] != "") {
			$error_message[] = "表示件数が無制限の時には件数を入力しないでください。<br/>
					件数を指定する時には、件数指定をチェックして件数を入力してください。";
		}
		if($_POST["article_number_set"] == "1") {
			$article_number = mb_convert_kana($_POST["article_number"], "a", "UTF-8"); 
			if ($_POST["article_number"] == "" || is_numeric($article_number) == false) {
				$error_message[] = "件数指定は数字で入力してください。";
			}
			elseif ($article_number < 0) {
				$error_message[] = "件数指定は0以上の数字で入力してください。";
			}
		}
		$url_no_space = changeSpace($_POST["url"]);
		if($url_no_space != "") {
			if (! preg_match('/^(ht|f)tp(s)?:\/\/[\w]/', $url_no_space)) {
				$error_message[] = "正しいURLを入力してください。";
			}
		}
		/* エラーがあったらメッセージを表示 */
		if (count($error_message)) {
			require_once("template/edit_db_error.php");
		} 
		else {
			editDataDB(DATABASE);
			/* RSS作成 */
			writeRSS($_POST["id"]);

			require_once("template/default.php");
		}
	}




	/******************************************************/
	/* 個別記事データベース処理 */
	/******************************************************/

	/* 個別記事データベース新規作成ページを表示 */
	if($_POST["create_article_page"]) {
		/* 現在の時間を取得して変数に代入 */
		$time_array = explode(",", date("Y,n,j,G,i,s"));
		$year = $time_array[0];
		$month = $time_array[1];
		$day = $time_array[2];
		$hour = $time_array[3];
		$minute = $time_array[4];
		$second = $time_array[5];
		require_once("template/create_article.php");
	}
	/* 個別記事データベース新規作成処理 */
	if($_POST["create_article"]) {

		$year = mb_convert_kana($_POST["year"], "a", "UTF-8");
		$month = mb_convert_kana($_POST["month"], "a", "UTF-8");
		$day = mb_convert_kana($_POST["day"], "a", "UTF-8");
		$hour = mb_convert_kana($_POST["hour"], "a", "UTF-8");
		$minute = mb_convert_kana($_POST["minute"], "a", "UTF-8");

		if($_POST["title"] == "") {
			$error_message[] = "タイトルを入力してください。";
		}
		if($_POST["contents"] == "") {
			$error_message[] = "本文を入力してください。";
		}
		if ($_POST["year"] == "" || is_numeric($year) == false) {
			$error_message[] = "年は数字で入力してください。";
		}
		elseif ($year < 1971 || $year > 2037) {
			$error_message[] = "年は西暦で4桁の数字で入力してください。<br />
								1971年～2037年の時間で入力してください。";
		}		
		if ($_POST["month"] == "" || is_numeric($month) == false) {
			$error_message[] = "月は数字で入力してください。";
		}
		elseif ($month < 1 || $month > 12) {
			$error_message[] = "正しい月を数字で入力してください。";
		}		
		if ($_POST["day"] == "" || is_numeric($day) == false) {
			$error_message[] = "日は数字で入力してください。";
		}
		elseif ($day < 1 || $day > 31) {
			$error_message[] = "正しい日を数字で入力してください。";
		}
		if ($_POST["hour"] == "" || is_numeric($hour) == false) {
			$error_message[] = "時は数字で入力してください。";
		}
		elseif ($hour < 0 || $hour > 24) {
			$error_message[] = "正しい時を数字で入力してください。";
		}
		if ($_POST["minute"] == "" || is_numeric($minute) == false) {
			$error_message[] = "分は数字で入力してください。";
		}
		elseif ($minute < 0 || $minute > 59) {
			$error_message[] = "正しい分を数字で入力してください。";
		}

		/* エラーがあったらメッセージを表示 */
		if (count($error_message)) {
			require_once("template/create_article_error.php");
		} 
		else {
			writeDataArticle("db/".$_POST["db_id"]."/article.csv");
			/* 【管理データベースクラス作成】　指定した管理データベースIDの内容を操作するクラス*/
			$database_obj = new DatabaseManage($_POST["db_id"]);
			/* RSS作成 */
			writeRSS($_POST["db_id"]);
			/* 記事リストの1ページ目を表示設定 */
			$page = 1;
			require_once("template/article_list.php");
		}

	}




	/* 個別記事データベース編集処理 */
	if($_POST["edit_article"]) {

		$year = mb_convert_kana($_POST["year"], "a", "UTF-8");
		$month = mb_convert_kana($_POST["month"], "a", "UTF-8");
		$day = mb_convert_kana($_POST["day"], "a", "UTF-8");
		$hour = mb_convert_kana($_POST["hour"], "a", "UTF-8");
		$minute = mb_convert_kana($_POST["minute"], "a", "UTF-8");

		if($_POST["title"] == "") {
			$error_message[] = "タイトルを入力してください。";
		}
		if($_POST["contents"] == "") {
			$error_message[] = "本文を入力してください。";
		}
		if ($_POST["year"] == "" || is_numeric($year) == false) {
			$error_message[] = "年は数字で入力してください。";
		}
		elseif ($year < 1971 || $year > 2037) {
			$error_message[] = "年は西暦で4桁の数字で入力してください。<br />
								1971年～2037年の時間で入力してください。";
		}		
		if ($_POST["month"] == "" || is_numeric($month) == false) {
			$error_message[] = "月は数字で入力してください。";
		}
		elseif ($month < 1 || $month > 12) {
			$error_message[] = "正しい月を数字で入力してください。";
		}		
		if ($_POST["day"] == "" || is_numeric($day) == false) {
			$error_message[] = "日は数字で入力してください。";
		}
		elseif ($day < 1 || $day > 31) {
			$error_message[] = "正しい日を数字で入力してください。";
		}
		if ($_POST["hour"] == "" || is_numeric($hour) == false) {
			$error_message[] = "時は数字で入力してください。";
		}
		elseif ($hour < 0 || $hour > 24) {
			$error_message[] = "正しい時を数字で入力してください。";
		}
		if ($_POST["minute"] == "" || is_numeric($minute) == false) {
			$error_message[] = "分は数字で入力してください。";
		}
		elseif ($minute < 0 || $minute > 59) {
			$error_message[] = "正しい分を数字で入力してください。";
		}

		/* エラーがあったらメッセージを表示 */
		if (count($error_message)) {
			require_once("template/edit_article_error.php");
		} 
		else {
			editDataArticle("db/".$_POST["db_id"]."/article.csv", $_POST["article_id"]);
			/* 【管理データベースクラス作成】　指定した管理データベースIDの内容を操作するクラス*/
			$database_obj = new DatabaseManage($_POST["db_id"]);
			/* RSS作成 */
			writeRSS($_POST["db_id"]);
			/* 記事リストの1ページ目を表示設定 */
			$page = 1;
			require_once("template/article_list.php");
		}

	}



	
		/* 個別記事データベース削除 */
	if($_POST["delete_article"]) {
		if($_POST["id_array"]) {
			/* 削除するデータベースIDの配列を引数として渡す */
			deleteDataArticle("db/".$_POST["db_id"]."/article.csv", $_POST["id_array"]);
			/* 【管理データベースクラス作成】　指定した管理データベースIDの内容を操作するクラス*/
			$database_obj = new DatabaseManage($_POST["db_id"]);
			/* RSS作成 */
			writeRSS($_POST["db_id"]);
			/* 記事リストの1ページ目を表示設定 */
			$page = 1;
			require_once("template/article_list.php");
		}
		else {
			$error_message[] = "削除する項目を選択してください。";
			/* 【管理データベースクラス作成】　指定した管理データベースIDの内容を操作するクラス*/
			$database_obj = new DatabaseManage($_POST["db_id"]);
			/* 記事リストの1ページ目を表示設定 */
			$page = 1;
			require_once("template/article_list.php");
		}
	}



}




else {
	/* すでにPETTERを使っている場合 */
	if (file_exists(DATABASE)) {

		/******************************************************/
		/* テキストリンクをクリックしたときのデータ処理 */
		/******************************************************/

		/* 表示する画面の場合分け */
		$data = $_GET["data"];
		/* 記事が投稿されているデータベースのID */
		$db_id = $_GET["db_id"];

		/* ●記事管理画面 */
		if ($data == "article_list") {			
			/* 表示させる記事のページ */
			$page = $_GET["page"];

			/* 【管理データベースクラス作成】　指定した管理データベースIDの内容を操作するクラス*/
			$database_obj = new DatabaseManage($db_id);
			require_once("template/article_list.php");
		}

		/* ●記事編集画面 */
		elseif ($data == "article_edit") {			
			/* 記事の個別ID */
			$article_id = $_GET["article_id"];
			/* 【管理データベースクラス作成】　指定した管理データベースIDの内容を操作するクラス*/
			$database_obj = new DatabaseManage($db_id);			
			/* 【管理記事クラス作成】　指定した記事IDの内容を操作するクラス*/
			$article_obj = new ArticleManage($db_id, $article_id);
			require_once("template/edit_article.php");
		}

		/* ●管理画面トップ */
		else {
			require_once("template/default.php");
		}

	}

	/* ●インストール後設定画面 */
	else {
		require_once("template/entrance.php");
	}
}
?>


</div><!-- main -->

</div><!-- contents -->

</div><!-- wrapper -->

</body>
</html>
