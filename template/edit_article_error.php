<h2 class="clearfix">
	<div class="db_id">ID　<?php echo $_POST["db_id"]; ?></div>
	<?php echo $_POST["db_title"]; ?>
</h2>
<h3>記事編集</h3>
<div class="link_up"><a href="admin.php?data=article_list&db_id=<?php echo $_POST["db_id"]; ?>&page=1">記事一覧へ</a></div>
<?php
/* エラーメッセージ表示 */
if (count($error_message)) {
	foreach ($error_message as $message) {
		print('<p class="error_message">'.$message.'</p>'."\n");
	}
} 
?>


<form method="POST" action="<?php print($_SERVER["PHP_SELF"]); ?>">
<input type="hidden" name="db_id" value="<?php echo $_POST["db_id"]; ?>" />
<input type="hidden" name="db_title" value="<?php echo $_POST["db_title"]; ?>" />
<input type="hidden" name="article_id" value="<?php echo $_POST["article_id"]; ?>" />
<input type="hidden" name="second" value="<?php echo $_POST["second"]; ?>" />

<table cellpadding="0" cellspacing="0" class="form_table">
<tr>
	<th>タイトル</th>
	<td><input class="article_title" type="text" name="title" value="<?php echo $_POST["title"]; ?>" /></td>
</tr>
<tr>
	<th>本文</th>
	<td><textarea class="article_contents" name="contents" ><?php echo $_POST["contents"]; ?></textarea></td>
</tr>
<tr>
	<th>時間</th>
	<td>
<input class="time4" type="text" maxlength="4" name="year" value="<?php echo $_POST["year"]; ?>" /> 年&nbsp;&nbsp;
<input class="time2" type="text" maxlength="2" name="month" value="<?php echo $_POST["month"]; ?>" /> 月&nbsp;&nbsp;
<input class="time2" type="text" maxlength="2" name="day" value="<?php echo $_POST["day"]; ?>" /> 日&nbsp;&nbsp;
<input class="time2" type="text" maxlength="2" name="hour" value="<?php echo $_POST["hour"]; ?>" /> 時&nbsp;&nbsp;
<input class="time2" type="text" maxlength="2" name="minute" value="<?php echo $_POST["minute"]; ?>" /> 分
	</td>
</tr>
</table>

<div class="form_btn"><input type="submit" name="edit_article" value="編集" /></div>

</form>