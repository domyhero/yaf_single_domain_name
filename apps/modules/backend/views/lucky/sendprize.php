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
	<form action="<?php echo YUrl::createBackendUrl('Lucky', 'sendprize'); ?>" method="post" name="myform" id="myform">
		<table cellpadding="2" cellspacing="1" class="table_form" width="100%">
		    <?php if ($detail['goods_type'] == 'jb'): ?>
			<tr>
				<th>QQ号码：</th>
				<td><input type="text" name="data[qq]" id="qq" size="20" class="input-text" value=""></td>
			</tr>
			<?php elseif ($detail['goods_type'] == 'hf'): ?>
			<tr>
				<th>手机号码：</th>
				<td><input type="text" name="data[mobilephone]" id="mobilephone" size="20" class="input-text" value=""></td>
			</tr>
			<?php elseif ($detail['goods_type'] == 'sw'): ?>
			<tr>
				<th width="100">快递公司：</th>
				<td><select name="data[express_name]">
				<?php foreach($logistics_list_dict as $v): ?>
				<option value="<?php echo $v; ?>"><?php echo $v; ?></option>
				<?php endforeach; ?>
				</select></td>
			</tr>
			<tr>
				<th width="100">快递单号：</th>
				<td><input type="text" name="data[express_sn]" id="express_sn" size="20" class="input-text" value=""></td>
			</tr>
			<tr>
				<th width="100">发货时间：</th>
				<td><input type="text" name="data[express_time]" id="express_time" size="20" class="input-text date" value=""></td>
			</tr>
			<?php else: ?>
			<tr>
			 <td>
			     <td></
			 </td>
			</tr>
			<?php endif; ?>
			<tr>
				<td width="100%" align="center" colspan="2">
				    <input id="form_submit" type="button" name="dosubmit" class="btn_submit" value=" 提交 " />
				    <input type="hidden" name="id" value="<?php echo $detail['id']; ?>" />
				</td>
			</tr>
		</table>

	</form>
</div>

<script type="text/javascript">

Calendar.setup({
	weekNumbers: false,
    inputField : "express_time",
    trigger    : "express_time",
    dateFormat: "%Y-%m-%d %H:%I:%S",
    showTime: true,
    minuteStep: 1,
    onSelect   : function() {this.hide();}
});

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