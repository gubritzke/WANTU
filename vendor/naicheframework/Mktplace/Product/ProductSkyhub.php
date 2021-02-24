<?php
namespace Naicheframework\Mktplace\Product;
use Naicheframework\Mktplace\Model\ModelProduct;
use Naicheframework\Mktplace\Auth\AuthSkyhub;
use Naicheframework\Api\Request;

/**
 * @author: Vitor Deco
 */
class ProductSkyhub extends ProductAbstract
{
    /**
     * definir lista de retorno de status http
     */
    const STATUS_200 = "Sucesso - a requisição foi processada com sucesso";
    const STATUS_201 = "Criado - a requisição foi processada com sucesso e resultou em um novo recurso criado";
    const STATUS_204 = "Sem conteúdo - a requisição foi processada com sucesso e não existe conteúdo adicional na resposta";
    const STATUS_400 = "Requisição mal-formada - a requisição não está de acordo com o formato esperado. Verifique o JSON (body) que está sendo enviado";
    const STATUS_401 = "Não autenticado - os dados de autenticação estão incorretos. Verifique o cabeçalho (header) da requisição o e-mail e o token";
    const STATUS_403 = "Não autorizado - você está tentando acessar um recurso ao qual não permissão";
    const STATUS_404 = "Não encontrado - você está tentando acessar um recurso que não existe não existe na SkyHub";
    const STATUS_406 = "Formato não aceito - a SkyHub não suporta o formato de dados especificado no cabeçalho (Accept)";
    const STATUS_415 = "Formato de mídia não aceito - a SkyHub não consegue processar os dados enviados por conta de seu formato. Certifique-se do uso do charset UTF-8 (tanto no header 'Content-Type', quanto no próprio body da requisição)";
    const STATUS_422 = "Erro semântico - apesar do formato da requisição estar correto, os dados ferem alguma regra de negócio (por exemplo: transição inválida do status de pedido)";
    const STATUS_429 = "Limite de requisições ultrapassado - você fez mais requisições do que o permitido em um determinado recurso";
    const STATUS_500 = "Erro interno - ocorreu um erro no servidor da SkyHub ao tentar processar a requisição";
    const STATUS_502 = "Erro interno - ocorreu um erro no servidor da SkyHub ao tentar processar a requisição";
    const STATUS_503 = "Serviço indisponível - a API da SkyHub está temporariamente fora do ar";
    const STATUS_504 = "Timeout - a requisição levou muito tempo e não pode ser processada";
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
     * retornar um produto específico
     * @param string $sku
     */
    public function get( $sku )
    {
        try
        {
            //executar CURL
            $response = $this->api->call("products/" . $sku, [], "GET")->result();
            
            //verificar se retornou algum erro
            $this->checkHttpResponse();
            
            //model product
            $model = new ModelProduct();
            $result = $model->populate($response);
            
            return $result;
            
        } catch( \Exception $e ){
            
            throw new \Exception($e->getMessage(), $e->getCode());
            
        }
    }
    
    /**
     * retornar uma lista de produtos
     */
    public function list()
    {
        try
        {
            //executar CURL
            $response = $this->api->call("products/", [], "GET")->result();
    
            //verificar se retornou algum erro
            $this->checkHttpResponse();
    
            return $response;
    
        } catch( \Exception $e ){
    
            throw new \Exception($e->getMessage(), $e->getCode());
    
        }
    }
    
    /**
     * retornar URLs dos marketplaces
     */
    public function urls()
    {
        try
        {
            //executar CURL
            $response = $this->api->call("products/urls", [], "GET")->result();
    
            //verificar se retornou algum erro
            $this->checkHttpResponse();
    
            return $response;
    
        } catch( \Exception $e ){
    
            throw new \Exception($e->getMessage(), $e->getCode());
    
        }
    }
    
    /**
     * criar um produto
     * @param ModelProduct $product
     */
    public function create( ModelProduct $product )
    {
        try 
        {
            //definir parâmetros
            $product = $product->toArray();
            $params = ['product' => $product];
            
            //executar CURL
            $response = $this->api->call("products", $params, "POST")->result();
            
            //verificar se retornou algum erro
            $this->checkHttpResponse();
            
            return $response;
            
        } catch( \Exception $e ){
			
			throw new \Exception($e->getMessage(), $e->getCode());
			
		}
    }
    
    /**
     * atualizar um produto
     * @param ModelProduct $product
     */
    public function update( ModelProduct $product )
    {
        try
        {
            //definir sku
            $sku = $product->getSku();
            
            //definir parâmetros
            $product = $product->toArray();
            $product = array_filter($product);
            $params = ['product' => $product];
            
            //executar CURL
            $response = $this->api->call("products/" . $sku, $params, "PUT")->result();
    
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
            $response = $this->api->call("products/" . $sku, [], "DELETE")->result();
    
            //verificar se retornou algum erro
            $this->checkHttpResponse();
    
            return $response;
    
        } catch( \Exception $e ){
    
            throw new \Exception($e->getMessage(), $e->getCode());
    
        }
    }
    
    /**
     * sincronizar um produto fazendo a verificação se é para salvar, atualizar ou deletar
     * @param ModelProduct $product
     */
    public function synchronize( ModelProduct $product )
    {
        try
        {
            //verificar se é pra remover
            if( $product->getStatus() == "removed" )
            {
                return $this->delete($product->getSku());
            }
            
            //verificar se existe
            $response = $this->get($product->getSku());
            if( !empty($response->getSku()) )
            {
                //atualizar
                return $this->update($product);
                
            } else {
                
                //cadastrar
                return $this->create($product);
                
            }
        
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