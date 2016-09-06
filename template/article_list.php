<h2 class="clearfix">
	<div class="db_id">ID　<?php echo $database_obj->getId(); ?></div>
	<?php echo $database_obj->getTitle(); ?>
</h2>
<h3>記事一覧</h3>
<div class="link_up"><a href="admin.php">管理内容一覧へ</a></div>
<?php
/* エラーメッセージ表示 */
if (count($error_message)) {
	foreach ($error_message as $message) {
		print('<p class="error_message">'.$message.'</p>'."\n");
	}
} 
?>


<div class="article_page_list">
<?php
	/* ページリスト表示させる */
	displayArriclePageList($database_obj->getId(), $page);
?>
</div>


<form method="POST" action="<?php print($_SERVER["PHP_SELF"]); ?>">
<input type="hidden" name="db_id" value="<?php echo $database_obj->getId(); ?>" />
<input type="hidden" name="db_title" value="<?php echo $database_obj->getTitle(); ?>" />
<table class="article_list_table">
<tr>
	<th>&nbsp;</th>
	<th>タイトル</th>
	<th>時間</th>
</tr>

<?php
	readData("db/".$database_obj->getId()."/article.csv", "article_list");
?>

</table>


<div class="clearfix">
<ul class="article_list_ul">
	<li class="left"><input type="submit" name="create_article_page" value="作成" /></li>
	<li class="right"><input type="submit" name="delete_article" value="削除" onClick="return confirm('選択した内容を削除します。よろしいですか？')" /></li>
</ul>
</div>


</form>