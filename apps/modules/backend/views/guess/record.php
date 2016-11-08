<?php
require_once (dirname(__DIR__) . '/common/header.php');
?>

<style type="text/css">
html {
	_overflow-y: scroll
}
</style>
<div class="pad-lr-10">

	<form name="searchform" action="" method="get">
		<table width="100%" cellspacing="0" class="search-form">
			<tbody>
				<tr>
					<td>
						<div class="explain-col">
							<p style="margin-top: 10px;">
							手机号码：<input name="mobilephone" type="text" class="input-text" placeholder="手机号码" />
							用户账号：<input name="username" type="text" class="input-text" placeholder="用户账号" />
							是否中奖：<select name="is_prize">
							        <option <?php echo $is_prize==-1 ? 'selected="selected"' : ''; ?> value="-1">全部</option>
							        <option <?php echo $is_prize==0 ? 'selected="selected"' : ''; ?> value="0">否</option>
							        <option <?php echo $is_prize==1 ? 'selected="selected"' : ''; ?> value="1">是</option>
							    </select>
							    <input type="hidden" name="guess_id" value="<?php echo $guess_id; ?>" />
							    <input type="submit" name="search" class="button" value="搜索" />
							</p>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</form>

	<form name="myform" id="myform" action="" method="post">
		<div class="table-list">
			<table width="100%" cellspacing="0">
				<thead>
					<tr>
						<th align="center">用户账号</th>
						<th align="center">手机号码</th>
						<th align="center">投注金币</th>
						<th align="center">中奖金币</th>
						<th align="center">是否中奖</th>
						<th align="center">投注时间</th>
					</tr>
				</thead>
				<tbody>
                <?php foreach ($list as $item): ?>
    	           <tr>
						<td align="center"><?php echo $item['username']; ?></td>
						<td align="center"><?php echo $item['mobilephone']; ?></td>
						<td align="center"><?php echo $item['bet_gold']; ?></td>
						<td align="center"><?php echo $item['prize_money']; ?></td>
						<td align="center"><?php echo $item['is_prize'] ? '是' : '否'; ?></td>
						<td align="center"><?php echo $item['created_time']; ?></td>
			       </tr>
                <?php endforeach; ?>
                </tbody>
			</table>

			<div id="pages">
<?php echo $page_html; ?>
</div>

		</div>

	</form>
</div>

</body>
</html>