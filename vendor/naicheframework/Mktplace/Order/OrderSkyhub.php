<?php
namespace Naicheframework\Mktplace\Order;
use Naicheframework\Mktplace\Auth\AuthSkyhub;
use Naicheframework\Api\Request;
use Naicheframework\Mktplace\Model\ModelOrder;
use Naicheframework\Mktplace\Model\ModelShipment;

/**
 * @author: Vitor Deco
 */
class OrderSkyhub extends OrderAbstract
{
    /**
     * definir lista de retorno de status http
     */
    const STATUS_200 = "Requisição executada com sucesso";
    const STATUS_201 = "Requisição executada com sucesso";
    const STATUS_204 = "Pedido criado com sucesso";
    const STATUS_400 = "Status não encontrado";
    const STATUS_401 = "Erro na autenticação - Não foi possível realizar a autenticação. Verifique se as credenciais de acesso estão corretas.";
    const STATUS_403 = "Operação não autorizada";
    const STATUS_404 = "Pedido não encontrado";
    const STATUS_422 = "Status inválido";
    const STATUS_ERROR = "Houve um problema durante a requisição, tente novamente";
    
    /**
     * definir API para realizar as requisições
     * @var Request
     */
    private $api;
    
    /**
     * construtor informando a autenticação
     * @param AuthSkyhub $auth
     */
    public function __construct( AuthSkyhub $auth )
    {
        //definir a API
        $this->api = $auth->getApi();
    }

    /**
     * retornar uma lista de pedidos
     */
    public function list()
    {
        try
        {
            $data = array();
            $data['page'] = 1;
            $data['per_page'] = 30;
            $data['filters']['sale_system'] = 'Marketplace';
            $data['filters']['statuses'][] = 'pending';
            
            //executar CURL
            $response = $this->api->call("orders", $data, "GET")->debug();
            
            //verificar se retornou algum erro
            $this->checkHttpResponse();
    
            return $response;
    
        } catch( \Exception $e ){
    
            throw new \Exception($e->getMessage(), $e->getCode());
    
        }
    }
    
    /**
     * retornar um pedido da fila
     * @return ModelOrder
     */
    public function queueGet()
    {
        try
        {
            //executar CURL
            $response = $this->api->call("queues/orders", [], "GET")->result();
            
            //verificar se retornou algum erro
            $this->checkHttpResponse();
            
            //model order
            $model = new ModelOrder();
            $result = $model->populate($response);
            
            return $result;
            
        } catch( \Exception $e ){
        
            throw new \Exception($e->getMessage(), $e->getCode());
        
        }
    }
    
    /**
     * excluir um pedido da fila
     * @param string $code
     * @return bool
     */
    public function queueDelete( $code )
    {
        try
        {
            //executar CURL
            $response = $this->api->call("queues/orders/" . $code, [], "DELETE")->result();
            
            //verificar se retornou algum erro
            $this->checkHttpResponse();
            
            return true;
            
        } catch( \Exception $e ){
    
            throw new \Exception($e->getMessage(), $e->getCode());
    
        }
    }
    
    /**
     * retornar um pedido específico
     * @param string $code
     * @return ModelOrder
     */
    public function get( $code )
    {
        try
        {
            //executar CURL
            $response = $this->api->call("orders/" . $code, [], "GET")->result();
            
            //verificar se retornou algum erro
            $this->checkHttpResponse();
            
            //model order
            $model = new ModelOrder();
            $result = $model->populate($response);
            
            return $result;
            
        } catch( \Exception $e ){
            
            throw new \Exception($e->getMessage(), $e->getCode());
            
        }
    }

    /**
     * OBS: NÃO VÁLIDO PARA CONTAS EM PRODUÇÃO
     *
     * criar um pedido (sandbox)
     * @param ModelOrder $order
     */
    public function create( ModelOrder $order )
    {
        try
        {
            //definir parâmetros
            $order = array_filter($order->toArray());
            $params = ['order' => $order];
            //echo'<pre>'; print_r($params); exit;
    
            //executar CURL
            $response = $this->api->call("orders", $params, "POST")->result();
    
            //verificar se retornou algum erro
            $this->checkHttpResponse();
    
            return $response;
    
        } catch( \Exception $e ){
            	
            throw new \Exception($e->getMessage(), $e->getCode());
            	
        }
    }
    
