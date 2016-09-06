<?php
session_start();

/* 設定ファイルを読み込む */
require_once("set_up.php");

/* エラーメッセージを格納する */
$error_message = array();

// ログインボタンが押されたかを判定
// 初めてのアクセスでは認証は行わずエラーメッセージは表示しないように
if (isset($_POST["login"])) {

	// user_nameが「odenge」でpasswordが「koike」だとログイン出来るようになっている
	if ($_POST["petter_user_name"] == PETTER_USER_NAME && $_POST["petter_password"] == PETTER_PASSWORD) {

		// ログインが成功した証をセッションに保存
		$_SESSION["petter_user_name"] = $_POST["petter_user_name"];
		$_SESSION["petter_password"] = $_POST["petter_password"];

		// 管理者専用画面へリダイレクト
		$login_url = "admin.php";
		header("Location: {$login_url}");
		exit;
	}
	else {
		if ($_POST["petter_user_name"] != PETTER_USER_NAME) {
			$error_message[] = "ユーザ名が違います。";
		}
		if ($_POST["petter_password"] != PETTER_PASSWORD) {
			$error_message[] = "パスワードが違います。";
		}
	}

}
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
<title>PETTER　ログイン画面</title>
</head>
<body>

<div id="wrapper">

<div id="contents">

<div id="main">


<div id="header" class="clearfix">

<h1><img src="images/title_petter.jpg" alt="Petter" border="0" usemap="#Map" />
<map name="Map" id="Map"><area shape="rect" coords="213,6,247,39" href="http://www.love.co.jp/" alt="rss2.0" target="_blank" />
<area shape="rect" coords="2,0,178,39" href="admin.php" alt="PETTER" />
</map></a></h1>

</div><!-- header -->




<div class="login">
<?php
/* エラーがあったらメッセージを表示 */
if (count($error_message)) {
	foreach ($error_message as $message) {
		print('<p class="error_message">'.$message.'</p>'."\n");
	}
} 
?>
<form action="index.php" method="POST">

<table cellpadding="0" cellspacing="0" class="login_table">
<tr>
	<th>ユーザ名：</th>
	<td><input type="text" name="petter_user_name" value="<?php echo $_POST["petter_user_name"] ?>" /></td>
</tr>
<tr>
	<th>パスワード：</th>
	<td><input type="password" name="petter_password" value="<?php echo $_POST["petter_password"] ?>" /></td>
</tr>
</table>

<div class="login_btn"><input type="submit" name="login" value="ログイン" /></div>

</form>
</div>






</div><!-- main -->

</div><!-- contents -->

</div><!-- wrapper -->

</body>
</html>