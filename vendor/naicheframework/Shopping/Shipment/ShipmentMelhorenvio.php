<?php
namespace Naicheframework\Shopping\Shipment;
use Naicheframework\Shopping\Model\Package;
use Naicheframework\Shopping\Model\Sender;
use Naicheframework\Shopping\Model\Recipient;
use Naicheframework\Shopping\Model\ShipmentMultiple;
use Naicheframework\Shopping\Model\Shipment;
use Naicheframework\Shopping\Validate\ValidateCalcularFrete;
use Naicheframework\Shopping\Model\Order;

/**
 * @NAICHE | Vitor Deco
 */
class ShipmentMelhorenvio extends ShipmentAbstract
{
	//método de envio
	const TYPE = "MELHORENVIO";
	
	//nome do método de envio
	const NAME = "Melhor Envio";
	
	//class que faz o request na api
	protected $api;

	//chaves de acesso
	protected $access_token = null;
	protected $client_id = null;
	protected $client_secret = null;
	
	public function __construct(array $config)
	{
	    //definir configurações
	    foreach( $config as $key => $value )
	    {
	        $this->$key = $value;
	    }
	    
	    //instancia da class que faz o request na api
		$url = "https://www.melhorenvio.com.br/api/v2";
		$authorization = "Authorization: " . $this->access_token;
		$this->api = new \Naicheframework\Api\Request($url, $authorization);
		$this->api->setJsonData(true);
	    
		//define o método de envio
		parent::__construct(self::TYPE, self::NAME);
	}
	
	public function setSandbox($bool)
	{
	    if( $bool === true )
	    {
	        $url = "https://sandbox.melhorenvio.com.br/api/v2";
	        $authorization = "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjQ5YTVkZDNhY2FkMmVlMzZhMjQ1N2Y5OWEzMWRmMmM4MjNlMDZmZjFiOGIxNDgyMGU0YzFkZTc0M2RiYTU2MDljNmVlMDZlODYyYmZmZThmIn0.eyJhdWQiOiIxIiwianRpIjoiNDlhNWRkM2FjYWQyZWUzNmEyNDU3Zjk5YTMxZGYyYzgyM2UwNmZmMWI4YjE0ODIwZTRjMWRlNzQzZGJhNTYwOWM2ZWUwNmU4NjJiZmZlOGYiLCJpYXQiOjE1Mzc5NzMzMDIsIm5iZiI6MTUzNzk3MzMwMiwiZXhwIjoxNTY5NTA5MzAyLCJzdWIiOiIzYTVmOTcxZi1iMGE2LTRiYTEtOWQxYy1lN2ViN2VjNWY3MzYiLCJzY29wZXMiOlsiY2FydC1yZWFkIiwiY2FydC13cml0ZSIsImNvbXBhbmllcy1yZWFkIiwiY29tcGFuaWVzLXdyaXRlIiwiY291cG9ucy1yZWFkIiwiY291cG9ucy13cml0ZSIsIm5vdGlmaWNhdGlvbnMtcmVhZCIsIm9yZGVycy1yZWFkIiwicHJvZHVjdHMtcmVhZCIsInByb2R1Y3RzLXdyaXRlIiwicHVyY2hhc2VzLXJlYWQiLCJzaGlwcGluZy1jYWxjdWxhdGUiLCJzaGlwcGluZy1jYW5jZWwiLCJzaGlwcGluZy1jaGVja291dCIsInNoaXBwaW5nLWNvbXBhbmllcyIsInNoaXBwaW5nLWdlbmVyYXRlIiwic2hpcHBpbmctcHJldmlldyIsInNoaXBwaW5nLXByaW50Iiwic2hpcHBpbmctc2hhcmUiLCJzaGlwcGluZy10cmFja2luZyIsImVjb21tZXJjZS1zaGlwcGluZyIsInRyYW5zYWN0aW9ucy1yZWFkIiwidXNlcnMtcmVhZCIsInVzZXJzLXdyaXRlIiwid2ViaG9va3MtcmVhZCIsIndlYmhvb2tzLXdyaXRlIl19.C4PnMtBQ6PVQxKyM_14hec-rQNkX5rhYdob4su_NOlD9pEjKwME0Ubqe6B3ICt6Pmvxr6cCp8Vw2OklbJVes2Zo7qyDqNb_Ikh-jIVG9u42OnPYnEV4LuaAHpNZlhCaKQr6ycKHg3IpsMwnwIV8SwAdSsZRTeQv6tIf1Qr5i8WAdK4pAMR3OLJ44mfipvoBpbIqAFeFnmV_O-nuXRgjWt320AtDOyNxA6WwbeJVnf3HJhoXnMjRLkaxaql06B3g3nL22BN3mh10AHvCvqkWppI7Hn1vwWrdQJTFi0l-UMxIY3YwW9peRXTprQgvomHjeccmUJ5j5RzpbyXYZemYPS0xssLmOGnomTLJ02w7-PnixIVVq4dnJyUzmxlEfe_y7HjKEEdurN21q767lcX8zJqUlO-PdQWNViycUs9DTuS_es1wS1jXMh2cnZP1iGoiT2cWlI6UXnC2ENs1sfa1eEN1HrfrtEmkfNzA3Gh0pnmKFFQA0DtQjEr4RGGetlRX43f1VUD8VBPkqW3f6TyLQZIPRlj1K3O4-tf1YEuHU0NVyFkqwPcYIfW5a3nGiHK_EF1PfdnLSqQP2rZ-rEIaGAOuJr2vJcQ472W2yvTwZ_j7Pzi5MnjG-x38ZKu4MEmrZbdkqTQiKRLIZKLY0q34O4V0xYU2yt-f7DnjTGSjyyZQ";
	        $this->api = new \Naicheframework\Api\Request($url, $authorization);
	        $this->api->setJsonData(true);
	    }
	     
	    parent::setSandbox($bool);
	    return $this;
	}
	
