<h2>管理設定変更</h2>
<div class="link_up"><a href="admin.php">管理内容一覧へ</a></div>
<?php
/* エラーメッセージ表示 */
if (count($error_message)) {
	foreach ($error_message as $message) {
		print('<p class="error_message">'.$message.'</p>'."\n");
	}
} 
?>


<form method="POST" action="<?php print($_SERVER["PHP_SELF"]) ?>" />
<input type="hidden" name="id" value="<?php echo $_POST["id"]; ?>" />

<table cellpadding="0" cellspacing="0" class="form_table">
<tr>
	<th>管理内容</th>
	<td><input class="title" type="text" name="title" value="<?php echo $_POST["title"]; ?>" /></td>
</tr>
<tr>
	<th>表示順</th>
	<td class="clearfix">
		<ul class="form_ul">
			<li><input type="radio" name="order" value="0" id="desc" <?php if($_POST["order"] == 0){echo "checked";} ?> /><label for="desc"> 上が最新</label></li>
			<li><input type="radio" name="order" value="1" id="asc" <?php if($_POST["order"] == 1){echo "checked";} ?> /><label for="asc"> 下が最新</label></li>
		</ul>
	</td>
</tr>
<tr>
	<th>表示件数</th>
	<td class="clearfix">
		<ul class="form_ul">
			<li><input type="radio" name="article_number_set" value="0" id="number_set_no" <?php if($_POST["article_number_set"] == 0){echo "checked";} ?> /><label for="number_set_no"> 無制限</label></li>
			<li><input type="radio" name="article_number_set" value="1" id="number_set_yes" <?php if($_POST["article_number_set"] != 0){echo "checked";} ?> /><label for="number_set_yes"> 件数指定</label> <input class="article_number" type="text" name="article_number" value="<?php echo $_POST["article_number"]; ?>" /></li>
		</ul>
	</td>
</tr>
<tr>
	<th>newマーク</th>
	<td class="clearfix">
		<ul class="form_ul">
			<li><input type="radio" name="new" value="0" id="new0" <?php if($_POST["new"] == 0){echo "checked";} ?> /><label for="new0"> 無し</label></li>
			<li><input type="radio" name="new" value="1" id="new1" <?php if($_POST["new"] == 1){echo "checked";} ?> /><label for="new1"> 最新投稿日</label></li>
			<li><input type="radio" name="new" value="2" id="new2" <?php if($_POST["new"] == 2){echo "checked";} ?> /><label for="new2"> 1週間以内</label></li>
			<li><input type="radio" name="new" value="3" id="new3" <?php if($_POST["new"] == 3){echo "checked";} ?> /><label for="new3"> 1ヶ月以内</label></li>
		</ul>
	</td>
</tr>
<tr>
	<th>デザインテンプレート</th>
	<td>
<select name="design">
<?php
if ($handle = opendir('design/')) {
	while (false !== ($file = readdir($handle))) {
		$file = str_replace(".", "", $file);
		$file = str_replace("php", "", $file);
		if ($file != "") {
			if ($_POST["design"] == $file) {
				print("<option selected>".$file."</option>");
			}
			else {
				print("<option>".$file."</option>");
			}
		}
	}
	closedir($handle);
}
?>
</select>
	</td>
</tr>
<tr>
	<th>表示ページURL</th>
	<td><input class="url" type="text" name="url" value="<?php echo $_POST["url"]; ?>" /></td>
</tr>
</table>

<div class="form_btn"><input type="submit" name="edit_db" value="更新" /></div>

</form>