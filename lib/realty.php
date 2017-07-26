<?php

namespace MSergeev\Packages\Homerent\Lib;

use MSergeev\Core\Entity\Query;
use MSergeev\Core\Lib as CoreLib;
use MSergeev\Packages\Homerent\Tables;

class Realty
{
	/**
	 * @var array Массив полей таблицы Объектов недвижимости
	 */
	private static $tableFields = array(
		'ID',
		'ACTIVE',
		'SORT',
		'NAME',
		'ADDRESS',
		'DESCRIPTION'
	);

	public static function addFromPost (array $arPost)
	{
		$arData = array();
		if (!isset($arPost['name']))
		{
			Errors::addError('EMPTY_NAME','Не заполнено поле "Название"');
			return false;
		}
		else
		{
			$arData['NAME'] = CoreLib\Tools::validateStringVal($arPost['name']);
		}
		if (isset($arPost['address']))
		{
			$arData['ADDRESS'] = CoreLib\Tools::validateStringVal($arPost['address']);
		}
		if (isset($arPost['description']))
		{
			$arData['DESCRIPTION'] = CoreLib\Tools::validateStringVal($arPost['description']);
		}

		return self::addDB($arData);
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

		$arRes = Tables\RealtyTable::getList($arList);
		if ($arRes && intval($arList['limit'])==1 && isset($arRes[0]))
		{
			$arRes = $arRes[0];
		}

		return $arRes;
	}

	public static function showListTable ()
	{
		$arList = static::getList(null,false);
		//msDebug($arList);
		if ($arList)
		{
			echo '<div id="realtyList"></div><div id="realtyPager"></div>';

			$imgSrcPath = CoreLib\Tools::getSitePath(CoreLib\Loader::getTemplate('homerent')."images/");

			//msDebug($arList);
			$arDatas = array();
			foreach ($arList as $list)
			{
				$arDatas[] = array(
					'id' => $list['ID'],
					'name' => $list['NAME'],
					'active' => ($list['ACTIVE'])?'Да':'Нет',
					'sort' => $list['SORT'],
					'address' => !is_null($list['ADDRESS'])?addslashes(CoreLib\Tools::cropString($list['ADDRESS'])):'',
					'address_full' => !is_null($list['ADDRESS'])?addslashes($list['ADDRESS']):'',
					'description' => !is_null($list['DESCRIPTION'])?addslashes(CoreLib\Tools::cropString($list['DESCRIPTION'])):'',
					'edit' => "<a class='table_button' href='edit.php?id=".$list['ID']."'><img src='".$imgSrcPath."edit.png'></a>",
					'delete' => "<a class='table_button' href='delete.php?id=".$list['ID']."'><img src='".$imgSrcPath."delete.png'></a>"
				);
			}

			$webixHelper = new CoreLib\WebixHelper();

			$webixHelper->addFunctionSortByTimestamp();

			$arData = array(
				'grid' => 'realtyGrid',
				'container' => 'realtyList',
				'tooltip' => true,
				'pager' => array('container'=>'realtyPager'),
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
						'id' => 'address',
						'header' => 'Адрес',
						'tooltip' => '#address_full#'
					)),
					$webixHelper->getColumnArray('STRING',array(
						'id' => 'description',
						'header' => 'Описание'
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
			echo 'Объекты недвижимости еще не были созданы';
			return false;
		}
	}

	public static function updateFromPost (array $arPost)
	{
		$arData = array();
		if (!isset($arPost['name']))
		{
			Errors::addError('EMPTY_NAME','Не заполнено поле "Название"');
			return false;
		}
		else
		{
			$arData['NAME'] = CoreLib\Tools::validateStringVal($arPost['name']);
		}
		if (!isset($arPost['id']) || intval($arPost['id'])<=0)
		{
			Errors::addError('EMPTY_ID','Не указан ID редактируемой записи');
			return false;
		}
		else
		{
			$updateID = intval($arPost['id']);
		}
		if (isset($arPost['active']))
		{
			$arData['ACTIVE'] = CoreLib\Tools::validateBoolVal($arPost['active']);
		}
		if (isset($arPost['sort']))
		{
			$arData['SORT'] = CoreLib\Tools::validateIntVal($arPost['sort']);
		}
		if (isset($arPost['address']))
		{
			$arData['ADDRESS'] = CoreLib\Tools::validateStringVal($arPost['address']);
		}
		if (isset($arPost['description']))
		{
			$arData['DESCRIPTION'] = CoreLib\Tools::validateStringVal($arPost['description']);
		}
		$arRealty = self::getList($updateID);
		foreach ($arRealty as $key=>$value)
		{
			if (isset($arData[$key]) && $arRealty[$key] === $arData[$key] && !is_null($value))
			{
				unset($arData[$key]);
			}
		}
		if (!empty($arData))
		{
			return self::updateDB($updateID, $arData);
		}
		else
		{
			return true;
		}
	}

	public static function deleteFromPost (array $arPost)
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

	public static function checkCanDelete ($realtyID)
	{
		if (intval($realtyID)<=0)
		{
			Errors::addError('WRONG_ID','Неверный ID удаляемого объекта');
			return false;
		}
		if (!Tables\RealtyTable::checkTableLinks())
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

	public static function showSelectRealty ($value='null',$field1='class="form-control"')
	{
		$arRealty = self::getList();
		$arValues = array();
		foreach ($arRealty as $ar_realty)
		{
			$arValues[] = array(
				'NAME' => '['.$ar_realty['ID'].'] '.$ar_realty['NAME'],
				'VALUE' => $ar_realty['ID']
			);
		}

		return SelectBox('realty',$arValues,'--Выбрать--',$value,$field1);
	}

	protected static function addDB (array $arData)
	{
		if (empty($arData))
		{
			return false;
		}
		else
		{
			$query = new Query('insert');
			$query->setInsertParams(
				$arData,
				Tables\RealtyTable::getTableName(),
				Tables\RealtyTable::getMapArray()
			);
			$res = $query->exec();
			if ($res->getResult())
			{
				return $res->getInsertId();
			}
			else
			{
				return false;
			}
		}
	}

	protected static function updateDB ($updateID, array $arUpdate)
	{
		if (intval($updateID)<=0)
		{
			Errors::addError('WRONG_ID','Неверное значение ID редактируемого объекта');
			return false;
		}

		$query = new Query('update');
		$query->setUpdateParams(
			$arUpdate,
			$updateID,
			Tables\RealtyTable::getTableName(),
			Tables\RealtyTable::getMapArray()
		);
		$res = $query->exec();

		return $res->getResult();
	}

	protected static function deleteDB ($deleteID, $confirm=false)
	{
		$query = new Query('delete');
		$query->setDeleteParams(
			$deleteID,
			$confirm,
			Tables\RealtyTable::getTableName(),
			Tables\RealtyTable::getMapArray(),
			Tables\RealtyTable::getTableLinks()
		);
		$res = $query->exec();

		if ($res->getResult())
		{
			return true;
		}
		else
		{
			Errors::addError('NOT_DELETE','Произошла непредвиденная ошибка при попытке удаления объекта недвижимости');
			return false;
		}
	}
}