    public function calcularFrete(Package $package, Sender $sender, Recipient $recipient)
	{
		try 
		{
			//validar campos
			$validate = new ValidateCalcularFrete();
			if( !$validate::isNotEmpty($sender->getCep()) )
			{
				throw new \Exception($validate::ERROR_REQUIRED_REMETENTE_CEP);
			}
			
			if( !$validate::isNotEmpty($recipient->getCep()) )
			{
				throw new \Exception($validate::ERROR_REQUIRED_DESTINATARIO_CEP);
			}
			
			$cond1 = !$validate::isNotEmpty($package->getAltura());
			$cond2 = !$validate::isNotEmpty($package->getComprimento());
			$cond3 = !$validate::isNotEmpty($package->getLargura());
			$cond4 = !$validate::isNotEmpty($package->getPeso());
			$cond5 = !$validate::isNotEmpty($package->getValor());
			if( $cond1 || $cond2 || $cond3 || $cond4 || $cond5 )
			{
				throw new \Exception($validate::ERROR_REQUIRED);
			}
			
			//definir params para calcular frete
			$params = array(
			    "from" => [
			        'postal_code' => $sender->getCep(),
			    ],
			    "to" => [
			        'postal_code' => $recipient->getCep(),
			    ],
			    "package" => [
			        'weight' => ($package->getPeso() < 1) ? 1 : $package->getPeso(),
			        'width' => $package->getLargura(),
			        'height' => $package->getAltura(),
			        'length' => $package->getComprimento(),
			    ],
			    "options" => [
			        'insurance_value' => $package->getValor(),
			        'receipt' => false,
			        'own_hand' => false,
			        'collect' => false,
			    ],
			    //"services" => "1,2"; //ids das transportadoras
			);
			
			//faz a consulta na api
			$url = "me/shipment/calculate";
			$response = $this->api->call($url, $params, 'POST')->result();
			if( !is_array($response) ) $response = array($response);
			
			//remover as transportadoras indisponíveis
			$response = array_filter($response, function($obj){
			    return isset($obj->error) ? false : true;
			});
			//echo'<pre>'; print_r($response); exit;
			
			//erros
			if( !count($response) )
			{
				throw new \Exception($validate::ERROR_CEP_AREA);
			}
			
			//resultado
			$shipmentMultiple = new ShipmentMultiple();
			
			//resultado item
			foreach( $response as $row )
			{
			    //definir nome do serviço
			    $servico = $this->getServicoById($row->id);
			    
			    $checkIfExists = $shipmentMultiple->getItemByService($servico);
			    if( $checkIfExists->getServico() == "" )
			    {
			        //arredondar preço para cima em 1 casa decimal
			        $row->price = ceil($row->price * 10) / 10;
			        
			        $item = new Shipment();
			        $item->setServico($servico);
			        $item->setPrazo($row->delivery_time);
			        $item->setValor($row->price);
			        $shipmentMultiple->addItem($item);
			    }
			}
			
			return $shipmentMultiple;
			
		} catch( \Exception $e ){
			
			throw new \Exception($e->getMessage(), $e->getCode());
			
		}
	}
	
