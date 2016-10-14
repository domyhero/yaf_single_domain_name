<?php
use common\YUrl;
require_once (dirname(__DIR__) . '/common/header.php');
?>

<div class="subnav">
	<div class="content-menu ib-a blue line-x">
		<a class="add fb"
			href="javascript:postDialog('addResult', '<?php echo YUrl::createBackendUrl('Result', 'add'); ?>', '添加开奖结果', 600, 500)"><em>添加开奖结果</em></a>
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
							手机号码：<input name="mobilephone" type="text" class="input-text" placeholder="手机号码" />
							用户账号：<input name="username" type="text" class="input-text" placeholder="用户账号" />
							<input type="hidden" name="aid" value="<?php echo $aid; ?>" />
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
						<th align="center">注册时间</th>
						<th align="center">最后登录时间</th>
						<th align="center">参与时间</th>
					</tr>
				</thead>
				<tbody>
                <?php foreach ($list as $item): ?>
    	           <tr>
						<td align="center"><?php echo $item['username']; ?></td>
						<td align="center"><?php echo $item['mobilephone']; ?></td>
						<td align="center"><?php echo $item['reg_time']; ?></td>
						<td align="center"><?php echo $item['last_login_time']; ?></td>
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