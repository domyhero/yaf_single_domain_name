<?php
use common\YUrl;
use common\YCore;
require_once (dirname(__DIR__) . '/common/header.php');
?>

<style type="text/css">
html {
	_overflow-y: scroll
}
</style>

<div class="pad_10">
	<form action="<?php echo YUrl::createBackendUrl('Freight', 'add'); ?>"
		method="post" name="myform" id="myform">
		<table cellpadding="2" cellspacing="1" class="table_form" width="100%">
			<tr>
				<th width="100">模板名称：</th>
				<td><input type="text" name="freight_name" id="freight_name"
					size="30" class="input-text" value=""></td>
			</tr>
			<tr>
				<th width="100">计费类型：</th>
				<td><select name="fright_type">
						<option value="1">按件数</option>
						<option value="2">按重量</option>
				</select></td>
			</tr>
			<tr>
				<th width="100">运费承担：</th>
				<td><select name="bear_freight">
						<option value="1">买家包邮</option>
						<option value="2">买家承担运费</option>
				</select></td>
			</tr>
			<tr>
				<th width="100">发货时间：</th>
				<td><select name="send_time">
						<option value="4">4小时内发货</option>
						<option value="12">12小时内发货</option>
						<option value="24">24小时内发货</option>
						<option value="48">两天内内发货</option>
						<option value="72">三天内发货</option>
				</select></td>
			</tr>
			<tr>
				<th width="100">包邮金额：</th>
				<td><input type="text" name="baoyou_fee" id="baoyou_fee" size="10"
					class="input-text" value=""></td>
			</tr>
			<tr>
				<th width="100">计费规则：</th>
				<td>
					<div>
						<input type="text" name="base_step" id="base_step" size="5"
							class="input-text" value="">件内, <input type="text"
							name="base_freight" id="base_freight" size="5" class="input-text"
							value="">元,每增加 <input type="text" name="rate_step" id="rate_step"
							size="5" class="input-text" value="">件,增加运费 <input type="text"
							name="step_freight" id="step_freight" size="5" class="input-text"
							value="">元
					</div>
					<div>
						<input type="text" name="base_step" id="base_step" size="5"
							class="input-text" value="">克内, <input type="text"
							name="base_freight" id="base_freight" size="5" class="input-text"
							value="">元,每增加 <input type="text" name="rate_step" id="rate_step"
							size="5" class="input-text" value="">克,增加运费 <input type="text"
							name="step_freight" id="step_freight" size="5" class="input-text"
							value="">元
					</div>
				</td>
			</tr>
			<tr>
				<td width="100%" align="center" colspan="2"><input id="form_submit"
					type="button" name="dosubmit" class="btn_submit" value=" 提交 " /></td>
			</tr>
		</table>

	</form>
</div>

<script type="text/javascript">

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
</script>

</body>
</html>