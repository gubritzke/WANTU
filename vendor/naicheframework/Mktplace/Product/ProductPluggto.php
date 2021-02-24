<?php
namespace Naicheframework\Mktplace\Product;
use Naicheframework\Mktplace\Model\ModelProduct;
use Naicheframework\Mktplace\Auth\AuthSkyhub;
use Naicheframework\Api\Request;
use Naicheframework\Mktplace\Auth\AuthAbstract;
use Naicheframework\Mktplace\Auth\AuthPluggto;
use Naicheframework\Mktplace\Model\Pluggto\ModelPluggtoProduct;

/**
 * @author: Vitor Deco
 */
class ProductPluggto extends ProductAbstract
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
     * retornar um produto específico
     * @param string $sku
     */
    public function get( $sku )
    {
        try
        {
            //executar CURL
            $url = "skus/" . $sku . "?access_token=" . $this->access_token;
            $response = $this->api->call($url, [], "GET")->result();
            $response = $response->Product;
            //echo'<pre>'; print_r($response); exit;
            
            //verificar se retornou algum erro
            $this->checkHttpResponse();
            
            //model product
            $model = new ModelPluggtoProduct();
            $result = $model->populate($response);
            
            return $result;
            
        } catch( \Exception $e ){
            
            throw new \Exception($e->getMessage(), $e->getCode());
            
        }
    }
    
    /**
     * retornar uma lista de produtos
     * 
     * Paging
     * next - Insert the last id found in the previus call
     * limit - Insert the number or registers to be returned
     * 
     * Filters
     * created - Check from creation date (ie 2017-01-01to2017-02-01)
     * modified - Check from update date. (ie 2017-01-01to2017-02-01)
     * available - Check if is available in your app
     * all - Search a string (like) in name or description field
     * sku - Search by sku (exact) sku in parent or child
     * bysku - Search by sku (like) in parent or child
     * category - Search by string in category field (like)
     * attribute - Search by attribute code or attribute value field (exact)
     * id - Search by pluggto id in parent or child
     * has_external - See if has your application product id
     * quantity - Search by quantity
     * others - you can search by any other api propriety, like "brand=MyBrand"
     */
    public function list($limit=100, $next=null)
    {
        try
        {
            //filters
            $filters = array(
                'limit' => $limit,
                'next' => $next,
            );
            $qrystring = http_build_query(array_filter($filters));
            
            //executar CURL
            $url = "products?access_token=" . $this->access_token . "&" . $qrystring;
            $response = $this->api->call($url, [], "GET")->result();
            
            //verificar se retornou algum erro
            $this->checkHttpResponse();
    
            //loop nos resultados
            foreach( $response->result as &$row )
            {
                $model = new ModelPluggtoProduct();
                $row = $model->populate($row->Product);
            }
            
            return $response;
    
        } catch( \Exception $e ){
    
            throw new \Exception($e->getMessage(), $e->getCode());
    
        }
    }
    
    /**
     * criar um produto
     * @param ModelPluggtoProduct $product
     */
    public function create( ModelPluggtoProduct $product )
    {
        try 
        {
            //definir parâmetros
            $params = $product->toArrayClean();
            
            //executar CURL
            $url = "skus/" . $product->getSku() . "?access_token=" . $this->access_token;
            $response = $this->api->call($url, $params, "PUT")->result();
            $response = $response->Product;
            
            //verificar se retornou algum erro
            $this->checkHttpResponse();
            
            return $response;
            
        } catch( \Exception $e ){
			
			throw new \Exception($e->getMessage(), $e->getCode());
			
		}
    }
    
    /**
     * atualizar um produto
     * @param ModelPluggtoProduct $product
     */
    public function update( ModelPluggtoProduct $product )
    {
        try
        {
            //definir parâmetros
            $params = $product->toArrayClean();
            
            //executar CURL
            $url = "skus/" . $product->getSku() . "?access_token=" . $this->access_token;
            $response = $this->api->call($url, $params, "PUT")->result();
            $response = $response->Product;
            
            //verificar se retornou algum erro
            $this->checkHttpResponse();
            
            return $response;
            
        } catch( \Exception $e ){
            	
            throw new \Exception($e->getMessage(), $e->getCode());
            	
        }
    }
    
    /**
     * deletar um produto
     * @param string $sku
     */
    public function delete( $sku )
    {
        try
        {
            //executar CURL
            $url = "skus/" . $sku . "?access_token=" . $this->access_token;
            $response = $this->api->call($url, [], "DELETE")->result();
    
            //verificar se retornou algum erro
            $this->checkHttpResponse();
    
            return $response;
    
        } catch( \Exception $e ){
    
            throw new \Exception($e->getMessage(), $e->getCode());
    
        }
    }
    
    /**
     * sincronizar um produto fazendo a verificação se é para salvar, atualizar ou deletar
     * @param ModelPluggtoProduct $product
     */
    public function synchronize( ModelPluggtoProduct $product )
    {
        try
        {
            //verificar se é pra remover
            if( $product->getStatus() == "removed" )
            {
                return $this->delete($product->getSku());
            }
            
            //criar ou atualizar
            return $this->update($product);
        
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