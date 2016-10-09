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
	<form action="<?php echo YUrl::createBackendUrl('User', 'edit'); ?>" method="post" name="myform" id="myform">
		<table cellpadding="2" cellspacing="1" class="table_form" width="100%">
			<tr>
				<th width="100">账号：</th>
				<td><input type="text" name="username" id="username" size="20"
					class="input-text" value="<?php echo $userinfo['username']; ?>"></td>
			</tr>
			<tr>
				<th width="100">密码：</th>
				<td><input type="password" name="password" id="password" size="20"
					class="input-text" value=""></td>
			</tr>
			<tr>
				<th width="100">手机号码：</th>
				<td><input type="text" name="mobilephone" id="mobilephone" size="20"
					class="input-text" value="<?php echo $userinfo['mobilephone']; ?>"></td>
			</tr>
			<tr>
				<th width="100">邮箱：</th>
				<td><input type="text" name="email" id="email" size="20"
					class="input-text" value="<?php echo $userinfo['email']; ?>"></td>
			</tr>
			<tr>
				<th width="100">真实姓名：</th>
				<td><input type="text" name="realname" id="realname" size="20"
					class="input-text" value="<?php echo $userinfo['realname']; ?>"></td>
			</tr>
			<tr>
				<th width="100">签名：</th>
				<td><input type="text" name="signature" id="signature" size="40"
					class="input-text" value="<?php echo $userinfo['signature']; ?>"></td>
			</tr>
			<tr>
				<th width="100">头像：</th>
				<td><input type="hidden" name="avatar" id="avatar"
					value="<?php echo $userinfo['avatar']; ?>" />
					<div id="avatar_view"></div></td>
			</tr>
			<tr>
				<td width="100%" align="center" colspan="2"><input type="hidden"
					name="user_id" value="<?php echo $userinfo['user_id']; ?>" /> <input
					id="form_submit" type="button" name="dosubmit" class="btn_submit"
					value=" 提交 " /></td>
			</tr>
		</table>

	</form>
</div>

<script src="<?php echo YUrl::assets('js', '/AjaxUploader/uploadImage.js'); ?>"></script>
<script type="text/javascript">

var uploadUrl = '<?php echo YUrl::createBackendUrl('Index', 'upload'); ?>';
var baseJsUrl = '<?php echo YUrl::assets('js', ''); ?>';
var filUrl    = '<?php echo YUrl::getDomainName(); ?>';
uploadImage(filUrl, baseJsUrl, 'avatar_view', 'avatar', 120, 120, uploadUrl);

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