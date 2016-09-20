<?php
use common\YUrl;
require_once (APP_VIEW_PATH . DIRECTORY_SEPARATOR . 'common/header.php');
?>

<div class="subnav">
	<div class="content-menu ib-a blue line-x">
		<a href='javascript:;' class="on"><em>订单列表</em></a>
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
								收货人：<input type="text" value="<?php echo $receiver_name; ?>"
									class="input-text" name="receiver_name" placeholder="收货人姓名" />
								手机号：<input type="text" value="<?php echo $receiver_mobile; ?>"
									class="input-text" name="receiver_mobile" placeholder="收货人手机号码" />
								<input type="hidden" name="goods_id"
									value="<?php echo $goods_id; ?>" /> <input type="submit"
									name="search" class="button" value="搜索" />
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
						<th align="center">订单号</th>
						<th align="center">订单总额</th>
						<th align="center">支付类型</th>
						<th align="center">支付状态</th>
						<th align="center">支付时间</th>
						<th align="center">订单状态</th>
						<th width="120" align="center">下单时间</th>
						<th width="100" align="center">管理操作</th>
					</tr>
				</thead>
				<tbody>
    <?php foreach ($list as $item): ?>
    	<tr>
						<td align="center"><?php echo $item['order_id']; ?></td>
						<td align="center"><?php echo $item['order_sn']; ?></td>
						<td align="center"><?php echo $item['payment_price']; ?></td>
						<td align="center"><?php echo $item['payment_type']; ?></td>
						<td align="center"><?php echo $item['pay_status']; ?></td>
						<td align="center"><?php echo $item['pay_time']; ?></td>
						<td align="center"><?php echo $item['order_status']; ?></td>
						<td align="center"><?php echo $item['created_time']; ?></td>
						<td align="center"><a href="###"
							onclick="normalDialog(<?php echo $item['order_id'] ?>, '<?php echo YUrl::createBackendUrl('Order', 'deliverGoods', ['order_id' => $item['order_id']]); ?>', '<?php echo "发货：{$item['order_sn']}" ?>')"
							title="发货">发货</a> | <a href="###"
							onclick="normalDialog(<?php echo $item['order_id'] ?>, '<?php echo YUrl::createBackendUrl('Order', 'close', ['order_id' => $item['order_id']]); ?>', '<?php echo "关闭订单：{$item['order_sn']}" ?>')"
							title="关闭">关闭</a> | <a href="###"
							onclick="normalDialog(<?php echo $item['order_id'] ?>, '<?php echo YUrl::createBackendUrl('Order', 'delete', ['order_id' => $item['order_id']]); ?>', '<?php echo "删除订单：{$item['order_sn']}" ?>')"
							title="删除">删除</a> | <a href="###"
							onclick="normalDialog(<?php echo $item['order_id'] ?>, '<?php echo YUrl::createBackendUrl('Order', 'adjustAddress', ['order_id' => $item['order_id']]); ?>', '<?php echo "修改地址：{$item['order_sn']}" ?>')"
							title="修改地址">修改地址</a> <a href="###"
							onclick="normalDialog(<?php echo $item['order_id'] ?>, '<?php echo YUrl::createBackendUrl('Order', 'adjustFreight', ['order_id' => $item['order_id']]); ?>', '<?php echo "调运费：{$item['order_sn']}" ?>')"
							title="调运费">调运费</a></td>
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

</script>
</body>
</html>