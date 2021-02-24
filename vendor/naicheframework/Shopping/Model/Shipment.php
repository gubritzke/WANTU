<?php
namespace Naicheframework\Shopping\Model;

/**
 * @author: Vitor Deco
 */
class Shipment extends ModelAbstract
{
	/**
	 * tipo de serviço utilizado: Super Rápido, Rápido, Econômico
	 * @var string
	 */
    protected $servico;
    
    /**
     * valor retornado
     * @var number
     */
    protected $valor;
    
    /**
     * prazo retornado
     * @var int
     */
    protected $prazo;
    
    /**
     * mensagem de erro retornado ou alguma observação
     * @var string
     */
    protected $observacao;
    
    /**
     * quantidade de pacotes que foram agrupados
     * @var int
     */
    protected $quantidade;
    
    public function setServico($value)
    {
    	$this->servico = $value;
    }
    public function getServico()
    {
        return $this->servico;
    }
    public function checkServico()
    {
        return !empty($this->servico) ? true : false;
    }
    public function getServicoApi()
    {
        return mb_strtoupper(strtok($this->servico, ' '));
    }
    
    public function getValor()
    {
    	return $this->valor;
    }
    public function setValor($value)
    {
    	$this->valor = !empty($value) ? $value : 0;
    }
    
    public function getPrazo()
    {
    	return $this->prazo;
    }
    public function setPrazo($value)
    {
    	$this->prazo = $value;
    }
    
    public function getObservacao()
    {
    	return $this->observacao;
    }
    public function setObservacao($value)
    {
    	$this->observacao = $value;
    }
    
    public function getQuantidade()
    {
    	return $this->quantidade;
    }
    public function setQuantidade($value)
    {
    	$this->quantidade = (int)$value;
    }
}