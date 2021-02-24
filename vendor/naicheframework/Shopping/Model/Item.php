<?php
namespace Naicheframework\Shopping\Model;

/**
 * @author: Vitor Deco
 */
class Item extends ModelAbstract
{
	protected $id;
	protected $id_item;
	protected $ref;
	protected $ean;
	protected $descricao;
	protected $obs;
	protected $imagem;
	protected $preco;
	protected $quantidade;
	protected $peso;
	protected $comissao;
	protected $promocao;
	protected $desconto;
	protected $tempo_manuseio;
	protected $nfe_chave;
	protected $vendedor;
	protected $frete_api;
	protected $frete_tipo;
	protected $frete_prazo;
	protected $frete_valor;
	protected $frete_rastreio;
	protected $status;
	protected $status_motivo;
	
	public function getId()
	{
		return $this->id;
	}
	
	public function setId($value)
	{
		$this->id = $value;
	}

	public function getIdItem()
	{
	    return $this->id_item;
	}
	
	public function setIdItem($value)
	{
	    $this->id_item = $value;
	}
	
	public function getRef()
	{
	    return $this->ref;
	}
	
	public function setRef($value)
	{
	    $this->ref = $value;
	}
	
	public function getEan()
	{
		return $this->ean;
	}
	
	public function setEan($value)
	{
		$this->ean = $value;
	}

	public function getDescricao()
	{
	    return $this->descricao;
	}
	
	public function setDescricao($value)
	{
	    $this->descricao = $value;
	}

	public function getObs()
	{
	    return $this->obs;
	}
	
	public function setObs($value)
	{
	    $this->obs = $value;
	}
	public function getImagem()
	{
	    return $this->imagem;
	}
	
	public function setImagem($value)
	{
	    $this->imagem = $value;
	}
	
	public function getPreco()
	{
		return $this->preco;
	}
	
	public function setPreco($value)
	{
		$this->preco = $value;
	}

	public function getQuantidade()
	{
		return !empty($this->quantidade) ? $this->quantidade : 1;
	}
	
	public function setQuantidade($value)
	{
		$this->quantidade = $value;
	}
	
	public function getPeso()
	{
		return (int)$this->peso;
	}
	
	public function setPeso($value)
	{
		$this->peso = $value;
	}

	public function getComissao()
	{
		return $this->comissao;
	}
	
	public function setComissao($value)
	{
		$this->comissao = $value;
	}
	
	public function getPromocao()
	{
	    return $this->promocao;
	}
	
	public function setPromocao($value)
	{
	    $this->promocao = $value;
	}
    
	public function getDesconto()
	{
	    return $this->desconto;
	}
	
	public function setDesconto($value)
	{
	    $this->desconto = $value;
	}
    
	public function getTempoManuseio()
	{
	    return $this->tempo_manuseio;
	}
	
	public function setTempoManuseio($value)
	{
	    $this->tempo_manuseio = $value;
	}
	
	public function getVendedor()
	{
	    return $this->vendedor;
	}
	
	public function setVendedor($value)
	{
	    $this->vendedor = $value;
	}
	
	public function getFreteApi()
	{
	    return $this->frete_api;
	}
	
	public function setFreteApi($value)
	{
	    $this->frete_api = $value;
	}
	
    public function getFreteTipo()
	{
	    return $this->frete_tipo;
	}
	
	public function setFreteTipo($value)
	{
	    $this->frete_tipo = $value;
	}
	
    public function getFretePrazo()
	{
	    return $this->frete_prazo;
	}
	
	public function setFretePrazo($value)
	{
	    $this->frete_prazo = $value;
	}
	
	public function getFreteValor()
	{
	    return $this->frete_valor;
	}
	
	public function setFreteValor($value)
	{
	    $this->frete_valor = $value;
	}

	public function getFreteRastreio()
	{
	    return $this->frete_rastreio;
	}
	
	public function setFreteRastreio($value)
	{
	    $this->frete_rastreio = $value;
	}

	public function getStatus()
	{
	    return $this->status;
	}
	
	public function setStatus($value)
	{
	    $this->status = $value;
	}

	public function getStatusMotivo()
	{
	    return $this->status_motivo;
	}
	
	public function setStatusMotivo($value)
	{
	    $this->status_motivo = $value;
	}
	
	/**
	 * calcular o subtotal multiplicando o preço pela quantidade
	 * @return number
	 */
	public function getSubtotal()
	{
	    return $this->getPreco() * $this->getQuantidade();
	}
	
	public function getNfeChave()
	{
	    return $this->nfe_chave;
	}
	
	public function setNfeChave($value)
	{
	    $this->nfe_chave = $value;
	}
	
	public function getNfeNumero()
	{
	    return substr($this->nfe_chave, 25, 9);
	}
}