DROP DATABASE IF EXISTS phper;
CREATE DATABASE phper DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
use phper;


DROP TABLE IF EXISTS ms_user;
CREATE TABLE ms_user(
	user_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '用户ID',
	username CHAR(20) NOT NULL COMMENT '账号',
	password CHAR(32) NOT NULL COMMENT '密码',
	salt CHAR(6) NOT NULL COMMENT '密码盐',
	mobilephone CHAR(11) NOT NULL DEFAULT '' COMMENT '手机号码',
	mobilephone_ok TINYINT(1) NOT NULL DEFAULT '0' COMMENT '手机验证状态：0未验证、1已验证',
	mobilephone_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '手机验证通过时间',
	email CHAR(50) NOT NULL DEFAULT '' COMMENT '邮箱',
	email_ok TINYINT(1) NOT NULL DEFAULT '0' COMMENT '邮箱验证状态：0未验证、1已验证',
	email_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '邮箱验证通过时间',
	last_login_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '最后登录时间',
	reg_time INT(11) UNSIGNED NOT NULL COMMENT '注册时间',
	PRIMARY KEY(user_id),
	UNIQUE KEY `username_unique` (username),
	KEY `mobilephone_key` (mobilephone),
	KEY `email_key` (email)
) ENGINE = InnoDB DEFAULT CHARSET UTF8 COMMENT '用户表';


