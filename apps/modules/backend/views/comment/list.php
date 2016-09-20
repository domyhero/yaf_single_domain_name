<?php
use common\YUrl;
require_once (dirname(__DIR__) . '/common/header.php');
?>

<div class="subnav">
	<div class="content-menu ib-a blue line-x">
		<a href='javascript:;' class="on"><em>评论列表</em></a>
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
								订单号：<input type="text" value="<?php echo $order_sn; ?>"
									class="input-text" name="order_sn" placeholder="订单号" /> 评价等级：<select
									name="evaluate_level">
									<option value="-1">不限</option>
									<option
										<?php echo $evaluate_level==1 ? 'selected="selected"' : ''; ?>
										value="1">好评</option>
									<option
										<?php echo $evaluate_level==2 ? 'selected="selected"' : ''; ?>
										value="2">中评</option>
									<option
										<?php echo $evaluate_level==3 ? 'selected="selected"' : ''; ?>
										value="3">差评</option>
								</select> 用户账号：<input type="text"
									value="<?php echo $username; ?>" class="input-text"
									name="username" placeholder="用户账号" /> 手机号：<input type="text"
									value="<?php echo $mobilephone; ?>" class="input-text"
									name="mobilephone" placeholder="用户手机号" /> <input type="submit"
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
						<th align="center">商品图片</th>
						<th align="center">商品名称</th>
						<th align="center">订单号</th>
						<th align="center">评论人</th>
						<th align="center">初评内容/初评回复</th>
						<th align="center">追评内容/追评回复</th>
						<th align="center">评价等级</th>
						<th width="100" align="center">管理操作</th>
					</tr>
				</thead>
				<tbody>
    <?php foreach ($list as $item): ?>
    	<tr>
						<td align="center"><img width="120"
							src="<?php echo $item['goods_image']; ?>" /></td>
						<td align="center"><?php echo htmlspecialchars($item['goods_name']); ?></td>
						<td align="center"><?php echo "{$item['username']}[{$item['mobilephone']}]"; ?></td>
						<td align="center"><?php echo "[{$item['content1_time']}]{$item['content1']}" ?></td>
						<td align="center"><?php echo "[{$item['content2_time']}]{$item['content2']}" ?></td>
						<td align="center"><?php echo $item['evaluate_level_label']; ?></td>
						<td align="center"><a href="###"
							onclick="deleteDialog('deleteComment', '<?php echo YUrl::createBackendUrl('Comment', 'delete', ['comment_id' => $item['cid']]); ?>', '<?php echo "评论" ?>')"
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

</script>
</body>
</html>