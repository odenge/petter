<h3>PETTERへようこそ。<br />
情報を管理する魔法　PETTERをぜひ使ってください。
</h3>
<?php
/* エラーメッセージ表示 */
if (count($error_message)) {
	foreach ($error_message as $message) {
		print('<p class="error_message">'.$message.'</p>'."\n");
	}
} 
?>


<form method="POST" action="<?php print($_SERVER["PHP_SELF"]) ?>">

<table cellpadding="0" cellspacing="0" class="form_table">
<tr>
	<th>管理内容</th>
	<td><input class="title" type="text" name="title" /></td>
</tr>
</table>

<div class="form_btn"><input type="submit" name="entrance" value="PETTER" /></div>

</form>