<?php
namespace Naicheframework\Shopping\Service;
use Naicheframework\Shopping\Model\OrderMultiple;
use Naicheframework\Shopping\Model\PromotionMultiple;
use Naicheframework\Shopping\Model\Promotion;
use Naicheframework\Shopping\Model\Coupon;

/**
 * @author: Vitor Deco
 */
class ServiceOrder
{
    /**
     * adiciona o desconto do cupom nos itens válidos dos pedidos
     * 
     * @param OrderMultiple $orderMultiple
     * @param Coupon $coupon
     * @return \Naicheframework\Shopping\Model\OrderMultiple
     */
    public function adicionarDescontoCupomPorItem(OrderMultiple $orderMultiple, Coupon $coupon)
    {
        //verificar se o cupom é do tipo FIXO
        if( $coupon->getTipo() == "fixo" )
        {
            $orderMultiple->setDesconto($coupon->getValor());
            
        } else {
        
            //loop nos pedidos
            foreach( $orderMultiple->getOrder() as $order )
            {
                //loop nos itens
                foreach( $order->getItem() as $item )
                {
                    //validar cupom para o item
                    if( $coupon->checkItem($item->getId()) )
                    {
                        //aplicar desconto no item
                        $item->setDesconto($coupon->getDesconto($item->getSubtotal()));
                    }
                }
            }
            
        }
        
        return $orderMultiple;
    }
    
    public function adicionarDescontoPromocaoPorItem(OrderMultiple $orderMultiple, PromotionMultiple $promotionMultiple)
    {
        //loop em todos os itens do pedido
        foreach( $orderMultiple->getOrder() as $order )
        {
            foreach( $order->getItem() as $item )
            {
                //adicionar array de promoções
                if( !empty($item->getPromocao()) )
                {
                    //inserir de acordo com a quantidade do item
                    for( $i=$item->getQuantidade(); $i>0; $i-- )
                    {
                        $promotion = $promotionMultiple->getPromotionById($item->getPromocao());
                        $promotion->addItem($item);
                    }
                }
            }
        }
        
        //loop em todas as promoções
        foreach( $promotionMultiple->getPromotion() as $promotion )
        {
            $orderMultiple = $this->adicionarDescontoPromocaoRecursivoPorItem($orderMultiple, $promotion);
        }
        
        return $orderMultiple;
    }
    
    private function adicionarDescontoPromocaoRecursivoPorItem(OrderMultiple $orderMultiple, Promotion $promotion)
    {
        //verificar se a promoção está ativa
        if( $promotion->countItem() >= $promotion->getQuantidade() )
        {
            //loop nos items
            $i = 0;
            foreach( $promotion->getItem() as $item )
            {
                //verificar se a quantidade de produtos da promoção foi alcançada
                $i++;
                if( $i > $promotion->getQuantidade() ) break; //sair do loop
                
                //calcular valor do desconto
                $desconto = ($item->getPreco() - $promotion->getValorDoItem());
                
                //definir o item para adicionar o desconto
                $itemById = $orderMultiple->getItemById($item->getId());
                
                //verificar se já existe um desconto para o item e adicionar
                if( !empty($itemById->getDesconto()) )
                {
                    $desconto = $desconto + $itemById->getDesconto();
                }
                
                //adicionar desconto para o item
                $itemById->setDesconto($desconto);
                
                //deletar item
                $promotion->delItem($item->getId());
            }
             
            //executar a função novamente
            return $this->adicionarDescontoPromocaoRecursivoPorItem($orderMultiple, $promotion);
        }
         
        return $orderMultiple;
    }
    
}