<?php
namespace Application\Validate;

/**
 * @NAICHE | Vitor Deco
 */
class ValidateInformacoesComplementaresAnalise extends ValidateAbstract 
{
	public static function etapa7($params)
	{
		$validate = new \Naicheframework\Helper\Validate();
		
		try
		{

			$required = ["possui_cnh"];
			if( !$validate::isArrayNotEmpty($params, $required) )
			{
				throw new \Exception(self::ERROR_POSSUI_CNH);
			}
			
			$required = ["e_fumante"];
			if( !$validate::isArrayNotEmpty($params, $required) )
			{
				throw new \Exception(self::ERROR_E_FUMANTE);
			}
			
			$required = ["alguma_deficiencia"];
			if( !$validate::isArrayNotEmpty($params, $required) )
			{
				throw new \Exception(self::ERROR_ALGUMA_DEFICIENCIA);
			}
			
			$required = ["possui_conta_bancaria"];
			if( !$validate::isArrayNotEmpty($params, $required) )
			{
				throw new \Exception(self::ERROR_POSSUI_CONTA_BANCARIA);
			}
			
			$required = ["possui_cartao_de_credito"];
			if( !$validate::isArrayNotEmpty($params, $required) )
			{
				throw new \Exception(self::ERROR_POSSUI_CARTAO_DE_CREDITO);
			}
			
			$required = ["quantas_pessoas_moram_em_seu_domiciolio"];
			if( !$validate::isArrayNotEmpty($params, $required) )
			{
				throw new \Exception(self::ERROR_QUANTAS_PESSOAS_MORAM_EM_SEU_DOMICILIO);
			}
			
			$required = ["quantos_pessoas_trabalham_em_seu_domiciolio"];
			if( !$validate::isArrayNotEmpty($params, $required) )
			{
				throw new \Exception(self::ERROR_QUANTAS_PESSOAS_TRABALHAM_EM_SEU_DOMICILIO);
			}
			
			$required = ["como_voce_avalia_seu_convivio"];
			if( !$validate::isArrayNotEmpty($params, $required) )
			{
				throw new \Exception(self::ERROR_COMO_VOCE_AVIALIA_SEU_CONVIVIO_FAMILIAR);
			}
			
			$required = ["seu_domiciolio_e"];
			if( !$validate::isArrayNotEmpty($params, $required) )
			{
				throw new \Exception(self::ERROR_SEU_DOMICILIO_E);
			}
			
			$required = ["renda_total"];
			if( !$validate::isArrayNotEmpty($params, $required) )
			{
				throw new \Exception(self::ERROR_RENDA_TOTAL);
			}
			
			$required = ["como_voce_conheceu_a_wantu"];
			if( !$validate::isArrayNotEmpty($params, $required) )
			{
				throw new \Exception(self::ERROR_COMO_CONHECEU_O_WANTU);
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