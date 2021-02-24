<?php
namespace Naicheframework\Shopping\Service;
use Naicheframework\Shopping\Model\OrderMultiple;
use Naicheframework\Shopping\Shipment\ShipmentAbstract;
use Naicheframework\Shopping\Model\ShipmentMultiple;
use Naicheframework\Shopping\Model\Shipment;
use Naicheframework\Shopping\Model\Item;

/**
 * @author: Vitor Deco
 */
class ServicePackage
{
    /**
     * aplicar as regras de frete individualmente para cada seller
     * 
     * @param OrderMultiple $orderMultiple
     * @return \Naicheframework\Shopping\Model\OrderMultiple
     */
    public function aplicarRegrasNoFrete(OrderMultiple $orderMultiple)
    {
        //echo'<pre>'; print_r($orderMultiple); exit;
        
        //verificar se exister regras de frete
        if( $orderMultiple->checkShipmentRules() )
        {
            //loop em todos os pedidos por seller
            foreach( $orderMultiple->getOrder() as $order )
            {
                //loop em todas as regras
                foreach( $orderMultiple->getShipmentRules() as $rule )
                {
                    //verificar condição de CEP
                    $cond1 = !$rule->checkCepRange() ? true : false;
                    if( ($cond1 == false) )
                    {
                        foreach( $rule->getCepRange() as $cep )
                        {
                            $cep = explode("-", $cep);
                            if( ($order->getDestinatario()->getCep() >= $cep[0]) && ($order->getDestinatario()->getCep() <= $cep[1]) )
                            {
                                $cond1 = true;
                            }
                        }
                    }
                    
                    //verificar condição de valor mínimo
                    $cond2 = !$rule->checkGastoMinimo() ? true : false;
                    if( ($cond2 == false) && ($order->getTotal() >= $rule->getGastoMinimo()) )
                    {
                        $cond2 = true;
                    }
                    
                    //verificar condição de peso mínimo
                    $cond3 = !$rule->checkPesoMinimo() ? true : false;
                    if( ($cond3 == false) && ($order->getPesoMax() >= $rule->getPesoMinimo()) )
                    {
                        $cond3 = true;
                    }
                    
                    //verificar condição de peso máximo
                    $cond4 = !$rule->checkPesoMaximo() ? true : false;
                    if( ($cond4 == false) && ($order->getPesoMax() <= $rule->getPesoMaximo()) )
                    {
                        $cond4 = true;
                    }
                    
                    //verificar condição de loja
                    $cond5 = !$rule->checkLoja() ? true : false;
                    if( ($cond5 == false) && in_array($order->getGroup(), $rule->getLoja()) )
                    {
                        $cond5 = true;
                    }
                    
                    //verificar condição de produto
                    $cond6 = !$rule->checkProduto() ? true : false;
                    if( ($cond6 == false) && count(array_intersect($rule->getProduto(), $order->getItemIds())) )
                    {
                        $cond6 = true;
                    }
                    
                    //verificar se todas as condições são válidas
                    if( $cond1 && $cond2 && $cond3 && $cond4 && $cond5 && $cond6 )
                    {
                        //loop em todos os pacotes do pedido
                        foreach( $order->getPackage() as $package )
                        {
                            //verificar TIPO da regra
                            if( in_array($rule->getTipo(), [$rule::TIPO_FIXO_DESCONTO, $rule::TIPO_PORCENTAGEM_DESCONTO]) )
                            {
                                //recuperar entrega com o menor valor
                                $shipmentItem = $package->getShipments()->getItemByPrecoMin();
                                
                                //verificar se é do tipo desconto porcentagem
                                if( $rule->getTipo() == $rule::TIPO_PORCENTAGEM_DESCONTO )
                                {
                                    $valor = $shipmentItem->getValor() - ($shipmentItem->getValor() * $rule->getValor() / 100);
                                }
                                
                                //verificar se é do tipo desconto fixo
                                if( $rule->getTipo() == $rule::TIPO_FIXO_DESCONTO )
                                {
                                    $valor = ($shipmentItem->getValor() > $rule->getValor()) ? ($shipmentItem->getValor() - $rule->getValor()) : 0;
                                }
                                
                                //definir valor
                                $shipmentItem->setValor($valor);
                                
                            } else {
                            
                                //verificar se já existe o serviço de entrega
                                $jaExiste = $package->getShipments()->getItemByService($rule->getServico())->checkServico();
                                
                                if( !$jaExiste )
                                {
                                    //adicionar método de entrega
                                    $shipmentItem = new Shipment();
                                    $shipmentItem->setServico($rule->getServico());
                                    $shipmentItem->setValor($rule->getValor());
                                    $shipmentItem->setPrazo($package->getShipments()->getItemByPrazoMax()->getPrazo());
                                    $package->getShipments()->addItem($shipmentItem);
                                }
                                
                            }
                        }
                    }
                }
            }
        }
        
        return $orderMultiple;
    }
    
