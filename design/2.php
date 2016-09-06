<?php $time = date("Y年m月d日　H:i", $timestamp); ?>

<tr>
<td class="new">
	<!-- ID -->
	<a name="<?php echo $article_id; ?>" id="<?php echo $article_id; ?>"></a>

	<!-- newマーク -->
	<?php
	$data_time = date("Y,m,d", $timestamp);
	if($new ==1 && $new_time == $data_time) {
		echo '<img src="images/new.gif" alt="NEW" title="NEW" />';
	}
	elseif($new ==2 && $now_time - $timestamp < 604800) {
		echo '<img src="images/new.gif" alt="NEW" title="NEW" />';
	}
	elseif($new ==3 && $now_time - $timestamp < 2592000) {
		echo '<img src="images/new.gif" alt="NEW" title="NEW" />';
	}
	?>
</td>
<td class="title">
	<!-- タイトル -->
	<?php echo $title; ?>
</td>
<td class="contents">
	<!-- 本文 -->
	<?php echo $contents; ?>
</td>
<td class="time">
	<!-- 時間 -->
	<?php echo $time; ?>
</td>
</tr>
