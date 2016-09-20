<?php
use common\YUrl;
require_once (dirname(__DIR__) . '/common/header.php');
?>

<style type="text/css">
html {
	_overflow-y: scroll
}
</style>

<div class="pad_10">
	<form action="<?php echo YUrl::createBackendUrl('Goods', 'add'); ?>"
		method="post" name="myform" id="myform">
		<table cellpadding="2" cellspacing="1" class="table_form" width="100%">
			<tr>
				<th width="80">商品名称：</th>
				<td><input type="text" name="goods_name" id="goods_name" size="60"
					class="input-text" value=""></td>
			</tr>
			<tr>
				<th width="80">分类：</th>
				<td><select name="cat_id" id="cat_id">
						<option value="-1">请选择分类</option>
				</select></td>
			</tr>
			<tr>
				<th width="80">推广语：</th>
				<td><input type="text" name="slogan" id="slogan" size="60"
					class="input-text" value=""></td>
			</tr>
			<tr>
				<th width="80">允许金币兑换：</th>
				<td><select name="is_exchange" id="is_exchange">
						<option value="0">否</option>
						<option value="1">是</option>
				</select></td>
			</tr>
			<tr>
				<th width="80">重量(g)：</th>
				<td><input type="text" name="weight" id="weight" size="5"
					class="input-text" value="0"></td>
			</tr>
			<tr>
				<th width="80">重量(g)：</th>
				<td><input type="text" name="weight" id="weight" size="5"
					class="input-text" value=""></td>
			</tr>
			<tr>
				<td width="100%" align="center" colspan="2"><input id="form_submit"
					type="button" name="dosubmit" class="btn_submit" value=" 提交 " /></td>
			</tr>
		</table>

	</form>
</div>

<script type="text/javascript">
<!--

$(document).ready(function(){
	$('#form_submit').click(function(){
	    $.ajax({
	    	type: 'post',
            url: $('form').eq(0).attr('action'),
            dataType: 'json',
            data: $('form').eq(0).serialize(),
            success: function(data) {
                if (data.errcode == 0) {
                	top.dialog.getCurrent().close({"refresh" : 1});
                } else {
                	dialogTips(data.errmsg, 3);
                }
            }
	    });
	});
});

//-->
</script>

</body>
</html>