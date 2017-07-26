<?php

namespace MSergeev\Packages\Homerent\Lib;

use MSergeev\Core\Entity\Query;
use MSergeev\Core\Lib as CoreLib;
use MSergeev\Packages\Homerent\Tables;

class Accounts
{
	private static $tableFields = array(
		'ID',
		'ACTIVE',
		'SORT',
		'NAME',
		'REALTY_ID',
		'REALTY_ID.ACTIVE' => 'REALTY_ACTIVE',
		'REALTY_ID.SORT' => 'REALTY_SORT',
		'REALTY_ID.NAME' => 'REALTY_NAME',
		'REALTY_ID.ADDRESS' => 'REALTY_ADDRESS',
		'REALTY_ID.DESCRIPTION' => 'REALTY_DESCRIPTION',
		'PERSONAL_NUMBER',
		'START_VALUE',
		'START_ACTIVE',
		'END_ACTIVE'
	);

	public static function showSelectAccounts ($value='null',$field1='class="form-control"')
	{
		$arAccounts = self::getList();
		$arValues = array();
		foreach ($arAccounts as $ar_account)
		{
			$arValues[] = array(
				'NAME' => '['.$ar_account['PERSONAL_NUMBER'].'] '.$ar_account['NAME'].' ('.$ar_account['NAME'].')',
				'VALUE' => $ar_account['ID']
			);
		}

		return SelectBox('account',$arValues,'--Выбрать--',$value,$field1);
	}

