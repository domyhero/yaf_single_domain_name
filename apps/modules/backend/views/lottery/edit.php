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
	<form action="<?php echo YUrl::createBackendUrl('Lottery', 'edit'); ?>" method="post" name="myform" id="myform">
		<table cellpadding="2" cellspacing="1" class="table_form" width="100%">
			<tr>
				<th width="100">活动名称：</th>
				<td><input type="text" name="title" id="title" size="50" class="input-text" value="<?php echo htmlspecialchars($detail['title']); ?>"></td>
			</tr>
			<tr>
				<th width="100">彩票类型：</th>
				<td><select name="lottery_type">
				    <option <?php echo $detail['lottery_type']==1 ? 'selected="selected"' : ''; ?> value="1">双色球</option>
				    <option <?php echo $detail['lottery_type']==2 ? 'selected="selected"' : ''; ?> value="2">大乐透</option>
				</select></td>
			</tr>
			<tr>
				<th width="100">投注号码：</th>
				<td><input type="text" name="bet_number" id="bet_number" size="40" class="input-text" value="<?php echo htmlspecialchars($detail['bet_number']); ?>"></td>
			</tr>
			<tr>
				<th width="100">人数上限：</th>
				<td><input type="text" name="person_limit" id="person_limit" size="10" class="input-text" value="<?php echo $detail['person_limit']; ?>"></td>
			</tr>
			<tr>
				<th width="100">开放参与时间：</th>
				<td><input type="text" name="open_apply_time" id="open_apply_time" size="20" class="date input-text" value="<?php echo $detail['open_apply_time']; ?>"></td>
			</tr>
			<tr>
				<th width="100">领取时间：</th>
				<td>
				  <input type="text" name="start_time" id="start_time" size="20" class="date input-text" value="<?php echo $detail['start_time']; ?>"> 至
				  <input type="text" name="end_time" id="end_time" size="20" class="date input-text" value="<?php echo $detail['end_time']; ?>">
				</td>
			</tr>
			<tr>
				<th width="100">显示状态：</th>
				<td><select name="display">
				    <option <?php echo $detail['display']==0 ? 'selected="selected"' : ''; ?> value="0">隐藏</option>
				    <option <?php echo $detail['display']==1 ? 'selected="selected"' : ''; ?> value="1">显示</option>
				</select></td>
			</tr>
			<tr>
				<td width="100%" align="center" colspan="2">
				    <input id="form_submit" type="button" name="dosubmit" class="btn_submit" value=" 提交 " />
				    <input type="hidden" name="aid" value="<?php echo $detail['aid']; ?>" />
				</td>
			</tr>
		</table>

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

Calendar.setup({
	weekNumbers: false,
    inputField : "open_apply_time",
    trigger    : "open_apply_time",
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