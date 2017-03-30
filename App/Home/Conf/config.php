<?php
return array(
	'URL_MODEL'=>2,
	'TMPL_TEMPLATE_SUFFIX'  =>  '.php',
	'URL_CASE_INSENSITIVE'=>false,
	'TMPL_ENGINE_TYPE'=>'PHP',
	'LANG_SWITCH_ON' => true,   // 开启语言包功能
	'LANG_AUTO_DETECT' => true, // 自动侦测语言 开启多语言功能后有效
	'LANG_LIST'        => 'zh-cn', // 允许切换的语言列表 用逗号分隔
	'URL_HTML_SUFFIX'       =>  '',  // URL伪静态后缀设置
	'VAR_LANGUAGE'     => 'l', // 默认语言切换变量
	'DB_TYPE'               =>  'mysql',     // 数据库类型
	'DB_HOST'               =>  'localhost', // 服务器地址
	'DB_NAME'               =>  'mebook',          // 数据库名
	'DB_USER'               =>  'root',      // 用户名
	'DB_PWD'                =>  'p31183w7066d',          // 密码
	'DB_PORT'               =>  '6602',        // 端口
	'DB_PREFIX'             =>  'b_',    // 数据库表前缀
	'DB_CHARSET'            =>  'utf8',      // 数据库编码默认采用utf8
	'SESSION_TYPE'=>'Db',
	'SESSION_OPTIONS'=>array(		
		  'type'=> 'db',			//配置session存储方式为数据库
		  'name'=> 'mebook_id',
    	  'expire'=>7200,
   		  'prefix'=> 'mebook',
	  ),
	'TOKEN_ON'      =>    true,  // 是否开启令牌验证 默认关闭
);