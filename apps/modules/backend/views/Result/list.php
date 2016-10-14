<?php
use common\YUrl;
require_once (dirname(__DIR__) . '/common/header.php');
?>

<div class="subnav">
	<div class="content-menu ib-a blue line-x">
		<a class="add fb" href="javascript:postDialog('addResult', '<?php echo YUrl::createBackendUrl('Result', 'add'); ?>', '添加开奖结果', 600, 500)"><em>添加开奖结果</em></a>
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
						<th align="center">彩票类型</th>
						<th align="center">期次</th>
						<th align="center">开奖时间</th>
						<th align="center">开奖号码</th>
						<th align="center">一等奖金</th>
						<th align="center">二等奖金</th>
						<th align="center">一等人数</th>
						<th align="center">二等人数</th>
						<th align="center">三等人数</th>
						<th align="center">四等人数</th>
						<th align="center">五等人数</th>
						<th align="center">六等人数</th>
						<th width="120" align="center">创建时间</th>
						<th width="100" align="center">管理操作</th>
					</tr>
				</thead>
				<tbody>
                <?php foreach ($list as $item): ?>
    	           <tr>
						<td align="center"><?php echo $item['id']; ?></td>
						<td align="center"><?php echo $item['lottery_label']; ?></td>
						<td align="center"><?php echo $item['phase_sn']; ?></td>
						<td align="center"><?php echo $item['lottery_result']; ?></td>
						<td align="center"><?php echo $item['first_prize']; ?>元</td>
						<td align="center"><?php echo $item['second_prize']; ?>元</td>
						<td align="center"><?php echo $item['first_prize_count']; ?></td>
						<td align="center"><?php echo $item['second_prize_count']; ?></td>
						<td align="center"><?php echo $item['third_prize_count']; ?></td>
						<td align="center"><?php echo $item['fourth_prize_count']; ?></td>
						<td align="center"><?php echo $item['fifth_prize_count']; ?></td>
						<td align="center"><?php echo $item['sixth_prize_count']; ?></td>
						<td align="center"><?php echo $item['lottery_time']; ?></td>
						<td align="center"><?php echo $item['created_time']; ?></td>
						<td align="center">
						  <a href="###" onclick="edit(<?php echo $item['id'] ?>, '<?php echo "{$item['lottery_label']} {$item['phase_sn']} 期" ?>')" title="修改">修改</a> |
						  <a href="###" onclick="deleteDialog('deleteResult', '<?php echo YUrl::createBackendUrl('Result', 'delete', ['id' => $item['id']]); ?>', '<?php echo "{$item['lottery_label']} {$item['phase_sn']} 期" ?>')" title="删除">删除</a>
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
	var page_url = "<?php echo YUrl::createBackendUrl('Result', 'edit'); ?>?id="+id;
	postDialog('editResult', page_url, title, 600, 500);
}
</script>
</body>
</html>