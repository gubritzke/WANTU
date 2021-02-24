<?php
namespace Naicheframework\Shopping\Model;

/**
 * @author: Vitor Deco
 */
class Order extends ModelAbstract
{
    /**
     * id do pedido
     * @var int
     */
    protected $id_pedido;
    
    /**
     * desconto do pedido
     * @var number
     */
    protected $desconto;
    
    /**
     * comissão em porcentagem
     * @var int
     */
    protected $comissao;
    
    /**
     * código do gateway de pagamento
     * @var string
     */
    protected $gateway_code;
    
	/**
	 * grupo do pedido
	 * @var string
	 */
	protected $group;
	
	/**
	 * serviço de entrega dos pacotes (Econômica, Rápida, Super Rápida)
	 * @var string
	 */
	protected $servico;
	
	/**
	 * @var Sender
	 */
	protected $remetente;
	
	/**
	 * @var Recipient
	 */
	protected $destinatario;
	
	/**
	 * array com itens/produtos do pedido
	 * @var Item
	 */
	protected $itens = array();
	
	/**
	 * array de pacotes
	 * @var Package
	 */
	protected $pacotes = array();
	
	/**
	 * array de apis de entrega
	 * @var ShipmentApi
	 */
	protected $shipment_apis = array();
	
	public function getIdPedido()
	{
	    return $this->id_pedido;
	}
	
	public function setIdPedido($value)
	{
	    $this->id_pedido = $value;
	}
	
	public function getDesconto()
	{
	    return $this->desconto;
	}
	
	public function setDesconto($value)
	{
	    $this->desconto = $value;
	}
	
	public function getComissao()
	{
	    return $this->comissao;
	}
	
	public function setComissao($value)
	{
	    $this->comissao = $value;
	}
	
	public function getGatewayCode()
	{
	    return $this->gateway_code;
	}
	
	public function setGatewayCode($value)
	{
	    $this->gateway_code = $value;
	}
	
    public function getGroup()
    {
    	return $this->group;
    }
    
    public function setGroup($value)
    {
    	$this->group = $value;
    }

    public function getServico()
    {
    	return $this->servico;
    }
    
    public function setServico($value)
    {
    	$this->servico = $value;
    }
    
	public function getRemetente()
    {
    	return $this->remetente;
    }
    
    public function setRemetente(Sender $value)
    {
    	$this->remetente = $value;
    }
    
    public function getDestinatario()
    {
    	return $this->destinatario;
    }
    
    public function setDestinatario(Recipient $value)
    {
    	$this->destinatario = $value;
    }
    
    /**
     * @return Item
     */
    public function getItem()
    {
    	return $this->itens;
    }
    
    /**
     * @return Item
     */
    public function getItemById($value)
    {
        foreach( $this->itens as $item )
    	{
    		if( $item->getId() == $value )
    		{
    			return $item;
    		}
    	}
    	
    	$item = new Item();
    	return $item;
    }
    
    /**
     * @return Item
     */
    public function getItemByPosition($value)
    {
        if( !empty($this->itens[$value]) )
        {
            return $this->itens[$value];
        }
         
        $item = new Item();
        return $item;
    }
    
    /**
     * @return array com ids
     */
    public function getItemIds()
    {
        $ids = array();
        
        foreach( $this->itens as $item )
        {
            $ids[$item->getId()] = $item->getId();
        }
         
        return $ids;
    }
    
    /**
     * @return number
     */
    public function getItemDesconto()
    {
        $desconto = 0;
        
        foreach( $this->itens as $item )
        {
            $desconto += $item->getDesconto();
        }
         
        return $desconto;
    }
    
    /**
     * @param Item $item
     */
    public function addItem(Item $item)
    {
    	$this->itens[] = $item;
    }
    
    /**
     * @return int
     */
    public function countItem()
    {
    	return count($this->itens);
    }
    
    /**
     * @return Package
     */
    public function getPackage()
    {
    	return $this->pacotes;
    }
    
    /**
     * @return Package
     */
    public function getPackageCurrent()
    {
        return current($this->pacotes);
    }
    
    /**
     * @return Package
     */
    public function getPackageById($value)
    {
    	foreach( $this->pacotes as $package )
    	{
    		if( in_array($value, $package->getId()) )
    		{
    			return $package;
    		}
    	}
    	
    	$package = new Package();
    	return $package;
    }
    
    /**
     * @param Package $package
     */
    public function addPackage(Package $package)
    {
		$this->pacotes[] = $package;
    }
    
    /**
     * @return int
     */
    public function countPackage()
    {
    	return count($this->pacotes);
    }
    
