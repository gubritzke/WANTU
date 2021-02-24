<?php
namespace Application\Validate;

/**
 * @NAICHE | Vitor Deco
 */
class ValidateCompetencias extends ValidateAbstract 
{
	public static function competencias($params)
	{
		$validate = new \Naicheframework\Helper\Validate();
		
		try
		{
			$required = ["p1"];
			if( !$validate::isArrayNotEmpty($params, $required) )
			{
				throw new \Exception(self::ERROR_P1);
			}
			
			$required = ["p2"];
			if( !$validate::isArrayNotEmpty($params, $required) )
			{
			    throw new \Exception(self::ERROR_P2);
			}
			
			$required = ["p3"];
			if( !$validate::isArrayNotEmpty($params, $required) )
			{
			    throw new \Exception(self::ERROR_P3);
			}
			
			$required = ["p4"];
			if( !$validate::isArrayNotEmpty($params, $required) )
			{
			    throw new \Exception(self::ERROR_P4);
			}
			
			$required = ["p5"];
			if( !$validate::isArrayNotEmpty($params, $required) )
			{
			    throw new \Exception(self::ERROR_P5);
			}
			
			$required = ["p6"];
			if( !$validate::isArrayNotEmpty($params, $required) )
			{
			    throw new \Exception(self::ERROR_P6);
			}
			
			$required = ["p7"];
			if( !$validate::isArrayNotEmpty($params, $required) )
			{
			    throw new \Exception(self::ERROR_P7);
			}
			
			$required = ["p8"];
			if( !$validate::isArrayNotEmpty($params, $required) )
			{
			    throw new \Exception(self::ERROR_P8);
			}
			
			$required = ["p9"];
			if( !$validate::isArrayNotEmpty($params, $required) )
			{
			    throw new \Exception(self::ERROR_P9);
			}
			
			$required = ["p10"];
			if( !$validate::isArrayNotEmpty($params, $required) )
			{
			    throw new \Exception(self::ERROR_P10);
			}
			
			$required = ["p11"];
			if( !$validate::isArrayNotEmpty($params, $required) )
			{
			    throw new \Exception(self::ERROR_P11);
			}
			
			$required = ["p12"];
			if( !$validate::isArrayNotEmpty($params, $required) )
			{
			    throw new \Exception(self::ERROR_P12);
			}
			
			$required = ["p13"];
			if( !$validate::isArrayNotEmpty($params, $required) )
			{
			    throw new \Exception(self::ERROR_P13);
			}
			
			$required = ["p14"];
			if( !$validate::isArrayNotEmpty($params, $required) )
			{
			    throw new \Exception(self::ERROR_P14);
			}
			
			$required = ["p15"];
			if( !$validate::isArrayNotEmpty($params, $required) )
			{
			    throw new \Exception(self::ERROR_P15);
			}
		
		} catch( \Exception $e ){
			
			$validate->setError('error', $e->getMessage());
		}
		
		//retornar JSON
		if( $validate->isAjax() ) $validate->json();
		
		//retornar true ou mensagem
		return ( !$validate->isValid() ) ? $validate->current() : true;
	}
}