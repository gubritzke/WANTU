<?php
namespace Application\Classes;

/**
 * @NAICHE | Deco
 * Class with all messages
 */
class MailMessage extends \Naicheframework\Email\Mail
{
	private $url = null;
	
	private $sms = null;

	private $email_admin = array();
	
	public function __construct($config=array(), $debug=false)
	{
		//define se é pra debugar
		$this->debug = $debug;
		
		//define a url base
		$this->url = 'http://'.$_SERVER["HTTP_HOST"];
		
		//instancia da class SMS
		$this->sms = new \Naicheframework\Sms\Sms('kaique', '123');
		
		$this->addFrom = 'no-reply@wantu.com.br';
		
		//construct parent
		parent::__construct($config);
	}
    
    public function sendMessage($to, $subject, $replace, $celphones=null)
    {
    	
    	$message = '<p>Email de teste em ' . date('d/m/Y H:i:s') . '</p>';
    
    	//assunto
    	$this->setSubject = $subject;
    
    	//to
    	$this->addTo = $to;

    	//send
    	return $this->sendReplace($replace, $celphones);
    	
    }
    
    public function cadastroSucesso($to, $replace)
    {
    	$message = '
            <p><b>Olá, {nome}</b></p>
            <p>Seu cadastro foi efetuado com sucesso!</p>
            <p><a href="http://'.$_SERVER['HTTP_HOST'].'">Clique aqui</a> para acessar seu painel.</p>
		';
    	
    	$this->setSubject = 'Cadastro Wantu - ' . $this->setSubject;
    	$this->addTo = $to;
    	return $this->sendReplace($message, $replace);
    }
    
    public function planoPago($to, $replace)
    {
    	$message = '
            <p><b>Olá, {nome}</b></p>
            <p>Seu pagamento para o plano foi efetuado com sucesso!</p>
            <p><a href="http://'.$_SERVER['HTTP_HOST'].'">Clique aqui</a> para acessar seu painel.</p>
		';
    	
    	$this->setSubject = 'Seu plano Wantu! - ' . $this->setSubject;
    	$this->addTo = $to;
    	return $this->sendReplace($message, $replace);
    }
    
    public function testesConcluidos($to, $replace)
    {
        $message = '
            <p><b>Olá, o usuário {nome}</b></p>
            <p>Concluiu toda a trilha Wantu</p>
            <p><a href="http://'.$_SERVER['HTTP_HOST'].'">Clique aqui</a> para acessar seu painel.</p>
		';
        
        $this->setSubject = 'Trilha concluída - ' . $this->setSubject;
        $this->addTo = $to;
        return $this->sendReplace($message, $replace);
    }
    
    public function planoCancelado($to, $replace)
    {
    	$message = '
            <p><b>Olá, {nome}</b></p>
            <p>Houve um problema com o pagamento para o plano!</p>
            <p><a href="http://'.$_SERVER['HTTP_HOST'].'">Clique aqui</a> para acessar seu painel.</p>
		';
    	
    	$this->setSubject = 'Plano Cancelado - ' . $this->setSubject;
    	$this->addTo = $to;
    	return $this->sendReplace($message, $replace);
    }
    
    public function esqueciSenhaCliente($to, $replace, $cellphones = null){
    	
    	
    	$message = '
            <p><b>Olá, </b></p>
            <p><a href="http://'.$_SERVER['HTTP_HOST'].'/login/recuperar-senha/?slug={link}">Clique aqui</a> para alterar a senha.</p>
		';
    	
    	$this->setSubject = 'Alteração de Senha - ' . $this->setSubject;
    	$this->addTo = $to;
    	//echo '<pre>'; print_r($this->addTo); exit;
    	
    	return $this->sendReplace($message, $replace);
    }
    
    public function contato($to, $replace, $cellphones = null){
        
        
        $message = '
            <p><b>Nome:</b> {nome} </p>
            <p><b>Email:</b> {email} </p>
            <p><b>Telefone:</b> {telefone} </p>
            <p><b>Assunto:</b> {assunto} </p>
            <p><b>mensagem:</b> {mensagem} </p>
		';
        
        $this->setSubject = 'Novo Contato - ' . $this->setSubject;
        $this->addTo = $to;
        //echo '<pre>'; print_r($this->addTo); exit;
        
        return $this->sendReplace($message, $replace);
    }
    
