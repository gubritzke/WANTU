<?php
namespace Application\Classes;

/**
 * @NAICHE | Deco
 * Classe que auxilia no gerenciamento das notificações para os usuários
 */
class Notification 
{
	const TIPO_JOB = "job";
	const TIPO_CADASTRO = "cadastro";
	const TIPO_CADASTRO_CLIENTE = "cadastro_cliente";
	
	public static function cadastroAdminNovoConsultor($id_remetente, $id_tipo)
	{
		$message = 'Novo cadastro realizado aguardando aprovação.';
		
		$data = array();
		$data['id_remetente'] = $id_remetente;
		$data['id_tipo'] = $id_tipo;
		$data['tipo'] = self::TIPO_CADASTRO;
		$data['descricao'] = $message;
		return self::insert($data);
	}
	
	public static function cadastroAdminEdicaoConsultor($id_remetente, $id_tipo, $replace)
	{
		$message = 'Cadastro atualizado (menu {alterado}) e aguardando aprovação.';
	
		$data = array();
		$data['id_remetente'] = $id_remetente;
		$data['id_tipo'] = $id_tipo;
		$data['tipo'] = self::TIPO_CADASTRO;
		$data['descricao'] = self::replace($message, $replace);
		return self::insert($data);
	}
	
	public static function cadastroAdminNovoCliente($id_remetente, $id_tipo)
	{
		$message = 'Novo cadastro de cliente realizado aguardando aprovação.';
	
		$data = array();
		$data['id_remetente'] = $id_remetente;
		$data['id_tipo'] = $id_tipo;
		$data['tipo'] = self::TIPO_CADASTRO;
		$data['descricao'] = $message;
		return self::insert($data);
	}

	public static function cadastroAdminNovoClienteApp($id_remetente, $id_tipo, $app)
	{
		$message = 'Novo cadastro de cliente  realizado no app ' . $app . ' aguardando aprovação.';
	
		$data = array();
		$data['id_remetente'] = $id_remetente;
		$data['id_tipo'] = $id_tipo;
		$data['tipo'] = self::TIPO_CADASTRO_CLIENTE;
		$data['descricao'] = $message;
		return self::insert($data);
	}
	
	public static function projetoAdminNovo($id_remetente, $id_tipo)
	{
		$message = 'Novo projeto iniciado aguardando consultores.';
		
		$data = array();
		$data['id_remetente'] = $id_remetente;
		$data['id_tipo'] = $id_tipo;
		$data['tipo'] = self::TIPO_JOB;
		$data['descricao'] = $message;
		return self::insert($data);
	}
	
	public static function projetoConsultorNovo($id_remetente, $id_tipo, $id_destinatario)
	{
		$message = 'Novo projeto iniciado aguardando sua cotação.';

		$data = array();
		$data['id_remetente'] = $id_remetente;
		$data['id_destinatario'] = $id_destinatario;
		$data['id_tipo'] = $id_tipo;
		$data['tipo'] = self::TIPO_JOB;
		$data['descricao'] = $message;
		return self::insert($data);
	}
	
	public static function projetoNovaMensagem($id_remetente, $id_tipo, $id_destinatario=0)
	{
		$message = 'Nova(s) mensagem(s) no projeto.';
	
		$data = array();
		$data['id_remetente'] = $id_remetente;
		$data['id_destinatario'] = $id_destinatario;
		$data['id_tipo'] = $id_tipo;
		$data['tipo'] = self::TIPO_JOB;
		$data['descricao'] = $message;
		return self::insert($data);
	}
	
	public static function get($id_destinatario=0)
	{
		$requestApi = new \Naicheframework\RequestApi\HttpPost();
		 
		//seleciona as notificações
		$filter = array();
		$filter['id_destinatario'] = $id_destinatario;
		$filter['lido'] = 0;
		$filter['order'] = 'criado DESC';
		$result = $requestApi->post($filter, 'notificacao')->getResult();
		 
		//loop nos dados para definir alguns campos
		foreach( $result as $row )
		{
			$row->remetente_nome = !empty($row->remetente_nome) ? $row->remetente_nome : "Easy4People";
		}
		
		//echo'<pre>'; print_r($result); exit;
		return $result;
	}
	
	//'id_remetente', 'id_destinatario','id_tipo','tipo','descricao','lido','criado','modificado'
    private static function insert($data)
    {
    	$requestApi = new \Naicheframework\RequestApi\HttpPost();
    	
    	//verifica se existe uma notificação igual
    	$filter = $data;
    	$filter['lido'] = 0;
    	$result = $requestApi->post($filter, 'notificacao')->getResult();
    	
    	//adiciona a notificação
    	if( !count($result) )
    	{
    		$result = $requestApi->post($data, 'notificacao/save')->getResult();
    	}
    	
    	return $result;
    }
    
    private static function replace($message, $replace)
    {
    	//array search
    	$search = array();
    	foreach( array_keys($replace) as $value ) $search[] = '{' . $value . '}';
    	 
    	//replace
    	$message = str_replace($search, $replace, $message);
    	 
    	//trim
    	$message = trim($message);
    	 
    	return $message;
    }
}