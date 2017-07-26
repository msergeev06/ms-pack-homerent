<?php

namespace MSergeev\Packages\Homerent\Tables;

use MSergeev\Core\Lib\DataManager;
use MSergeev\Core\Entity;
use MSergeev\Core\Lib\TableHelper;

class ExpenseCategoryTable extends DataManager
{
	public static function getTableName ()
	{
		return 'ms_homerent_expense_category';
	}

	public static function getTableTitle ()
	{
		return 'Категории расходов';
	}

	public static function getTableLinks ()
	{
		return array(
			'ID' => array(
				'ms_homerent_account_expenses' => 'EXPENSE_ID',
				'ms_homerent_service' => 'EXPENSE_ID'
			)
		);
	}

	public static function getMap ()
	{
		return array(
			TableHelper::primaryField(),
			TableHelper::activeField(),
			TableHelper::sortField(),
			new Entity\StringField('NAME',array(
				'required' => true,
				'title' => 'Название расхода'
			)),
			new Entity\StringField('CODE',array(
				'required' => true,
				'title' => 'Код расхода'
			))
		);
	}

	public static function getArrayDefaultValues ()
	{
		return array(
			array(
				'SORT' => 10,
				'NAME' => 'Холодная вода',
				'CODE' => 'coldwater'
			),
			array(
				'SORT' => 20,
				'NAME' => 'Горячая вода',
				'CODE' => 'hotwater'
			),
			array(
				'SORT' => 30,
				'NAME' => 'Электричество',
				'CODE' => 'electric'
			),
			array(
				'SORT' => 40,
				'NAME' => 'Газ',
				'CODE' => 'gas'
			),
			array(
				'SORT' => 50,
				'NAME' => 'Отопление',
				'CODE' => 'heat'
			),
			array(
				'NAME' => 'Водоотведение',
				'CODE' => 'sewerage'
			),
			array(
				'NAME' => 'Освещение',
				'CODE' => 'lighting'
			),
			array(
				'NAME' => 'Содержание жилья',
				'CODE' => 'maintenance'
			),
			array(
				'NAME' => 'Ремонт',
				'CODE' => 'repair'
			),
			array(
				'NAME' => 'Обслуживание лифтов',
				'CODE' => 'elevator'
			),
			array(
				'NAME' => 'Вывоз мусора',
				'CODE' => 'garbage'
			),
			array(
				'NAME' => 'Подогрев горячей воды',
				'CODE' => 'heatingwater'
			),
			array(
				'NAME' => 'Антенна',
				'CODE' => 'antenna'
			),
			array(
				'NAME' => 'Домофон',
				'CODE' => 'intercom'
			),
			array(
				'NAME' => 'Охрана',
				'CODE' => 'security'
			),
			array(
				'NAME' => 'Консьерж',
				'CODE' => 'concierge'
			),
			array(
				'NAME' => 'Гараж',
				'CODE' => 'garage'
			),
			array(
				'NAME' => 'Уборка снега',
				'CODE' => 'snow'
			),
			array(
				'NAME' => 'Телевидение',
				'CODE' => 'tv'
			),
			array(
				'NAME' => 'Телефон',
				'CODE' => 'phone'
			),
			array(
				'NAME' => 'Интернет',
				'CODE' => 'internet'
			),
			array(
				'NAME' => 'Содержание системы ГВС',
				'CODE' => 'hotwatermaintenance'
			),
			array(
				'SORT' => 10000,
				'NAME' => 'Прочее',
				'CODE' => 'other'
			)
		);
	}
}