# 用户副表。
DROP TABLE IF EXISTS ms_user_data;
CREATE TABLE ms_user_data(
	id INT(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
	user_id INT(11) UNSIGNED NOT NULL COMMENT '用户ID',
	nickname CHAR(10) NOT NULL DEFAULT '' COMMENT '昵称',
	realname CHAR(10) NOT NULL DEFAULT '' COMMENT '真实姓名',
	avatar CHAR(50) NOT NULL DEFAULT '' COMMENT '头像地址',
	mobilephone CHAR(11) NOT NULL DEFAULT '' COMMENT '手机号码',
	signature CHAR(50) NOT NULL DEFAULT '' COMMENT '个性签名',
	birthday CHAR(10) NOT NULL DEFAULT '0000-00-00' COMMENT '生日',
	sex TINYINT(1) NOT NULL DEFAULT '0' COMMENT '性别：1男、2女、0保密',
	email CHAR(50) NOT NULL DEFAULT '' COMMENT '邮箱',
	created_time INT(11) UNSIGNED NOT NULL COMMENT '创建时间',
	modified_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改时间',
	PRIMARY KEY(id),
	KEY(user_id)
) ENGINE = InnoDB DEFAULT CHARSET UTF8 COMMENT '用户副表';


# 找回密码记录表
DROP TABLE IF EXISTS ms_find_pwd;
CREATE TABLE ms_find_pwd(
	id INT(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
	user_id INT(11) UNSIGNED NOT NULL COMMENT '用户ID',
	find_type TINYINT(1) NOT NULL COMMENT '找回密码类型：1手机号找回、2邮箱找回',
	to_account CHAR(50) NOT NULL COMMENT '手机或邮箱或其他',
	code CHAR(6) NOT NULL COMMENT '验证码',
	check_times SMALLINT(3) NOT NULL DEFAULT '0' COMMENT '验证次数',
	is_ok TINYINT(1) NOT NULL DEFAULT '0' COMMENT '最后一次否验证通过标记。0未使用、1已通过验证、2未验证通过',
	ip CHAR(15) NOT NULL COMMENT 'IP地址',
	created_time INT(11) UNSIGNED NOT NULL COMMENT '创建时间',
	modified_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改时间',
	PRIMARY KEY(id),
	KEY(find_type, to_account)
) ENGINE = InnoDB DEFAULT CHARSET UTF8 COMMENT '找回密码记录表';


# 用户登录历史表
# 记录用户的登录行为，提供风险评估。
DROP TABLE IF EXISTS ms_user_login;
CREATE TABLE ms_user_login(
	id INT(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
	user_id INT(11) UNSIGNED NOT NULL COMMENT '用户ID',
	login_time INT(10) NOT NULL COMMENT '登录时间',
	login_ip CHAR(50) NOT NULL COMMENT '登录IP',
	login_entry TINYINT(1) NOT NULL COMMENT '登录入口：1PC、2APP、3WAP',
	PRIMARY KEY(id),
	KEY(user_id)
) ENGINE = InnoDB DEFAULT CHARSET UTF8 COMMENT '用户登录历史表';


# 此表要保存账号历史禁用记录
DROP TABLE IF EXISTS ms_user_blacklist;
CREATE TABLE ms_user_blacklist(
	id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
	user_id INT(11) UNSIGNED NOT NULL COMMENT '用户ID',
	username CHAR(20) NOT NULL COMMENT '账号',
	ban_type SMALLINT(1) NOT NULL COMMENT '禁用类型：1永久封禁、2临时封禁',
	ban_start_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '封禁开始时间',
	ban_end_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '封禁截止时间',
	ban_reason CHAR(255) NOT NULL DEFAULT '' COMMENT '账号封禁原因',
	status TINYINT(1) NOT NULL DEFAULT '0' COMMENT '记录状态：0失效、1生效',
	created_by INT(11) UNSIGNED NOT NULL COMMENT '创建人',
	created_time INT(11) UNSIGNED NOT NULL COMMENT '创建时间',
	modified_by INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改人',
	modified_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改时间',
	PRIMARY KEY(id),
	KEY(username),
	KEY(user_id)
) ENGINE = InnoDB DEFAULT CHARSET UTF8 COMMENT '用户黑名单表';


# 记录第三方登录绑定。
DROP TABLE IF EXISTS ms_user_bind;
CREATE TABLE ms_user_bind(
	id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
	user_id INT(11) UNSIGNED NOT NULL COMMENT '用户ID',
	bind_type CHAR(10) NOT NULL COMMENT '绑定类型：qq、weibo、weixin',
	openid VARCHAR(100) NOT NULL COMMENT 'openid',
	created_time INT(11) UNSIGNED NOT NULL COMMENT '创建时间',
	status TINYINT(1) NOT NULL DEFAULT '0' COMMENT '记录状态：0失效、1生效',
	PRIMARY KEY(id),
	KEY(openid),
	KEY(user_id),
	KEY(bind_type)
) ENGINE = InnoDB DEFAULT CHARSET UTF8 COMMENT '用户绑定表';


# 短信发送/验证码日志表
DROP TABLE IF EXISTS `ms_sms_log`;
CREATE TABLE ms_sms_log (
	log_id INT(11) UNSIGNED AUTO_INCREMENT COMMENT '日志ID',
	op_type TINYINT(1) NOT NULL COMMENT '操作类型：1发送、2验证',
	mobilephone CHAR(11) NOT NULL COMMENT '手机号码',
	sms_txt CHAR(200) NOT NULL COMMENT '短信内容',
	sms_code CHAR(6) NOT NULL DEFAULT '' COMMENT '验证码。如果是非验证码短信，此值为空字符串',
	is_destroy TINYINT(1) NOT NULL DEFAULT '0' COMMENT '是否验证成功立即销毁。1是、0否',
	created_time INT(11) UNSIGNED NOT NULL COMMENT '创建时间',
	PRIMARY KEY(log_id),
	KEY(mobilephone)
)ENGINE = InnoDB DEFAULT CHARSET = 'UTF8' COMMENT '短信发送/验证日志表';


# 敏感词表
DROP TABLE IF EXISTS ms_sensitive;
CREATE TABLE ms_sensitive(
	id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
	lv TINYINT(1) NOT NULL DEFAULT '0' COMMENT '敏感等级',
	val VARCHAR(50) NOT NULL COMMENT '敏感词',
	created_by INT(11) UNSIGNED NOT NULL COMMENT '创建人',
	created_time INT(11) UNSIGNED NOT NULL COMMENT '创建时间',
	modified_by INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改人',
	modified_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改时间',
	PRIMARY KEY(id),
	KEY(val)
) ENGINE = InnoDB DEFAULT CHARSET UTF8 COMMENT '敏感词表';


# IP黑名单表
DROP TABLE IF EXISTS ms_ip_ban;
CREATE TABLE ms_ip_ban(
	id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
	ip VARCHAR(15) NOT NULL COMMENT 'IP地址',
	remark CHAR(50) NOT NULL DEFAULT '' COMMENT '备注',
	created_by INT(11) UNSIGNED NOT NULL COMMENT '创建人',
	created_time INT(11) UNSIGNED NOT NULL COMMENT '创建时间',
	modified_by INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改人',
	modified_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改时间',
	PRIMARY KEY(id),
	KEY(ip)
) ENGINE = InnoDB DEFAULT CHARSET UTF8 COMMENT 'IP黑名单表';


# 记录周更新备份一次，按月份保存历史数据。
DROP TABLE IF EXISTS ms_log;
CREATE TABLE ms_log(
	log_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
	log_type TINYINT(1) NOT NULL DEFAULT '0' COMMENT '日志类型：参见models\Log常量',
	log_user_id INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '操作用户ID',
	log_time INT(11) UNSIGNED NOT NULL COMMENT '日志产生时间',
	errcode INT(11) NOT NULL DEFAULT '0' COMMENT '错误编号',
	content TEXT COMMENT '日志内容',
	created_time INT(11) UNSIGNED NOT NULL COMMENT '日志创建时间',
	PRIMARY KEY(log_id)
) ENGINE = InnoDB DEFAULT CHARSET UTF8 COMMENT '系统日志表';


# 字典类型表
DROP TABLE IF EXISTS ms_dict_type;
CREATE TABLE ms_dict_type(
	dict_type_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
	type_code CHAR(50) NOT NULL COMMENT '字典类型编码',
    type_name CHAR(50) NOT NULL COMMENT '字典类型名称',
    description CHAR(200) NOT NULL DEFAULT '' COMMENT '字典类型描述',
    status TINYINT(1) NOT NULL DEFAULT '0' COMMENT '状态：0失效、1有效、2删除',
    created_by INT(11) UNSIGNED NOT NULL COMMENT '类型创建人',
	created_time INT(11) UNSIGNED NOT NULL COMMENT '类型创建时间',
	modified_by INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '类型修改人',
	modified_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '类型修改时间',
	PRIMARY KEY(dict_type_id),
	KEY `type_code` (type_code)
) ENGINE = InnoDB DEFAULT CHARSET UTF8 COMMENT '字典类型表';
INSERT INTO ms_dict_type (`dict_type_id`, `type_code`, `type_name`, `description`, `status`, `created_by`, `created_time`, `modified_by`, `modified_time`) 
VALUES ('1', 'category_type_list', '分类类型列表', '此分类类型列表用在分类列表中。', '1', '1', unix_timestamp(now()), '0', '0');


# 字典数据表
DROP TABLE IF EXISTS ms_dict;
CREATE TABLE ms_dict(
	dict_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
	dict_type_id INT(11) UNSIGNED NOT NULL COMMENT '主键',
	dict_code CHAR(50) NOT NULL COMMENT '字典编码',
    dict_value CHAR(255) NOT NULL DEFAULT '' COMMENT '字典值',
    description CHAR(255) NOT NULL DEFAULT '' COMMENT '字典类型描述',
    listorder SMALLINT(1) NOT NULL DEFAULT '0' COMMENT '排序。小在前',
    status TINYINT(1) NOT NULL DEFAULT '0' COMMENT '状态：0失效、1有效、2删除',
    created_by INT(11) UNSIGNED NOT NULL COMMENT '类型创建人',
	created_time INT(11) UNSIGNED NOT NULL COMMENT '类型创建时间',
	modified_by INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '类型修改人',
	modified_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '类型修改时间',
	PRIMARY KEY(dict_id),
	KEY(dict_type_id)
) ENGINE = InnoDB DEFAULT CHARSET UTF8 COMMENT '字典数据表';
INSERT INTO ms_dict (`dict_type_id`, `dict_code`, `dict_value`, `description`, `listorder`, `status`, `created_by`, `created_time`, `modified_by`, `modified_time`) 
VALUES 
('1', '1', '文章分类', '文章分类的值最好别更改。因为，会影响此分类关联的子分类。如确实要变更，请检查此ID对应的表ms_category的分类是否有值。如果有请处理之后再变更此值。', '0', '1', '1', unix_timestamp(now()), '0', '0'),
('1', '2', '友情链接分类', '请别随意更改编码值。因为与它关联的子分类数据会失去依赖。', '0', '1', '1', unix_timestamp(now()), '0', '0'),
('1', '3', '商品分类', '请别随意更改编码值。因为与它关联的子分类数据会失去依赖。', '0', '1', '1', unix_timestamp(now()), '0', '0');


# 系统配置表
# 一些需要动态修改的配置。
DROP TABLE IF EXISTS ms_config;
CREATE TABLE ms_config(
	config_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
	ctitle CHAR(255) NOT NULL COMMENT '配置标题',
	cname CHAR(255) NOT NULL COMMENT '名称',
	cvalue CHAR(255) NOT NULL DEFAULT '' COMMENT '配置值',
	description CHAR(255) NOT NULL DEFAULT '' COMMENT '配置描述',
    status TINYINT(1) NOT NULL DEFAULT '0' COMMENT '状态：0失效、1有效、2删除',
    created_by INT(11) UNSIGNED NOT NULL COMMENT '类型创建人',
	created_time INT(11) UNSIGNED NOT NULL COMMENT '类型创建时间',
	modified_by INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '类型修改人',
	modified_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '类型修改时间',
	PRIMARY KEY(config_id),
	KEY `cname` (cname)
) ENGINE = InnoDB DEFAULT CHARSET UTF8 COMMENT '系统配置表';
INSERT INTO ms_config (`ctitle`, `cname`, `cvalue`, `description`, `status`, `created_by`, `created_time`, `modified_by`, `modified_time`)
VALUES('排他登录', 'is_unique_login', '1', '1是、0否。即同一时间账号只能在一个地方登录。不允许账号在其他地方登录。', 1, 1, unix_timestamp(now()), 0, 0),
('网站名称', 'site_name', 'PHP解说', '', 1, 1, unix_timestamp(now()), 0, 0),
('PC登录超时时间(分钟)', 'pc_logout_time', '30', '登录超时时间。距离上次最后操作时间大于当前指定时间分钟内将登录超时并退出登录', 1, 1, unix_timestamp(now()), 0, 0),
('系统维护状态', 'system_status', '1', '除管理后台之外的地方维护状态。1是正常、0是关闭系统', 1, 1, unix_timestamp(now()), 0, 0),
('系统业务运行等级', 'system_service_level', '0', '示例：1,8 。1:注册功能、2:登录功能、4:找回密码、8:密码修改、16:支付功能、32:短信功能、64:邮件功能、128:评价功能、256:上传功能、512:订单查看功能、1024:提现功能、2048:API接口、4096:微信应用、8192:关闭全站（除后台）', 1, 1, unix_timestamp(now()), 0, 0),
('luosimao短信KEY', 'luosimao_sms_key', '5d68e2564cc9deac5bc8d74935dc4e8c', 'luosimao短信发送KEY。', 1, 1, unix_timestamp(now()), 0, 0),
('省市区JSON文件更新版本', 'district_json_version', '', '省市区JSON文件更新版本', 1, 1, unix_timestamp(now()), 0, 0),
('APP登录超时时间(天)', 'app_logout_time', '30', '登录超时时间。距离上次最后操作时间大于当前指定时间分钟内将登录超时并退出登录', 1, 1, unix_timestamp(now()), 0, 0),
('后台登录超时时间(分钟)', 'admin_logout_time', '30', '超时则需要重新登录', 1, 1, unix_timestamp(now()), 0, 0);


# 文件表
# 上传的图片、视频等文件记录在此表中。
# 如果是公开的图片则图片链接是固定的。私有的则图片链接是动态生成的。
DROP TABLE IF EXISTS ms_files;
CREATE TABLE ms_files(
	file_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
	file_name CHAR(50) NOT NULL COMMENT '文件名称',
	file_type TINYINT(1) NOT NULL COMMENT '文件类型：1-图片、2-其他文件',
	file_size INT(11) UNSIGNED NOT NULL COMMENT '文件大小。单位：(byte)',
	file_md5 CHAR(32) NOT NULL COMMENT '文件md5值',
	user_type TINYINT(1) NOT NULL COMMENT '用户类型：1管理员、2普通用户',
	user_id INT(11) UNSIGNED NOT NULL COMMENT '用户ID',
	status TINYINT(1) NOT NULL DEFAULT '0' COMMENT '状态：0失效、1有效、2删除',
	created_time INT(11) UNSIGNED NOT NULL COMMENT '创建时间',
	PRIMARY KEY(file_id)
) ENGINE = InnoDB DEFAULT CHARSET UTF8 COMMENT '文件表';


# 管理员表
DROP TABLE IF EXISTS ms_admin;
CREATE TABLE ms_admin(
	admin_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '管理员ID',
	realname CHAR(20) NOT NULL COMMENT '真实姓名',
	username CHAR(20) NOT NULL COMMENT '账号',
	password CHAR(32) NOT NULL COMMENT '密码',
	salt CHAR(6) NOT NULL COMMENT '密码盐',
	mobilephone CHAR(11) NOT NULL DEFAULT '' COMMENT '手机号码',
	roleid SMALLINT(3) NOT NULL DEFAULT '0' COMMENT '角色ID',
	lastlogintime INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '最后登录时间戳',
	status TINYINT(1) NOT NULL DEFAULT '0' COMMENT '状态：0失效、1有效、2删除',
	created_time INT(11) UNSIGNED NOT NULL COMMENT '创建时间',
	PRIMARY KEY(admin_id),
	KEY(username),
	KEY(mobilephone)
) ENGINE = InnoDB DEFAULT CHARSET UTF8 COMMENT '管理员表';
INSERT INTO ms_admin (admin_id, realname, username, password, salt, status, created_time, roleid)
VALUES(1, '超级管理员', 'admin', 'c7935cc8ee50b752345290d8cf136827', 'abcdef', 1, unix_timestamp(now()), 1);


# 管理员登录历史表	
DROP TABLE IF EXISTS ms_admin_login_history;
CREATE TABLE `ms_admin_login_history` (
  id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  admin_id INT(11) UNSIGNED NOT NULL COMMENT '管理员ID',
  browser_type CHAR(10) NOT NULL COMMENT '浏览器类型。tablet平板、phone手机、computer电脑',
  user_agent VARCHAR(200) NOT NULL COMMENT '浏览器UA',
  ip CHAR(15) NOT NULL COMMENT '登录IP',
  address VARCHAR(100) NOT NULL DEFAULT '' COMMENT 'IP对应的地址信息',
  created_time INT(11) UNSIGNED NOT NULL COMMENT '登录时间',
  PRIMARY KEY(id)
) ENGINE=InnoDB DEFAULT CHARSET UTF8 COMMENT '管理员登录历史表';


# 角色表	
DROP TABLE IF EXISTS ms_admin_role;
CREATE TABLE ms_admin_role(
	roleid INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '角色ID',
	rolename CHAR(20) NOT NULL COMMENT '角色名称',
	listorder SMALLINT(3) NOT NULL DEFAULT '0' COMMENT '排序。小在前。',
	description CHAR(255) NOT NULL DEFAULT '' COMMENT '角色说明',
	status TINYINT(1) NOT NULL DEFAULT '0' COMMENT '状态：0失效、1有效、2删除',
	created_time INT(11) UNSIGNED NOT NULL COMMENT '创建时间',
	is_default TINYINT(1) NOT NULL DEFAULT '0' COMMENT '默认角色拥有最高权限。不可删除此默认角色。超级管理员只能属于此角色，其他用户不可分配此角色',
	PRIMARY KEY(roleid)
) ENGINE = InnoDB DEFAULT CHARSET UTF8 COMMENT '角色表';
INSERT INTO ms_admin_role (roleid, rolename, status, is_default, created_time) VALUES(1, '超级管理员', 1, 1, unix_timestamp(now()));


# 角色权限表	
DROP TABLE IF EXISTS ms_admin_role_priv;
CREATE TABLE `ms_admin_role_priv` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `roleid` SMALLINT(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '角色ID',
  `menu_id` INT(11) UNSIGNED NOT NULL COMMENT '菜单ID',
  PRIMARY KEY(id),
  KEY(roleid)
) ENGINE=InnoDB DEFAULT CHARSET UTF8 COMMENT '角色权限表';


# 文章表
DROP TABLE IF EXISTS ms_news;
CREATE TABLE `ms_news` (
	news_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '文章ID、主键',
	cat_id INT(11) UNSIGNED NOT NULL COMMENT '分类ID。对应ms_category.cat_id',
	title CHAR(50) NOT NULL COMMENT '文章标题',
	code CHAR(20) NOT NULL COMMENT '文章编码(只允许字母数字下划线横线,不能为纯数字)',
	intro CHAR(250) NOT NULL COMMENT '文章简介。也是SEO中的description',
	keywords CHAR(50) NOT NULL DEFAULT '' COMMENT '文章关键词。也是SEO中的keywords',
	image_url CHAR(100) NOT NULL DEFAULT '' COMMENT '文章列表图片',
	source CHAR(20) NOT NULL DEFAULT '' COMMENT '文章来源',
	display TINYINT(1) NOT NULL DEFAULT '0' COMMENT '文章是否显示。1显示、0隐藏',
	status TINYINT(1) NOT NULL DEFAULT '0' COMMENT '文章状态：0无效、1正常、2删除',
	listorder SMALLINT(6) UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序。小到大排序。',
	hits INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '文章访问量',
	created_by INT(11) UNSIGNED NOT NULL COMMENT '创建人',
	created_time INT(11) UNSIGNED NOT NULL COMMENT '创建时间',
	modified_by INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改人',
	modified_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改时间',
	PRIMARY KEY(news_id),
	KEY(created_time),
	KEY(created_by)
) ENGINE=InnoDB DEFAULT CHARSET UTF8 COMMENT '文章表';


# 文章副表
DROP TABLE IF EXISTS ms_news_data;
CREATE TABLE `ms_news_data` (
	news_id INT(11) UNSIGNED NOT NULL COMMENT '文章ID',
	content TEXT COMMENT '文章内容',
	PRIMARY KEY(news_id)
) ENGINE=InnoDB DEFAULT CHARSET UTF8 COMMENT '文章副表';


# 友情链接表
# 通过一个URL来统一跳转这些友情链接。方便统计。
DROP TABLE IF EXISTS ms_link;
CREATE TABLE `ms_link` (
	link_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
	link_name VARCHAR(50) NOT NULL COMMENT '友情链接名称',
	link_url VARCHAR(100) NOT NULL COMMENT '友情链接URL',
	cat_id INT(11) UNSIGNED NOT NULL COMMENT '友情链接分类ID。对应ms_category.cat_id',
	image_url VARCHAR(100) NOT NULL DEFAULT '' COMMENT '友情链接图片',
	display TINYINT(1) NOT NULL DEFAULT '0' COMMENT '是否显示。1显示、0隐藏',
	status TINYINT(1) NOT NULL DEFAULT '0' COMMENT '状态：0无效、1正常、2删除',
	listorder SMALLINT(6) UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序。小到大排序。',
	hits INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'URL点击量',
	created_by INT(11) UNSIGNED NOT NULL COMMENT '创建人',
	created_time INT(11) UNSIGNED NOT NULL COMMENT '创建时间',
	modified_by INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改人',
	modified_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改时间',
	PRIMARY KEY(link_id)
) ENGINE=InnoDB DEFAULT CHARSET UTF8 COMMENT '友情链接表';


# 广告位置接表
DROP TABLE IF EXISTS ms_ad_position;
CREATE TABLE `ms_ad_position` (
	pos_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
	pos_name VARCHAR(50) NOT NULL COMMENT '广告位置名称',
	pos_code VARCHAR(50) NOT NULL COMMENT '广告位置编码。通过编码来读取广告数据',
	pos_ad_count SMALLINT(5) NOT NULL COMMENT '该广告位置显示可展示广告的数量',
	status TINYINT(1) NOT NULL COMMENT '状态：0无效、1正常、2删除',
	created_by INT(11) UNSIGNED NOT NULL COMMENT '创建人',
	created_time INT(11) UNSIGNED NOT NULL COMMENT '创建时间',
	modified_by INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改人',
	modified_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改时间',
	PRIMARY KEY(pos_id)
) ENGINE=InnoDB DEFAULT CHARSET UTF8 COMMENT '广告位置接表';


# 广告表
DROP TABLE IF EXISTS ms_ad;
CREATE TABLE `ms_ad` (
	ad_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
	ad_name VARCHAR(50) NOT NULL COMMENT '广告名称',
	pos_id INT(11) UNSIGNED NOT NULL COMMENT '广告位置。对应ms_ad_postion.pos_id',
	ad_image_url VARCHAR(100) NOT NULL COMMENT '广告图片',
	ad_url VARCHAR(100) NOT NULL COMMENT '广告图片URL跳转地址',
	start_time INT(11) UNSIGNED NOT NULL COMMENT '广告生效时间',
	end_time INT(11) UNSIGNED NOT NULL COMMENT '广告失效时间',
	display TINYINT(1) NOT NULL DEFAULT '1' COMMENT '显示状态：1显示、0隐藏',
	status TINYINT(1) NOT NULL COMMENT '状态：0无效、1正常、2删除',
	listorder SMALLINT(6) UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序。小到大排序。',
	remark VARCHAR(255) NOT NULL DEFAULT '' COMMENT '备注',
	created_by INT(11) UNSIGNED NOT NULL COMMENT '创建人',
	created_time INT(11) UNSIGNED NOT NULL COMMENT '创建时间',
	modified_by INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改人',
	modified_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改时间',
	PRIMARY KEY(ad_id)
) ENGINE=InnoDB DEFAULT CHARSET UTF8 COMMENT '广告表';


# 分类表
# 所有父分类ID为0的分类，都有一个共同的虚拟顶级父类ID为0。
DROP TABLE IF EXISTS `ms_category`;
CREATE TABLE ms_category(
	cat_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '分类ID',
	cat_name VARCHAR(50) NOT NULL COMMENT '分类名称',
	cat_type SMALLINT(3) NOT NULL COMMENT '分类类型。见category_type_list字典。',
	parentid INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '父分类ID',
	lv SMALLINT(3) NOT NULL COMMENT '菜单层级',
	cat_code VARCHAR(50) NOT NULL COMMENT '分类code编',
	is_out_url TINYINT(1) NOT NULL DEFAULT '0' COMMENT '是否外部链接：1是、0否',
	out_url VARCHAR(255) NOT NULL DEFAULT '' COMMENT '外部链接地址',
	display TINYINT(1) NOT NULL DEFAULT '0' COMMENT '显示状态：1是、0否',
	tpl_name CHAR(50) NOT NULL DEFAULT '' COMMENT '模板名称',
	status TINYINT(1) NOT NULL DEFAULT '0' COMMENT '状态：0无效、1正常、2删除',
	listorder SMALLINT(5) NOT NULL DEFAULT '0' COMMENT '排序值。小到大排列。',
	modified_by INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改人',
	modified_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改时间戳',
	created_time INT(11) UNSIGNED NOT NULL COMMENT '创建时间戳',
	created_by INT(11) UNSIGNED NOT NULL COMMENT '管理员账号ID',
	PRIMARY KEY(cat_id),
	KEY(cat_code)
) ENGINE = InnoDB DEFAULT CHARSET UTF8 COMMENT '分类表';

# 收藏表
DROP TABLE IF EXISTS ms_favorites;
CREATE TABLE ms_favorites(
	id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
	user_id INT(11) UNSIGNED NOT NULL COMMENT '用户ID',
	obj_type TINYINT(1) NOT NULL COMMENT '收藏类型：1商品收藏、2文章收藏',
	obj_id INT(11) UNSIGNED NOT NULL COMMENT '商品ID/文章ID',
	status TINYINT(1) NOT NULL DEFAULT '0' COMMENT '状态：0无效、1正常、2删除',
	modified_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改时间',
	created_time INT(11) UNSIGNED NOT NULL COMMENT '创建时间',
	PRIMARY KEY(id),
	KEY(user_id)
) ENGINE = InnoDB DEFAULT CHARSET UTF8 COMMENT '用户收藏夹';


# 后台菜单表
DROP TABLE IF EXISTS ms_menu;
CREATE TABLE `ms_menu` (
  `menu_id` SMALLINT(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` CHAR(40) NOT NULL DEFAULT '',
  `parentid` SMALLINT(6) NOT NULL DEFAULT '0',
  `c` CHAR(50) NOT NULL DEFAULT '',
  `a` CHAR(50) NOT NULL DEFAULT '',
  `data` CHAR(255) NOT NULL DEFAULT '',
  `listorder` SMALLINT(6) UNSIGNED NOT NULL DEFAULT '0',
  `display` ENUM('1','0') NOT NULL DEFAULT '1',
  PRIMARY KEY (`menu_id`),
  KEY `listorder` (`listorder`),
  KEY `parentid` (`parentid`),
  KEY `module` (`c`,`a`)
) ENGINE=InnoDB DEFAULT CHARSET UTF8 COMMENT '后台菜单表';


INSERT INTO `ms_menu` VALUES ('1000', '常用功能', '0', '', '', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('1001', '常用功能', '1000', '', '', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('1002', '修改密码', '1001', 'Admin', 'editPwd', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('1003', '登录历史', '1001', 'Admin', 'loginHistory', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('1004', '管理后台首页', '1001', 'Index', 'Index', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('1005', '管理后台Ajax获取菜单', '1001', 'Index', 'leftMenu', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('1006', '管理后台右侧默认页', '1001', 'Index', 'right', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('1007', '管理后台面包屑', '1001', 'Index', 'arrow', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('1008', '文件上传', '1001', 'Index', 'upload', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('1009', '生成首页', '1001', 'Index', 'createHomePage', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('2000', '系统设置', '0', '', '', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('2001', '系统配置', '2000', '', '', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('2002', '字典管理', '2001', 'Dict', 'index', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('2003', '添加字典类型', '2001', 'Dict', 'addType', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('2004', '编辑字典类型', '2001', 'Dict', 'editType', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('2005', '删除字典类型', '2001', 'Dict', 'deleteType', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('2006', '字典列表', '2001', 'Dict', 'dict', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('2007', '删除字典', '2001', 'Dict', 'delete', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('2008', '添加字典', '2001', 'Dict', 'add', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('2009', '更新字典', '2001', 'Dict', 'edit', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('2010', '字典类型排序', '2001', 'Dict', 'sortType', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('2011', '字典排序', '2001', 'Dict', 'sortDict', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('2012', '字典缓存清除', '2001', 'Dict', 'ClearCache', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('2013', '配置管理', '2001', 'Config', 'index', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('2014', '添加配置', '2001', 'Config', 'add', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('2015', '编辑配置', '2001', 'Config', 'edit', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('2016', '删除配置', '2001', 'Config', 'delete', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('2017', '配置排序', '2001', 'Config', 'sort', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('2018', '配置缓存清除', '2001', 'Config', 'ClearCache', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('2019', '菜单列表', '2001', 'Menu', 'index', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('2020', '添加菜单', '2001', 'Menu', 'add', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('2021', '编辑菜单', '2001', 'Menu', 'edit', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('2022', '删除菜单', '2001', 'Menu', 'delete', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('2023', '菜单排序', '2001', 'Menu', 'sort', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('2200', '敏感词管理', '2000', 'Sensitive', '', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('2201', '敏感词列表', '2200', 'Sensitive', 'index', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('2202', '添加敏感词', '2200', 'Sensitive', 'add', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('2203', '更新敏感词', '2200', 'Sensitive', 'edit', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('2204', '敏感词删除', '2200', 'Sensitive', 'delete', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('2300', 'IP禁止', '2000', 'Ip', '', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('2301', '被禁IP列表', '2300', 'Ip', 'index', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('2302', '添加IP', '2300', 'Ip', 'add', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('2303', '删除IP', '2300', 'Ip', 'delete', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('2400', '省市区管理', '2000', 'District', '', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('2401', '添加省市区', '2400', 'District', 'add', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('2402', '编辑省市区', '2400', 'District', 'edit', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('2403', '删除省市区', '2400', 'District', 'delete', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('2404', '省市区排序', '2400', 'District', 'sort', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('2405', '省市区列表', '2400', 'District', 'index', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('2406', '创建省市区JSON文件', '2400', 'District', 'createJsonFile', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('2500', '日志管理', '2000', 'Log', '', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('2501', '日志查看', '2500', 'Log', 'index', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('2700', '文件管理', '2000', 'File', '', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('2701', '文件列表', '2700', 'File', 'index', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('2702', '更新文件', '2700', 'File', 'edit', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('2703', '添加文件', '2700', 'File', 'add', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('2704', '删除文件', '2700', 'File', 'delete', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('3000', '权限管理', '0', '', '', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('3001', '管理员管理', '3000', 'Admin', '', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('3002', '管理员列表', '3001', 'Admin', 'index', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('3003', '添加管理员', '3002', 'Admin', 'add', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('3004', '更新管理员', '3003', 'Admin', 'edit', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('3005', '删除管理员', '3004', 'Admin', 'delete', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('3100', '角色管理', '3000', 'Role', '', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('3101', '角色列表', '3100', 'Role', 'index', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('3102', '添加角色', '3100', 'Role', 'add', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('3103', '更新角色', '3100', 'Role', 'update', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('3104', '删除角色', '3100', 'Role', 'delete', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('3105', '角色赋权', '3100', 'Role', 'setPermission', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('4000', '内容管理', '0', '', '', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('4001', '分类管理', '4000', 'Category', '', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('4002', '分类列表', '4001', 'Category', 'index', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('4003', '添加分类', '4001', 'Category', 'add', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('4004', '更新分类', '4001', 'Category', 'edit', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('4005', '删除分类', '4001', 'Category', 'delete', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('4006', '分类排序', '4001', 'Category', 'sort', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('4100', '文章管理', '4000', 'News', '', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('4101', '文章列表', '4100', 'News', 'index', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('4102', '添加文章', '4100', 'News', 'add', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('4103', '更新文章', '4100', 'News', 'edit', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('4104', '删除文章', '4100', 'News', 'sort', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('4105', '文章排序', '4100', 'News', 'sort', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('4200', '友情链接', '4000', 'Link', '', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('4201', '友情链接列表', '4200', 'Link', 'index', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('4202', '添加友情链接', '4200', 'Link', 'add', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('4203', '更新友情链接', '4200', 'Link', 'edit', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('4204', '删除友情链接', '4200', 'Link', 'delete', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('4205', '友情链接排序', '4200', 'Link', 'sort', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('4300', '广告管理', '4000', 'Ad', '', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('4301', '广告位置列表', '4300', 'Ad', 'positionList', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('4302', '添加广告位置', '4300', 'Ad', 'positionAdd', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('4303', '更新广告位置', '4300', 'Ad', 'positionEdit', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('4304', '删除广告位置', '4300', 'Ad', 'positionDelete', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('4305', '广告列表', '4300', 'Ad', 'index', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('4306', '添加广告', '4300', 'Ad', 'add', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('4307', '更新广告', '4300', 'Ad', 'edit', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('4308', '删除广告', '4300', 'Ad', 'delete', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('4309', '广告排序', '4300', 'Ad', 'sort', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('5000', '用户管理', '0', '', '', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('5001', '用户管理', '5000', 'User', '', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('5002', '用户列表', '5001', 'User', 'index', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('5003', '添加用户', '5001', 'User', 'add', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('5004', '更新用户', '5001', 'User', 'edit', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('5005', '禁用用户', '5001', 'User', 'forbid', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('5006', '查看用户详情', '5001', 'User', 'view', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('5007', '解禁用户', '5001', 'User', 'unforbid', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('6000', '商城管理', '0', '', '', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('6001', '商城管理', '6000', 'Shop', '', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('6002', '商品列表', '6001', 'Goods', 'list', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('6003', '商品添加', '6001', 'Goods', 'add', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('6004', '商品编辑', '6001', 'Goods', 'edit', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('6005', '商品删除', '6001', 'Goods', 'delete', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('6006', '订单列表', '6001', 'Order', 'list', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('6007', '订单发货', '6001', 'Order', 'deliverGoods', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('6008', '订单调价', '6001', 'Order', 'editPrice', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('6009', '订单收货地址调整', '6001', 'Order', 'adjustAddress', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('6010', '订单运费调整', '6001', 'Order', 'adjustFreight', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('6011', '订单关闭', '6001', 'Order', 'close', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('6012', '订单删除', '6001', 'Order', 'delete', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('6013', '优惠券管理', '6001', 'Coupon', 'list', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('6014', '优惠券添加', '6001', 'Coupon', 'add', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('6015', '优惠券编辑', '6001', 'Coupon', 'edit', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('6016', '优惠券删除', '6001', 'Coupon', 'delete', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('6017', '优惠券发送记录', '6001', 'Coupon', 'history', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('6018', '评论列表', '6001', 'comment', 'list', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('6019', '评论隐藏', '6001', 'comment', 'delete', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('6020', '评论回复', '6001', 'comment', 'reply', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('6021', '运费模板管理', '6001', 'freight', 'list', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('6022', '运费模板添加', '6001', 'freight', 'add', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('6023', '运费模板编辑', '6001', 'freight', 'edit', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('6024', '运费模板删除', '6001', 'freight', 'delete', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('7000', '活动管理', '0', '', '', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('7001', '彩票活动', '7000', 'Lottery', '', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('7003', '活动列表', '7001', 'Lottery', 'list', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('7004', '添加彩票活动', '7001', 'Lottery', 'add', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('7005', '编辑彩票活动', '7001', 'Lottery', 'edit', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('7006', '删除彩票活动', '7001', 'Lottery', 'delete', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('7007', '彩票活动参与用户列表', '7001', 'Lottery', 'users', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('7008', '彩票开奖结果', '7001', 'Result', 'list', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('7009', '添加彩票开奖结果', '7001', 'Result', 'add', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('7010', '编辑彩票开奖结果', '7001', 'Result', 'edit', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('7011', '删除彩票开奖结果', '7001', 'Result', 'delete', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('7100', '抽奖活动', '7000', 'Lucky', '', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('7101', '奖品列表', '7100', 'Lucky', 'list', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('7102', '设置奖品', '7100', 'Lucky', 'set', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('7103', '抽奖记录', '7100', 'Lucky', 'record', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('7104', '抽奖记录删除', '7100', 'Lucky', 'deleteRecord', '', '0', '0');
INSERT INTO `ms_menu` VALUES ('7105', '发送奖品', '7100', 'Lucky', 'sendPrize', '', '0', '0');

INSERT INTO `ms_menu` VALUES ('8000', '消费明细', '0', '', '', '', '0', '1');

INSERT INTO `ms_menu` VALUES ('8001', '现金消费', '8000', 'Cash', '', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('8002', '支付记录', '8001', 'Cash', 'payLog', '', '0', '1');

INSERT INTO `ms_menu` VALUES ('8100', '金币消费', '8000', 'Cash', '', '', '0', '1');
INSERT INTO `ms_menu` VALUES ('8101', '金币记录', '8100', 'Cash', 'glodLog', '', '0', '1');


# 商城字典初始化。
INSERT INTO `ms_dict_type` (`dict_type_id`, `type_code`, `type_name`, `description`, `status`, `created_by`, `created_time`, `modified_by`, `modified_time`) VALUES ('30', 'order_operation_code', '订单操作编码', '订单操作编码：标识下单之后，买家或卖家对订单的操作。', '1', '1', unix_timestamp(now()), '0', '0');
INSERT INTO `ms_dict` (`dict_type_id`, `dict_code`, `dict_value`, `description`, `listorder`, `status`, `created_by`, `created_time`, `modified_by`, `modified_time`) VALUES ('30', 'payment', '订单支付', '订单支付', '0', '1', '1', unix_timestamp(now()), '0', '0');
INSERT INTO `ms_dict` (`dict_type_id`, `dict_code`, `dict_value`, `description`, `listorder`, `status`, `created_by`, `created_time`, `modified_by`, `modified_time`) VALUES ('30', 'shipped', '订单发货', '订单发货', '0', '1', '1', unix_timestamp(now()), '0', '0');
INSERT INTO `ms_dict` (`dict_type_id`, `dict_code`, `dict_value`, `description`, `listorder`, `status`, `created_by`, `created_time`, `modified_by`, `modified_time`) VALUES ('30', 'trade_successed', '交易成功', '交易成功或确认收货', '0', '1', '1', unix_timestamp(now()), '0', '0');
INSERT INTO `ms_dict` (`dict_type_id`, `dict_code`, `dict_value`, `description`, `listorder`, `status`, `created_by`, `created_time`, `modified_by`, `modified_time`) VALUES ('30', 'canceled', '买家订单取消', '订单取消。只能由买家操作才能变成这个状态。', '0', '1', '1', unix_timestamp(now()), '0', '0');
INSERT INTO `ms_dict` (`dict_type_id`, `dict_code`, `dict_value`, `description`, `listorder`, `status`, `created_by`, `created_time`, `modified_by`, `modified_time`) VALUES ('30', 'closed', '商家订单关闭', '订单关闭。只能由商家操作才能变成这个状态。', '0', '1', '1', unix_timestamp(now()), '0', '0');
INSERT INTO `ms_dict` (`dict_type_id`, `dict_code`, `dict_value`, `description`, `listorder`, `status`, `created_by`, `created_time`, `modified_by`, `modified_time`) VALUES ('30', 'edit_address', '商家编辑收货地址', '商家编辑收货地址。当下单用户填写了错误的收货地址之后，可以要求商家修改。', '0', '1', '1', unix_timestamp(now()), '0', '0');
INSERT INTO `ms_dict` (`dict_type_id`, `dict_code`, `dict_value`, `description`, `listorder`, `status`, `created_by`, `created_time`, `modified_by`, `modified_time`) VALUES ('30', 'edit_logistics', '商家修改物流信息', '商家修改物流信息', '0', '1', '1', unix_timestamp(now()), '0', '0');
INSERT INTO `ms_dict` (`dict_type_id`, `dict_code`, `dict_value`, `description`, `listorder`, `status`, `created_by`, `created_time`, `modified_by`, `modified_time`) VALUES ('30', 'deleted_order', '删除订单', '删除订单', '0', '1', '1', unix_timestamp(now()), '0', '0');

INSERT INTO `ms_dict_type` (`dict_type_id`, `type_code`, `type_name`, `description`, `status`, `created_by`, `created_time`, `modified_by`, `modified_time`) VALUES ('40', 'logistics_list', '常用快递', '常用快递', '1', '1', unix_timestamp(now()), '0', '0');
INSERT INTO `ms_dict` (`dict_type_id`, `dict_code`, `dict_value`, `description`, `listorder`, `status`, `created_by`, `created_time`, `modified_by`, `modified_time`) VALUES ('40', 'sf', '顺风速递', '顺风速递', '0', '1', '1', unix_timestamp(now()), '0', '0');
INSERT INTO `ms_dict` (`dict_type_id`, `dict_code`, `dict_value`, `description`, `listorder`, `status`, `created_by`, `created_time`, `modified_by`, `modified_time`) VALUES ('40', 'ems', '邮政EMS', '邮政EMS', '0', '1', '1', unix_timestamp(now()), '0', '0');
INSERT INTO `ms_dict` (`dict_type_id`, `dict_code`, `dict_value`, `description`, `listorder`, `status`, `created_by`, `created_time`, `modified_by`, `modified_time`) VALUES ('40', 'yt', '圆通速递', '圆通速递', '0', '1', '1', unix_timestamp(now()), '0', '0');
INSERT INTO `ms_dict` (`dict_type_id`, `dict_code`, `dict_value`, `description`, `listorder`, `status`, `created_by`, `created_time`, `modified_by`, `modified_time`) VALUES ('40', 'st', '申通速递', '申通速递', '0', '1', '1', unix_timestamp(now()), '0', '0');
INSERT INTO `ms_dict` (`dict_type_id`, `dict_code`, `dict_value`, `description`, `listorder`, `status`, `created_by`, `created_time`, `modified_by`, `modified_time`) VALUES ('40', 'zt', '中通快递', '中通快递', '0', '1', '1', unix_timestamp(now()), '0', '0');
INSERT INTO `ms_dict` (`dict_type_id`, `dict_code`, `dict_value`, `description`, `listorder`, `status`, `created_by`, `created_time`, `modified_by`, `modified_time`) VALUES ('40', 'tt', '天天快递', '天天快递', '0', '1', '1', unix_timestamp(now()), '0', '0');
INSERT INTO `ms_dict` (`dict_type_id`, `dict_code`, `dict_value`, `description`, `listorder`, `status`, `created_by`, `created_time`, `modified_by`, `modified_time`) VALUES ('40', 'yd', '韵达快递', '韵达快递', '0', '1', '1', unix_timestamp(now()), '0', '0');
INSERT INTO `ms_dict` (`dict_type_id`, `dict_code`, `dict_value`, `description`, `listorder`, `status`, `created_by`, `created_time`, `modified_by`, `modified_time`) VALUES ('40', 'htky', '百世快递', '百世快递', '0', '1', '1', unix_timestamp(now()), '0', '0');
INSERT INTO `ms_dict` (`dict_type_id`, `dict_code`, `dict_value`, `description`, `listorder`, `status`, `created_by`, `created_time`, `modified_by`, `modified_time`) VALUES ('40', 'qfkd', '全峰快递', '全峰快递', '0', '1', '1', unix_timestamp(now()), '0', '0');
INSERT INTO `ms_dict` (`dict_type_id`, `dict_code`, `dict_value`, `description`, `listorder`, `status`, `created_by`, `created_time`, `modified_by`, `modified_time`) VALUES ('40', 'dbwl', '德邦物流', '德邦物流', '0', '1', '1', unix_timestamp(now()), '0', '0');
INSERT INTO `ms_dict` (`dict_type_id`, `dict_code`, `dict_value`, `description`, `listorder`, `status`, `created_by`, `created_time`, `modified_by`, `modified_time`) VALUES ('40', 'rufengda', '如风达快递', '如风达快递', '0', '1', '1', unix_timestamp(now()), '0', '0');
INSERT INTO `ms_dict` (`dict_type_id`, `dict_code`, `dict_value`, `description`, `listorder`, `status`, `created_by`, `created_time`, `modified_by`, `modified_time`) VALUES ('40', 'zjs', '宅急送', '宅急送', '0', '1', '1', unix_timestamp(now()), '0', '0');

INSERT INTO `ms_config` (`ctitle`, `cname`, `cvalue`, `description`, `status`, `created_by`, `created_time`, `modified_by`, `modified_time`) VALUES ('用户收货地址最大数量', 'max_user_address_count', '20', '允许创建的用户地址最大数量值。', '1', '1', unix_timestamp(now()), '0', '0');


# 评价表
DROP TABLE IF EXISTS `mall_appraise`;
CREATE TABLE mall_appraise (
	aid INT(11) UNSIGNED AUTO_INCREMENT COMMENT '评价ID',
	order_id INT(11) UNSIGNED NOT NULL COMMENT '订单ID。对应 mall_order.order_id',
	user_id INT(11) UNSIGNED NOT NULL COMMENT '用户ID。对应 ms_user.user_id',
	score1 DOUBLE(8,2) NOT NULL COMMENT '宝贝描述相符评分',
	score2 DOUBLE(8,2) NOT NULL COMMENT '卖家服务态度评分',
	score3 DOUBLE(8,2) NOT NULL COMMENT '物流服务质量评分',
	client_ip INT(11) UNSIGNED NOT NULL COMMENT '用户IP十进制数字',
	created_time INT(11) UNSIGNED NOT NULL COMMENT '创建时间',
	PRIMARY KEY(aid),
	KEY(user_id),
	UNIQUE KEY(order_id)
)ENGINE = InnoDB DEFAULT CHARSET = 'UTF8' COMMENT '评价表';


# 评论表
DROP TABLE IF EXISTS `mall_comment`;
CREATE TABLE mall_comment (
	cid INT(11) UNSIGNED AUTO_INCREMENT COMMENT '评论ID',
	order_id INT(11) UNSIGNED NOT NULL COMMENT '订单ID。对应mall_order.order_id',
	sub_order_id INT(11) UNSIGNED NOT NULL COMMENT '订单ID。对应mall_order_item.sub_order_id',
	goods_id INT(11) UNSIGNED NOT NULL COMMENT '商品ID。对应mall_goods.goods_id',
	product_id INT(11) UNSIGNED NOT NULL COMMENT '货品ID。对应mall_product.product_id',
	evaluate_level TINYINT(1) NOT NULL COMMENT '商品好评等级：1好评、2中评、3差评',
	user_id INT(11) UNSIGNED NOT NULL COMMENT '用户ID。对应ms_user.user_id',
	content1 CHAR(200) NOT NULL COMMENT '主评',
	content1_time INT(11) UNSIGNED NOT NULL COMMENT '主评时间',
	reply1 CHAR(200) NOT NULL DEFAULT '' COMMENT '主评回复',
	reply1_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '主评回复时间',
	content2 CHAR(200) NOT NULL DEFAULT '' COMMENT '追评',
	content2_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '追评时间',
	reply2 CHAR(200) NOT NULL DEFAULT '' COMMENT '追评回复',
	reply2_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '追评回复时间',
	client_ip INT(11) UNSIGNED NOT NULL COMMENT '用户IP十进制数字',
	is_display TINYINT(1) NOT NULL DEFAULT '1' COMMENT '是否显示',
	PRIMARY KEY(cid),
	KEY(order_id),
	KEY(user_id),
	UNIQUE KEY(sub_order_id)
)ENGINE = InnoDB DEFAULT CHARSET = 'UTF8' COMMENT '买家评论表';


# 购物车表
DROP TABLE IF EXISTS mall_cart;
CREATE TABLE mall_cart(
	id INT(11) UNSIGNED AUTO_INCREMENT COMMENT '主键ID',
	user_id INT(11) UNSIGNED NOT NULL COMMENT '用户ID',
	goods_id INT(11) UNSIGNED NOT NULL COMMENT '商品ID',
	product_id INT(11) UNSIGNED NOT NULL COMMENT '货品ID',
	quantity INT(11) UNSIGNED NOT NULL COMMENT '购买数量',
	status TINYINT(1) NOT NULL COMMENT '状态：0无效、1正常、2删除',
	modified_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改时间戳',
	created_time INT(11) UNSIGNED NOT NULL COMMENT '创建时间戳',
	PRIMARY KEY(id),
	KEY(user_id, product_id)
) ENGINE = InnoDB DEFAULT CHARSET UTF8 COMMENT '用户购物车表';


# 商品规格值
# spec_val_json = [
# '颜色' => ['红色', '金色', '白银'],
# '尺寸' => ['35', '36', '38', '39']
# ];
DROP TABLE IF EXISTS mall_goods;
CREATE TABLE mall_goods(
	goods_id INT(11) UNSIGNED AUTO_INCREMENT COMMENT '商品ID',
	goods_name VARCHAR(100) NOT NULL COMMENT '商品名称',
	cat_code VARCHAR(50) NOT NULL COMMENT '商品分类编码。对应ms_category.cat_code',
	slogan VARCHAR(50) NOT NULL DEFAULT '' COMMENT '广告语、标识',
	min_market_price DOUBLE(8,2) NOT NULL DEFAULT '0.00' COMMENT '商品最低市场价格',
	max_market_price DOUBLE(8,2) NOT NULL DEFAULT '0.00' COMMENT '商品最高市场价格',
	min_price DOUBLE(8,2) NOT NULL DEFAULT '0.00' COMMENT '商品最低销售价格',
	max_price DOUBLE(8,2) NOT NULL DEFAULT '0.00' COMMENT '商品最高销售价格',
	is_exchange TINYINT(1) NOT NULL DEFAULT '0' COMMENT '是否允许金币兑换',
	goods_img VARCHAR(100) NOT NULL DEFAULT '' COMMENT '商品图片',
	weight INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '重量。单位(g)',
	buy_count INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '购买次数',
	month_buy_count INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '近30天购买次数',
	listorder SMALLINT(5) NOT NULL DEFAULT '0' COMMENT '排序值。小到大排列。',
	marketable TINYINT(1) NOT NULL COMMENT '上下架状态：1上架、0下架',
	marketable_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '上下架时间',
	freight_tpl_id INT(11) UNSIGNED NOT NULL COMMENT '运费模板ID。0表示卖家包邮。',
	status TINYINT(1) NOT NULL COMMENT '商品状态：0无效、1正常、2删除',
	spec_val_json VARCHAR(1000) NOT NULL DEFAULT '' COMMENT '商品规格。json格式。',
	limit_count SMALLINT(10) NOT NULL DEFAULT '0' COMMENT '限购数量。0不限购。',
	description TEXT NOT NULL COMMENT '商品详情',
	modified_by INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改人',
	modified_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改时间戳',
	created_time INT(11) UNSIGNED NOT NULL COMMENT '创建时间戳',
	created_by INT(11) UNSIGNED NOT NULL COMMENT '创建人',
	PRIMARY KEY(goods_id),
	KEY(cat_code)
) ENGINE = InnoDB DEFAULT CHARSET UTF8 COMMENT '商品表';


# 货品表
DROP TABLE IF EXISTS mall_product;
CREATE TABLE mall_product(
	product_id INT(11) UNSIGNED AUTO_INCREMENT COMMENT '货品ID',
	goods_id INT(11) UNSIGNED NOT NULL COMMENT '商品ID',
	market_price DOUBLE(8,2) NOT NULL COMMENT '市场价格',
	sales_price DOUBLE(8,2) NOT NULL COMMENT '销售价格',
	stock INT(11) UNSIGNED NOT NULL COMMENT '货品库存',
	spec_val VARCHAR(100) NOT NULL DEFAULT '' COMMENT '规格值：颜色:红色|尺寸:35',
	status TINYINT(1) NOT NULL COMMENT '商品状态：0无效、1正常、2删除',
	modified_by INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改人',
	modified_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改时间戳',
	created_time INT(11) UNSIGNED NOT NULL COMMENT '创建时间戳',
	created_by INT(11) UNSIGNED NOT NULL COMMENT '创建人',
	PRIMARY KEY(product_id),
	KEY(goods_id)
) ENGINE = InnoDB DEFAULT CHARSET UTF8 COMMENT '货品表';


# 运费表
DROP TABLE IF EXISTS mall_freight_tpl;
CREATE TABLE mall_freight_tpl(
	tpl_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '运费模板ID',
	freight_name CHAR(20) NOT NULL COMMENT '运费模板名称',
	send_time SMALLINT(5) UNSIGNED NOT NULL DEFAULT '12' COMMENT '发货时间。单位(小时)。0代表立即发货。',
	bear_freight TINYINT(1) NOT NULL COMMENT '运费承担：1卖家包邮、2买家承担运费（通过规则运费可能为0）',
	freight_type TINYINT(1) NOT NULL DEFAULT '1' COMMENT '计费类型：1计件、2计重',
	base_step INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '基础计费步长',
	base_freight INT(11) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '基础步长运费。可以设置为0。',
	rate_step INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '计费步长。设置为0代表取消按步长记费',
	step_freight INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '每步长计费多少钱。设置为0代表不计费',
	no_area VARCHAR(1000) NOT NULL DEFAULT '' COMMENT '不配送区域。格式：1111,222,333。一般不配送区域是只港澳台西藏内蒙古新疆。最多只允许设置100个。',
	baoyou_fee INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '商品满多少元包邮。设置为0取消此条件。',
	status TINYINT(1) NOT NULL COMMENT '状态：0无效、1正常、2删除',
	modified_by INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改人用户ID',
	modified_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改时间戳',
	created_time INT(11) UNSIGNED NOT NULL COMMENT '创建时间戳',
	created_by INT(11) UNSIGNED NOT NULL COMMENT '创建人用户ID',
	PRIMARY KEY(tpl_id)
) ENGINE = InnoDB DEFAULT CHARSET UTF8 COMMENT '商品运费模板';


# 相册图片最多允许5张。
DROP TABLE IF EXISTS mall_goods_image;
CREATE TABLE mall_goods_image(
	image_id INT(11) UNSIGNED AUTO_INCREMENT COMMENT '主键ID',
	goods_id INT(11) UNSIGNED NOT NULL COMMENT '商品ID',
	image_url VARCHAR(100) NOT NULL COMMENT '图片URL',
	status TINYINT(1) NOT NULL COMMENT '状态：0无效、1正常、2删除',
	modified_by INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改人',
	modified_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改时间戳',
	created_time INT(11) UNSIGNED NOT NULL COMMENT '创建时间戳',
	created_by INT(11) UNSIGNED NOT NULL COMMENT '创建人',
	PRIMARY KEY(image_id),
	KEY(goods_id)
) ENGINE = InnoDB DEFAULT CHARSET UTF8 COMMENT '商品相册表';


# 最多20个收货地址
DROP TABLE IF EXISTS mall_user_address;
CREATE TABLE mall_user_address(
	address_id INT(11) UNSIGNED AUTO_INCREMENT COMMENT '地址ID',
	user_id INT(11) UNSIGNED NOT NULL COMMENT '用户ID',
	realname CHAR(10) NOT NULL COMMENT '收货人姓名',
	zipcode CHAR(6) DEFAULT NULL COMMENT '收货人邮编',
	mobilephone CHAR(11) DEFAULT NULL COMMENT '收货人手机',
	district_id INT(11) UNSIGNED NOT NULL COMMENT '地区id,ms_district.district_id',
	address CHAR(50) NOT NULL COMMENT '收货人地址。除省市区街道后的部分',
	is_default TINYINT(1) NOT NULL DEFAULT '0' COMMENT '是否默认收货地址：0否、1是',
	status TINYINT(1) NOT NULL COMMENT '状态：0无效、1正常、2删除',
	modified_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改时间戳',
	created_time INT(11) UNSIGNED NOT NULL COMMENT '创建时间戳',
	PRIMARY KEY(address_id),
	KEY(user_id),
	KEY(mobilephone),
	KEY(district_id)
) ENGINE = InnoDB DEFAULT CHARSET UTF8 COMMENT '用户收货地址表';


# 订单表
DROP TABLE IF EXISTS mall_order;
CREATE TABLE mall_order(
	order_id INT(11) UNSIGNED AUTO_INCREMENT COMMENT '订单ID',
	order_sn CHAR(50) NOT NULL COMMENT '订单号',
	user_id INT(11) UNSIGNED NOT NULL COMMENT '用户ID。对应ms_user.user_id',
	total_price DOUBLE(8,2) NOT NULL COMMENT '订单实付金额',
	payment_type TINYINT(1) NOT NULL COMMENT '支付类型。1RMB、2金币。',
	payment_price DOUBLE(8,2) NOT NULL COMMENT '订单实付金额',
	pay_status TINYINT(1) NOT NULL DEFAULT '0' COMMENT '支付状态：0未支付、1已支付',
	pay_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '支付时间戳',
	order_status SMALLINT(3) NOT NULL DEFAULT '0' COMMENT '订单状态：0待付款、1已付款、2已发货、3交易成功、4交易关闭、5交易取消',
	shipping_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '发货时间戳',
	done_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '交易成功时间戳',
	closed_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '交易关闭时间戳',
	cancel_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '交易取消时间戳',
	need_invoice TINYINT(1) UNSIGNED NOT NULL COMMENT '是否需要发票：0不需要、1需要',
	invoice_type TINYINT(1) UNSIGNED NOT NULL COMMENT '发票类型：1个人、2公司',
	invoice_name CHAR(50) NOT NULL DEFAULT '' COMMENT '发票抬头',
	receiver_name CHAR(20) NOT NULL COMMENT '收货人姓名',
	receiver_province CHAR(20) DEFAULT NULL COMMENT '收货人省，存中文',
	receiver_city CHAR(20) DEFAULT NULL COMMENT '收货人市，存中文',
	receiver_district CHAR(20) DEFAULT NULL COMMENT '收货人区，存中文',
	receiver_street CHAR(20) DEFAULT NULL COMMENT '收货人街道，存中文',
	receiver_address CHAR(100) NOT NULL COMMENT '收货人地址',
	receiver_zip CHAR(6) DEFAULT NULL COMMENT '收货人邮编',
	receiver_mobile CHAR(11) DEFAULT NULL COMMENT '收货人手机',
	buyer_message CHAR(50) DEFAULT NULL COMMENT '买家留言，给卖家看的',
	freight_price DOUBLE(8,2) NOT NULL DEFAULT '0.00' COMMENT '运费',
	gold_pay INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '积分兑换花费数量',
	user_coupon_id INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '优惠券ID',
	user_coupon_money INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '优惠券减免的金额',
	comment_status TINYINT(1) NOT NULL DEFAULT '0' COMMENT '评论状态：0未评论、1已评论',
	reply_status TINYINT(1) NOT NULL DEFAULT '0' COMMENT '回复状态：0未回复、1已回复',
	refund_status TINYINT(1) NOT NULL DEFAULT '0' COMMENT '退款状态：0未退款、1部分退款中、2整单退款中、3卖家拒绝退款、4买家取消退款',
	status TINYINT(1) NOT NULL COMMENT '状态：0无效、1正常、2删除',
	modified_by INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改人',
	modified_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改时间戳',
	created_time INT(11) UNSIGNED NOT NULL COMMENT '下单时间戳',
	created_by INT(11) UNSIGNED NOT NULL COMMENT '创建人',
	PRIMARY KEY(order_id),
	UNIQUE KEY(order_sn),
	KEY(user_id, order_status)
) ENGINE = InnoDB DEFAULT CHARSET UTF8 COMMENT '订单主表';


# 订单明细表
DROP TABLE IF EXISTS mall_order_item;
CREATE TABLE mall_order_item(
	sub_order_id INT(11) UNSIGNED AUTO_INCREMENT COMMENT '子订单ID',
	order_id INT(11) UNSIGNED NOT NULL COMMENT '主订单ID',
	goods_id INT(11) UNSIGNED NOT NULL COMMENT '商品ID',
	goods_name CHAR(100) NOT NULL COMMENT '商品名称',
	goods_image CHAR(80) NOT NULL COMMENT '商品图片',
	product_id INT(11) UNSIGNED NOT NULL COMMENT '货品ID',
	spec_val CHAR(100) NOT NULL DEFAULT '' COMMENT '规格值',
	market_price DOUBLE(8,2) NOT NULL COMMENT '市场价',
	sales_price DOUBLE(8,2) NOT NULL COMMENT '销售价',
	is_edit_price TINYINT(1) NOT NULL DEFAULT '0' COMMENT '是否改价。1是、0否。',
	old_price DOUBLE(8,2) NOT NULL DEFAULT '0.00' COMMENT '改价前的价格',
	quantity SMALLINT(3) UNSIGNED NOT NULL COMMENT '购买数量',
	payment_price DOUBLE(8,2) NOT NULL COMMENT '实付金额=销售价*购买数量',
	total_price DOUBLE(8,2) NOT NULL COMMENT '商品总额=市场价*购买数量',
	comment_status TINYINT(1) NOT NULL DEFAULT '0' COMMENT '评论状态：0未评论、1已初评、2已追评',
	reply_status TINYINT(1) NOT NULL DEFAULT '0' COMMENT '回复状态：0未回复、1已回复、2已追加回复',
	refund_status TINYINT(1) NOT NULL DEFAULT '0' COMMENT '退款状态：0未退款、1退款中、2卖家拒绝退款、3买家取消退款',
	modified_by INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改人',
	modified_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改时间戳',
	created_time INT(11) UNSIGNED NOT NULL COMMENT '创建时间戳',
	created_by INT(11) UNSIGNED NOT NULL COMMENT '创建人',
	PRIMARY KEY(sub_order_id),
	KEY(order_id)
) ENGINE = InnoDB DEFAULT CHARSET UTF8 COMMENT '订单明细表';


# 订单操作日志表
DROP TABLE IF EXISTS mall_order_log;
CREATE TABLE mall_order_log(
	log_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
	order_id INT(11) UNSIGNED NOT NULL COMMENT '主订单ID',
	action_type VARCHAR(20) NOT NULL COMMENT '操作类型：canceled取消、closed关闭、edit_address修改收货地址、edit_logistics修改物流信息、trade_successed交易成功、shipped已发货、payment支付',
	log_content VARCHAR(1000) NOT NULL DEFAULT '' COMMENT '操作内容。如果是修改地址要把新旧地址放里面。',
	user_id INT(11) UNSIGNED NOT NULL COMMENT '操作人',
	created_time INT(11) UNSIGNED NOT NULL COMMENT '创建时间戳',
	PRIMARY KEY(log_id),
	KEY(order_id)
) ENGINE = InnoDB DEFAULT CHARSET UTF8 COMMENT '订单操作日志表';


# 物流单号在未确认收货之前均可修改。
DROP TABLE IF EXISTS mall_logistics;
CREATE TABLE mall_logistics(
	id INT(11) UNSIGNED AUTO_INCREMENT COMMENT '主键ID',
	order_id INT(11) UNSIGNED NOT NULL COMMENT '订单ID',
	logistics_code VARCHAR(20) NOT NULL DEFAULT '' COMMENT '物流编码',
	logistics_number VARCHAR(50) NOT NULL DEFAULT '' COMMENT '物流单号',
	modified_by INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改人',
	modified_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改时间戳',
	created_time INT(11) UNSIGNED NOT NULL COMMENT '创建时间戳',
	created_by INT(11) UNSIGNED NOT NULL COMMENT '创建人',
	PRIMARY KEY(id)
) ENGINE = InnoDB DEFAULT CHARSET UTF8 COMMENT '订单物流信息表';


# 支付记录表
DROP TABLE IF EXISTS mall_payment_log;
CREATE TABLE mall_payment_log(
	payment_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
	user_id INT(11) UNSIGNED NOT NULL COMMENT '用户ID',
	payment_code VARCHAR(20) NOT NULL COMMENT '支付类型编码。对应ms_payment_cfg.payment_code',
	order_id INT(11) UNSIGNED NOT NULL COMMENT '主订单ID',
	serial_number VARCHAR(50) NOT NULL COMMENT '支付流水号',
	amount DOUBLE(8,2) NOT NULL COMMENT '支付金额',
	created_time INT(11) UNSIGNED NOT NULL COMMENT '创建时间戳',
	PRIMARY KEY(payment_id),
	KEY(order_id)
) ENGINE = InnoDB DEFAULT CHARSET UTF8 COMMENT '支付记录表';


# 支付配置表
DROP TABLE IF EXISTS mall_payment_cfg;
CREATE TABLE mall_payment_cfg(
	cfg_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
	payment_code VARCHAR(20) NOT NULL COMMENT '支付类型编码。',
	modified_by INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改人',
	modified_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改时间戳',
	created_time INT(11) UNSIGNED NOT NULL COMMENT '创建时间戳',
	created_by INT(11) UNSIGNED NOT NULL COMMENT '创建人',
	PRIMARY KEY(cfg_id)
) ENGINE = InnoDB DEFAULT CHARSET UTF8 COMMENT '支付配置表';


# 优惠券表
DROP TABLE IF EXISTS mall_coupon;
CREATE TABLE mall_coupon(
	coupon_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '优惠券ID',
	coupon_name VARCHAR(20) NOT NULL COMMENT '优惠券名称',
	money INT(11) UNSIGNED NOT NULL COMMENT '优惠券金额',
	order_money INT(11) UNSIGNED NOT NULL COMMENT '订单金额多少可用',
	get_start_time INT(11) UNSIGNED NOT NULL COMMENT '领取开始时间',
	get_end_time INT(11) UNSIGNED NOT NULL COMMENT '领取截止时间',
	limit_quantity SMALLINT(3) UNSIGNED NOT NULL COMMENT '每人限领优惠券数量',
	expiry_date INT(11) UNSIGNED NOT NULL COMMENT '使用有效期截止',
	status TINYINT(1) NOT NULL COMMENT '状态：0无效、1正常、2删除',
	modified_by INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改人',
	modified_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改时间戳',
	created_time INT(11) UNSIGNED NOT NULL COMMENT '创建时间戳',
	created_by INT(11) UNSIGNED NOT NULL COMMENT '创建人',
	PRIMARY KEY(coupon_id)
) ENGINE = InnoDB DEFAULT CHARSET UTF8 COMMENT '优惠券表';


# 用户优惠券表
DROP TABLE IF EXISTS mall_user_coupon;
CREATE TABLE mall_user_coupon(
	id INT(11) UNSIGNED AUTO_INCREMENT COMMENT '主键ID',
	coupon_id INT(11) UNSIGNED NOT NULL COMMENT '优惠券ID',
	user_id INT(11) UNSIGNED NOT NULL COMMENT '用户ID',
	is_use TINYINT(1) NOT NULL COMMENT '是否使用',
	use_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '使用时间',
	modified_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改时间戳',
	created_time INT(11) UNSIGNED NOT NULL COMMENT '创建时间戳',
	PRIMARY KEY(id),
	KEY(user_id),
	KEY(coupon_id)
) ENGINE = InnoDB DEFAULT CHARSET UTF8 COMMENT '用户优惠券表';


# 系统 SESSION 表
DROP TABLE IF EXISTS ms_session;
CREATE TABLE ms_session (
  session_id varchar(100) NOT NULL COMMENT 'php session_id',
  session_expire int(11) UNSIGNED NOT NULL COMMENT 'session到期时间',
  session_data blob,
  UNIQUE KEY `session_id` (`session_id`)
)ENGINE = MyISAM DEFAULT CHARSET=utf8 COMMENT 'session表';

# 系统缓存表
DROP TABLE IF EXISTS ms_cache;
CREATE TABLE ms_cache (
  cache_key varchar(100) NOT NULL COMMENT '缓存key',
  cache_expire int(11) UNSIGNED NOT NULL COMMENT '缓存到期时间',
  cache_data blob,
  UNIQUE KEY `cache_key` (`cache_key`)
)ENGINE = MyISAM DEFAULT CHARSET=utf8 COMMENT '缓存表';

# --------------- 游戏相关 start ------------#

### 初始化游戏模型需要的字典数据
INSERT INTO `ms_dict_type` (`dict_type_id`, `type_code`, `type_name`, `description`, `status`, `created_by`, `created_time`, `modified_by`, `modified_time`) 
VALUES ('20', 'game_gold_consume_code', '游戏金币消费编码', '游戏金币消费编码：通过此编码可以知道金币是在何种情况下消费。比如：add_ssq_reward 代表双色球中奖增加。', '1', '1', unix_timestamp(now()), '0', '0');

INSERT INTO `ms_dict` (`dict_type_id`, `dict_code`, `dict_value`, `description`, `listorder`, `status`, `created_by`, `created_time`, `modified_by`, `modified_time`) VALUES ('20', 'add_ssq_reward', '双色球中奖', '双色球中奖', '0', '1', '1', unix_timestamp(now()), '0', '0');
INSERT INTO `ms_dict` (`dict_type_id`, `dict_code`, `dict_value`, `description`, `listorder`, `status`, `created_by`, `created_time`, `modified_by`, `modified_time`) VALUES ('20', 'add_dlt_reward', '大乐透中奖', '大乐透中奖', '0', '1', '1', unix_timestamp(now()), '0', '0');
INSERT INTO `ms_dict` (`dict_type_id`, `dict_code`, `dict_value`, `description`, `listorder`, `status`, `created_by`, `created_time`, `modified_by`, `modified_time`) VALUES ('20', 'cut_ssq_bet', '双色球投注', '双色球投注', '0', '1', '1', unix_timestamp(now()), '0', '0');
INSERT INTO `ms_dict` (`dict_type_id`, `dict_code`, `dict_value`, `description`, `listorder`, `status`, `created_by`, `created_time`, `modified_by`, `modified_time`) VALUES ('20', 'cut_dlt_bet', '大乐透投注', '大乐透投注', '0', '1', '1', unix_timestamp(now()), '0', '0');
INSERT INTO `ms_dict` (`dict_type_id`, `dict_code`, `dict_value`, `description`, `listorder`, `status`, `created_by`, `created_time`, `modified_by`, `modified_time`) VALUES ('20', 'add_checkin', '每日签到', '每日签到', '0', '1', '1', unix_timestamp(now()), '0', '0');
INSERT INTO `ms_dict` (`dict_type_id`, `dict_code`, `dict_value`, `description`, `listorder`, `status`, `created_by`, `created_time`, `modified_by`, `modified_time`) VALUES ('20', 'add_buy_goods', '购买商品赠送', '购买商品赠送', '0', '1', '1', unix_timestamp(now()), '0', '0');


INSERT INTO `ms_config` (`ctitle`, `cname`, `cvalue`, `description`, `status`, `created_by`, `created_time`, `modified_by`, `modified_time`) VALUES ('金币与人民币兑换率', 'gold_ratio', '1000', '1元人民币兑换多少金币', '1', '1', unix_timestamp(now()), '0', '0');

# 玩家金币表
DROP TABLE IF EXISTS `gm_gold`;
CREATE TABLE gm_gold(
	id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
	user_id INT(11) UNSIGNED NOT NULL COMMENT '玩家ID。对应ms_user.user_id',
	gold INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '乐豆数量。包含未用完的赠送的乐豆。',
	v INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '记录版本号,处理并发修改问题',
	modified_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改时间戳',
	created_time INT(11) UNSIGNED NOT NULL COMMENT '创建时间戳',
	PRIMARY KEY(id),
	KEY(user_id)
) ENGINE = InnoDB DEFAULT CHARSET UTF8 COMMENT '玩家乐豆表';


# 玩家金币消费记录表
DROP TABLE IF EXISTS `gm_gold_consume`;
CREATE TABLE gm_gold_consume(
	id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
	user_id INT(11) UNSIGNED NOT NULL COMMENT '玩家ID。对应ms_user.user_id',
	consume_type TINYINT(1) NOT NULL COMMENT '消费类型：1增加、2扣减',
	consume_code CHAR(20) NOT NULL COMMENT '类型编码。通过编码可以知晓是因何产生的。编码通过字典配置。',
	gold INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '影响的乐豆数量',
	created_time INT(11) UNSIGNED NOT NULL COMMENT '创建时间戳',
	PRIMARY KEY(id),
	KEY(user_id)
) ENGINE = InnoDB DEFAULT CHARSET UTF8 COMMENT '乐豆消费记录';


# 用户投注记录表
DROP TABLE IF EXISTS `gm_bet_record`;
CREATE TABLE gm_bet_record(
	bet_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
	user_id INT(11) UNSIGNED NOT NULL COMMENT '玩家ID。对应ms_user.user_id',
	game_code INT(11) UNSIGNED NOT NULL COMMENT '游戏编码',
	bet_gold INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '投注的金币数量',
	bet_status TINYINT(1) NOT NULL COMMENT '中奖状态：0待开奖、1已中奖、2未中奖',
	reward_gold INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '中奖金币',
	modified_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改时间戳',
	created_time INT(11) UNSIGNED NOT NULL COMMENT '投注时间戳',
	PRIMARY KEY(bet_id),
	KEY(user_id)
) ENGINE = InnoDB DEFAULT CHARSET UTF8 COMMENT '投注记录';


# 用户投注号码记录表
DROP TABLE IF EXISTS `gm_bet_record_number`;
CREATE TABLE gm_bet_record_number(
	id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
	bet_id INT(11) UNSIGNED NOT NULL COMMENT '投注记录ID。对应ms_bet_record.bet_id',
	bet_gold INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '投注的金币数量',
	bet_number CHAR(100) NOT NULL COMMENT '投注号码',
	bet_status TINYINT(1) NOT NULL COMMENT '中奖状态：0待开奖、1已中奖、2未中奖',
	bet_level SMALLINT(3) NOT NULL DEFAULT '0' COMMENT '中奖等级。有些游戏是没有等级的。默认就是0。根据游戏特点选择是否使用此字段。',
	reward_gold INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '中奖金币',
	modified_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改时间戳',
	created_time INT(11) UNSIGNED NOT NULL COMMENT '投注时间戳',
	PRIMARY KEY(id),
	KEY(bet_id)
) ENGINE = InnoDB DEFAULT CHARSET UTF8 COMMENT '投注号码记录表';



# 彩票活动表
DROP TABLE IF EXISTS `gm_lottery_activity`;
CREATE TABLE gm_lottery_activity(
	aid INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '活动ID',
	title CHAR(20) NOT NULL COMMENT '活动名称',
	lottery_type TINYINT(1) NOT NULL COMMENT '彩票类型：1双色球、2大乐透',
	bet_number CHAR(100) NOT NULL COMMENT '投注号码(复式)',
	bet_money INT(11) UNSIGNED NOT NULL COMMENT '投注金额',
	bet_count INT(11) UNSIGNED NOT NULL COMMENT '投注数量',
	person_limit INT(11) UNSIGNED NOT NULL COMMENT '人数限制(参与该活动的最大人数)',
	open_apply_time INT(11) UNSIGNED NOT NULL COMMENT '开放参与时间',
	start_time INT(11) UNSIGNED NOT NULL COMMENT '彩票活动开始时间。从这个时间开始计算彩票活动的中奖资金',
	end_time INT(11) UNSIGNED NOT NULL COMMENT '彩票活动结束时间。从这个时间结束计算彩票活动的中奖资金',
	prize_money INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '中奖总额。这个总额每开一次会算总和，直到活动结束',
	apply_count SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0' COMMENT '参与人数',
	display TINYINT(1) NOT NULL DEFAULT '0' COMMENT '是否显示：1是、0否',
	status TINYINT(1) NOT NULL COMMENT '状态：0无效、1正常、2删除',
	modified_by INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改人',
	modified_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改时间戳',
	created_by INT(11) UNSIGNED NOT NULL COMMENT '创建人',
	created_time INT(11) UNSIGNED NOT NULL COMMENT '创建时间戳',
	PRIMARY KEY(aid)
) ENGINE = InnoDB DEFAULT CHARSET UTF8 COMMENT '彩票活动表';


# 彩票活动参与记录表
DROP TABLE IF EXISTS `gm_lottery_user`;
CREATE TABLE gm_lottery_user(
	id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
	aid INT(11) UNSIGNED NOT NULL COMMENT '活动ID',
	user_id INT(11) UNSIGNED NOT NULL COMMENT '用户ID',
	prize_money INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '分得奖金。单位(分)。活动结束才计入这个值。',
	created_time INT(11) UNSIGNED NOT NULL COMMENT '创建时间戳',
	PRIMARY KEY(id),
	UNIQUE KEY `user_activity_index`(`aid`, `user_id`)
) ENGINE = InnoDB DEFAULT CHARSET UTF8 COMMENT '彩票活动中奖记录表';


# 彩票活动中奖记录表
DROP TABLE IF EXISTS `gm_lottery_prize`;
CREATE TABLE gm_lottery_prize(
	id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
	aid INT(11) UNSIGNED NOT NULL COMMENT '活动ID',
	phase_sn CHAR(10) NOT NULL COMMENT '彩票期次',
	bet_number CHAR(100) NOT NULL COMMENT '投注号码(复式)',
	lottery_result CHAR(20) NOT NULL COMMENT '彩票开奖号码',
	prize_level SMALLINT(3) NOT NULL COMMENT '中奖等级:0(未中奖)、1(一等奖)、2(二等奖)、3(三等奖)、4(四等奖)、5(五等奖)、6(六等奖)',
	prize_money INT(11) UNSIGNED NOT NULL COMMENT '中奖金额(税前)',
	prize_money_at INT(11) UNSIGNED NOT NULL COMMENT '中奖金额(税后)',
	created_time INT(11) UNSIGNED NOT NULL COMMENT '创建时间戳',
	PRIMARY KEY(id)
) ENGINE = InnoDB DEFAULT CHARSET UTF8 COMMENT '彩票活动中奖记录表';


# 彩票开奖结果表
DROP TABLE IF EXISTS `gm_lottery_result`;
CREATE TABLE gm_lottery_result(
	id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '活动ID',
	lottery_type CHAR(5) NOT NULL COMMENT '彩票类型:1-双色球、2-dlt',
	phase_sn CHAR(10) NOT NULL COMMENT '彩票期次',
	lottery_result CHAR(20) NOT NULL COMMENT '彩票开奖号码',
	first_prize INT(11) UNSIGNED NOT NULL COMMENT '一等奖金额',
	second_prize INT(11) UNSIGNED NOT NULL COMMENT '二等奖金额',
	first_prize_count INT(11) UNSIGNED NOT NULL COMMENT '一等奖中奖注数',
	second_prize_count INT(11) UNSIGNED NOT NULL COMMENT '二等奖中奖注数',
	third_prize_count INT(11) UNSIGNED NOT NULL COMMENT '三等奖中奖注数',
	fourth_prize_count INT(11) UNSIGNED NOT NULL COMMENT '四等奖中奖注数',
	fifth_prize_count INT(11) UNSIGNED NOT NULL COMMENT '五等奖中奖注数',
	sixth_prize_count INT(11) UNSIGNED NOT NULL COMMENT '六等奖中奖注数',
	lottery_time INT(11) UNSIGNED NOT NULL COMMENT '开奖时间',
	status TINYINT(1) NOT NULL COMMENT '状态：0无效、1正常、2删除',
	modified_by INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改人',
	modified_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改时间戳',
	created_by INT(11) UNSIGNED NOT NULL COMMENT '创建人',	
	created_time INT(11) UNSIGNED NOT NULL COMMENT '创建时间戳',
	PRIMARY KEY(id)
) ENGINE = InnoDB DEFAULT CHARSET UTF8 COMMENT '彩票开奖结果表';


# 抽奖奖励表
DROP TABLE IF EXISTS `gm_lucky_goods`;
CREATE TABLE gm_lucky_goods(
	id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '活动ID',
	goods_name CHAR(50) NOT NULL COMMENT '商品名称',
	day_max SMALLINT(5) NOT NULL DEFAULT '0' COMMENT '每天中奖最大次数。0代表不限制',
	min_range INT(11) NOT NULL COMMENT '随机数最小值',
	max_range INT(11) NOT NULL COMMENT '随机数最大值',
	goods_type CHAR(10) NOT NULL COMMENT '商品类型:jb-金币、qb-Q币、hf-话费、sw-实物、no-未中奖',
	image_url CHAR(100) NOT NULL COMMENT '奖品图片',
	created_by INT(11) UNSIGNED NOT NULL COMMENT '创建人',	
	created_time INT(11) UNSIGNED NOT NULL COMMENT '创建时间戳',
	PRIMARY KEY(id)
) ENGINE = InnoDB DEFAULT CHARSET UTF8 COMMENT '抽奖奖励表';


# 抽奖中奖记录表
DROP TABLE IF EXISTS `gm_lucky_prize`;
CREATE TABLE gm_lucky_prize(
	id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '活动ID',
	user_id INT(11) UNSIGNED NOT NULL COMMENT '用户ID',
	goods_name CHAR(50) NOT NULL COMMENT '商品名称',
	goods_type CHAR(10) NOT NULL COMMENT '商品类型:jb-金币、qb-Q币、hf-话费、sw-实物、no-未中奖',
	is_send TINYINT(1) NOT NULL DEFAULT '0' COMMENT '是否已经发放奖励：0否、1是',
	send_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '奖励发送时间',
	range_val INT(11) UNSIGNED NOT NULL COMMENT '随机到的值',
	get_info CHAR(255) NOT NULL DEFAULT '' COMMENT 'QQ号/手机号码/收货地址信息', 
	send_info CHAR(255) NOT NULL DEFAULT '' COMMENT '奖励发送信息',
	status TINYINT(1) NOT NULL COMMENT '状态：0无效、1正常、2删除',
	modified_by INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改人',
	modified_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改时间戳',
	created_time INT(11) UNSIGNED NOT NULL COMMENT '中奖时间戳',
	PRIMARY KEY(id),
	KEY(user_id),
	KEY(goods_type)
) ENGINE = InnoDB DEFAULT CHARSET UTF8 COMMENT '抽奖中奖记录表';

# --------------- 游戏相关 end   ------------#