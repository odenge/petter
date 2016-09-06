<h2>管理内容一覧</h2>
<?php
/* エラーメッセージ表示 */
if (count($error_message)) {
	foreach ($error_message as $message) {
		print('<p class="error_message">'.$message.'</p>'."\n");
	}
} 
?>


<form method="POST" action="admin.php">
<table class="default_table">
<?php
	readData(DATABASE, "default");
?>
</table>


<div class="clearfix">
<ul class="default_ul">
	<li class="left"><input type="submit" name="create_db_page" value="作成" /></li>
	<li class="center"><input type="submit" name="edit_db_page" value="設定変更" /></li>
	<li class="right"><input type="submit" name="delete_db" value="削除" onClick="return confirm('選択した内容を削除します。よろしいですか？')" /></li>
</ul>
</div>

</form>