	public function imprimirEtiqueta(Order $order)
	{
	    try
	    {
	        //inserir etiquetas no carrinho
	        $etiquetas = array();
	        foreach( $order->getPackage() as $package )
	        {
	           $etiquetas[] = $this->imprimirEtiquetaCarrinho($order, $package);
	        }
	        
	        //comprar etiquetas do carrinho
	        $paid = $this->imprimirEtiquetaComprar($etiquetas);
	        if( !$paid ) throw new \Exception("Houve um problema ao comprar as etiquetas no Melhor Envior!");
	        
	        //gerar etiquetas compradas
	        $this->imprimirEtiquetaGerar($etiquetas);
	        
	        //consultar link para imprimir as etiquetas geradas
	        $link = $this->imprimirEtiquetaLink($etiquetas);
	        echo '<prE>'; print_r($etiquetas);
	        echo $link; exit;
	        
	    } catch( \Exception $e ){
	        
	        //registrar log de erro
	        $var = array(
	            'file' => $e->getFile(),
	            'line' => $e->getLine(),
	            'order' => $order->toArray(),
	        );
	        \Naicheframework\Log\Log::error($e->getMessage(), $var);
            
	        //criar exceção
	        throw new \Exception($e->getMessage(), $e->getCode());
	    }
	}
	
	private function imprimirEtiquetaCarrinho(Order $order, Package $package)
	{
// 	    echo'<prE>'; print_r($order); exit;
	    
        //definir remetente
        $sender = $order->getRemetente();

        //definir destinatário
        $recipient = $order->getDestinatario();

        //====================================
        // Inserção de etiquetas no carrinho
        //====================================
        $params = array(
            "service" => $this->getServicoByName($order->getServico()), // id do serviço escolhido
            "agency" => 1, //@todo id da agência de postagem (obrigatório se for JadLog)
            "from" => [ // remetente
                "name" => $sender->getNome(), // nome
                "phone" => $sender->getTelefone(true), // telefone com ddd (obrigatório se não for Correios)
                "email" => $sender->getEmail(), // email (opcional)
                //"document" => "16571478358", // cpf (opcional)
                "company_document" => $sender->getDocumento(true), // cnpj (obrigatório se não for Correios)
                "state_register" => $sender->getIe(), // inscrição estadual (obrigatório se não for Correios) pode ser informado "isento"
                "address" => $sender->getLogradouro(), // logradouro
                "complement" => $sender->getComplemento(), // complemento
                "number" => $sender->getNumero(), // número
                "district" => $sender->getBairro(), // bairro
                "city" => $sender->getCidade(), // cidade
                "state_abbr" => $sender->getEstado(), // uf do estado
                "country_id" => "BR", // id do país
                "postal_code" => $sender->getCep(), // cep
                "note" => $sender->getReferencia() // observação
            ],
            "to" => [// destinatário
                "name" => $recipient->getNome(),
                "phone" => $recipient->getTelefone(true), // telefone com ddd (obrigatório se não for Correios)
                "email" => $recipient->getEmail(),
                "document" => $recipient->getDocumento(true), // obrigatório se for transportadora e não for logística reversa
                //"company_document" => "89794131000100", // (opcional) (a menos que seja transportadora e logística reversa)
                //"state_register" => "123456", // (opcional) (a menos que seja transportadora e logística reversa)
                "address" => $recipient->getLogradouro(),
                "complement" => $recipient->getComplemento(),
                "number" => $recipient->getNumero(),
                "district" => $recipient->getBairro(),
                "city" => $recipient->getCidade(),
                "state_abbr" => $recipient->getEstado(),
                "country_id" => "BR",
                "postal_code" => $recipient->getCep(),
                "note" => $recipient->getReferencia() // (opcional) impresso na etiqueta
            ],
            "products" => [], // lista de produtos para preenchimento da declaração de conteúdo
            "package" => [ // informações do pacote (volume) - ainda não é possível cadastrar volumes
                "weight" => $package->getPeso(),
                "width" => $package->getLargura(),
                "height" => $package->getAltura(),
                "length" => $package->getComprimento(),
            ],
            "options" => [ // opções
                "insurance_value" => 0, // valor declarado/segurado
                "receipt" => false, // aviso de recebimento
                "own_hand" => false, // mão propria
                "collect" => false, // coleta
                "reverse" => false, // logística reversa (se for reversa = true, ainda sim from será o remetente e to o destinatário)
                "non_commercial" => false, // envio de objeto não comercializável (flexibiliza a necessidade de pessoas júridicas para envios com transportadoras como Latam Cargo, porém se for um envio comercializável a mercadoria pode ser confisca pelo fisco)
                "invoice" => [], // nota fiscal (opcional se for Correios)
                //"reminder": "lembrete" // lembrete (opcional) impresso acima da etiqueta
            ],
            //"coupon": "MEUCUPON" // cupom de desconto
        );

        //loop nos itens
        foreach( $order->getItem() as $item )
        {
            //verificar se os itens estão no pacote
            if( in_array($item->getId(), $package->getId()) )
            {
                //add item
                $params['products'][] = array(
                    "name" => $item->getDescricao(), // nome do produto (max 255 caracteres)
                    "quantity" => $item->getQuantidade(), // quantidade de items desse produto
                    "unitary_value" => $item->getPreco(), // R$ 4,50 valor do produto
                    //"weight" => $item->getPeso(), // peso 1kg, opcional
                );
                 
                //add valor assegurado
                $params['options']['insurance_value'] += $item->getSubtotal();
                
                //add nota fiscal
                $params['options']['invoice'] = array(
                    'number' => $item->getNfeNumero(), //número da nota
                    'key' => $item->getNfeChave(), //chave da nf-e
                );
            }
        }
        //echo'<pre>'; print_r($params); exit;

        //faz a consulta na api
        $url = "me/cart";
        $response = $this->api->call($url, $params, 'POST')->result();
        return $response->id;
	}
	