	public static function addFromPost (array $arPost)
	{
		$arData = array();
		$dateHelper = new CoreLib\DateHelper();
		if (!isset($arPost['name']) || strlen($arPost['name'])<2)
		{
			Errors::addError('EMPTY_NAME','Не указано название счёта');
		}
		else
		{
			$arData['NAME'] = CoreLib\Tools::validateStringVal($arPost['name']);
		}
		if (!isset($arPost['realty']) || intval($arPost['realty'])<=0)
		{
			Errors::addError('EMPTY_REALTY','Не указан объект недвижимости, к которому будет привязан счёт');
		}
		else
		{
			$arData['REALTY_ID'] = CoreLib\Tools::validateIntVal($arPost['realty']);
		}
		if (!isset($arPost['personal_number']) || strlen($arPost['personal_number'])<2)
		{
			Errors::addError('EMPTY_PERSONAL_NUMBER','Не указан лицевой счёт');
		}
		else
		{
			$arData['PERSONAL_NUMBER'] = CoreLib\Tools::validateStringVal($arPost['personal_number']);
		}
		if (!isset($arPost['start_value']))
		{
			Errors::addError('EMPTY_START_VALUE','Не указано начальное значение остатка на счёте');
		}
		else
		{
			$arData['START_VALUE'] = CoreLib\Tools::validateFloatVal($arPost['start_value']);
		}
		if (!isset($arPost['start_date']) || !$dateHelper->validateDate($arPost['start_date']))
		{
			Errors::addError('EMPTY_START_DATE','Не указана дана начала срока действия счёта');
		}
		else
		{
			$arData['START_ACTIVE'] = $dateHelper->convertDateFromDB($arPost['start_date']);
		}
		if (strlen($arPost['end_date'])==10 && $dateHelper->validateDate($arPost['end_date']))
		{
			$arData['END_ACTIVE'] = $dateHelper->convertDateFromDB($arPost['end_date']);
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
		$arList['order'] = array('SORT'=>'ASC','NAME'=>'ASC');

		$arRes = Tables\AccountTable::getList($arList);
		if ($arRes && intval($arList['limit'])==1 && isset($arRes[0]))
		{
			$arRes = $arRes[0];
		}
		if ($arRes && intval($limit)==1)
		{
			$arRes['NOW_VALUE'] = self::getAccountValue($arRes['ID'],$arRes);
		}
		elseif ($arRes && intval($limit)>1)
		{
			foreach ($arRes as &$ar_res)
			{
				$ar_res['NOW_VALUE'] = self::getAccountValue($ar_res['ID'],$ar_res);
			}
			unset($ar_res);
		}
		else
		{
			$arRes = array();
		}

		return $arRes;
	}

	public static function getAccountValue ($accountID, $arAccount=array())
	{
		if (empty($arAccount))
		{
			$arAccount = self::getList($accountID);
		}
		$value = $arAccount['START_VALUE'];

		return round($value,2);
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
				$arDatas[] = array(
					'id' => $list['ID'],
					'active' => ($list['ACTIVE'])?'Да':'Нет',
					'sort' => $list['SORT'],
					'name' => $list['NAME'],
					'realty_name' => '['.$list['REALTY_ID'].'] '.$list['REALTY_NAME'],
					'realty_address' => !is_null($list['REALTY_ADDRESS'])?addslashes($list['REALTY_ADDRESS']):'',
					'realty_description' => !is_null($list['REALTY_DESCRIPTION'])?addslashes($list['REALTY_DESCRIPTION']):'',
					'personal_number' => !is_null($list['PERSONAL_NUMBER'])?$list['PERSONAL_NUMBER']:'',
					'start_value' => $list['START_VALUE'],
					'now_value' => $list['NOW_VALUE'],
					'start_active' => $list['START_ACTIVE'],
					'end_active' => !is_null($list['END_ACTIVE'])?$list['END_ACTIVE']:'',
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
						'id' => 'realty_name',
						'header' => 'Недвижимость',
						'tooltip' => 'Адрес: #realty_address#<br>'
							.'Описание: #realty_description#'
					)),
					$webixHelper->getColumnArray('STRING',array(
						'id' => 'personal_number',
						'header' => 'ЛС'
					)),
					$webixHelper->getColumnArray('INT',array(
						'id' => 'start_value',
						'header' => 'Нач. знач.'
					)),
					$webixHelper->getColumnArray('INT',array(
						'id' => 'now_value',
						'header' => 'Тек. знач.'
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
			echo 'Счета еще не были созданы';
			return false;
		}
	}

	public static function updateFromPost (array $arPost)
	{
		$arData = array();
		$updateID = null;
		//msDebug($arPost);
		$dateHelper = new CoreLib\DateHelper();
		if (!isset($arPost['id']) || intval($arPost['id'])<=0)
		{
			Errors::addError('EMPTY_ID','Не указан ID изменяемого счёта');
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
			Errors::addError('EMPTY_NAME','Не указано название счёта');
		}
		else
		{
			$arData['NAME'] = CoreLib\Tools::validateStringVal($arPost['name']);
		}
		if (!isset($arPost['realty']) || intval($arPost['realty'])<=0)
		{
			Errors::addError('EMPTY_REALTY','Не указан объект недвижимости, к которому будет привязан счёт');
		}
		else
		{
			$arData['REALTY_ID'] = CoreLib\Tools::validateIntVal($arPost['realty']);
		}
		if (!isset($arPost['personal_number']) || strlen($arPost['personal_number'])<2)
		{
			Errors::addError('EMPTY_PERSONAL_NUMBER','Не указан лицевой счёт');
		}
		else
		{
			$arData['PERSONAL_NUMBER'] = CoreLib\Tools::validateStringVal($arPost['personal_number']);
		}
		if (!isset($arPost['start_value']))
		{
			Errors::addError('EMPTY_START_VALUE','Не указано начальное значение остатка на счёте');
		}
		else
		{
			$arData['START_VALUE'] = CoreLib\Tools::validateFloatVal($arPost['start_value']);
		}
		if (!isset($arPost['start_date']) || !$dateHelper->validateDate($arPost['start_date']))
		{
			Errors::addError('EMPTY_START_DATE','Не указана дана начала срока действия счёта');
		}
		else
		{
			$arData['START_ACTIVE'] = $dateHelper->convertDateFromDB($arPost['start_date']);
		}
		if (strlen($arPost['end_date'])==10 && $dateHelper->validateDate($arPost['end_date']))
		{
			$arData['END_ACTIVE'] = $dateHelper->convertDateFromDB($arPost['end_date']);
		}
		else
		{
			$arData['END_ACTIVE'] = NULL;
		}



		if (!Errors::issetErrors())
		{
			return self::updateDB ($updateID,$arData);
		}

		return false;
	}

	public static function checkCanDelete ($accountID=null)
	{
		if (intval($accountID)<=0)
		{
			Errors::addError('WRONG_ID','Неверный ID удаляемого объекта');
			return false;
		}
		if (!Tables\AccountTable::checkTableLinks())
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

	public static function deleteFromPost (array $arPost=array())
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

	protected static function addDB (array $arAdd)
	{
		$query = new Query('insert');
		$query->setInsertParams(
			$arAdd,
			Tables\AccountTable::getTableName(),
			Tables\AccountTable::getMapArray()
		);
		$res = $query->exec();
		if ($res->getResult())
		{
			return $res->getInsertId();
		}

		return false;
	}

	protected static function updateDB ($updateID=null, array $arUpdate)
	{
		if (is_null ($updateID))
		{
			Errors::addError('EMPTY_ID','Не указан ID изменяемого счёта');
		}
		else
		{
			$arRes = Tables\AccountTable::getList(
				array(
					'filter'=>array('ID'=>$updateID),
					'limit' => 1
				)
			);
			if ($arRes && isset($arRes[0]))
			{
				$arRes = $arRes[0];
			}
			if (!$arRes)
			{
				Errors::addError('NOT_ISSET_UPDATE_ID','Не найдена указанная запись');
			}
			else
			{
				unset($arRes['ID']);
				foreach ($arRes as $field=>$value)
				{
					if (isset($arUpdate[$field]))
					{
						if (!is_null($arUpdate[$field]))
						{
							if ($arUpdate[$field]===$arRes[$field])
							{
								unset($arUpdate[$field]);
							}
						}
						else
						{
							if (is_null($arRes[$field]))
							{
								unset($arUpdate[$field]);
							}
						}
					}
				}
			}
		}

		if (!Errors::issetErrors())
		{
			if (!empty($arUpdate))
			{
				$query = new Query('update');
				$query->setUpdateParams(
					$arUpdate,
					$updateID,
					Tables\AccountTable::getTableName(),
					Tables\AccountTable::getMapArray()
				);
				$res = $query->exec();
				if ($res->getResult())
				{
					return true;
				}
				else
				{
					Errors::addError('UPDATE_ERROR','Ошибка сохранения данных');
					return false;
				}
			}
		}

		return !Errors::issetErrors();
	}

	protected static function deleteDB ($deleteID=null, $confirm=false)
	{
		if (!is_null($deleteID))
		{
			$query = new Query('delete');
			$query->setDeleteParams(
				intval($deleteID),
				$confirm,
				Tables\AccountTable::getTableName(),
				Tables\AccountTable::getMapArray(),
				Tables\AccountTable::getTableLinks()
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