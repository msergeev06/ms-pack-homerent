<?php

namespace MSergeev\Packages\Homerent\Lib;

use MSergeev\Core\Lib as CoreLib;
use MSergeev\Packages\Homerent\Tables;
use MSergeev\Core\Entity\Query;

class CounterTariffs
{
	private static $tableFields = array(
		'ID',
		'ACTIVE',
		'SORT',
		'COUNTER_ID',
		'COUNTER_ID.ACTIVE'                             => 'COUNTER_ACTIVE',
		'COUNTER_ID.SORT'                               => 'COUNTER_SORT',
		'COUNTER_ID.ACCOUNT_ID'                         => 'COUNTER_ACCOUNT_ID',
		'COUNTER_ID.ACCOUNT_ID.ACTIVE'                  => 'COUNTER_ACCOUNT_ACTIVE',
		'COUNTER_ID.ACCOUNT_ID.SORT'                    => 'COUNTER_ACCOUNT_SORT',
		'COUNTER_ID.ACCOUNT_ID.NAME'                    => 'COUNTER_ACCOUNT_NAME',
		'COUNTER_ID.ACCOUNT_ID.REALTY_ID'               => 'COUNTER_ACCOUNT_REALTY_ID',
		'COUNTER_ID.ACCOUNT_ID.REALTY_ID.ACTIVE'        => 'COUNTER_ACCOUNT_REALTY_ACTIVE',
		'COUNTER_ID.ACCOUNT_ID.REALTY_ID.SORT'          => 'COUNTER_ACCOUNT_REALTY_SORT',
		'COUNTER_ID.ACCOUNT_ID.REALTY_ID.NAME'          => 'COUNTER_ACCOUNT_REALTY_NAME',
		'COUNTER_ID.ACCOUNT_ID.REALTY_ID.ADDRESS'       => 'COUNTER_ACCOUNT_REALTY_ADDRESS',
		'COUNTER_ID.ACCOUNT_ID.REALTY_ID.DESCRIPTION'   => 'COUNTER_ACCOUNT_REALTY_DESCRIPTION',
		'COUNTER_ID.ACCOUNT_ID.PERSONAL_NUMBER'         => 'COUNTER_ACCOUNT_PERSONAL_NUMBER',
		'COUNTER_ID.ACCOUNT_ID.START_VALUE'             => 'COUNTER_ACCOUNT_START_VALUE',
		'COUNTER_ID.ACCOUNT_ID.START_ACTIVE'            => 'COUNTER_ACCOUNT_START_ACTIVE',
		'COUNTER_ID.ACCOUNT_ID.END_ACTIVE'              => 'COUNTER_ACCOUNT_END_ACTIVE',
		'COUNTER_ID.NAME'                               => 'COUNTER_NAME',
		'COUNTER_ID.DESCRIPTION'                        => 'COUNTER_DESCRIPTION',
		'COUNTER_ID.FORMAT_DECIMAL'                     => 'COUNTER_FORMAT_DECIMAL',
		'COUNTER_ID.START_DATE'                         => 'COUNTER_START_DATE',
		'COUNTER_ID.END_DATE'                           => 'COUNTER_END_DATE',
		'NAME',
		'TITLE',
		'START',
		'END',
		'TYPE',
		'SUM',
		'SUM_BENEFIT',
		'SUM_BEFORE_THRESHOLD1',
		'SUM_BEFORE_THRESHOLD1_BENEFIT',
		'THRESHOLD1',
		'SUM_AFTER_THRESHOLD1',
		'SUM_AFTER_THRESHOLD1_BENEFIT',
		'THRESHOLD2',
		'SUM_AFTER_THRESHOLD2',
		'SUM_AFTER_THRESHOLD2_BENEFIT'
	);

	public static function showSelectTariffTypes ($value='null',$field1='class="form-control"')
	{
		$arValues = array(
			array('NAME'=>'Простой','VALUE'=>1),
			array('NAME'=>'С порогом','VALUE'=>2),
			array('NAME'=>'С двумя порогами','VALUE'=>3)
		);

		return SelectBox('tariff-type',$arValues,'--Выбрать--',$value,$field1);
	}

