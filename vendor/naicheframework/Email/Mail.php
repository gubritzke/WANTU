<?php
namespace Naicheframework\Email;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;

/**
 * @NAICHE | Vitor Deco
 * Class to send mail
 * 
 * Example: 
 * $email = new Email(['addTo'=>'email@domain.com']);
 * $email->send('message');
 */
abstract class Mail
{
    //config
    //config
	public $addTo		 = null;
	public $addCc        = null;
	public $addBcc       = null;
	public $addReplyTo   = null;
	public $addFrom      = 'no-reply@wantu.com.br';
	public $setSubject   = 'Wantu';
	public $setTitle   	 = null;
	public $setBody      = null;
	
	//ambiente de teste
	public $is_sandbox 	 = false;
	public $addTest 	 = null;
	
	//config SMTP
	private $name        = 'leadsmanager';
	private $host        = 'leadsmanager.com.br';
	private $port        =  587;
	private $connClass   = 'login';
	private $username    = 'noreply@leadsmanager.com.br';
	private $password    = 'ETrvwvCezhr9';
	private $ssl         = 'tls';
    
    //debug
    protected $debug	 = false;
    
    public function __construct($config = array())
    {
    	//configurações do smtp
    	foreach( $config as $k => $v )
    	{
    		$this->{$k} = $v;
    	}
    }
    
	protected function send($msg) 
    {
		
		try {
			//echo '<pre>'; print_r($this->addFrom); exit;
			//trim
			$msg = trim($msg);
			
			//debug
			if( $this->debug === true )
			{
				echo $this->createHTML($msg); 
				exit;
			}
			
			//ambiente
			if( $this->is_sandbox )
			{
				$this->addTo = $this->addTest;
				$this->addCc = null;
				$this->addBcc = null;
			}
			
	        //set message with HTML
	        $this->setMessage($msg);

	        //zend mail
	        $message = new Message();
	        
	        //zend mail - set configs
	        $message->addTo( $this->addTo );
	        $message->addFrom( $this->addFrom, $this->name );
	        $message->setSubject( $this->setSubject );
	        $message->setBody( $this->setBody );
			$message->setEncoding('UTF-8'); //Encoding da mensagem
			
	        //zend mail - optionals
	        if( !empty($this->addCc) )
	            $message->addCc( $this->addCc );
	        
	        if( !empty($this->addBcc) )
	            $message->addBcc( $this->addBcc );
	        
	        if( !empty($this->addReplyTo) )
	            $message->addReplyTo( $this->addReplyTo );
	        
            $connection_config = [];
            
            if ( $this->ssl == 'tls' || $this->ssl == 'ssl' ){
            	
            	$connection_config = [
            		'username'      => $this->username,
            		'password'      => $this->password,
            		'ssl'           => $this->ssl
            	];
            	
            } else {
            	
            	$connection_config = [
            			'username'      => $this->username,
            			'password'      => $this->password
            	];
            	
            }
            
	        //SMTP transport - with login authentication
	        $transport = new SmtpTransport();
	        $options   = new SmtpOptions(array(
	        	'name'              => $this->name,
	        	'host'              => $this->host,
	        	'port'		        => $this->port,
	        	'connection_class'  => $this->connClass,
	        	'connection_config' => $connection_config
	        ));
	        $transport->setOptions($options);
	        $transport->send($message);
	    
	        return true;
	        
		} catch (\Exception $e){
	    	return $e->getMessage();
	    }
    }
    
	protected function setMessage($msg = null, $type = 'html')
    {
    	//Somente em html
		if ( $type == 'html' )
		{
	    	$content = new MimePart($this->createHTML($msg));
	    	$content->type = "text/html";
	    	$content->setCharset('utf8');    
	    	
	    //Em texto
		} else {
	    	$StripTags = new \Zend\Filter\StripTags();
	    	$content = new MimePart($StripTags->filter($msg));
	    	$content->type = "text/plain";
	    	$content->setCharset('utf8');
		}
    	
    	$body = new MimeMessage();
    	$body->setParts(array($content));
    	
    	return $this->setBody = $body;
    }
    
    abstract protected function createHTML($msg);
}