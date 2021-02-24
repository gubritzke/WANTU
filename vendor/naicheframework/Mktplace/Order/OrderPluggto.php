<?php
namespace Naicheframework\Mktplace\Order;
use Naicheframework\Mktplace\Auth\AuthSkyhub;
use Naicheframework\Api\Request;
use Naicheframework\Mktplace\Model\ModelOrder;
use Naicheframework\Mktplace\Model\ModelShipment;

/**
 * @author: Vitor Deco
 */
class OrderPluggto extends OrderAbstract
{
    /**
     * definir lista de retorno de status http
     */
    const STATUS_200 = "Requisição executada com sucesso";
    const STATUS_ERROR = "Houve um problema durante a requisição, tente novamente";
    
    /**
     * definir API para realizar as requisições
     * @var Request
     */
    private $api;
    
    /**
     * definir Access Token para autenticar as requisições
     * @var string
     */
    private $access_token;
    
    /**
     * construtor informando a autenticação
     * @param AuthPluggto $auth
     */
    public function __construct( AuthPluggto $auth )
    {
        //definir API
        $this->api = $auth->getApi();
        
        //definir Access Token
        $this->access_token = $auth->getAccessToken();
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
    public function get()
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