<?php

namespace MSergeev\Packages\Homerent\Tables;

use MSergeev\Core\Lib\DataManager;
use MSergeev\Core\Entity;
use MSergeev\Core\Lib\TableHelper;

class RealtyTable extends DataManager
{
	public static function getTableName ()
	{
		return 'ms_homerent_realty';
	}

	public static function getTableTitle ()
	{
		return 'Объекты недвижимости';
	}

	public static function getTableLinks ()
	{
		return array(
			'ID' => array(
				'ms_homerent_account' => 'REALTY_ID'
			)
		);
	}

	public static function getMap ()
	{
		return array(
			TableHelper::primaryField(array('title'=>'ID объекта недвижимости')),
			TableHelper::activeField(),
			TableHelper::sortField(),
			new Entity\StringField('NAME',array(
				'required' => true,
				'title' => 'Название объекта недвижимости'
			)),
			new Entity\StringField('ADDRESS',array(
				'title' => 'Адрес объекта недвижимости'
			)),
			new Entity\TextField('DESCRIPTION',array(
				'title' => 'Описание объекта недвижимости'
			))
		);
	}
}