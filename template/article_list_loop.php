<?php $time = date("Y年　m月d日　H:i", $timestamp); ?>




<tr>
    <td class="checkbox"><input type="checkbox" name="id_array[<?php echo $article_id ?>]" value="<?php echo $article_id ?>"></td>
    <td class="title"><a href="admin.php?data=article_edit&db_id=<?php echo $db_id ?>&article_id=<?php echo $article_id ?>"><?php echo $title ?></a></td>
    <td class="time"><?php echo $time ?></td>
</tr>
