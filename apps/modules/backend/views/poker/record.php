<?php
use common\YUrl;
require_once (dirname(__DIR__) . '/common/header.php');
?>

<div class="subnav">
	<div class="content-menu ib-a blue line-x">
		<a href='javascript:;' class="on"><em>用户参与记录列表</em></a>
	</div>
</div>
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
							    是否开奖：<select name="is_open">
							        <option <?php echo $is_open==-1 ? 'selected="selected"' : ''; ?> value="-1">全部</option>
							        <option <?php echo $is_open==0 ? 'selected="selected"' : ''; ?> value="0">否</option>
							        <option <?php echo $is_open==1 ? 'selected="selected"' : ''; ?> value="1">是</option>
							    </select>
							    用户牌型：<select name="poker_type">
							        <option <?php echo $poker_type==-1 ? 'selected="selected"' : ''; ?> value="-1">全部</option>
							        <option <?php echo $poker_type==0 ? 'selected="selected"' : ''; ?> value="0">否</option>
							        <option <?php echo $poker_type==1 ? 'selected="selected"' : ''; ?> value="1">是</option>
							    </select>
							    用户名：<input type="text" name="username" value="" class="input text" />
							    手机号：<input type="text" name="mobilephone" value="" class="input text" />
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
						<th align="center">记录ID</th>
						<th align="center">用户名</th>
						<th align="center">手机号</th>
						<th align="center">投注金额</th>
						<th align="center">是否中奖</th>
						<th align="center">中奖金币</th>
						<th align="center">用户翻到的牌</th>
						<th align="center">系统给出的牌</th>
						<th width="120" align="center">创建时间</th>
					</tr>
				</thead>
				<tbody>
                <?php foreach ($list as $item): ?>
    	           <tr>
						<td align="center"><?php echo $item['id']; ?></td>
						<td align="center"><?php echo $item['username']; ?></td>
						<td align="center"><?php echo $item['mobilephone']; ?></td>
						<td align="center"><?php echo $item['bet_gold']; ?></td>
						<td align="center"><?php echo $item['is_prize'] ? '是' : '否'; ?></td>
						<td align="center"><?php echo $item['prize_money']; ?></td>
						<td align="center"><?php echo $item['poker']; ?></td>
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
<script type="text/javascript">
Calendar.setup({
	weekNumbers: false,
    inputField : "start_time",
    trigger    : "start_time",
    dateFormat: "%Y-%m-%d %H:%I:%S",
    showTime: true,
    minuteStep: 1,
    onSelect   : function() {this.hide();}
});

Calendar.setup({
	weekNumbers: false,
    inputField : "end_time",
    trigger    : "end_time",
    dateFormat: "%Y-%m-%d %H:%I:%S",
    showTime: true,
    minuteStep: 1,
    onSelect   : function() {this.hide();}
});

function edit(id, name) {
	var title = '修改『' + name + '』';
	var page_url = "<?php echo YUrl::createBackendUrl('Guess', 'edit'); ?>?guess_id="+id;
	postDialog('editGuess', page_url, title, 600, 350);
}
</script>
</body>
</html>