    /**
     * atualiza um pacote validando os seus limites
     * 
     * Dimensões dos pacotes
     * Peso: máx 30kg (sedex 10 é 10kg)
     * Comprimento: min. 16cm e máx. 105cm
     * Largura: min. 11cm e máx 105cm
     * Altura: min. 2cm e máx. 105cm
     * Soma máxima das dimensões para caixa: máx. 200cm
     * 
     * Dimensões de uma mala pequena
     * Altura: 55 cm
     * Largura: 36 cm
     * Comprimento: 21 cm
     * 
     * @param Package $package
     */
    public function mergePackage(Package $package)
    {
        //definir limites
        $pesoLimite = 30;
        $comprimentoLimite = 70;
        $larguraLimite = 105;
        $alturaLimite = 105;
        $somaLimite = 200;
        
    	//loop nos pacotes
    	foreach( $this->pacotes as $pacote )
    	{
    	    //verificar se é pra atualizar ou criar um novo pacote
    	    $cond1 = ($pacote->getPeso() + $package->getPeso() <= $pesoLimite);
    	    $cond2 = ($pacote->getComprimento() + $package->getComprimento() <= $comprimentoLimite);
    	    $cond3 = ($pacote->getLargura() + $package->getLargura() <= $larguraLimite);
    	    $cond4 = ($pacote->getAltura() + $package->getAltura() <= $alturaLimite);
    	    $cond5 = ($pacote->getSomaDimensoes() + $package->getSomaDimensoes() <= $somaLimite);
    		if( $cond1 && $cond2 && $cond3 && $cond4 && $cond5 )
    		{
    			$packageToMerge = $pacote;
    		}
    	}
    	
    	//atualizar o pacote existente
    	if( isset($packageToMerge) )
    	{
    		//atualizar quantidade de produtos no pacote
    		foreach( $package->getId() as $id ) $packageToMerge->addId($id);
    		
    		//somar preço dos produtos no pacote
    		$value = $packageToMerge->getValor() + $package->getValor();
    		$packageToMerge->setValor($value);
    		
    		//somar peso dos produtos no pacote
    		$value = $packageToMerge->getPesoGramas() + $package->getPesoGramas();
    		$packageToMerge->setPeso($value);
    		
    		//somar altura dos produtos no pacote
    		//$value = $packageToMerge->getAltura() + $package->getAltura();
    		//$packageToMerge->setAltura($value);
    		
    		//definir maior altura entre os produtos do pacote
    		if( $packageToMerge->getAltura() < $package->getAltura() )
    		{
    		    $packageToMerge->setAltura($package->getAltura());
    		}
    		
    		//somar largura dos produtos no pacote
    		//$value = $packageToMerge->getLargura() + $package->getLargura();
    		//$packageToMerge->setLargura($value);
    		
    		//definir maior largura entre os produtos do pacote
    		if( $packageToMerge->getLargura() < $package->getLargura() )
    		{
    		    $packageToMerge->setLargura($package->getLargura());
    		}
    		
    		//somar comprimento dos produtos no pacote
    		$value = $packageToMerge->getComprimento() + $package->getComprimento();
    		$packageToMerge->setComprimento($value);
    		
    		//definir maior comprimento entre os produtos do pacote
    		//if( $packageToMerge->getComprimento() < $package->getComprimento() )
    		//{
    		//  $packageToMerge->setComprimento($package->getComprimento());
    		//}
    		
    		//atualizar as entregas
    		$shipmentMultiple = $package->getShipments();
    		if( !empty($shipmentMultiple) && $shipmentMultiple->itemLenght() )
    		{
    			$packageToMerge->setShipments($shipmentMultiple);
    		}
    		
    	} else {
    		
    		//adicionar um novo pacote
    		$this->addPackage($package);
    		
    	}
    	
    }
    
    /**
     * @return ShipmentMultiple
     */
    public function getShipments()
    {
    	//resultado
    	$shipmentMultiple = new ShipmentMultiple();
    	
    	//loop em todas as entregas calculadas
    	foreach( $this->pacotes as $package )
    	{
    		foreach( $package->getShipments()->getItens() as $row )
    		{
    			//verificar se o serviço já existe
    			$item = $shipmentMultiple->getItemByService($row->getServico());
    			
    			if( empty($item->getServico()) )
    			{
    				//adicionar item
    				$item = new Shipment();
    				$item->setServico($row->getServico());
    				$item->setPrazo($row->getPrazo());
    				$item->setValor($row->getValor());
    				$item->setObservacao($row->getObservacao());
    				$shipmentMultiple->addItem($item);
    				
    			} else {
    				
    				//definir novo prazo
    				$value = ($item->getPrazo() >= $row->getPrazo()) ? $item->getPrazo() : $row->getPrazo();
    				$item->setPrazo($value);
    				
    				//definir novo valor
    				$value = ($item->getValor() + $row->getValor());
    				$item->setValor($value);
    			}
    		}
    	}
    	
    	return $shipmentMultiple;
    }
    
    /**
     * @return number
     */
    public function countIds()
    {
    	$count = 0;
    	foreach( $this->pacotes as $package )
    	{
    		$count += (int)$package->countIds();
    	}
    	return $count;
    }
    
    /**
     * calcular a soma total de todos os itens
     * @return float
     */
    public function getTotal()
    {
        //soma todos os valores
        $total = 0;
    
        //loop em todos os itens
        foreach( $this->getItem() as $item )
        {
            $total += $item->getPreco() * $item->getQuantidade();
        }
    
        return $total;
    }
    
    /**
     * calcular valor total do frete
     * @return float
     */
    public function getFreteTotal()
    {
        //frete total
        $frete_total = 0;
        
        //loop em todos os itens do pedido
        foreach( $this->getItem() as $item )
        {
            $frete_total += $item->getFreteValor();
        }
         
        return $frete_total;
    }
    
    /**
     * retornar o maior peso entre os itens adicionados
     * @return number
     */
    public function getPesoMax()
    {
        //peso final
        $peso_final = 0;
         
        //loop em todos os itens do pedido
        foreach( $this->getItem() as $item )
        {
            //verificar se o peso do item é maior que o peso final
            if( $item->getPeso() > $peso_final )
            {
                $peso_final = $item->getPeso();
            }
        }
         
        return $peso_final;
    }
    
    /**
     * definir quais APIs podem ser utilizadas
     * @return \Naicheframework\Shopping\Model\ShipmentApi
     */
    public function getShipmentApis()
    {
        return $this->shipment_apis;
    }
    public function addShipmentApi(ShipmentApi $value)
    {
        $this->shipment_apis[] = $value;
    }
    
    /**
     * ordenar os pacotes criados por preço
     */
    public function orderShipmentItemByPrice()
    {
        foreach( $this->getPackage() as $package )
        {
            $package->orderShipmentItemByPrice();
        }
    }
}