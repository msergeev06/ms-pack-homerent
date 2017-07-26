<?php

namespace MSergeev\Packages\Homerent\Tables;

use MSergeev\Core\Lib\DataManager;
use MSergeev\Core\Entity;
use MSergeev\Core\Lib\TableHelper;

class TariffingTable extends DataManager
{
	public static function getTableName ()
	{
		return 'ms_homerent_tariffing';
	}

	public static function getTableTitle ()
	{
		return 'Тип тарификации';
	}

	public static function getTableLinks ()
	{
		return array(
			'ID' => array(
				'ms_homerent_service' => 'TARIFFING_ID'
			)
		);
	}

	public static function getMap ()
	{
		return array(
			TableHelper::primaryField(array('title'=>'ID типа тарификации')),
			TableHelper::sortField(),
			new Entity\StringField('NAME',array(
				'required' => true,
				'title' => 'Название типа тарификации'
			)),
			new Entity\StringField('CODE',array(
				'required' => true,
				'title' => 'Код типа тарификации'
			))
		);
	}

	public static function getArrayDefaultValues ()
	{
		return array(
			array(
				'SORT' => 10,
				'NAME' => 'Норматив в месяц',
				'CODE' => 'standard-month'
			),
			array(
				'SORT' => 20,
				'NAME' => 'Норматив в сутки',
				'CODE' => 'standard-day'
			),
			array(
				'SORT' => 30,
				'NAME' => 'Площадь',
				'CODE' => 'area'
			),
			array(
				'SORT' => 40,
				'NAME' => 'Переменный расход',
				'CODE' => 'variable'
			),
			array(
				'SORT' => 50,
				'NAME' => 'Фиксированная сумма',
				'CODE' => 'fix-sum'
			),
			array(
				'SORT' => 60,
				'NAME' => 'Переменная сумма',
				'CODE' => 'variable-sum'
			),
			array(
				'SORT' => 70,
				'NAME' => 'Количество жильцов',
				'CODE' => 'people'
			),
			array(
				'SORT' => 80,
				'NAME' => 'Количество дней',
				'CODE' => 'days'
			)
		);
	}
}