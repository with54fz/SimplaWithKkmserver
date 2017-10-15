<?php
/**
 * Модуль для работы с kkmserver по протоколу обратного вызова
 * для касс, в которых используется протокол
 * ОФД 1.0 !!
 *
 * (c) http://with54fz.ru/ , email: main@with54fz.ru
 * @ver 0.0.1
 */
 
require_once(dirname(__FILE__).'/DesignSimpla.php');
require_once(dirname(__FILE__).'/../kkmserver/src/KkmAssist.php');

class Design extends DesignSimpla
{
	public function __construct()
	{
		parent::__construct();
        	$this->smarty->assign('kkmAssist',new KkmAssist());
	}
}