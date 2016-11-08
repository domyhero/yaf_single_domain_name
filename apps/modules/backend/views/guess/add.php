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
	<form action="<?php echo YUrl::createBackendUrl('Guess', 'add'); ?>" method="post" name="myform" id="myform">
		<table cellpadding="2" cellspacing="1" class="table_form" width="100%">
			<tr>
				<th width="100">竞猜标题：</th>
				<td><input type="text" name="title" id="title" size="30" class="input-text" value=""></td>
			</tr>
			<tr>
				<th width="100">竞猜图片：</th>
				<td>
					<input type="hidden" name="image_url" id="image_url" value="" />
					<div id="image_url_view"></div>
				</td>
			</tr>
			<tr>
				<th width="100">截止参与时间：</th>
				<td><input type="text" name="deadline" id="deadline" size="20" class="date input-text" value=""></td>
			</tr>
			<tr>
				<th width="100">是否开奖：</th>
				<td>
					<select name="is_open">
						<option value="0">否</option>
						<option value="1">是</option>
					</select>
				</td>
			</tr>
			<tr>
				<th width="100">开奖结果：</th>
				<td><select>
					<option value="A">-</option>
					<?php foreach($options as $opk => $opv): ?>
					<option value="<?php echo $opk; ?>"><?php echo $opv; ?></option>
					<?php endforeach; ?>
				</select></td>
			</tr>
			<tr>
				<th width="100">选项：</th>
				<td>
					<?php foreach($options as $opk => $opv): ?>
					<p style="padding-top: 10px;">
						<input type="text" name="options_data[<?php echo $opk; ?>][op_title]" size="30" class="input-text" value="">[<?php echo $opv; ?>]
						<input type="text" name="options_data[<?php echo $opk; ?>][op_odds]" size="5" class="input-text" value="">[<?php echo $opv; ?>赔率]
					</p>
					<?php endforeach; ?>
				</td>
			</tr>
			<tr>
				<td width="100%" align="center" colspan="2">
					<input id="form_submit" type="button" name="dosubmit" class="btn_submit" value=" 提交 " />
				</td>
			</tr>
		</table>

	</form>
</div>

<script src="<?php echo YUrl::assets('js', '/AjaxUploader/uploadImage.js'); ?>"></script>
<script type="text/javascript">

var uploadUrl = '<?php echo YUrl::createBackendUrl('Index', 'upload'); ?>';
var baseJsUrl = '<?php echo YUrl::assets('js', ''); ?>';
var filUrl    = '<?php echo YUrl::getDomainName(); ?>';
uploadImage(filUrl, baseJsUrl, 'image_url_view', 'image_url', 120, 120, uploadUrl);

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

Calendar.setup({
	weekNumbers: false,
    inputField : "deadline",
    trigger    : "deadline",
    dateFormat: "%Y-%m-%d %H:%I:%S",
    showTime: true,
    minuteStep: 1,
    onSelect   : function() {this.hide();}
});

</script>

</body>
</html>