<?php
/**
 * MSergeev\Packages\Homerent\Lib\Errors
 * Содержит методы обработки ошибок
 *
 * @package MSergeev\Packages\Homerent
 * @subpackage Lib
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2016 Mikhail Sergeev
 */

namespace MSergeev\Packages\Homerent\Lib;

/**
 * Class Errors
 *
 * @static
 */
class Errors
{
	/**
	 * @var array Массив ошибок и уведомлений
	 *
	 * @private
	 * @static
	 */
	private static $arErrors = array(
		'WARNING' => array(),
		'ERROR' => array()
	);

	/**
	 * Добавляет ошибку или уведомление к массиву
	 *
	 * @param string        $code   Код ошибки. При совпадении значение заменится
	 * @param string|null   $text   Текст ошибки. Либо null, если необходимо удалить ошибку
	 * @param string        $type   Тип (ERROR - ошибка, WARNING - предупреждение)
	 */
	public static function addError ($code, $text, $type='ERROR|WARNING')
	{
		if ($type=='ERROR|WARNING') $type='ERROR';

		$type=strtoupper($type);
		if (!is_null($text))
		{
			self::$arErrors[$type][$code] = $text;
		}
		else
		{
			unset(self::$arErrors[$type][$code]);
		}
	}

	/**
	 * Отобращает список ошибок и предупреждений
	 *
	 * @return string
	 */
	public static function showErrorList()
	{
		$echo = '';
		foreach (self::$arErrors as $type=>$array)
		{
			if (!empty($array))
			{
				switch ($type)
				{
					case 'WARNING':
						$echo .= "<div class=\"warning\"><ul>Предупреждения:<br>";
						break;
					case 'ERROR':
						$echo .= "<div class=\"error\"><ul>Ошибки:<br>";
						break;
				}
				foreach ($array as $key=>$value)
				{
					$echo .= '<li>['.$key.'] '.$value.'</li>';
				}
				$echo .= "</ul></div>";
			}
		}

		return $echo;
	}

	/**
	 * Возвращает TRUE, если есть зарегистрированные ошибки, FALSE - в противном случае
	 *
	 * @return bool
	 */
	public static function issetErrors ()
	{
		if (empty(self::$arErrors['ERROR']))
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	/**
	 * Возвращает TRUE, если есть зарегистрированные ошибки, FALSE - в противном случае
	 *
	 * @return bool
	 */
	public static function issetWarnings ()
	{
		if (empty(self::$arErrors['WARNING']))
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	/**
	 * Возвращает TRUE, если есть зарегистрированные ошибки или предупреждения, FALSE - в противном случае
	 *
	 * @return bool
	 */
	public static function issetWarningsAndErrors ()
	{
		if (empty(self::$arErrors['WARNING']) && empty(self::$arErrors['ERROR']))
		{
			return false;
		}
		else
		{
			return true;
		}
	}
}