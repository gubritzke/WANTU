<?php
namespace Application\Validate;

use Application\Model\ModelLogin;

/**
 * @NAICHE | Vitor Deco
 */
class ValidateUsuario extends ValidateAbstract 
{
	public static function cadastro($params)
	{
		$validate = new \Naicheframework\Helper\Validate();
		
		try
		{
			//obrigatórios
			$required = ["nome", "email", "cpf", "senha"];
			if( !$validate::isArrayNotEmpty($params, $required) )
			{
				throw new \Exception(self::ERROR_REQUIRED);
			}
			
			if( !$validate::isCPF($params["cpf"]) )
			{
				throw new \Exception(self::ERROR_CPF);
			}
			
    	    //senha confirmar
    		if( $params['senha'] != $params['senha_confirmar'] )
    		{
    			throw new \Exception(self::ERROR_SENHA_CONFIRMAR);
    		}
    		
    		//e-mail duplicidade
    		if( self::validateEmailDuplicate($params['email']) )
    		{
    			throw new \Exception(self::ERROR_EMAIL_DUPLICIDADE);
    		}
    		
    		//e-mail duplicidade
    		if( self::validateCpfDuplicate($params['cpf']) )
    		{
    			throw new \Exception(self::ERROR_CPF_DUPLICIDADE);
    		}
			
		} catch( \Exception $e ){
			
			$validate->setError('error', $e->getMessage());
		}
		
		//retornar JSON
		if( $validate->isAjax() ) $validate->json();
		
		//retornar true ou mensagem
		return ( !$validate->isValid() ) ? $validate->current() : true;
	}
	
	public static function validateEmailDuplicate($email)
	{
	    //selecionar no banco
		$model = new ModelLogin(self::$tb, self::$adapter);
	    $where = "email = '" . $email . "'";
	    $count = $model->get(['where' => $where])->count();
	    
	    //return bool
	    return $count ? true : false;
	}
	
	public static function validateCpfDuplicate($cpf)
	{
		//selecionar no banco
		$model = new ModelLogin(self::$tb, self::$adapter);
		$where = "cpf = '" . $cpf . "'";
		$count = $model->get(['where' => $where])->count();
		
		//return bool
		return $count ? true : false;
	}
}