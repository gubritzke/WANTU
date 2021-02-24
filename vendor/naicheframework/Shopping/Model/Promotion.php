<?php
namespace Naicheframework\Shopping\Model;

/**
 * @author: Vitor Deco
 */
class Promotion extends ModelAbstract
{
    protected $id;
    protected $titulo;
    protected $quantidade;
    protected $valor;
	
    /**
     * @var Item
     */
    protected $item = array();
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setId($value)
    {
        $this->id = $value;
    }
    
    public function getTitulo()
    {
        return $this->titulo;
    }
    
    public function setTitulo($value)
    {
        $this->titulo = $value;
    }
    
    public function getQuantidade()
    {
        return $this->quantidade;
    }
    
    public function setQuantidade($value)
    {
        $this->quantidade = $value;
    }
    
	public function getValor()
    {
    	return $this->valor;
    }
    
    public function setValor($value)
    {
    	$this->valor = $value;
    }
    
    /**
     * adicionar um item
     * @param Item $item
     */
    public function addItem(Item $item)
    {
        $this->item[] = $item;
    }
    
    /**
     * @return Item
     */
    public function getItem()
    {
        return $this->item;
    }
    
    /**
     * deletar um item
     */
    public function delItem($value)
    {
        foreach( $this->item as $key=>$item )
        {
            if( $item->getId() == $value )
            {
                unset($this->item[$key]);
                break;
            }
        }
    }
    
    /**
     * contar itens
     * @return number
     */
    public function countItem()
    {
        return count($this->item);
    }
    
    /**
     * retorna o valor de cada item
     * @return number
     */
    public function getValorDoItem()
    {
        return ($this->valor / $this->quantidade);
    }
}