    public function contatoParaEmpresa($to, $replace, $cellphones = null){
        
        
        $message = '
            <p><b>Nome:</b> {nome} {sobrenome} </p>
            <p><b>Email:</b> {email} </p>
            <p><b>Telefone:</b> {telefone} </p>
            <p><b>Empresa:</b> {empresa} </p>
            <p><b>Colaboradores:</b> {colaboradores} </p>
            <p><b>Vagas:</b> {vagas} </p>
            <p><b>Gestao competências:</b> {gestao_competencias} </p>
            <p><b>Competências utilizadas:</b> {competencias_utilizadas} </p>
            <p><b>Estado:</b> {estado} </p>
            <p><b>Área de atuação:</b> {area_de_atuacao} </p>
		';
        
        $this->setSubject = 'Contato Para Empresas - ' . $this->setSubject;
        $this->addTo = $to;
        //echo '<pre>'; print_r($this->addTo); exit;
        
        return $this->sendReplace($message, $replace);
    }
    
    private function sendReplace($message, $replace)
    {

    	//array replace add items
    	//$replace['url'] = $this->url;
    	
    	//array search
    	$search = array();
    	foreach( array_keys($replace) as $value ) $search[] = '{' . $value . '}';
    	
    	//replace
    	$message = str_replace($search, $replace, $message);
    	
    	//trim
    	$message = trim($message);
    	
    	
    	return $this->send($message);
    	
    }
    
    function sanitizeString( $string ) {
        
        $string = strtolower($string);
        // Código ASCII das vogais
        $ascii['a'] = range(224, 230);
        $ascii['e'] = range(232, 235);
        $ascii['i'] = range(236, 239);
        $ascii['o'] = array_merge(range(242, 246), array(240, 248));
        $ascii['u'] = range(249, 252);
        
        // Código ASCII dos outros caracteres
        $ascii['b'] = array(223);
        $ascii['c'] = array(231);
        $ascii['d'] = array(208);
        $ascii['n'] = array(241);
        $ascii['y'] = array(253, 255);
        
        foreach ($ascii as $key=>$item) {
            $acentos = '';
            foreach ($item AS $codigo) $acentos .= chr($codigo);
            $troca[$key] = '/['.$acentos.']/i';
        }
        
        $string = preg_replace(array_values($troca), array_keys($troca), $string);
        
        // Slug?
        if ($slug) {
            // Troca tudo que não for letra ou número por um caractere ($slug)
            $string = preg_replace('/[^a-z0-9]/i', $slug, $string);
            // Tira os caracteres ($slug) repetidos
            $string = preg_replace('/' . $slug . '{2,}/i', $slug, $string);
            $string = trim($string, $slug);
        }
        
        return $string;
        
    }
    
    protected function createHTML($msg)
    {
    	
    	$html  = '<table border="0" cellspacing="0" style="font-family:\'Arial\'; font-size:14px; color:#777; margin:auto; margin-top:50px; border:1px solid #eaeaea;">';
    	$html .= '<tr>';
    	$html .= '<td style="width:600px; background-color:#84139e; padding:20px; text-align:center;">';
    	$html .= '<a href="http://'.$_SERVER["HTTP_HOST"].'">';
    	$html .= '<img src="http://'.$_SERVER["HTTP_HOST"].'/assets/application/img/logo/logo-white.png" height="20px" />';
    	$html .= '</a>';
    	$html .= '</td>';
    	$html .= '</tr>';
    	$html .= '<tr>';
    	$html .= '<td style="width:600px; background-color:#fff; padding:30px; font-size:14px; border-bottom:15px solid #84139e;">';
    	$html .= $msg;
    	$html .= '</td>';
    	$html .= '</tr>';
    	$html .= '</table>';
    
    	return $html;
    	
    }
}