    /**
     * OBS: NÃO VÁLIDO PARA CONTAS EM PRODUÇÃO
     * 
     * aprovar um pedido específico
     * @param string $code
     */
    public function approval( $code )
    {
        try
        {
            //definir vars
            $data = [
                "status" => "payment_received",
            ];
    
            //executar CURL
            $response = $this->api->call("orders/" . $code . "/approval", $data, "POST")->result();
    
            //verificar se retornou algum erro
            $this->checkHttpResponse();
    
            return $response;
    
        } catch( \Exception $e ){
    
            throw new \Exception($e->getMessage(), $e->getCode());
    
        }
    }
    
    /**
     * faturar um pedido específico adicionando nota fiscal
     * @param string $code
     * @param string $invoice
     */
    public function invoice( $code, $invoice )
    {
        try
        {
            //definir vars
            $data = array();
            $data["status"] = "payment_received";
            $data["invoice"] = [
                "key" => $invoice
            ];
            
            //executar CURL
            $response = $this->api->call("orders/" . $code . "/invoice", $data, "POST")->result();
            
            //verificar se retornou algum erro
            $this->checkHttpResponse();
            
            return $response;
            
        } catch( \Exception $e ){
            
            throw new \Exception($e->getMessage(), $e->getCode());
            
        }
    }

    /**
     * cancelar um pedido específico
     * @param string $code
     */
    public function cancel( $code )
    {
        try
        {
            //definir vars
            $data = [
                "status" => "order_canceled",
            ];
    
            //executar CURL
            $response = $this->api->call("orders/" . $code . "/cancel", $data, "POST")->result();
    
            //verificar se retornou algum erro
            $this->checkHttpResponse();
    
            return $response;
    
        } catch( \Exception $e ){
    
            throw new \Exception($e->getMessage(), $e->getCode());
    
        }
    }
    
    /**
     * finalizar um pedido específico
     * @param string $code
     */
    public function delivery( $code )
    {
        try
        {
            //definir vars
            $data = [
                "status" => "complete",
            ];
    
            //executar CURL
            $response = $this->api->call("orders/" . $code . "/delivery", $data, "POST")->result();
    
            //verificar se retornou algum erro
            $this->checkHttpResponse();
    
            return $response;
    
        } catch( \Exception $e ){
    
            throw new \Exception($e->getMessage(), $e->getCode());
    
        }
    }
    
    /**
     * enviar dados de entrega
     * @param string $code
     * @param ModelShipment $shipment
     */
    public function shipments( $code, ModelShipment $shipment )
    {
        try
        {
            //definir vars
            $data = [
                "status" => "order_shipped",
                "shipment" => $shipment->toArrayClean(),
            ];
            
            //executar CURL
            $response = $this->api->call("orders/" . $code . "/shipments", $data, "POST")->result();
            
            //verificar se retornou algum erro
            $this->checkHttpResponse();
            
            return $response;
            
        } catch( \Exception $e ){
            
            throw new \Exception($e->getMessage(), $e->getCode());
            
        }
    }

    /**
     * obter etiqueta de frete
     * @param string $code
     */
    public function shipmentLabels( $code )
    {
        try
        {
            //executar CURL
            $response = $this->api->call("orders/" . $code . "/shipment_labels", [], "GET")->debug();
    
            //verificar se retornou algum erro
            $this->checkHttpResponse();
    
            return $response;
    
        } catch( \Exception $e ){
    
            throw new \Exception($e->getMessage(), $e->getCode());
    
        }
    }
    
    /**
     * exceção no transporte
     * @param string $code
     */
    public function shipmentException( $code, $observation )
    {
        try
        {
            //definir vars
            $date = date("Y-m-d") . "T" . date("H:i:s") . "-03:00";
            
            $data = [
                "shipment_exception" => [
                    "occurrence_date" => $date,
                    "observation" => $observation
                ]
            ];
            
            //executar CURL
            $response = $this->api->call("orders/" . $code . "/shipment_exception", $data, "POST")->result();
            
            //verificar se retornou algum erro
            $this->checkHttpResponse();
            
            return $response;
            
        } catch( \Exception $e ){
    
            throw new \Exception($e->getMessage(), $e->getCode());
    
        }
    }
    
    private function checkHttpResponse()
    {
        $http_response = http_response_code();
        if( !in_array($http_response, [200,201]) )
        {
            $message = "STATUS_" . $http_response;
            $message = isset(self::$message) ? self::$message : self::STATUS_ERROR;
            throw new \Exception($message, $http_response);
        }
    }
}