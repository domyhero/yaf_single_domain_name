<?php
use common\YUrl;
require_once (dirname(__DIR__) . '/common/header.php');
?>

<div class="subnav">
	<div class="content-menu ib-a blue line-x">
		<a href='javascript:;' class="on"><em>抽奖活动奖品列表</em></a>
	</div>
</div>
<style type="text/css">
html {
	_overflow-y: scroll
}
</style>
<div class="pad-lr-10">

	<form name="myform" id="myform" action="<?php echo YUrl::createBackendUrl('Lucky', 'set'); ?>" method="post">
		<div class="table-list">
			<table width="100%" cellspacing="0">
				<thead>
					<tr>
						<th align="center">奖品名称</th>
						<th align="center">奖品图片</th>
						<th align="center">每天中奖最大次数</th>
						<th align="center">随机最小值</th>
						<th align="center">随机最大值</th>
						<th align="center">商品类型</th>
						<th width="120" align="center">创建人</th>
						<th width="120" align="center">创建时间</th>
					</tr>
				</thead>
				<tbody>
                <?php foreach ($list as $item): ?>
    	           <tr>
						<td align="center"><input type="text" name="goods[<?php echo $item['id']; ?>][goods_name]" value="<?php echo htmlspecialchars($item['goods_name']); ?>" /></td>
						<td align="center">
						<input type="hidden" name="goods[<?php echo $item['id']; ?>][image_url]" id="avatar_<?php echo $item['id']; ?>" value="<?php echo $item['image_url']; ?>" />
					    <div id="avatar_view_<?php echo $item['id']; ?>"></div>
						</td>
						<td align="center"><input type="text" size="5" name="goods[<?php echo $item['id']; ?>][day_max]" value="<?php echo htmlspecialchars($item['day_max']); ?>" /></td>
						<td align="center"><input type="text" size="5" name="goods[<?php echo $item['id']; ?>][min_range]" value="<?php echo htmlspecialchars($item['min_range']); ?>" /></td>
						<td align="center"><input type="text" size="5" name="goods[<?php echo $item['id']; ?>][max_range]" value="<?php echo htmlspecialchars($item['max_range']); ?>" /></td>
						<td align="center">
						  <select name="goods[<?php echo $item['id']; ?>][goods_type]">
						      <?php
						      foreach ($goods_type_dict as $goods_type_code => $goods_type_label) {
						          if ($goods_type_code == $item['goods_type']) {
						              echo "<option selected=\"selected\" value=\"{$goods_type_code}\">{$goods_type_label}</option>";
						          } else {
						              echo "<option value=\"{$goods_type_code}\">{$goods_type_label}</option>";
						          }
						      }
						      ?>
						  </select>
						</td>
						<td align="center"><?php echo $item['created_by']; ?></td>
						<td align="center"><?php echo $item['created_time']; ?></td>
			       </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="10" align="center">
                    <input id="form_submit" type="button" name="dosubmit" class="btn_submit" value=" 保存 " />
                    </td>
                </tr>
                </tbody>
			</table>

		</div>

	</form>
</div>

<script src="<?php echo YUrl::assets('js', '/AjaxUploader/uploadImage.js'); ?>"></script>
<script type="text/javascript">

var uploadUrl = '<?php echo YUrl::createBackendUrl('Index', 'upload'); ?>';
var baseJsUrl = '<?php echo YUrl::assets('js', ''); ?>';
var filUrl    = '<?php echo YUrl::getDomainName(); ?>';
uploadImage(filUrl, baseJsUrl, 'avatar_view_1', 'avatar_1', 40, 40, uploadUrl);
uploadImage(filUrl, baseJsUrl, 'avatar_view_2', 'avatar_2', 40, 40, uploadUrl);
uploadImage(filUrl, baseJsUrl, 'avatar_view_3', 'avatar_3', 40, 40, uploadUrl);
uploadImage(filUrl, baseJsUrl, 'avatar_view_4', 'avatar_4', 40, 40, uploadUrl);
uploadImage(filUrl, baseJsUrl, 'avatar_view_5', 'avatar_5', 40, 40, uploadUrl);
uploadImage(filUrl, baseJsUrl, 'avatar_view_6', 'avatar_6', 40, 40, uploadUrl);
uploadImage(filUrl, baseJsUrl, 'avatar_view_7', 'avatar_7', 40, 40, uploadUrl);
uploadImage(filUrl, baseJsUrl, 'avatar_view_8', 'avatar_8', 40, 40, uploadUrl);
uploadImage(filUrl, baseJsUrl, 'avatar_view_9', 'avatar_9', 40, 40, uploadUrl);

$(document).ready(function(){
	$('#form_submit').click(function(){
	    $.ajax({
	    	type: 'post',
            url: $('form').eq(0).attr('action'),
            dataType: 'json',
            data: $('form').eq(0).serialize(),
            success: function(data) {
                if (data.errcode == 0) {
                	window.location.reload();
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