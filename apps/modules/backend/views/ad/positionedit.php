<?php
use common\YUrl;
require_once (APP_VIEW_PATH . DIRECTORY_SEPARATOR . 'common/header.php');
?>

<style type="text/css">
html {
	_overflow-y: scroll
}
</style>

<div class="pad_10">
	<form
		action="<?php echo YUrl::createBackendUrl('Ad', 'positionEdit'); ?>"
		method="post" name="myform" id="myform">
		<table cellpadding="2" cellspacing="1" class="table_form" width="100%">
			<tr>
				<th width="80">广告位置名称：</th>
				<td><input type="text" name="pos_name" id="pos_name" size="20"
					class="input-text"
					value="<?php echo htmlspecialchars($detail['pos_name']); ?>"></td>
			</tr>
			<tr>
				<th width="80">广告位置编码：</th>
				<td><input type="text" name="pos_code" id="pos_code" size="20"
					class="input-text"
					value="<?php echo htmlspecialchars($detail['pos_code']); ?>"></td>
			</tr>
			<tr>
				<th width="100">允许展示的广告数量：</th>
				<td><input type="text" name="pos_ad_count" id="pos_ad_count"
					size="5" class="input-text"
					value="<?php echo htmlspecialchars($detail['pos_ad_count']); ?>"></td>
			</tr>
			<tr>
				<td width="100%" align="center" colspan="2"><input type="hidden"
					name="pos_id" value="<?php echo $detail['pos_id']; ?>" /> <input
					id="form_submit" type="button" name="dosubmit" class="btn_submit"
					value=" 提交 " /></td>
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