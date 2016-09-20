<?php
use common\YUrl;
require_once (APP_VIEW_PATH . DIRECTORY_SEPARATOR . 'common/header.php');
?>

<div class="subnav">
	<div class="content-menu ib-a blue line-x">
		<a href='javascript:;' class="on"><em>优惠券发送记录</em></a>
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
								<input type="hidden" name="coupon_id"
									value="<?php echo $coupon_id; ?>" /> <input type="text"
									value="<?php echo htmlspecialchars($username); ?>"
									class="input-text" name="username" placeholder="用户名" /> <input
									type="text"
									value="<?php echo htmlspecialchars($mobilephone); ?>"
									class="input-text" name="mobilephone" placeholder="手机号码" /> <input
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
						<th align="center">优惠券名称</th>
						<th align="center">优惠金额</th>
						<th align="center">订单金额</th>
						<th align="center">限领数量</th>
						<th align="center">领取时间</th>
						<th align="center">优惠券失效时间</th>
						<th width="120" align="center">使用时间</th>
						<th width="120" align="center">领取时间</th>
					</tr>
				</thead>
				<tbody>
    <?php foreach ($list as $item): ?>
    	<tr>
						<td align="center"><?php echo htmlspecialchars($item['coupon_name']); ?></td>
						<td align="center"><?php echo $item['money']; ?>元</td>
						<td align="center"><?php echo $item['order_money']; ?>元</td>
						<td align="center"><?php echo $item['limit_quantity']; ?></td>
						<td align="center"><?php echo "{$item['get_start_time']}~{$item['get_end_time']}"; ?></td>
						<td align="center"><?php echo $item['expiry_date']; ?></td>
						<td align="center"><?php echo $item['modified_time']; ?></td>
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

</script>
</body>
</html>