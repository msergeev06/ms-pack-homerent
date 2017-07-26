<?php

namespace MSergeev\Packages\Homerent\Tables;

use MSergeev\Core\Lib\DataManager;
use MSergeev\Core\Entity;
use MSergeev\Core\Lib\TableHelper;

class ServiceTable extends DataManager
{
	public static function getTableName ()
	{
		return 'ms_homerent_service';
	}

	public static function getTableTitle ()
	{
		return 'Услуги';
	}

	public static function getMap ()
	{
		return array(
			TableHelper::primaryField(array('title'=>'ID услуги')),
			TableHelper::activeField(),
			TableHelper::sortField(),
			new Entity\StringField('NAME',array(
				'required' => true,
				'title' => 'Название услуги'
			)),
			new Entity\StringField('TITLE',array(
				'title' => 'Описание услуги'
			)),
			new Entity\IntegerField('EXPENSE_ID',array(
				'required' => true,
				'link' => 'ms_homerent_expense_category.ID',
				'title' => 'ID типа расходов'
			)),
			new Entity\IntegerField('TARIFFING_ID',array(
				'required' => true,
				'link' => 'ms_homerent_tariffing.ID',
				'title' => 'ID типа тарификации'
			)),
			new Entity\DateField('START', array(
				'required' => true,
				'title' => 'Начало предоставления услуги'
			)),
			new Entity\DateField('END',array(
				'title' => 'Окончание предоставления услуги'
			))
		);
	}
}