	public static function addFromPost (array $arPost = array())
	{
		$arData = array();
		$dateHelper = new CoreLib\DateHelper();
		if (!isset($arPost['id']) || intval($arPost['id'])<=0)
		{
			Errors::addError('EMPTY_COUNTER_ID','Не указан ID счётчика');
		}
		else
		{
			$arData['COUNTER_ID'] = CoreLib\Tools::validateIntVal($arPost['id']);
		}

		if (!isset($arPost['name']) || strlen($arPost['name'])<=0)
		{
			Errors::addError('EMPTY_NAME','Не указано название тарифа');
		}
		else
		{
			$arData['NAME'] = CoreLib\Tools::validateStringVal($arPost['name']);
		}

		if (isset($arPost['title']) && strlen($arPost['title'])>0)
		{
			$arData['TITLE'] = CoreLib\Tools::validateStringVal($arPost['title']);
		}

		if (!isset($arPost['start_date']) || !$dateHelper->validateDate($arPost['start_date']))
		{
			Errors::addError('EMPTY_START_DATE','Не указана дата начала тарифа');
		}
		else
		{
			$arData['START'] = $dateHelper->convertDateFromDB($arPost['start_date']);
		}

		if (isset($arPost['end_date']) && $dateHelper->validateDate($arPost['end_date']))
		{
			$arData['END'] = $dateHelper->convertDateFromDB($arPost['end_date']);
		}

		if (!isset($arPost['tariff-type']) || intval($arPost['tariff-type'])==0 || intval($arPost['tariff-type'])>3)
		{
			Errors::addError('WRONG_TYPE','Не верный тип тарифа, либо тип тарифа не указан');
		}
		else
		{
			$arData['TYPE'] = CoreLib\Tools::validateIntVal($arPost['tariff-type']);

			switch ($arData['TYPE'])
			{
				case 1:
					if (!isset($arPost['sum']) || floatval($arPost['sum'])<=0)
					{
						Errors::addError('EMPTY_SUM','Не указана стоимость тарифа');
					}
					else
					{
						$arData['SUM'] = CoreLib\Tools::validateFloatVal($arPost['sum']);
					}

					if (isset($arPost['sum-benefit']) && floatval($arPost['sum-benefit'])>0)
					{
						$arData['SUM_BENEFIT'] = CoreLib\Tools::validateFloatVal($arPost['sum-benefit']);
					}
					break;
				case 2:
					if (!isset($arPost['sum-before-threshold1']) || floatval($arPost['sum-before-threshold1'])<=0)
					{
						Errors::addError('EMPTY_SUM_BEFORE_THRESHOLD','Не указана стоимость до порога 1');
					}
					else
					{
						$arData['SUM_BEFORE_THRESHOLD1'] = CoreLib\Tools::validateFloatVal($arPost['sum-before-threshold1']);
					}

					if (isset($arPost['sum-before-threshold1-benefit']) && floatval($arPost['sum-before-threshold1-benefit'])>0)
					{
						$arData['SUM_BEFORE_THRESHOLD1_BENEFIT'] = CoreLib\Tools::validateFloatVal($arPost['sum-before-threshold1-benefit']);
					}

					if (!isset($arPost['threshold1']) || floatval($arPost['threshold1'])<=0)
					{
						Errors::addError('EMPTY_THRESHOLD1','Не указано значение порога 1');
					}
					else
					{
						$arData['THRESHOLD1'] = CoreLib\Tools::validateFloatVal($arPost['threshold1']);
					}

					if (!isset($arPost['sum-after-threshold1']) || floatval($arPost['sum-after-threshold1'])<=0)
					{
						Errors::addError('EMPTY_SUM_AFTER_THRESHOLD1','Не указана стоимость свыше порога 1');
					}
					else
					{
						$arData['SUM_AFTER_THRESHOLD1'] = CoreLib\Tools::validateFloatVal($arPost['sum-after-threshold1']);
					}

					if (isset($arPost['sum-after-threshold1-benefit']) && floatval($arPost['sum-after-threshold1-benefit'])>0)
					{
						$arData['SUM_AFTER_THRESHOLD1_BENEFIT'] = CoreLib\Tools::validateFloatVal($arPost['sum-after-threshold1-benefit']);
					}
					break;
				case 3:
					if (!isset($arPost['sum-before-threshold1']) || floatval($arPost['sum-before-threshold1'])<=0)
					{
						Errors::addError('EMPTY_SUM_BEFORE_THRESHOLD','Не указана стоимость до порога 1');
					}
					else
					{
						$arData['SUM_BEFORE_THRESHOLD1'] = CoreLib\Tools::validateFloatVal($arPost['sum-before-threshold1']);
					}

					if (isset($arPost['sum-before-threshold1-benefit']) && floatval($arPost['sum-before-threshold1-benefit'])>0)
					{
						$arData['SUM_BEFORE_THRESHOLD1_BENEFIT'] = CoreLib\Tools::validateFloatVal($arPost['sum-before-threshold1-benefit']);
					}

					if (!isset($arPost['threshold1']) || floatval($arPost['threshold1'])<=0)
					{
						Errors::addError('EMPTY_THRESHOLD1','Не указано значение порога 1');
					}
					else
					{
						$arData['THRESHOLD1'] = CoreLib\Tools::validateFloatVal($arPost['threshold1']);
					}

					if (!isset($arPost['sum-after-threshold1']) || floatval($arPost['sum-after-threshold1'])<=0)
					{
						Errors::addError('EMPTY_SUM_AFTER_THRESHOLD1','Не указана стоимость свыше порога 1');
					}
					else
					{
						$arData['SUM_AFTER_THRESHOLD1'] = CoreLib\Tools::validateFloatVal($arPost['sum-after-threshold1']);
					}

					if (isset($arPost['sum-after-threshold1-benefit']) && floatval($arPost['sum-after-threshold1-benefit'])>0)
					{
						$arData['SUM_AFTER_THRESHOLD1_BENEFIT'] = CoreLib\Tools::validateFloatVal($arPost['sum-after-threshold1-benefit']);
					}

					if (!isset($arPost['threshold2']) || floatval($arPost['threshold2'])<=0)
					{
						Errors::addError('EMPTY_THRESHOLD2','Не указано значение порога 2');
					}
					else
					{
						$arData['THRESHOLD2'] = CoreLib\Tools::validateFloatVal($arPost['threshold2']);
					}

					if (!isset($arPost['sum-after-threshold2']) || floatval($arPost['sum-after-threshold2'])<=0)
					{
						Errors::addError('EMPTY_SUM_AFTER_THRESHOLD2','Не указана стоимость свыше порога 2');
					}
					else
					{
						$arData['SUM_AFTER_THRESHOLD2'] = CoreLib\Tools::validateFloatVal($arPost['sum-after-threshold2']);
					}

					if (isset($arPost['sum-after-threshold2-benefit']) && floatval($arPost['sum-after-threshold2-benefit'])>0)
					{
						$arData['SUM_AFTER_THRESHOLD2_BENEFIT'] = CoreLib\Tools::validateFloatVal($arPost['sum-after-threshold2-benefit']);
					}
					break;
				default:
					Errors::addError('WRONG_TYPE','Не верный тип тарифа, либо тип тарифа не указан');
					break;
			}

		}

		if (!Errors::issetErrors())
		{
			return self::addDB($arData);
		}
		else
		{
			return false;
		}
	}

