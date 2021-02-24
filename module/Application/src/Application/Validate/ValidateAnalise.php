<?php
namespace Application\Validate;

/**
 * @NAICHE | Vitor Deco
 */
class ValidateAnalise extends ValidateAbstract 
{
	public static function etapa1($params)
	{
		$validate = new \Naicheframework\Helper\Validate();
		
		try
		{
			//obrigatórios
// 			$required = ["data_de_nascimento", "sexo", "nome_mae", "nome_pai", "estado_civil", "filhos", "nacionalidade", "cep", "endereco", "numero", "bairro", "telefone_celular"];
// 			if( !$validate::isArrayNotEmpty($params, $required) )
// 			{
// 				throw new \Exception(self::ERROR_REQUIRED);
// 			}

			$required = ["data_de_nascimento"];
			if( !$validate::isArrayNotEmpty($params, $required) )
			{
				throw new \Exception(self::ERROR_DATA_DE_NASCIMENTO);
			}
			
			$required = ["sexo"];
			if( !$validate::isArrayNotEmpty($params, $required) )
			{
				throw new \Exception(self::ERROR_SEXO);
			}
			
			$required = ["estado_civil"];
			if( !$validate::isArrayNotEmpty($params, $required) )
			{
				throw new \Exception(self::ERROR_ESTADO);
			}
			
			if( !in_array($params["filhos"], [0,1,2,3,4,'outro']) )
			{
				throw new \Exception(self::ERROR_FILHOS);
			}
			
			$required = ["nacionalidade"];
			if( !$validate::isArrayNotEmpty($params, $required) )
			{
				throw new \Exception(self::ERROR_NACIONALIDADE);
			}
			
			$required = ["cep"];
			if( !$validate::isArrayNotEmpty($params, $required) )
			{
				throw new \Exception(self::ERROR_CEP);
			}
			
			$required = ["endereco"];
			if( !$validate::isArrayNotEmpty($params, $required) )
			{
				throw new \Exception(self::ERROR_ENDERECO);
			}
			
			$required = ["numero"];
			if( !$validate::isArrayNotEmpty($params, $required) )
			{
				throw new \Exception(self::ERROR_NUMERO);
			}
			
			$required = ["bairro"];
			if( !$validate::isArrayNotEmpty($params, $required) )
			{
				throw new \Exception(self::ERROR_BAIRRO);
			}
			
			$required = ["cidade"];
			if( !$validate::isArrayNotEmpty($params, $required) )
			{
				throw new \Exception(self::ERROR_CIDADE);
			}
			
			$required = ["telefone_celular"];
			if( !$validate::isArrayNotEmpty($params, $required) )
			{
				throw new \Exception(self::ERROR_TELEFONE_CELULAR);
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