    /**
     * calcular frete de todos os pacotes de todos os grupos
     *
     * @param OrderMultiple $orderMultiple
     * @param ShipmentAbstract[] $shipments
     * @return OrderMultiple
     */
    public function calcularFretes(OrderMultiple $orderMultiple)
    {
        //loop nos pedidos
        foreach( $orderMultiple->getOrder() as $order )
        {
            //definir remetente e destinatário
            $sender = $order->getRemetente();
            $recipient = $order->getDestinatario();
            
            //validar CEP do remetente e destinatário
            if( empty($sender->getCep()) || empty($recipient->getCep()) ) continue;
            
            //loop nos pacotes
            foreach( $order->getPackage() as $package )
            {
                //definir adição no prazo de entrega
                $tempo_manuseio = 0;
                foreach( $package->getId() as $id )
                {
                    $item = $order->getItemById($id);
                    if( $item->getTempoManuseio() > $tempo_manuseio )
                    {
                        $tempo_manuseio = $item->getTempoManuseio();
                    }
                }
                
                //loop nas APIs de entrega
                foreach( $order->getShipmentApis() as $shipmentApi )
                {
                    try 
                    {
                        //instanciar serviço de entrega
                        $serviceShipment = $shipmentApi->factory();
                        
                        //calcular o frete usando a API que está ativa CORREIO/MANDAE/OUTRA
                        $result = $serviceShipment->calcularFrete($package, $sender, $recipient);
                        
                        //loop nos itens
                        foreach( $result->getItens() as $item )
                        {
                            //adicionar dias no prazo de entrega
                            if( !empty($tempo_manuseio) )
                            {
                                $item->setPrazo( $item->getPrazo() + $tempo_manuseio );
                            }
                            
                            $package->getShipments()->addItem($item);
                        }
                        
                    } catch( \Exception $e ){
                        
                        \Naicheframework\Log\Log::error($e->getMessage());
                        continue;
                        
                    }
                }
            }
        }
        
        //verificar regras de entrega
        $orderMultiple = $this->aplicarRegrasNoFrete($orderMultiple);
        
        //ordenar fretes pelo preço
        $orderMultiple->orderShipmentItemByPrice();
        
        return $orderMultiple;
    }
	
	/**
	 * agrupar fretes de todos os pacotes de todos os grupos
	 * 
	 * @param OrderMultiple $orderMultiple
	 * @return ShipmentMultiple
	 */
	public function agruparFretes(OrderMultiple $orderMultiple)
	{
		//definir resultado
		$shipmentMultiple = new ShipmentMultiple();
		
		//loop nos pedidos
		foreach( $orderMultiple->getOrder() as $order )
		{
		    //loop nos pacotes
			foreach( $order->getPackage() as $package )
			{
			    //loops nos itens de entrega
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
						$item->setQuantidade(1);
						$shipmentMultiple->addItem($item);
						
					} else {
						
						//definir novo prazo
						$prazo = ($item->getPrazo() >= $row->getPrazo()) ? $item->getPrazo() : $row->getPrazo();
						
						//definir novo valor
						$valor = ($item->getValor() + $row->getValor());
						
						//definir a quantidade de pacotes
						$quantidade = ($item->getQuantidade() + 1);
						
						//atualizar item
						$item->setPrazo($prazo);
						$item->setValor($valor);
						$item->setQuantidade($quantidade);
					}
				}
			}
		}
		
		//remover os serviços que não foram retornados para todos os pacotes
		$countPackage = $orderMultiple->countPackage();
		foreach( $shipmentMultiple->getItens() as $index => $shipment )
		{
			if( $countPackage != $shipment->getQuantidade() )
			{
				$shipmentMultiple->delItem($index);
			}
		}
		
		//verificar se é pra add o serviço CONVENCIONAL
		if( !$orderMultiple->checkShipmentConvencional() )
		{
		    //criar serviço de entrega convencional contendo os menores preços de todos os pacotes
		    $item = new Shipment();
		    $item->setServico('Convencional');
		    
		    //loops nos pedidos
		    foreach( $orderMultiple->getOrder() as $order )
		    {
		        //loop nos pacotes
    			foreach( $order->getPackage() as $package )
    			{
    			    //recuperar o item de entrega com preço mínimo 
    			    $itemPrecoMin = $package->getShipments()->getItemByPrecoMin();
    			    
    		        //definir novo prazo
    		        $prazo = ($item->getPrazo() >= $itemPrecoMin->getPrazo()) ? $item->getPrazo() : $itemPrecoMin->getPrazo();
    		        $item->setPrazo($prazo);
    		        
    		        //definir novo valor
    		        $valor = ($item->getValor() + $itemPrecoMin->getValor());
    		        $item->setValor($valor);
    			}
		    }
		    
		    $shipmentMultiple->addItem($item);
		}
		
		//ordenar itens da entrega pelo valor
		$shipmentMultiple->orderShipmentItemByPrice();
		
		//retornar os itens da entrega
		return $shipmentMultiple;
	}
	
}