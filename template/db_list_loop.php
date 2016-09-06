<?php
/* 管理内容一覧表示 */
?>
<tr>
    <td class="checkbox"><input type="checkbox" name="id_array[<?php echo $id; ?>]" value="<?php echo $id; ?>"></td>
    <td class="db_content"><a href="admin.php?data=article_list&db_id=<?php echo $id; ?>&page=1"><?php echo $title; ?></a></td>
</tr>
