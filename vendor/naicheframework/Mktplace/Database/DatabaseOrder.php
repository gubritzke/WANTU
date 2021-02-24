<?php
namespace Naicheframework\Mktplace\Database;

use Naicheframework\Mktplace\Model\ModelOrder;
use Naicheframework\Mktplace\Model\ModelAddress;

/**
 * @author: Vitor Deco
 */
class DatabaseOrder 
{
    /**
     * definir API para realizar consultas no banco de dados
     */
    private $database;
    
    /**
     * construtor informando a API de consultar o banco de dados
     * @param $database
     */
    public function __construct( $database )
    {
        //definir a API
        $this->database = $database;
    }
    
    public function save( ModelOrder $order )
    {
        try
        {
            //adicionar informações do banco de dados ao pedido
            $order = $this->addDatabaseInfo($order);
            
            //definir informações do pagamento
            $payments = $order->getPaymentsAsArray();
            
            //definir informações do cliente
            $customer = $order->getCustomer();
            
            //definir endereço de entrega e cobrança
            $endereco = array();
            
            $address = $order->getShippingAddress();
            $endereco['entrega'] = $this->convertAddressToDatabaseFormat($address);
            
            $address = $order->getBillingAddress();
            $endereco['cobranca'] = $this->convertAddressToDatabaseFormat($address);
            
            //inserir pedido no banco de dados
            $pedido = array();
            $pedido['id_usuario'] = 0;
            $pedido['id_cupom'] = 0;
            $pedido['total'] = $order->getTotalOrdered();
            $pedido['desconto_total'] = 0;
            $pedido['endereco'] = json_encode($endereco);
            $pedido['frete_api'] = null;
            $pedido['frete_total'] = $order->getShippingCost();
            $pedido['frete_tipo'] = $order->getShippingMethod();
            $response = $this->database->call("pedido/save", $pedido)->result();
            if( empty($response->id) ) throw new \Exception('Houve um problema ao salvar no banco de dados!');
            $id_pedido = $response->id;
            
            //loop em todos os itens do pedido
            foreach( $order->getItems() as $item )
            {
                //inserir item do pedido no banco de dados
                $data = array();
                $data['id_pedido'] = $id_pedido;
                $data['id_pedido_status'] = $order->getOrderStatus();
                $data['id_produto_loja'] = $item->getProductId();
                $data['preco'] = $item->getPrice();
                $data['quantidade'] = $item->getQty();
                $data['descricao'] = $item->getName();
                $data['comissao'] = $item->getExtraByKey('comissao');
                $data['frete_tipo'] = $order->getShippingMethod();
                $data['frete_valor'] = null; //@todo Naiche - não temos o valor individual do frete
                $data['frete_prazo'] = $order->getEstimatedDeliveryTime();
                $response = $this->database->call("pedido_item/save", $data)->result();
                if( empty($response->id) ) throw new \Exception('Houve um problema ao salvar no banco de dados!');
                $id_pedido_item = $response->id;
                
                //remover item do estoque
                $where = "produto_estoque.id_produto_loja = '" . $item->getProductId() . "' AND produto_estoque.status = 'ativo'";
                $limit = $item->getQty();
                $estoque = $this->database->call("produto_estoque/select", ["where" => $where, "limit" => $limit], "GET")->result();
                $ids = array_column($estoque, "id_produto_estoque", "id_produto_estoque");
                
                $data = array();
                $data["status"] = "vendido";
                $data["where"] = "id_produto_estoque IN(" . implode(',', $ids) . ")";
                $response = $this->database->call("produto_estoque/update", $data)->result();
            }
            
            //inserir pagamento no banco de dados
            $data = array();
            $data['id_pedido'] = $id_pedido;
            $data['codigo'] = $order->getCode();
            $data['tipo'] = 'SkyHub';
            $data['gateway'] = 'SkyHub';
            $data['desconto'] = 0;
            $data['info'] = json_encode($payments);
            $data['status'] = $order->getPaymentStatus();
            $response = $this->database->call("pagamento/save", $data)->result();
            if( empty($response->id) ) throw new \Exception('Houve um problema ao salvar no banco de dados!');
            $id_pagamento = $response->id;
            
            //inserir mktplace no banco de dados
            $data = array();
            $data['id_pedido'] = $id_pedido;
            $data['cliente_nome'] = $customer->getName();
            $data['cliente_email'] = $customer->getEmail();
            $data['cliente_nascimento'] = $customer->getDateOfBirth();
            $data['cliente_doc'] = $customer->getVatNumber();
            $data['cliente_telefone'] = $customer->getPhoneFirst();
            $data['cliente_sexo'] = $customer->getGender();
            $data['mktplace_codigo'] = $order->getCode();
            $data['mktplace_nome'] = $order->getChannel();
            $data['mktplace_status'] = $order->getStatus()->getType();
            $data['mktplace_sync'] = $order->getSyncStatus();
            $response = $this->database->call("pedido_mktplace/save", $data)->result();
            if( empty($response->id) ) throw new \Exception('Houve um problema ao salvar no banco de dados!');
            $id_pedido_mktplace = $response->id;
            
            //retorna o codigo gerado no marketplace
            return $order->getCode();
            
        } catch( \Exception $e ){
            
            throw new \Exception($e->getMessage(), $e->getCode());
            
        }
    }
    
    private function convertAddressToDatabaseFormat( ModelAddress $address )
    {
        $array = array();
        $array["id_endereco"] = null;
        $array["tipo"] = null;
        $array["identificacao"] = null;
        $array["remetente"] = null;
        $array["cep"] = $address->getPostcode();
        $array["logradouro"] = $address->getStreet();
        $array["numero"] = $address->getNumber();
        $array["complemento"] = $address->getDetail();
        $array["bairro"] = $address->getNeighborhood();
        $array["cidade"] = $address->getCity();
        $array["estado"] = $address->getRegion();
        return $array;
    }
    
    /**
     * adicionar informações extras nos itens dos pedidos com base nas informações do banco de dados
     * @param ModelOrder $modelOrder
     * @return ModelOrder
     */
    private function addDatabaseInfo( ModelOrder $modelOrder )
    {
        //definir os ids dos itens do pedido
        $ids = $modelOrder->getItemIds();
        
        //selecionar os produtos
        $order = 'produto_loja.id_loja DESC';
        $where = 'produto_loja.id_produto_loja IN(' . implode(',', $ids) . ')';
        $produtos = $this->database->call("produto_loja/select", ["where" => $where, "order" => $order], "GET")->result();
        
        //loop nos produtos
        foreach( $produtos as $produto )
        {
            //selecionar o item
            $item = $modelOrder->getItemById($produto->id_produto_loja);
            
            //adicionar informações extras
            $item->setExtra($produto);
        }
        
        return $modelOrder;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}