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
							    奖品类型：
							    <select name="goods_type">
							         <option value="">不限</option>
							    <?php
							    foreach ($goods_type_dict as $goods_type_code => $goods_type_label) {
							        if ($goods_type_code == $goods_type) {
							            echo "<option selected=\"selected\" value=\"{$goods_type_code}\">{$goods_type_label}</option>";
							        } else {
							            echo "<option value=\"{$goods_type_code}\">{$goods_type_label}</option>";
							        }
							    }
							    ?>
							    </select>
							    用户账号：<input type="text" name="username" class="input-text" value="<?php echo $username; ?>" placeholder="用户登录账号" />
							    用户手机：<input type="text" name="mobilephone" class="input-text" value="<?php echo $mobilephone; ?>" placeholder="用户手机号码" />
							    奖品名称：<input type="text" name="goods_name" class="input-text" value="<?php echo htmlspecialchars($goods_name); ?>" placeholder="奖品名称" />
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
						<th align="center">ID</th>
						<th align="center">奖品名称</th>
						<th align="center">奖品类型</th>
						<th align="center">中奖人手机</th>
						<th align="center">中奖人账号</th>
						<th align="center">奖励发送</th>
						<th align="center">发送时间</th>
						<th align="center">随机值</th>
						<th align="center">中奖收货信息</th>
						<th width="120" align="center">创建时间</th>
						<th width="100" align="center">管理操作</th>
					</tr>
				</thead>
				<tbody>
                <?php foreach ($list as $item): ?>
    	           <tr>
						<td align="center"><?php echo $item['id']; ?></td>
						<td align="center"><?php echo htmlspecialchars($item['goods_name']); ?></td>
						<td align="center"><?php echo $item['goods_type']; ?></td>
						<td align="center"><?php echo $item['mobilephone']; ?></td>
						<td align="center"><?php echo $item['username']; ?></td>
						<td align="center"><?php echo $item['is_send']; ?></td>
						<td align="center"><?php echo $item['send_time']; ?></td>
						<td align="center"><?php echo $item['range_val']; ?></td>
						<td align="center"><?php echo json_decode($item['get_info']); ?></td>
						<td align="center"><?php echo $item['created_time']; ?></td>
						<td align="center">
						  <a href="javascript:postDialog('LuckyUsers', '<?php echo YUrl::createBackendUrl('Lucky', 'users', ['id' => $item['id']]); ?>', '参与活动的用户列表', 600, 500)">参与列表</a>  |
						  <a href="###" onclick="deleteDialog('deleteLuckyRecord', '<?php echo YUrl::createBackendUrl('Lucky', 'deleteRecord', ['id' => $item['id']]); ?>', '<?php echo htmlspecialchars($item['goods_name']) ?>')" title="删除">删除</a>
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
	var page_url = "<?php echo YUrl::createBackendUrl('Lucky', 'edit'); ?>?id="+id;
	postDialog('editLucky', page_url, title, 600, 350);
}
</script>
</body>
</html>