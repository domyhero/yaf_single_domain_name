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
	<form action="<?php echo YUrl::createBackendUrl('Result', 'add'); ?>" method="post" name="myform" id="myform">
		<table cellpadding="2" cellspacing="1" class="table_form" width="100%">
			<tr>
				<th width="100">彩票类型：</th>
				<td><select name="lottery_type">
				    <option value="1">双色球</option>
				    <option value="2">大乐透</option>
				</select></td>
			</tr>
			<tr>
				<th width="100">彩票期次：</th>
				<td><input type="text" name="phase_sn" id="phase_sn" size="10" class="input-text" value=""></td>
			</tr>
			<tr>
				<th width="100">开奖号码：</th>
				<td><input type="text" name="lottery_result" id="lottery_result" size="40" class="input-text" value=""></td>
			</tr>
			<tr>
				<th width="100">开奖时间：</th>
				<td><input type="text" name="lottery_time" id="lottery_time" size="20" class="date input-text" value=""></td>
			</tr>
			<tr>
				<th width="100">一等奖奖金：</th>
				<td><input type="text" name="first_prize" id="first_prize" size="10" class="input-text" value=""></td>
			</tr>
			<tr>
				<th width="100">二等奖奖金：</th>
				<td><input type="text" name="second_prize" id="second_prize" size="10" class="input-text" value=""></td>
			</tr>
			<tr>
				<th width="100">一等奖注数：</th>
				<td><input type="text" name="first_prize_count" id="first_prize_count" size="10" class="input-text" value=""></td>
			</tr>
			<tr>
				<th width="100">二等奖注数：</th>
				<td><input type="text" name="second_prize_count" id="second_prize_count" size="10" class="input-text" value=""></td>
			</tr>
			<tr>
				<th width="100">三等奖注数：</th>
				<td><input type="text" name="third_prize_count" id="third_prize_count" size="10" class="input-text" value=""></td>
			</tr>
			<tr>
				<th width="100">四等奖注数：</th>
				<td><input type="text" name="fourth_prize_count" id="fourth_prize_count" size="10" class="input-text" value=""></td>
			</tr>
			<tr>
				<th width="100">五等奖注数：</th>
				<td><input type="text" name="fifth_prize_count" id="fifth_prize_count" size="10" class="input-text" value=""></td>
			</tr>
			<tr>
				<th width="100">六等奖注数：</th>
				<td><input type="text" name="sixth_prize_count" id="sixth_prize_count" size="10" class="input-text" value=""></td>
			</tr>
			<tr>
				<td width="100%" align="center" colspan="2"><input id="form_submit" type="button" name="dosubmit" class="btn_submit" value=" 提交 " /></td>
			</tr>
		</table>

	</form>
</div>

<script type="text/javascript">
Calendar.setup({
	weekNumbers: false,
    inputField : "lottery_time",
    trigger    : "lottery_time",
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