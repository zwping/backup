<?php
/*
 * @Author       : zwping
 * @Date         : 2024-01-16 18:06:17
 * @LastEditors  : zwping
 * @LastEditTime : 2024-01-16 23:25:06
 * @Description  : 
 * Copyright (c) 2024 by zwping, All Rights Reserved. 
 */

namespace Encore\Admin\Backup;

use Dcat\Admin\Extend\ServiceProvider;
use Dcat\Admin\Admin;

class BackupServiceProvider extends ServiceProvider
{
	protected $js = [
        'js/index.js',
    ];
	protected $css = [
		'css/index.css',
	];

	public function register() {
		//
	}

	public function init() {
		parent::init();

		//
		
	}

	// public function settingForm() {
	// 	return new Setting($this);
	// }
	
	/** 
	 * 菜单
	 * dcat-admin 扩展中不支持直接添加权限, 未绑定权限的菜单为公共菜单
	 * */
    protected $menu = [
        [
            'title' => '备份管理',
            'uri'   => 'backup',
            'icon'  => 'fa-copy',
        ],
    ];

}
