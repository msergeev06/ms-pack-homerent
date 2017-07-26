<?php

namespace MSergeev\Packages\Homerent\Tables;

use MSergeev\Core\Lib\DataManager;
use MSergeev\Core\Entity;
use MSergeev\Core\Lib\TableHelper;

class AccountExpensesTable extends DataManager
{
	public static function getTableName ()
	{
		return 'ms_homerent_account_expenses';
	}

	public static function getTableTitle ()
	{
		return 'Связи счетов и типов расходов';
	}

	public static function getMap ()
	{
		return array(
			TableHelper::primaryField(),
			new Entity\IntegerField('ACCOUNT_ID',array(
				'required' => true,
				'link' => 'ms_homerent_account.ID',
				'title' => 'ID счёта'
			)),
			new Entity\IntegerField('EXPENSE_ID',array(
				'required' => true,
				'link' => 'ms_homerent_expense_category.ID',
				'title' => 'ID категории расходов'
			))
		);
	}
}