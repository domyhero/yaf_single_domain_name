<?php
use common\YUrl;
require_once (dirname(__DIR__) . '/common/header.php');
?>

<div class="subnav">
	<div class="content-menu ib-a blue line-x">
		<a class="add fb"
			href="javascript:postDialog('addFreight', '<?php echo YUrl::createBackendUrl('Freight', 'add'); ?>', '添加运费模板', 600, 300)"><em>添加运费模板</em></a>
		<a href='javascript:;' class="on"><em>运费模板列表</em></a>
	</div>
</div>
<style type="text/css">
html {
	_overflow-y: scroll
}
</style>
<div class="pad-lr-10">

	<form name="myform" id="myform" action="" method="post">
		<div class="table-list">
			<table width="100%" cellspacing="0">
				<thead>
					<tr>
						<th align="center">ID</th>
						<th align="center">模板名称</th>
						<th align="center">计费类型</th>
						<th align="center">运费承担</th>
						<th align="center">发货时间</th>
						<th align="center">包邮金额</th>
						<th width="120" align="center">修改时间</th>
						<th width="120" align="center">创建时间</th>
						<th width="100" align="center">管理操作</th>
					</tr>
				</thead>
				<tbody>
    <?php foreach ($list as $item): ?>
    	<tr>
						<td align="center"><?php echo $item['tpl_id']; ?></td>
						<td align="center"><?php echo htmlspecialchars($item['freight_name']); ?></td>
						<td align="center"><?php echo $item['freight_type_label']; ?></td>
						<td align="center"><?php echo $item['bear_freight_label']; ?></td>
						<td align="center"><?php echo $item['send_time']; ?>小时</td>
						<td align="center"><?php echo $item['baoyou_fee']; ?>元</td>
						<td align="center"><?php echo $item['modified_time']; ?></td>
						<td align="center"><?php echo $item['created_time']; ?></td>
						<td align="center"><a href="###"
							onclick="edit(<?php echo $item['tpl_id'] ?>, '<?php echo htmlspecialchars($item['freight_name']) ?>')"
							title="修改">修改</a> | <a href="###"
							onclick="deleteDialog('deleteFreight', '<?php echo YUrl::createBackendUrl('Freight', 'delete', ['tpl_id' => $item['tpl_id']]); ?>', '<?php echo htmlspecialchars($item['freight_name']) ?>')"
							title="删除">删除</a></td>
					</tr>
    <?php endforeach; ?>
    </tbody>
			</table>

		</div>

	</form>
</div>
<script type="text/javascript">
function edit(id, name) {
	var title = '修改『' + name + '』';
	var page_url = "<?php echo YUrl::createBackendUrl('Freight', 'edit'); ?>?tpl_id="+id;
	postDialog('editFreight', page_url, title, 600, 300);
}
</script>
</body>
</html>