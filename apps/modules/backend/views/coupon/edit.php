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
	<form action="<?php echo YUrl::createBackendUrl('Coupon', 'edit'); ?>"
		method="post" name="myform" id="myform">
		<table cellpadding="2" cellspacing="1" class="table_form" width="100%">
			<tr>
				<th width="100">优惠券名称：</th>
				<td><input type="text" name="coupon_name" id="coupon_name" size="50"
					class="input-text"
					value="<?php echo htmlspecialchars($detail['coupon_name']) ?>"></td>
			</tr>
			<tr>
				<th width="100">优惠券金额：</th>
				<td><input type="text" name="money" id="money" size="10"
					class="input-text" value="<?php echo $detail['money'] ?>"></td>
			</tr>
			<tr>
				<th width="100">订单金额：</th>
				<td><input type="text" name="order_money" id="order_money" size="10"
					class="input-text" value="<?php echo $detail['order_money'] ?>"></td>
			</tr>
			<tr>
				<th width="100">领取时间：</th>
				<td><input type="text" name="get_start_time" id="get_start_time"
					size="20" class="date input-text"
					value="<?php echo $detail['get_start_time'] ?>"> 至 <input
					type="text" name="get_end_time" id="get_end_time" size="20"
					class="date input-text"
					value="<?php echo $detail['get_end_time'] ?>"></td>
			</tr>
			<tr>
				<th width="100">每人限领数量：</th>
				<td><input type="text" name="limit_quantity" id="limit_quantity"
					size="10" class="input-text"
					value="<?php echo $detail['limit_quantity'] ?>"></td>
			</tr>
			<tr>
				<th width="100">优惠券过期时间：</th>
				<td><input type="text" name="expiry_date" id="expiry_date" size="20"
					class="date input-text"
					value="<?php echo $detail['expiry_date'] ?>"></td>
			</tr>
			<tr>
				<td width="100%" align="center" colspan="2"><input type="hidden"
					name="coupon_id" value="<?php echo $detail['coupon_id'] ?>" /> <input
					id="form_submit" type="button" name="dosubmit" class="btn_submit"
					value=" 提交 " /></td>
			</tr>
		</table>

	</form>
</div>

<script type="text/javascript">
Calendar.setup({
	weekNumbers: false,
    inputField : "get_start_time",
    trigger    : "get_start_time",
    dateFormat: "%Y-%m-%d %H:%I:%S",
    showTime: true,
    minuteStep: 1,
    onSelect   : function() {this.hide();}
});

Calendar.setup({
	weekNumbers: false,
    inputField : "get_end_time",
    trigger    : "get_end_time",
    dateFormat: "%Y-%m-%d %H:%I:%S",
    showTime: true,
    minuteStep: 1,
    onSelect   : function() {this.hide();}
});

Calendar.setup({
	weekNumbers: false,
    inputField : "expiry_date",
    trigger    : "expiry_date",
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