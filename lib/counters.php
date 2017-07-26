<?php

namespace MSergeev\Packages\Homerent\Lib;

use MSergeev\Core\Lib as CoreLib;
use MSergeev\Packages\Homerent\Tables;
use MSergeev\Core\Entity\Query;

class Counters
{
	private static $tableFields = array(
		'ID',
		'ACTIVE',
		'SORT',
		'ACCOUNT_ID',
		'ACCOUNT_ID.ACTIVE'                 => 'ACCOUNT_ACTIVE',
		'ACCOUNT_ID.SORT'                   => 'ACCOUNT_SORT',
		'ACCOUNT_ID.NAME'                   => 'ACCOUNT_NAME',
		'ACCOUNT_ID.REALTY_ID'              => 'ACCOUNT_REALTY_ID',
		'ACCOUNT_ID.REALTY_ID.ACTIVE'       => 'ACCOUNT_REALTY_ACTIVE',
		'ACCOUNT_ID.REALTY_ID.SORT'         => 'ACCOUNT_REALTY_SORT',
		'ACCOUNT_ID.REALTY_ID.NAME'         => 'ACCOUNT_REALTY_NAME',
		'ACCOUNT_ID.REALTY_ID.ADDRESS'      => 'ACCOUNT_REALTY_ADDRESS',
		'ACCOUNT_ID.REALTY_ID.DESCRIPTION'  => 'ACCOUNT_REALTY_DESCRIPTION',
		'ACCOUNT_ID.PERSONAL_NUMBER'        => 'ACCOUNT_PERSONAL_NUMBER',
		'ACCOUNT_ID.START_VALUE'            => 'ACCOUNT_START_VALUE',
		'ACCOUNT_ID.START_ACTIVE'           => 'ACCOUNT_START_ACTIVE',
		'ACCOUNT_ID.END_ACTIVE'             => 'ACCOUNT_END_ACTIVE',
		'NAME',
		'DESCRIPTION',
		'FORMAT_DECIMAL',
		'START_DATE',
		'END_DATE'
	);

	public static function addFromPost (array $arPost = array())
	{
		$arData = array();
		$dateHelper = new CoreLib\DateHelper();

		if (!isset($arPost['name']) || strlen($arPost['name'])<2)
		{
			Errors::addError('EMPTY_NAME','Не указано название счётчика');
		}
		else
		{
			$arData['NAME'] = CoreLib\Tools::validateStringVal($arPost['name']);
		}

		if (isset($arPost['description']) && strlen($arPost['description'])>0)
		{
			$arData['DESCRIPTION'] = CoreLib\Tools::validateStringVal($arPost['description']);
		}

		if (!isset($arPost['account']) || intval($arPost['account'])<=0)
		{
			Errors::addError('EMPTY_ACCOUNT_ID','Не указан лицевой счёт, к которому будет привязан счётчик');
		}
		else
		{
			$arData['ACCOUNT_ID'] = CoreLib\Tools::validateIntVal($arPost['account']);
		}

		if (!isset($arPost['decimal']))
		{
			Errors::addError('EMPTY_FORMAT_DECIMAL','Не указана точность счётчика (количество знаков после запятой)');
		}
		else
		{
			$arData['FORMAT_DECIMAL'] = CoreLib\Tools::validateIntVal($arPost['decimal']);
		}

		if (!isset($arPost['start_date']) || !$dateHelper->validateDate($arPost['start_date']))
		{
			Errors::addError('EMPTY_START_DATE','Не указана дата ввода счётчика в эксплуатацию');
		}
		else
		{
			$arData['START_DATE'] = $dateHelper->convertDateFromDB($arPost['start_date']);
		}

		if (strlen($arPost['end_date'])==10 && $dateHelper->validateDate($arPost['end_date']))
		{
			$arData['END_DATE'] = $dateHelper->convertDateFromDB($arPost['end_date']);
		}

		if (!Errors::issetErrors())
		{
			return self::addDB ($arData);
		}

		return false;
	}

