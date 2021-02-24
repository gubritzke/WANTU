<?php
namespace Naicheframework\Mktplace\Service;
use Naicheframework\Mktplace\Model\ModelProduct;
use Naicheframework\Mktplace\Auth\AuthSkyhub;
use Naicheframework\Api\Request;
use Naicheframework\Mktplace\Model\Pluggto\ModelPluggtoProduct;

/**
 * @author: Vitor Deco
 */
class ServiceProduct 
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
    
    /**
     * converte o array para model
     * @param array $array
     * @return ModelProduct
     */
    public function arrayToModel( $array )
    {
        try
        {
            //definir array com informações do produto
            $produto = $array;
            
            //converter json para object
            $produto->dimensoes = json_decode($produto->dimensoes);
            
            //definir filtros do produto
            $where = " (produto_filtro.id_produto = '" . $produto->id_produto . "') ";
            $order = "filtro_tipo.filtro_tipo_ordem ASC";
            $produto->filtros = $this->database->call('filtro/select', ['where'=>$where, 'order'=>$order], 'GET')->result();
            //echo'<pre>'; print_r($produto); exit;
            
            //definir status
            $status = ($produto->status == "excluido") ? "removed" : (($produto->mktplace_status == 1) ? "enabled" : "disabled");
            
            //definir preco de
            $preco_de = !empty($produto->mktplace_preco_de) ? $produto->mktplace_preco_de : $produto->mktplace_preco;
            
            //definir produto (model)
            $modelProduct = new \Naicheframework\Mktplace\Model\ModelProduct();
            $modelProduct->setSku($produto->id_produto_loja);
            $modelProduct->setName($produto->produto);
            $modelProduct->setDescription($produto->descricao);
            $modelProduct->setStatus($status);
            $modelProduct->setQty($produto->estoque);
            $modelProduct->setPrice($preco_de);
            $modelProduct->setPromotionalPrice($produto->mktplace_preco);
            $modelProduct->setCost($produto->mktplace_preco);
            $modelProduct->setWeight($produto->dimensoes->peso);
            $modelProduct->setHeight($produto->dimensoes->altura);
            $modelProduct->setWidth($produto->dimensoes->largura);
            $modelProduct->setLength($produto->dimensoes->comprimento);
            $modelProduct->setEan($produto->ean);
            $modelProduct->setNbm(null);
            
            $value = array_column($produto->filtros, 'filtro', 'filtro_tipo');
            $value = $value['Marca'];
            $modelProduct->setBrand($value);
            
            //definir imagens do produto (model)
            $modelProduct->addImage($produto->imagem_1);
            $modelProduct->addImage($produto->imagem_2);
            $modelProduct->addImage($produto->imagem_3);
            $modelProduct->addImage($produto->imagem_4);
            $modelProduct->addImage($produto->imagem_5);
            
            //definir categoria do produto (model)
            $filtros = array_filter($produto->filtros, function($obj){
                return in_array($obj->filtro_tipo, ['Categoria', 'Departamento', 'Gênero', 'Tamanho']) ? true : false;
            });
            //echo'<pre>'; print_r($filtros); exit;
            
            $modelCategory = new \Naicheframework\Mktplace\Model\ModelCategory();
            
            $value = array_column($filtros, 'id_filtro');
            $value = implode('', $value);
            $modelCategory->setCode($value);
            
            $value = array_column($filtros, 'filtro');
            $value = implode(' > ', $value);
            $modelCategory->setName($value);
            
            $modelProduct->addCategory($modelCategory);
            
            //definir especificações do produto (model)
            foreach( $produto->filtros as $filtro )
            {
                $modelSpecification = new \Naicheframework\Mktplace\Model\ModelSpecification();
                $modelSpecification->setKey($filtro->filtro_tipo);
                $modelSpecification->setValue($filtro->filtro);
                $modelProduct->addSpecification($modelSpecification);
            }
    
            return $modelProduct;
            
        } catch( \Exception $e ){
            
            throw new \Exception($e->getMessage(), $e->getCode());
            
        }
    }
    
    /**
     * converte o array para model pluggto
     * @param array $array
     * @return ModelPluggtoProduct
     */
    public function arrayToModelPluggto( $array )
    {
        try
        {
            //definir array com informações do produto
            $produto = $array;
            
            //converter json para object
            $produto->dimensoes = json_decode($produto->dimensoes);
            
            //definir filtros do produto
            $where = " (produto_filtro.id_produto = '" . $produto->id_produto . "') ";
            $order = "filtro_tipo.filtro_tipo_ordem ASC";
            $produto->filtros = $this->database->call('filtro/select', ['where'=>$where, 'order'=>$order], 'GET')->result();
            //echo'<pre>'; print_r($produto); exit;
            
            //definir status
            $status = ($produto->status == "excluido") ? "removed" : (($produto->mktplace_status == 1) ? "enabled" : "disabled");
            
            //definir preco de
            $preco_de = !empty($produto->mktplace_preco_de) ? $produto->mktplace_preco_de : $produto->mktplace_preco;
            
            //definir produto (model)
            $modelProduct = new \Naicheframework\Mktplace\Model\Pluggto\ModelPluggtoProduct();
            $modelProduct->setSku($produto->id_produto_loja);
            $modelProduct->setEan($produto->ean);
            $modelProduct->setName($produto->produto);
            $modelProduct->setExternal($produto->id_produto);
            $modelProduct->setQuantity($produto->estoque);
            $modelProduct->setSpecialPrice($produto->mktplace_preco);
            $modelProduct->setPrice($preco_de);
            $modelProduct->setShortDescription(null);
            $modelProduct->setDescription($produto->descricao);
            $modelProduct->setCost($produto->mktplace_preco);
            $modelProduct->setWarrantyTime(null);
            $modelProduct->setWarrantyMessage(null);
            $modelProduct->setLink(null);
            $modelProduct->setAvailable(null);
            $modelProduct->setHandlingTime(null);
            $modelProduct->setManufactureTime(null);
            $modelProduct->setStatus($status);
            
            $value = array_column($produto->filtros, 'filtro', 'filtro_tipo');
            $value = $value['Marca'];
            $modelProduct->setBrand($value);
            
            $modelDimension = new \Naicheframework\Mktplace\Model\Pluggto\ModelPluggtoDimension();
            $modelDimension->setWeight($produto->dimensoes->peso);
            $modelDimension->setHeight($produto->dimensoes->altura);
            $modelDimension->setWidth($produto->dimensoes->largura);
            $modelDimension->setLength($produto->dimensoes->comprimento);
            $modelProduct->setDimension($modelDimension);
            
            //definir imagens do produto (model)
            $modelPhoto = new \Naicheframework\Mktplace\Model\Pluggto\ModelPluggtoPhoto();
            $modelPhoto->setUrl($produto->imagem_1);
            $modelPhoto->setOrder(1);
            $modelProduct->addPhoto($modelPhoto);
    
            $modelPhoto = new \Naicheframework\Mktplace\Model\Pluggto\ModelPluggtoPhoto();
            $modelPhoto->setUrl($produto->imagem_2);
            $modelPhoto->setOrder(2);
            $modelProduct->addPhoto($modelPhoto);
            
            $modelPhoto = new \Naicheframework\Mktplace\Model\Pluggto\ModelPluggtoPhoto();
            $modelPhoto->setUrl($produto->imagem_3);
            $modelPhoto->setOrder(3);
            $modelProduct->addPhoto($modelPhoto);
            
            $modelPhoto = new \Naicheframework\Mktplace\Model\Pluggto\ModelPluggtoPhoto();
            $modelPhoto->setUrl($produto->imagem_4);
            $modelPhoto->setOrder(4);
            $modelProduct->addPhoto($modelPhoto);
            
            $modelPhoto = new \Naicheframework\Mktplace\Model\Pluggto\ModelPluggtoPhoto();
            $modelPhoto->setUrl($produto->imagem_5);
            $modelPhoto->setOrder(5);
            $modelProduct->addPhoto($modelPhoto);
            
            //definir categorias do produto (model)
            $filtros = array_filter($produto->filtros, function($obj){
                return in_array($obj->filtro_tipo, ['Categoria', 'Departamento', 'Gênero', 'Tamanho']) ? true : false;
            });
            $filtros = array_column($filtros, 'filtro');
            
            for( $i=1; $i<=count($filtros); $i++ )
            {
                $array = $filtros;
                $array = array_splice($array, 0, $i);
                $name = implode(' > ', $array);
                
                $modelCategory = new \Naicheframework\Mktplace\Model\Pluggto\ModelPluggtoCategory();
                $modelCategory->setName($name);
                $modelProduct->addCategory($modelCategory);
            }
            
            //definir atributos do produto (model)
            foreach( $produto->filtros as $filtro )
            {
                $modelAttributeValue = new \Naicheframework\Mktplace\Model\Pluggto\ModelPluggtoAttributeValue();
                $modelAttributeValue->setCode($filtro->id_filtro);
                $modelAttributeValue->setLabel($filtro->filtro);
                
                $modelAttribute = new \Naicheframework\Mktplace\Model\Pluggto\ModelPluggtoAttribute();
                $modelAttribute->setCode($filtro->id_filtro_tipo);
                $modelAttribute->setLabel($filtro->filtro_tipo);
                $modelAttribute->setValue($modelAttributeValue);
                $modelProduct->addAttribute($modelAttribute);
            }
            
            //não definidos
            //protected $price_table = array();
            //protected $stock_table = array();
            //protected $variations = array();
            
            return $modelProduct;
            
        } catch( \Exception $e ){
            
            throw new \Exception($e->getMessage(), $e->getCode());
            
        }
    }
}