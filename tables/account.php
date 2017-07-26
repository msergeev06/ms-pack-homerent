<?php

namespace MSergeev\Packages\Homerent\Tables;

use MSergeev\Core\Lib\DataManager;
use MSergeev\Core\Entity;
use MSergeev\Core\Lib\TableHelper;

class AccountTable extends DataManager
{
	public static function getTableName ()
	{
		return 'ms_homerent_account';
	}

	public static function getTableTitle ()
	{
		return 'Счета';
	}

	public static function getTableLinks ()
	{
		return array(
			'ID' => array(
				'ms_homerent_account_expenses' => 'ACCOUNT_ID',
				'ms_homerent_counter' => 'ACCOUNT_ID'
			)
		);
	}

	public static function getMap ()
	{
		return array(
			TableHelper::primaryField(array('title'=>'ID счёта')),
			TableHelper::activeField(),
			TableHelper::sortField(),
			new Entity\StringField('NAME',array(
				'required' => true,
				'title' => 'Название счёта'
			)),
			new Entity\IntegerField('REALTY_ID',array(
				'required' => true,
				'link' => 'ms_homerent_realty.ID',
				'title' => 'ID объекта недвижимости, которому принадлежит счёт'
			)),
			new Entity\StringField('PERSONAL_NUMBER',array(
				'title' => 'Лицевой номер счёта'
			)),
			new Entity\FloatField('START_VALUE',array(
				'required' => true,
				'default_value' => 0,
				'title' => 'Начальная сумма'
			)),
			new Entity\DateField('START_ACTIVE',array(
				'required' => true,
				'title' => 'Дата начала действия счёта'
			)),
			new Entity\DateField('END_ACTIVE',array(
				'title' => 'Дата окончания действия счёта'
			))
		);
	}
}