	public static function getList ($getID=null, $counterID=null, $bActive = true, $limit=0, $offset=0)
	{
		$arList = array(
			'select' => self::$tableFields
		);
		$bGetID = false;
		if (!is_null($getID) && intval($getID)>0)
		{
			$arList['filter']['ID'] = intval($getID);
			$arList['limit'] = 1;
			$bGetID = true;
		}
		elseif ($bActive)
		{
			$arList['filter']['ACTIVE'] = true;
		}
		if (!is_null($counterID) && intval($counterID)>0)
		{
			$arList['filter']['COUNTER_ID'] = intval($counterID);
		}

		if (intval($limit)>0)
		{
			$arList['limit'] = intval($limit);
		}
		if (intval($offset)>0)
		{
			$arList['offset'] = intval($offset);
		}
		$arList['order'] = array('END'=>'DESC','NAME'=>'ASC');

		$arRes = Tables\CounterTariffTable::getList($arList);
		if ($arRes && intval($arList['limit'])==1 && isset($arRes[0]))
		{
			$arRes = $arRes[0];
		}
		if (!$arRes)
		{
			$arRes = array();
		}

		return $arRes;
	}

	public static function showListTable ($counterID=null)
	{
		if (is_null($counterID))
		{
			echo 'Не указан ID счетчика';
			return false;
		}

		$arList = self::getList(null,$counterID,false);
		//msDebug($arList);
		if ($arList)
		{
			echo '<div id="accountList"></div><div id="accountPager"></div>';

			$imgSrcPath = CoreLib\Tools::getSitePath(CoreLib\Loader::getTemplate('homerent')."images/");

			//msDebug($arList);
			$arDatas = array();
			foreach ($arList as $list)
			{
				switch ($list['TYPE'])
				{
					case 1:
						$typeName = 'Простой';
						$tariff = $list['SUM'].'|'.floatval($list['SUM_BENEFIT']).'%';
						$description = 'Стоимость: '.$list['SUM'].'<br>'
							.'Льгота: '.floatval($list['SUM_BENEFIT']).'%';
						break;
					case 2:
						$typeName = 'С порогом';
						$tariff = $list['SUM_BEFORE_THRESHOLD1'].'|'
							.floatval($list['SUM_BEFORE_THRESHOLD1_BENEFIT']).'%|'
							.$list['THRESHOLD1'].'|'
							.$list['SUM_AFTER_THRESHOLD1'].'|'
							.floatval($list['SUM_AFTER_THRESHOLD1_BENEFIT']).'%';
						$description = 'Стоимость до порога: '.$list['SUM_BEFORE_THRESHOLD1'].'<br>'
							.'Льгота до порога: '.floatval($list['SUM_BEFORE_THRESHOLD1_BENEFIT']).'%<br>'
							.'Порог: '.$list['THRESHOLD1'].'<br>'
							.'Стоимость после порога: '.$list['SUM_AFTER_THRESHOLD1'].'<br>'
							.'Льгота после порога: '.floatval($list['SUM_AFTER_THRESHOLD1_BENEFIT']).'%';
						break;
					case 3:
						$typeName = 'С двумя порогами';
						$tariff = $list['SUM_BEFORE_THRESHOLD1'].'|'
							.floatval($list['SUM_BEFORE_THRESHOLD1_BENEFIT']).'%|'
							.$list['THRESHOLD1'].'|'
							.$list['SUM_AFTER_THRESHOLD1'].'|'
							.floatval($list['SUM_AFTER_THRESHOLD1_BENEFIT']).'%|'
							.$list['THRESHOLD2'].'|'
							.$list['SUM_AFTER_THRESHOLD2'].'|'
							.floatval($list['SUM_AFTER_THRESHOLD2_BENEFIT']).'%';
						$description = 'Стоимость до порога 1: '.$list['SUM_BEFORE_THRESHOLD1'].'<br>'
							.'Льгота до порога 1: '.floatval($list['SUM_BEFORE_THRESHOLD1_BENEFIT']).'%<br>'
							.'Порог 1: '.$list['THRESHOLD1'].'<br>'
							.'Стоимость после порога 1: '.$list['SUM_AFTER_THRESHOLD1'].'<br>'
							.'Льгота после порога 1: '.floatval($list['SUM_AFTER_THRESHOLD1_BENEFIT']).'%<br>'
							.'Порог 2: '.$list['THRESHOLD2'].'<br>'
							.'Стоимость после порога 2: '.$list['SUM_AFTER_THRESHOLD2'].'<br>'
							.'Льгота после порога 2: '.floatval($list['SUM_AFTER_THRESHOLD2_BENEFIT']).'%';
						break;
					default:
						$typeName = '';
						$tariff = '';
						$description = '';
						break;
				}
				$arDatas[] = array(
					'id' => $list['ID'],
					'active' => ($list['ACTIVE'])?'Да':'Нет',
					'name' => $list['NAME'].' ('.$list['TITLE'].')',
					'start' => $list['START'],
					'end' => !is_null($list['END'])?$list['END']:'',
					'type' => $typeName,
					'tariff' => $tariff,
					'description' => $description,
					'edit' => "<a class='table_button' href='tariff_edit.php?id=".$counterID."&tariff=".$list['ID']."'><img src='".$imgSrcPath."edit.png'></a>",
					'delete' => "<a class='table_button' href='tariff_delete.php?id=".$counterID."&tariff=".$list['ID']."'><img src='".$imgSrcPath."delete.png'></a>"
				);
			}

			$webixHelper = new CoreLib\WebixHelper();

			$webixHelper->addFunctionSortByTimestamp();

			$arData = array(
				'grid' => 'accountGrid',
				'container' => 'accountList',
				'tooltip' => true,
				'pager' => array('container'=>'accountPager'),
				'columns' => array(
					$webixHelper->getColumnArray('INT',array(
						'id' => 'id',
						'header' => 'ID',
					)),
					$webixHelper->getColumnArray('STRING',array(
						'id' => 'active',
						'header' => 'Активен'
					)),
					$webixHelper->getColumnArray('STRING',array(
						'id' => 'name',
						'header' => 'Название'
					)),
					$webixHelper->getColumnArray('STRING',array(
						'id' => 'start',
						'header' => 'Активен с'
					)),
					$webixHelper->getColumnArray('STRING',array(
						'id' => 'end',
						'header' => 'Активен по'
					)),
					$webixHelper->getColumnArray('STRING',array(
						'id' => 'type',
						'header' => 'Тип тарифа'
					)),
					$webixHelper->getColumnArray('STRING',array(
						'id' => 'tariff',
						'header' => 'Описание тарифа',
						'tooltip' => '#description#'
					)),
					$webixHelper->getColumnArray('EDIT'),
					$webixHelper->getColumnArray('DELETE')
				),
				'data' => $arDatas
			);

			CoreLib\Webix::showDataTable($arData);
			return true;
		}
		else
		{
			echo 'Тарифы для данного счётчика еще не были созданы';
			return false;
		}
	}

