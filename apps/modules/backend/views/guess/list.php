<?php
use common\YUrl;
require_once (dirname(__DIR__) . '/common/header.php');
?>

<div class="subnav">
	<div class="content-menu ib-a blue line-x">
		<a class="add fb"
			href="javascript:postDialog('addLottery', '<?php echo YUrl::createBackendUrl('Guess', 'add'); ?>', '添加活动', 600, 350)"><em>添加活动</em></a>
		<a href='javascript:;' class="on"><em>竞猜活动列表</em></a>
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
							    活动标题：<input type="text" name="title" value="" class="input text" />
							    时间：<input type="text" name="start_time" id="start_time" value="<?php echo $start_time; ?>" size="20" class="date input-text" />
								～ <input type="text" name="end_time" id="end_time" value="<?php echo $end_time; ?>" size="20" class="date input-text" />
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
						<th align="center">活动ID</th>
						<th align="center">活动名称</th>
						<th align="center">活动图片</th>
						<th align="center">截止时间</th>
						<th align="center">是否开奖</th>
						<th align="center">开奖结果</th>
						<th align="center">参与总人数</th>
						<th align="center">中奖总人数</th>
						<th align="center">投注总额</th>
						<th align="center">中奖总额</th>
						<th width="120" align="center">修改时间</th>
						<th width="120" align="center">创建时间</th>
						<th width="120" align="center">管理操作</th>
					</tr>
				</thead>
				<tbody>
                <?php foreach ($list as $item): ?>
    	           <tr>
						<td align="center"><?php echo $item['guess_id']; ?></td>
						<td align="center"><?php echo $item['title']; ?></td>
						<td align="center"><img alt="活动图片" src="<?php echo $item['image_url']; ?>" width="50" /></td>
						<td align="center"><?php echo $item['deadline']; ?></td>
						<td align="center"><?php echo $item['is_open'] ? '是' : '否'; ?></td>
						<td align="center"><?php echo $item['open_result']; ?></td>
						<td align="center"><?php echo $item['total_people']; ?></td>
						<td align="center"><?php echo $item['prize_people']; ?></td>
						<td align="center"><?php echo $item['total_bet_gold']; ?></td>
						<td align="center"><?php echo $item['total_prize_gold']; ?></td>
						<td align="center"><?php echo $item['modified_time']; ?></td>
						<td align="center"><?php echo $item['created_time']; ?></td>
						<td align="center">
						<a href="javascript:postDialog('GuessUsers', '<?php echo YUrl::createBackendUrl('Guess', 'record', ['guess_id' => $item['guess_id']]); ?>', '参与活动的用户列表', 800, 500)">参与列表</a><br />
						  <a href="###" onclick="edit(<?php echo $item['guess_id'] ?>, '<?php echo htmlspecialchars($item['title']) ?>')" title="修改">修改</a> |
						  <a href="###" onclick="deleteDialog('deleteGuess', '<?php echo YUrl::createBackendUrl('Guess', 'delete', ['guess_id' => $item['guess_id']]); ?>', '<?php echo htmlspecialchars($item['title']) ?>')" title="删除">删除</a>
						</td>
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