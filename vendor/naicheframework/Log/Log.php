<?php
namespace Naicheframework\Log;

class Log
{
    private static $path = null;
	private static $user = null;
	private static $error = null;
	
	public static function error($message, $var=array(), $dir=null)
	{
	    $dir = !empty($dir) ? $dir : null;
	    $dir .= "error/" . date("Y/m/d");
		$var['type'] = 'ERROR';
		$var['message'] = $message;
		self::register($var, $dir);
	}
	
	public static function debug($message, $var=array(), $dir=null)
	{
	    $dir = !empty($dir) ? $dir : null;
	    $dir .= "debug/" . date("Y/m/d");
		$var['type'] = 'DEBUG';
		$var['message'] = $message;
		self::register($var, $dir);
		self::checkError();
	}
	
	public static function setUser($user)
	{
		self::$user = $user;
	}
	
	public static function setPath($path)
	{
	    self::$path = $path;
	}
	
	public static function checkError()
	{
		$error = error_get_last();
		if( serialize(self::$error) != serialize($error) )
		{
			self::$error = $error;
			self::error('retorno da função error_get_last()', $error);
		}
	}
	
	public static function register($var=array(), $dir=null)
	{
		//estrutura padrão de data
		if( empty($dir) ) $dir = date("Y/m/d");
		
		//cria o caminho de onde irá salvar o log
		$path = self::$path . $dir . "/";
		$path = str_replace(['public/', 'public_html/'], '', $path);
		if( !is_dir($path) ) mkdir($path, 0777, true);
		
		//cria o arquivo ou utiliza um existente
		$filename = date('Y-m-d') . ".txt";
		$file = fopen($path . $filename, "a+");
		
		//real ip
		$client  = !empty($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : null;
		$forward = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : null;
		$remote  = $_SERVER['REMOTE_ADDR'];
		$ip = filter_var($client, FILTER_VALIDATE_IP) ? $client : (filter_var($forward, FILTER_VALIDATE_IP) ? $forward : $remote);
		
		//add informações
		$var['page'] = strtok($_SERVER["REQUEST_URI"], '?');
		$var['date'] = date('Y-m-d H:i:s');
		$var['ip'] = $ip;
		if( !empty($_GET) ) $var['get'] = $_GET;
		if( !empty($_POST) ) $var['post'] = $_POST;
		if( !empty(self::$user) ) $var['user'] = self::$user;
		$string = json_encode($var) . PHP_EOL;
		
		//escreve e fecha o arquivo
		fwrite($file, $string);
		fclose($file);
	}
}