	private function imprimirEtiquetaComprar(array $etiquetas)
	{
	    //definir params
	    $params = array(
	        "orders" => $etiquetas
	    );
	    
	    //faz a consulta na api
	    $url = "me/shipment/checkout";
	    $response = $this->api->call($url, $params, 'POST')->result();
	    return $response->purchase->status == "paid" ? true : false;
	}
	
	private function imprimirEtiquetaGerar(array $etiquetas)
	{
	    //definir params
	    $params = array(
	        "orders" => $etiquetas
	    );
	    
	    //faz a consulta na api
	    $url = "me/shipment/generate";
	    $result = $this->api->call($url, $params, 'POST')->result();
	    
	    //loop no resultado
	    foreach( $result as $etiqueta => $row )
	    {
	        //verificar se foi gerado com sucesso
	        if( $row->status == 1 )
	        {
	            
	        }
	    }
	    
	    return true;
	}
	
	private function imprimirEtiquetaLink(array $etiquetas)
	{
	    //definir params
	    $params = array(
	        "mode" => "public",
	        "orders" => $etiquetas
	    );
	     
	    //faz a consulta na api
	    $url = "me/shipment/print";
	    $response = $this->api->call($url, $params, 'POST')->result();
	    return !empty($response->url) ? $response->url : false;
	}
	
    public function rastrearPedido(array $codes)
    {
        //TODO
    }

    public function getLinkRastreio($code = null)
    {
        //TODO
    }

    /**
     * retorna o nome do serviço com base no Id do parâmetro
     * @param int $id
     * @return string|boolean
     */
    private function getServicoById($id)
    {
        switch( $id )
        {
            case 1: return "Correios PAC";
            case 2: return "Correios SEDEX";
            case 3: return "JadLog Econômico";
            case 4: return "JadLog Expresso";
            case 5: return "Shippify Expresso";
            case 6: return "Jamef Aéreo";
            case 7: return "Jamef Rodoviário";
            case 8: return "Via Brasil Aéreo";
            case 9: return "Via Brasil Rodoviário";
            case 10: return "LATAMCargo Próximo Dia";
            case 11: return "LATAMCargo Próximo Vôo";
            case 12: return "LATAMCargo Convencional";
            case 13: return "TNT Mercúrio Expresso";
            default: return false;
        }
    }
    
    /**
     * retorna o id do serviço com base no nome do parâmetro
     * @param string $servico
     * @return int|boolean
     */
    private function getServicoByName($servico)
    {
        switch( $servico )
        {
            case "Correios PAC": return 1;
            case "Correios SEDEX": return 2;
            case "JadLog Econômico": return 3;
            case "JadLog Expresso": return 4;
            case "Shippify Expresso": return 5;
            case "Jamef Aéreo": return 6;
            case "Jamef Rodoviário": return 7;
            case "Via Brasil Aéreo": return 8;
            case "Via Brasil Rodoviário": return 9;
            case "LATAMCargo Próximo Dia": return 10;
            case "LATAMCargo Próximo Vôo": return 11;
            case "LATAMCargo Convencional": return 12;
            case "TNT Mercúrio Expresso": return 13;
            default: return 1;
        }
    }
    
    /**
     * aplicar regras para definir o valor que será declarado no seguro da entrega
     * @param number $declaredValue
     * @return number
     */
    private function aplicarRegrasParaValorDeclarado($declaredValue)
    {
        //aplicar regras
        if( $declaredValue <= 100 )
        {
            //até 100 declarar 0
            $declaredValue = 0;
             
        } elseif( $declaredValue <= 399 ){
             
            //de 101 até 399 declarar 10%
            $declaredValue = $declaredValue * 10 / 100;
        }
        
        return $declaredValue;
    }
    
}