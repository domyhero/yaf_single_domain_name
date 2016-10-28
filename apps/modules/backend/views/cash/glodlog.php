<?php
use common\YUrl;
require_once (dirname(__DIR__) . '/common/header.php');
?>

<div class="subnav">
	<div class="content-menu ib-a blue line-x">
		<a href='javascript:;' class="on"><em>金币消费记录</em></a>
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
								账号：<input type="text" value="<?php echo $username; ?>" class="input-text" name="username" placeholder="请输入用户账号" />
								手机号：<input type="text" value="<?php echo $mobilephone; ?>" class="input-text" name="mobilephone" placeholder="请输入用户手机号" />
								时间：<input type="text" name="start_time" id="start_time" value="<?php echo $start_time; ?>" size="20" class="date input-text" />
								～ <input type="text" name="end_time" id="end_time" value="<?php echo $end_time; ?>" size="20" class="date input-text" />
								类型：<select name="consume_type">
    								<option value="-1">全部</option>
    								<option <?php echo $consume_type==1 ? 'selected="selected"' : ''; ?> value="1">增加</option>
    								<option <?php echo $consume_type==0 ? 'selected="selected"' : ''; ?> value="0">扣减</option>
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
						<th align="center">ID</th>
						<th align="center">账号</th>
						<th align="center">手机号</th>
						<th align="center">类型</th>
						<th align="center">用途</th>
						<th align="center">数量</th>
						<th align="center">时间</th>
					</tr>
				</thead>
				<tbody>
    <?php foreach ($list as $item): ?>
    	<tr>
						<td align="center"><?php echo $item['id']; ?></td>
						<td align="center"><?php echo $item['username']; ?></td>
						<td align="center"><?php echo $item['mobilephone']; ?></td>
						<td align="center"><?php echo $item['consume_type_label']; ?></td>
						<td align="center"><?php echo $item['consume_code_label']; ?></td>
						<td align="center"><?php echo $item['gold']; ?> 金币</td>
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
</script>
</body>
</html>