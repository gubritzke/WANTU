<?php
namespace Application\Validate;

use Application\Model\ModelLogin;

/**
 * @NAICHE | Vitor Deco
 */
class ValidateParaEmpresa extends ValidateAbstract 
{
	public static function paraEmpresa($params)
	{
		$validate = new \Naicheframework\Helper\Validate();
		
		try
		{
			//obrigatórios
			$required = ["nome", "email"];
			if( !$validate::isArrayNotEmpty($params, $required) )
			{
				throw new \Exception(self::ERROR_REQUIRED);
			}
    		//e-mail real
    		if( !$validate::isEmail($params['email']) )
    		{
    			throw new \Exception(self::ERROR_EMAIL_REAL);
    		}
    		
    		$required = ["telefone"];
    		if( !$validate::isArrayNotEmpty($params, $required) )
    		{
    		    throw new \Exception(self::ERROR_TELEFONE_EMPRESA);
    		}
    		
    		$required = ["empresa"];
    		if( !$validate::isArrayNotEmpty($params, $required) )
    		{
    		    throw new \Exception(self::ERROR_EMPRESA_EMPRESA);
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