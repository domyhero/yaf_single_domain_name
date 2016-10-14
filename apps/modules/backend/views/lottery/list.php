<?php
use common\YUrl;
require_once (dirname(__DIR__) . '/common/header.php');
?>

<div class="subnav">
	<div class="content-menu ib-a blue line-x">
		<a class="add fb"
			href="javascript:postDialog('addLottery', '<?php echo YUrl::createBackendUrl('Lottery', 'add'); ?>', '添加活动', 600, 350)"><em>添加活动</em></a>
		<a href='javascript:;' class="on"><em>彩票活动列表</em></a>
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
							显示状态：<select name="display">
							        <option <?php echo $display==-1 ? 'selected="selected"' : ''; ?> value="-1">全部</option>
							        <option <?php echo $display==0 ? 'selected="selected"' : ''; ?> value="0">否</option>
							        <option <?php echo $display==1 ? 'selected="selected"' : ''; ?> value="1">是</option>
							    </select>
							活动状态：<select name="activity_status">
							        <option <?php echo $activity_status==-1 ? 'selected="selected"' : ''; ?> value="-1">全部</option>
							        <option <?php echo $activity_status==0 ? 'selected="selected"' : ''; ?> value="0">报名中</option>
							        <option <?php echo $activity_status==1 ? 'selected="selected"' : ''; ?> value="1">开奖进行中</option>
							        <option <?php echo $activity_status==2 ? 'selected="selected"' : ''; ?> value="2">活动已经结束</option>
							    </select>
							    彩票类型：
							    <select name="lottery_type">
							        <option <?php echo $lottery_type==-1 ? 'selected="selected"' : ''; ?> value="-1">全部</option>
							        <option <?php echo $lottery_type==1 ? 'selected="selected"' : ''; ?> value="1">双色球</option>
							        <option <?php echo $lottery_type==2 ? 'selected="selected"' : ''; ?> value="2">大乐透</option>
							    </select>
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
						<th align="center">彩票类型</th>
						<th align="center">金额</th>
						<th align="center">注数</th>
						<th align="center">人数上限</th>
						<th align="center">开放时间</th>
						<th align="center">开始 ~ 结束</th>
						<th align="center">中奖金额</th>
						<th align="center">参与人数</th>
						<th align="center">是否显示</th>
						<th width="120" align="center">修改时间</th>
						<th width="120" align="center">创建时间</th>
						<th width="100" align="center">管理操作</th>
					</tr>
				</thead>
				<tbody>
                <?php foreach ($list as $item): ?>
    	           <tr>
						<td align="center"><?php echo $item['aid']; ?></td>
						<td align="center"><?php echo $item['title']; ?></td>
						<td align="center"><?php echo $item['lottery_label']; ?></td>
						<td align="center"><?php echo $item['bet_money']; ?></td>
						<td align="center"><?php echo $item['bet_count']; ?></td>
						<td align="center"><?php echo $item['person_limit']; ?></td>
						<td align="center"><?php echo $item['open_apply_time']; ?></td>
						<td align="center"><?php echo "{$item['start_time']}~{$item['end_time']}"; ?></td>
						<td align="center"><?php echo $item['prize_money']; ?></td>
						<td align="center"><?php echo $item['apply_count']; ?></td>
						<td align="center"><?php echo $item['display'] ? '显示' : '隐藏'; ?></td>
						<td align="center"><?php echo $item['modified_time']; ?></td>
						<td align="center"><?php echo $item['created_time']; ?></td>
						<td align="center">
						  <a href="###" onclick="edit(<?php echo $item['aid'] ?>, '<?php echo htmlspecialchars($item['title']) ?>')" title="修改">修改</a> |
						  <a href="javascript:postDialog('LotteryUsers', '<?php echo YUrl::createBackendUrl('Lottery', 'users', ['aid' => $item['aid']]); ?>', '参与活动的用户列表', 600, 500)">参与列表</a>  |
						  <a href="###" onclick="deleteDialog('deleteLottery', '<?php echo YUrl::createBackendUrl('Lottery', 'delete', ['aid' => $item['aid']]); ?>', '<?php echo htmlspecialchars($item['title']) ?>')" title="删除">删除</a>
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
function edit(id, name) {
	var title = '修改『' + name + '』';
	var page_url = "<?php echo YUrl::createBackendUrl('Lottery', 'edit'); ?>?aid="+id;
	postDialog('editLottery', page_url, title, 600, 350);
}
</script>
</body>
</html>