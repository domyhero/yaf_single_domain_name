<?php
use common\YUrl;
require_once (dirname(__DIR__) . '/common/header.php');
?>

<div class="subnav">
	<div class="content-menu ib-a blue line-x">
		<a class="add fb"
			href="javascript:postDialog('addCoupon', '<?php echo YUrl::createBackendUrl('Coupon', 'add'); ?>', '添加优惠券', 600, 350)"><em>添加优惠券</em></a>
		<a href='javascript:;' class="on"><em>优惠券列表</em></a>
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
								<input type="text"
									value="<?php echo htmlspecialchars($coupon_name); ?>"
									class="input-text" name="coupon_name" placeholder="优惠券名称" /> <input
									type="submit" name="search" class="button" value="搜索" />
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
						<th align="center">ID</th>
						<th align="center">优惠券名称</th>
						<th align="center">优惠金额</th>
						<th align="center">订单金额</th>
						<th align="center">限领数量</th>
						<th align="center">领取时间</th>
						<th align="center">优惠券失效时间</th>
						<th width="120" align="center">修改时间</th>
						<th width="120" align="center">创建时间</th>
						<th width="100" align="center">管理操作</th>
					</tr>
				</thead>
				<tbody>
    <?php foreach ($list as $item): ?>
    	<tr>
						<td align="center"><?php echo $item['coupon_id']; ?></td>
						<td align="center"><a
							href="<?php echo YUrl::createBackendUrl('Coupon', 'history', ['coupon_id' => $item['coupon_id']]); ?>"><?php echo htmlspecialchars($item['coupon_name']); ?></a></td>
						<td align="center"><?php echo $item['money']; ?>元</td>
						<td align="center"><?php echo $item['order_money']; ?>元</td>
						<td align="center"><?php echo $item['limit_quantity']; ?></td>
						<td align="center"><?php echo "{$item['get_start_time']}~{$item['get_end_time']}"; ?></td>
						<td align="center"><?php echo $item['expiry_date']; ?></td>
						<td align="center"><?php echo $item['modified_time']; ?></td>
						<td align="center"><?php echo $item['created_time']; ?></td>
						<td align="center"><a href="###"
							onclick="edit(<?php echo $item['coupon_id'] ?>, '<?php echo htmlspecialchars($item['coupon_name']) ?>')"
							title="修改">修改</a> | <a href="###"
							onclick="deleteDialog('deleteCoupon', '<?php echo YUrl::createBackendUrl('Coupon', 'delete', ['coupon_id' => $item['coupon_id']]); ?>', '<?php echo htmlspecialchars($item['coupon_name']) ?>')"
							title="删除">删除</a></td>
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
	var page_url = "<?php echo YUrl::createBackendUrl('Coupon', 'edit'); ?>?coupon_id="+id;
	postDialog('editCoupon', page_url, title, 600, 350);
}
</script>
</body>
</html>