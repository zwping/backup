<?php

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

	public function register()
	{
		//
	}

	public function init()
	{
		parent::init();

		//
		
	}

	public function settingForm()
	{
		return new Setting($this);
	}
}
