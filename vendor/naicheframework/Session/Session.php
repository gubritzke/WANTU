<?php
namespace Naicheframework\Session;
use Zend\Session\Container;

/**
 * @NAICHE | Vitor Deco
 */
class Session
{
    public static $token = 'base';
    
	/**
	 * set session
	 * @param string $key
	 * @param string $value
	 */
	public static function set($key, $value)
	{
		$session = new Container(self::$token);
		$session->offsetSet($key, $value);
	}
	
	/**
	 * get session
	 * @param string $key
	 * @param boolean $unset
	 */
	public static function get($key, $unset=false)
	{
		$session = new Container(self::$token);
		$result = $session->offsetGet($key);
		if( $unset ) $session->offsetUnset($key);
		return !empty($result) ? $result : false;
	}
	
	/**
	 * unset session
	 * @param string $key
	 */
	public static function unset($key)
	{
		$session = new Container(self::$token);
		return $session->offsetUnset($key);
	}
	
	/**
	 * has session
	 * @param string $key
	 */
	public static function has($key)
	{
		$session = new Container(self::$token);
		return $session->offsetExists($key);
	}
}