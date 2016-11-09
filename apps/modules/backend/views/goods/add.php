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
	<form action="<?php echo YUrl::createBackendUrl('Goods', 'add'); ?>" method="post" name="myform" id="myform">
		<table cellpadding="2" cellspacing="1" class="table_form" width="100%">
			<tr>
				<th width="80">商品名称：</th>
				<td><input type="text" name="goods_name" id="goods_name" size="60" class="input-text" value=""></td>
			</tr>
			<tr>
				<th width="80">分类：</th>
				<td>
				    <select name="cat_id" id="cat_id">
						<option value="-1">请选择分类</option>
				    </select>
				</td>
			</tr>
			<tr>
				<th width="80">推广语：</th>
				<td>
				    <input type="text" name="slogan" id="slogan" size="60" class="input-text" value="">
				</td>
			</tr>
			<tr>
				<th width="80">允许金币兑换：</th>
				<td>
				    <select name="is_exchange" id="is_exchange">
						<option value="0">否</option>
						<option value="1">是</option>
				    </select>
				    <span style="margin-left: 20px;">重量(g)：<input type="text" name="weight" id="weight" size="5" class="input-text" value="0"></span>
				    <span style="margin-left: 20px;">排序值(值小排前)：<input type="text" name="listorder" id="listorder" size="5" class="input-text" value="0"></span>
				</td>
			</tr>
			<tr>
				<th width="80">运费模板：</th>
				<td>
				    <select name="freight_tpl_id" id="freight_tpl_id">
						<option value="0">卖家包邮</option>
				    </select>
				</td>
			</tr>
			<tr>
				<th width="80">商品详情(g)：</th>
                <td><textarea name="description" id="editor_id" style="width: 700px; height: 400px;" rows="5" cols="50"></textarea></td>
			</tr>
			<tr>
				<td width="100%" align="center" colspan="2">
				    <input id="form_submit" type="button" name="dosubmit" class="btn_submit" value=" 提交 " />
				</td>
			</tr>
		</table>

	</form>
</div>

<script charset="utf-8" src="<?php echo YUrl::assets('js', '/kindeditor/kindeditor-all-min.js') ?>"></script>
<script charset="utf-8" src="<?php echo YUrl::assets('js', '/kindeditor/lang/zh-CN.js') ?>"></script>
<script type="text/javascript">
<!--

var editor;

$(document).ready(function(){

	KindEditor.ready(function(K) {
	    editor = K.create('#editor_id');
	});

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