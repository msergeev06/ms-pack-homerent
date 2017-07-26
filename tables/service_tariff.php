<?php

namespace MSergeev\Packages\Homerent\Tables;

use MSergeev\Core\Lib\DataManager;
use MSergeev\Core\Lib\TableHelper;
use MSergeev\Core\Entity;

class ServiceTariffTable extends DataManager
{
	public static function getTableName ()
	{
		return 'ms_homerent_service_tariff';
	}

	public static function getTableTitle ()
	{
		return 'Тарифы услуг';
	}

	public static function getMap ()
	{
		return array(
			TableHelper::primaryField(array('title'=>'ID тарифа')),
			TableHelper::activeField(),
			TableHelper::sortField(),
			new Entity\IntegerField('SERVICE_ID',array(
				'required' => true,
				'link' => 'ms_homerent_service.ID',
				'title' => 'ID услуги'
			)),
			new Entity\StringField('NAME',array(
				'required' => true,
				'title' => 'Название тарифа'
			)),
			new Entity\StringField('TITLE',array(
				'title' => 'Описание тарифа'
			)),
			new Entity\DateField('START',array(
				'required' => true,
				'title' => 'Дата начала тарифа'
			)),
			new Entity\DateField('END',array(
				'title' => 'Дата окончания тарифа'
			)),
			new Entity\IntegerField('TYPE',array(
				'required' => true,
				'default_value' => 1,
				'title' => 'Тип тарифа'
			)),
			new Entity\FloatField('SUM',array(
				'title' => 'Сумма для тарифа Простой'
			)),
			new Entity\FloatField('SUM_BENEFIT',array(
				'title' => 'Льгота для тарифа Простой'
			)),
			new Entity\FloatField('SUM_BEFORE_THRESHOLD1',array(
				'title' => 'Стоимость до порога 1'
			)),
			new Entity\FloatField('SUM_BEFORE_THRESHOLD1_BENEFIT',array(
				'title' => 'Льгота для стоимости до порога 1'
			)),
			new Entity\FloatField('THRESHOLD1',array(
				'title' => 'Порог 1'
			)),
			new Entity\FloatField('SUM_AFTER_THRESHOLD1',array(
				'title' => 'Стоимость свыше порога 1'
			)),
			new Entity\FloatField('SUM_AFTER_THRESHOLD1_BENEFIT',array(
				'title' => 'Льгота для стоимости свыше порога 1'
			)),
			new Entity\FloatField('THRESHOLD2',array(
				'title' => 'Порог 2'
			)),
			new Entity\FloatField('SUM_AFTER_THRESHOLD2',array(
				'title' => 'Стоимость свыше порога 2'
			)),
			new Entity\FloatField('SUM_AFTER_THRESHOLD2_BENEFIT',array(
				'title' => 'Льгота для стоимости свыше порога 2'
			))
		);
	}
}