	public static function getList ($getID=null, $bActive=true, $limit=0, $offset=0)
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

		if (intval($limit)>0)
		{
			$arList['limit'] = intval($limit);
		}
		if (intval($offset)>0)
		{
			$arList['offset'] = intval($offset);
		}
		$arList['order'] = array('SORT'=>'ASC','ID'=>'ASC');

		$arRes = Tables\CounterTable::getList($arList);
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

	public static function showListTable ()
	{
		$arList = self::getList(null,false);
		//msDebug($arList);
		if ($arList)
		{
			echo '<div id="accountList"></div><div id="accountPager"></div>';

			$imgSrcPath = CoreLib\Tools::getSitePath(CoreLib\Loader::getTemplate('homerent')."images/");

			//msDebug($arList);
			$arDatas = array();
			foreach ($arList as $list)
			{
				$arTariffs = CounterTariffs::getNowTariffs($list['ID']);
				$bFirstT = true;
				$tariff = '';
				$tariff_desc = '';
				foreach ($arTariffs as $ar_tariff)
				{
					switch ($ar_tariff['TYPE'])
					{
						case 1:
							$tariff_info = 'Стоимость: '.$ar_tariff['SUM'].'<br>'
								.'Льгота: '.floatval($ar_tariff['SUM_BENEFIT']).'%<br>';
							break;
						case 2:
							$tariff_info = 'Стоимость до порога: '.$ar_tariff['SUM_BEFORE_THRESHOLD1'].'<br>'
								.'Льгота до порога: '.floatval($ar_tariff['SUM_BEFORE_THRESHOLD1_BENEFIT']).'%<br>'
								.'Порог: '.$ar_tariff['THRESHOLD1'].'<br>'
								.'Стоимость после порога: '.$ar_tariff['SUM_AFTER_THRESHOLD1'].'<br>'
								.'Льгота после порога: '.floatval($ar_tariff['SUM_AFTER_THRESHOLD1_BENEFIT']).'%<br>';
							break;
						case 3:
							$tariff_info = 'Стоимость до порога 1: '.$ar_tariff['SUM_BEFORE_THRESHOLD1'].'<br>'
								.'Льгота до порога 1: '.floatval($ar_tariff['SUM_BEFORE_THRESHOLD1_BENEFIT']).'%<br>'
								.'Порог 1: '.$ar_tariff['THRESHOLD1'].'<br>'
								.'Стоимость после порога 1: '.$ar_tariff['SUM_AFTER_THRESHOLD1'].'<br>'
								.'Льгота после порога 1: '.floatval($ar_tariff['SUM_AFTER_THRESHOLD1_BENEFIT']).'%<br>'
								.'Порог 2: '.$ar_tariff['THRESHOLD2'].'<br>'
								.'Стоимость после порога 2: '.$ar_tariff['SUM_AFTER_THRESHOLD2'].'<br>'
								.'Льгота после порога 2: '.floatval($ar_tariff['SUM_AFTER_THRESHOLD2_BENEFIT']).'%<br>';
							break;
						default:
							$tariff_info = '';
							break;
					}
					if ($bFirstT)
					{
						$bFirstT = false;
						$tariff = $ar_tariff['NAME'];
						$tariff_desc = $ar_tariff['NAME'].'('.$ar_tariff['TITLE'].'):<br>'.$tariff_info;
					}
					else
					{
						$tariff .= ', '.$ar_tariff['NAME'];
						$tariff_desc .= $ar_tariff['NAME'].'('.$ar_tariff['TITLE'].'):<br>'.$tariff_info;
					}
				}

				$arDatas[] = array(
					'id' => $list['ID'],
					'active' => ($list['ACTIVE'])?'Да':'Нет',
					'sort' => $list['SORT'],
					'name' => $list['NAME'],
					'description' => !is_null($list['DESCRIPTION'])?$list['DESCRIPTION']:'',
					'account_name' => '['.$list['ACCOUNT_PERSONAL_NUMBER'].'] '.$list['ACCOUNT_NAME'],
					'decimal' => intval($list['FORMAT_DECIMAL']),
					'tariffs' => $tariff." <a class='table_button' href='tariffs.php?id=".$list['ID']."'><img src='".$imgSrcPath."edit.png'></a>",
					'tariffs_desc' => $tariff_desc,
					'start_active' => $list['START_DATE'],
					'end_active' => !is_null($list['END_DATE'])?$list['END_DATE']:'',
					'edit' => "<a class='table_button' href='edit.php?id=".$list['ID']."'><img src='".$imgSrcPath."edit.png'></a>",
					'delete' => "<a class='table_button' href='delete.php?id=".$list['ID']."'><img src='".$imgSrcPath."delete.png'></a>"
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
					$webixHelper->getColumnArray('INT',array(
						'id' => 'sort',
						'header' => 'Сортировка'
					)),
					$webixHelper->getColumnArray('STRING',array(
						'id' => 'name',
						'header' => 'Название'
					)),
					$webixHelper->getColumnArray('STRING',array(
						'id' => 'description',
						'header' => 'Описание'
					)),
					$webixHelper->getColumnArray('STRING',array(
						'id' => 'account_name',
						'header' => 'ЛС'
					)),
					$webixHelper->getColumnArray('INT',array(
						'id' => 'decimal',
						'header' => 'Точность',
						'tooltip' => "Редактировать тарифы"
					)),
					$webixHelper->getColumnArray('STRING',array(
						'id' => 'tariffs',
						'header' => 'Тарифы',
						'tooltip' => '#tariffs_desc#'
					)),
					$webixHelper->getColumnArray('STRING',array(
						'id' => 'start_active',
						'header' => 'Активен с'
					)),
					$webixHelper->getColumnArray('STRING',array(
						'id' => 'end_active',
						'header' => 'Активен по'
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
			echo 'Счётчики еще не были созданы';
			return false;
		}
	}

	public static function updateFromPost (array $arPost = array())
	{
		$arData = array();
		$dateHelper = new CoreLib\DateHelper();

		if (!isset($arPost['id']) || intval($arPost['id'])<=0)
		{
			Errors::addError('EMPTY_ID','Не указан ID изменяемого счётчика.');
			$updateID = null;
		}
		else
		{
			$updateID = intval($arPost['id']);
		}

		if (!isset($arPost['active']) || intval($arPost['active'])<=0)
		{
			$arData['ACTIVE'] = false;
		}
		else
		{
			$arData['ACTIVE'] = true;
		}

		if (!isset($arPost['sort']) || intval($arPost['sort'])<=0)
		{
			$arData['SORT'] = 500;
		}
		else
		{
			$arData['SORT'] = intval($arPost['sort']);
		}

		if (!isset($arPost['name']) || strlen($arPost['name'])<2)
		{
			Errors::addError('EMPTY_NAME','Не указано название счётчика. Название не будет изменено','WARNING');
		}
		else
		{
			$arData['NAME'] = CoreLib\Tools::validateStringVal($arPost['name']);
		}

		if (isset($arPost['description']) && strlen($arPost['description'])>0)
		{
			$arData['DESCRIPTION'] = CoreLib\Tools::validateStringVal($arPost['description']);
		}
		else
		{
			$arData['DESCRIPTION'] = null;
		}

		if (!isset($arPost['account']) || intval($arPost['account'])<=0)
		{
			Errors::addError('EMPTY_ACCOUNT_ID','Не указан лицевой счёт, к которому будет привязан счётчик. ЛС не будет изменен','WARNING');
		}
		else
		{
			$arData['ACCOUNT_ID'] = CoreLib\Tools::validateIntVal($arPost['account']);
		}

		if (!isset($arPost['decimal']))
		{
			Errors::addError('EMPTY_FORMAT_DECIMAL','Не указана точность счётчика (количество знаков после запятой). Точность не будет изменена','WARNING');
		}
		else
		{
			$arData['FORMAT_DECIMAL'] = CoreLib\Tools::validateIntVal($arPost['decimal']);
		}

		if (!isset($arPost['start_date']) || !$dateHelper->validateDate($arPost['start_date']))
		{
			Errors::addError('EMPTY_START_DATE','Не указана дата ввода счётчика в эксплуатацию. Дата не будет изменена','WARNING');
		}
		else
		{
			$arData['START_DATE'] = $dateHelper->convertDateFromDB($arPost['start_date']);
		}

		if (strlen($arPost['end_date'])==10 && $dateHelper->validateDate($arPost['end_date']))
		{
			$arData['END_DATE'] = $dateHelper->convertDateFromDB($arPost['end_date']);
		}

		$arCounter = self::getList($updateID);
		foreach ($arData as $key=>$value)
		{
			if (!is_null($arData[$key]))
			{
				if (!is_null($arCounter[$key]) && $arCounter[$key] === $arData[$key])
				{
					unset($arData[$key]);
				}
			}
			elseif (is_null($arCounter[$key]))
			{
				unset($arData[$key]);
			}
		}

		if (!Errors::issetErrors())
		{
			if (!empty($arData))
			{
				return self::updateDB($updateID, $arData);
			}
			else
			{
				return true;
			}
		}

		return false;
	}

	public static function checkCanDelete ($accountID = null)
	{
		if (intval($accountID)<=0)
		{
			Errors::addError('WRONG_ID','Неверный ID удаляемого объекта');
			return false;
		}
		if (!Tables\CounterTable::checkTableLinks())
		{
			return true;
		}
		else
		{
			Errors::addError(
				'ISSET_LINKED_DATA',
				'Существуют записи связанные с данным объектом. '
				.'При удалении данного объекта будут также удалены все связанные с ним записи. '
				.'ВНИМАНИЕ! Данная операция не обратима!',
				'WARNING'
			);
			return false;
		}
	}

	public static function deleteFromPost (array $arPost = array())
	{
		if (!isset($arPost['id']) || intval($arPost['id'])<=0)
		{
			Errors::addError('EMPTY_ID','Не указан ID удаляемой записи');
			return false;
		}
		if (!isset($arPost['confirm']) || intval($arPost['confirm'])<=0)
		{
			Errors::addError('NOT_CONFIRM','Нет подтверждения удаления записи','WARNING');
			return false;
		}

		return self::deleteDB (intval($arPost['id']),true);
	}


	protected static function addDB (array $arAdd = array())
	{
		$query = new Query('insert');
		$query->setInsertParams(
			$arAdd,
			Tables\CounterTable::getTableName(),
			Tables\CounterTable::getMapArray()
		);
		$res = $query->exec();
		if ($res->getResult())
		{
			return $res->getInsertId();
		}

		return false;
	}

	protected static function updateDB ($updateID, array $arUpdate = array())
	{
		$query = new Query('update');
		$query->setUpdateParams(
			$arUpdate,
			$updateID,
			Tables\CounterTable::getTableName(),
			Tables\CounterTable::getMapArray()
		);
		$res = $query->exec();

		return $res->getResult();
	}

	protected static function deleteDB ($deleteID=null, $confirm=false)
	{
		if (!is_null($deleteID) && intval($deleteID)>0)
		{
			$query = new Query('delete');
			$query->setDeleteParams(
				$deleteID,
				$confirm,
				Tables\CounterTable::getTableName(),
				Tables\CounterTable::getMapArray(),
				Tables\CounterTable::getTableLinks()
			);
			$res = $query->exec();
			if ($res->getResult())
			{
				return true;
			}
		}

		Errors::addError('NOT_DELETE','Произошла непредвиденная ошибка при попытке удаления счёта');
		return false;
	}
}