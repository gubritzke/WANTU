<?php
namespace Application\Validate;

/**
 * @NAICHE | Vitor Deco
 */
class ValidateDocumentosAnalise extends ValidateAbstract 
{
	public static function etapa2($params)
	{
		$validate = new \Naicheframework\Helper\Validate();
		
		try
		{

			$required = ["rg"];
			if( !$validate::isArrayNotEmpty($params, $required) )
			{
				throw new \Exception(self::ERROR_RG);
			}
			
			$required = ["orgao_emissor"];
			if( !$validate::isArrayNotEmpty($params, $required) )
			{
				throw new \Exception(self::ERROR_ORGAO_EMISSOR);
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