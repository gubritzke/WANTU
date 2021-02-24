<?php
namespace Application\Validate;

/**
 * @NAICHE | Vitor Deco
 */
class ValidateExperienciaProfissionalAnalise extends ValidateAbstract 
{
	public static function etapanew($params)
	{
		$validate = new \Naicheframework\Helper\Validate();
		
		try
		{

			$required = ["objetivos_profissionais"];
			if( !$validate::isArrayNotEmpty($params, $required) )
			{
				throw new \Exception(self::ERROR_OBJETIVO_PROFISSIONAIS);
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