	public static function getNowTariffs ($counterID=null)
	{
		if (!is_null($counterID) && intval($counterID)>0)
		{
			$arList = array(
				'select' => self::$tableFields
			);
			$arList['filter']['COUNTER_ID'] = intval($counterID);
			$arList['filter']['<=START'] = date('d.m.Y');
			$arList['filter']['>=END'] = date('d.m.Y');
			$arList['order'] = array('END'=>'DESC','NAME'=>'ASC');

			$arRes = Tables\CounterTariffTable::getList($arList);
			if (!$arRes)
			{
				unset($arList['filter']['<=START']);
				unset($arList['filter']['>=END']);
				$arList['select'] = array('NAME');
				$arList['group'] = 'NAME';
				$arList['order'] = array('NAME'=>'ASC');
				$arRes2 = Tables\CounterTariffTable::getList($arList);
				if (!$arRes2)
				{
					return array();
				}
				$arList['select'] = self::$tableFields;
				unset($arList['group']);
				$arList['order'] = array('END'=>'DESC');
				$arList['limit'] = 1;
				$arResult = array();
				foreach ($arRes2 as $ar_res2)
				{
					$arList['filter']['NAME'] = $ar_res2['NAME'];
					$arRes3 = Tables\CounterTariffTable::getList($arList);
					if ($arRes3 && isset($arRes3[0]))
					{
						$arRes3 = $arRes3[0];
					}
					if ($arRes3)
					{
						$arResult[] = $arRes3;
					}
				}
				return $arResult;
			}
			else
			{
				return $arRes;
			}
		}

		return array();
	}



	protected static function addDB (array $arAdd = array())
	{
		if (!empty($arAdd))
		{
			$query = new Query('insert');
			$query->setInsertParams(
				$arAdd,
				Tables\CounterTariffTable::getTableName(),
				Tables\CounterTariffTable::getMapArray()
			);
			$res = $query->exec();
			if ($res->getResult())
			{
				return $res->getInsertId();
			}
		}

		return false;
	}

}