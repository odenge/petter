<h2 class="clearfix">
	<div class="db_id">ID　<?php echo $database_obj->getId(); ?></div>
	<?php echo $database_obj->getTitle(); ?>
</h2>
<h3>記事編集</h3>
<div class="link_up"><a href="admin.php?data=article_list&db_id=<?php echo $database_obj->getId(); ?>&page=1">記事一覧へ</a></div>
<?php
/* エラーメッセージ表示 */
if (count($error_message)) {
	foreach ($error_message as $message) {
		print('<p class="error_message">'.$message.'</p>'."\n");
	}
} 
?>


<form method="POST" action="<?php print($_SERVER["PHP_SELF"]); ?>">
<input type="hidden" name="db_id" value="<?php echo $database_obj->getId(); ?>" />
<input type="hidden" name="db_title" value="<?php echo $database_obj->getTitle(); ?>" />
<input type="hidden" name="article_id" value="<?php echo $article_obj->getArticleId(); ?>" />
<input type="hidden" name="second" value="<?php echo date("s", $article_obj->getTime()); ?>" />

<table cellpadding="0" cellspacing="0" class="form_table">
<tr>
	<th>タイトル</th>
	<td><input class="article_title" type="text" name="title" value="<?php echo $article_obj->getTitle(); ?>" /></td>
</tr>
<tr>
	<th>本文</th>
	<td><textarea class="article_contents" name="contents"><?php echo changeEditOutputBr( $article_obj->getContents() ); ?></textarea></td>
</tr>
<tr>
	<th>時間</th>
	<td>
<input class="time4" type="text" maxlength="4" name="year" value="<?php echo date("Y", $article_obj->getTime()); ?>" /> 年&nbsp;&nbsp;
<input class="time2" type="text" maxlength="2" name="month" value="<?php echo date("m", $article_obj->getTime()); ?>" /> 月&nbsp;&nbsp;
<input class="time2" type="text" maxlength="2" name="day" value="<?php echo date("d", $article_obj->getTime()); ?>" /> 日&nbsp;&nbsp;
<input class="time2" type="text" maxlength="2" name="hour" value="<?php echo date("H", $article_obj->getTime()); ?>" /> 時&nbsp;&nbsp;
<input class="time2" type="text" maxlength="2" name="minute" value="<?php echo date("i", $article_obj->getTime()); ?>" /> 分
	</td>
</tr>
</table>

<div class="form_btn"><input type="submit" name="edit_article" value="編集" /></div>

</form>