<?php
namespace Naicheframework\Shopping\Model;

/**
 * @author: Vitor Deco
 */
class ShipmentRule extends ModelAbstract
{
    const TIPO_FIXO = 1;
    const TIPO_FIXO_DESCONTO = 2;
    const TIPO_PORCENTAGEM_DESCONTO = 3;
    
    protected $servico;
    protected $tipo = 1;
    protected $valor;
    protected $gasto_minimo = null;
    protected $peso_minimo;
    protected $peso_maximo;
    protected $cep_range = array();
    protected $loja = array();
    protected $produto = array();
    
	public function getServico()
    {
    	return $this->servico;
    }
    public function setServico($value)
    {
    	$this->servico = $value;
    }
    
    public function getTipo()
    {
        return $this->tipo;
    }
    public function setTipo($value)
    {
        $this->tipo = $value;
    }
    
    public function getValor()
    {
    	return $this->valor;
    }
    public function setValor($value)
    {
    	$this->valor = $value;
    }

    public function checkGastoMinimo()
    {
        return (bool)!is_null($this->gasto_minimo);
    }
    public function getGastoMinimo()
    {
        return $this->gasto_minimo;
    }
    public function setGastoMinimo($value)
    {
        $this->gasto_minimo = $value;
    }
    
    public function checkPesoMinimo()
    {
        return (bool)!empty($this->peso_minimo);
    }
    public function getPesoMinimo()
    {
        return $this->peso_minimo;
    }
    public function setPesoMinimo( $value )
    {
        $this->peso_minimo = $value;
    }
    
    public function checkPesoMaximo()
    {
        return (bool)!empty($this->peso_maximo);
    }
    public function getPesoMaximo()
    {
        return $this->peso_maximo;
    }
    public function setPesoMaximo( $value )
    {
        $this->peso_maximo = $value;
    }
    
    public function checkCepRange()
    {
        return count($this->cep_range) ? true : false;
    }
    public function getCepRange()
    {
        return is_array($this->cep_range) ? $this->cep_range : array();
    }
    public function setCepRange($value)
    {
        return $this->cep_range = $value;
    }
    public function addCepRange($value)
    {
        $this->cep_range[] = $value;
    }
    
    public function checkLoja()
    {
        return count($this->loja) ? true : false;
    }
    public function getLoja()
    {
        return is_array($this->loja) ? $this->loja : array();
    }
    public function setLoja($value)
    {
        return $this->loja = $value;
    }
    public function addLoja($value)
    {
        $this->loja[] = $value;
    }
    
    public function checkProduto()
    {
        return count($this->produto) ? true : false;
    }
    public function getProduto()
    {
        return is_array($this->produto) ? $this->produto : array();
    }
    public function setProduto($value)
    {
        return $this->produto = $value;
    }
    public function addProduto($value)
    {
        $this->produto[] = $value;
    }
}