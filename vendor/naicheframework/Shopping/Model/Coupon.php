<?php
namespace Naicheframework\Shopping\Model;

/**
 * @author: Vitor Deco
 */
class Coupon extends ModelAbstract
{
    protected $cupom;
    protected $valor;
    protected $tipo; //fixo ou porcentagem
    protected $items = array(); //ids
    
    public function getCupom()
    {
        return mb_strtoupper($this->cupom);
    }

    public function setCupom($cupom)
    {
        $this->cupom = $cupom;
    }

    public function getValor()
    {
        return $this->valor;
    }

    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    public function getTipo()
    {
        return $this->tipo;
    }

    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }
    
    public function getItems()
    {
        return $this->items;
    }
    
    public function setItems(array $value)
    {
        $this->items = $value;
    }
    
    public function addItem($value)
    {
        $this->items[] = (int)$value;
    }
    
    /**
     * verificar se o item está incluso no cupom
     * @param int $value
     * @return boolean
     */
    public function checkItem(int $value)
    {
        $cond1 = !count($this->getItems());
        $cond2 = in_array($value, $this->getItems());
        return $cond1 || $cond2;
    }
    
    /**
     * retornar o valor de acordo com o tipo do cupom
     * @return string
     */
    public function getValorString()
    {
        //verificar se é porcentagem
        if( $this->getTipo() == "porcentagem" )
        {
            //retornar valor em porcentagem
            return $this->getValor() . "%";
            
        } else {
            
            //retornar valor em R$
            return "R$ " . \Naicheframework\Helper\Convert::toReal($this->getValor());
        }
    }
    
    /**
     * calcular o valor do desconto
     * @param number $preco
     * @return number
     */
    public function getDesconto($preco)
    {
        //definir o desconto
        $desconto = $this->getValor();
        
        //verificar se é porcentagem
        if( $this->getTipo() == "porcentagem" )
        {
            //aplicar a porcentagem de desconto sobre o preço
            $desconto = ($preco * $desconto / 100);
        }
        
        return $desconto;
    }
}