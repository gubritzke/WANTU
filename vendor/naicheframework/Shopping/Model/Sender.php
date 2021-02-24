<?php
namespace Naicheframework\Shopping\Model;

/**
 * modelo do remetente
 * @author: Vitor Deco
 */
class Sender extends Address
{
    protected $nome;
    protected $documento;
    protected $ie;
    protected $telefone;
    protected $email;
    protected $nascimento;
    
    public function getNome()
    {
    	$name = $this->nome;
        
        //evitar bugs no nome
        $name = preg_replace('/\d/', '', $name);
        $name = preg_replace('/[\n\t\r]/', ' ', $name);
        $name = preg_replace('/\s(?=\s)/', '', $name);
        $name = trim($name);
        
    	return $name;
    }
    
    public function setNome($value)
    {
    	$this->nome = $value;
    }
    
    public function getDocumento($number_only = false)
    {
        if( $number_only )
        {
            $this->documento = str_replace([".","/","-"], "", $this->documento);
        }
        
    	return $this->documento;
    }
    
    public function setDocumento($value)
    {
        $this->documento = $value;
    }
    
    public function getDocumentoTipo()
    {
        $document = str_replace([".","/","-"], "", $this->documento);
        $length = strlen($document);
        return ($length == 14) ? "CNPJ" : "CPF";
    }
    
    public function getIe()
    {
        return $this->ie;
    }
    
    public function setIe($value)
    {
        $this->ie = $value;
    }
    
    public function getTelefoneDDD()
    {
        $result = str_replace(['(',')','-',' '], '', $this->telefone);
        return (int)substr($result, 0, 2);
    }
    
    public function getTelefoneNumber()
    {
        $result = str_replace(['(',')','-',' '], '', $this->telefone);
        return (int)substr($result, 2);
    }
    
    public function getTelefone($number_only = false)
    {
        if( $number_only )
        {
            $this->telefone = str_replace(['(',')','-',' '], '', $this->telefone);
        }
        
        return $this->telefone;
    }
    
    public function setTelefone($value)
    {
        $this->telefone = $value;
    }
    
    public function getEmail()
    {
        return $this->email;
    }
    
    public function setEmail($value)
    {
        $this->email = $value;
    }
    
    public function getNascimento()
    {
        return !empty($this->nascimento) ? $this->nascimento : "1990-07-16";
    }
    
    public function setNascimento($value)
    {
        $this->nascimento = $value;
    }
}