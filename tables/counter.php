<?php

namespace MSergeev\Packages\Homerent\Tables;

use MSergeev\Core\Lib\DataManager;
use MSergeev\Core\Entity;
use MSergeev\Core\Lib\TableHelper;

class CounterTable extends DataManager
{
	public static function getTableName ()
	{
		return 'ms_homerent_counter';
	}

	public static function getTableTitle ()
	{
		return 'Счётчики';
	}

	public static function getTableLinks ()
	{
		return array(
			'ID' => array(
				'ms_homerent_counter_tariff' => 'COUNTER_ID'
			)
		);
	}

	public static function getMap ()
	{
		return array(
			TableHelper::primaryField(array('title'=>'ID счётчика')),
			TableHelper::activeField(),
			TableHelper::sortField(),
			new Entity\IntegerField('ACCOUNT_ID',array(
				'required' => true,
				'link' => 'ms_homerent_account.ID',
				'title' => 'ID счёта'
			)),
			new Entity\StringField('NAME',array(
				'required' => true,
				'title' => 'Название счётчика'
			)),
			new Entity\TextField('DESCRIPTION',array(
				'title' => 'Описание счётчика'
			)),
			new Entity\IntegerField('FORMAT_DECIMAL',array(
				'required' => true,
				'default_value' => 2,
				'title' => 'Количество знаков после запятой'
			)),
			new Entity\DateField('START_DATE',array(
				'required' => true,
				'title' => 'Ввод в эксплуатацию'
			)),
			new Entity\DateField('END_DATE',array(
				'title' => 'Вывод из эксплуатации'
			))
		);
	}
}