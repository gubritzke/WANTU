<?php
namespace Naicheframework\Shopping\Service;

/**
 * @author: Vitor Deco
 */
class ServiceCreditCard
{
    /**
     * array com os fatores multiplicadores
     * ex: array(1 => 0, 2 => 0, 3 => 0, 4 => '0.0068', 5 => '0.0068', 6 => '0.0068');
     * @var array
     */
    protected $parcela_fator = array(1=>0, 2=>0, 3=>0);
    
    /**
     * valor mínimo para parcelar em mais de 1x
     * @var integer
     */
    protected $valor_minimo = 32;
    
    /**
     * o valor total será dividido por essa variável para definir a quantidade de parcelas
     * @var integer
     */
    protected $valor_dividir = 2;
    
    public function setParcelaFator($array)
    {
        $array = (array)$array;
        
        if( count($array) )
        {
            foreach( $array as $key=>$value )
            {
                $this->parcela_fator[(int)$key] = $value;
            }
        }
    }
    
    public function getParcelaFator($parcel=null)
    {
        if( !empty($parcel) )
        {
            return isset($this->parcela_fator[$parcel]) ? $this->parcela_fator[$parcel] : 0;
        }
        
        return $this->parcela_fator;
    }
    
    public function countParcelaFator()
    {
        return count($this->parcela_fator);
    }
    
    public function setValorMinimo($value)
    {
        if( !empty($value) )
        {
            $this->valor_minimo = $value;
        }
    }
    
    public function getValorMinimo()
    {
        return $this->valor_minimo;
    }
    
    public function setValorDividir($value)
    {
        if( !empty($value) )
        {
            $this->valor_dividir = $value;
        }
    }
    
    public function getValorDividir()
    {
        return $this->valor_dividir;
    }
    
    public function getParcelaMaximo($total)
    {
        $parcela_maximo = 1;
        
        //verificar se o total dividido por $this->valor_dividir é maior que $this->valor_minimo
        if( $total / $this->valor_dividir > $this->valor_minimo )
        {
            $parcela_maximo = floor($total / $this->valor_minimo);
            
            //verificar limite da quantidade de parcelas
            if( $parcela_maximo > $this->countParcelaFator() )
            {
                $parcela_maximo = $this->countParcelaFator();
            }
        }
        
        return $parcela_maximo;
    }
    
    public function getParcelasInArray($total)
    {
        //definir máximo de parcelas
        $parcela_maximo = $this->getParcelaMaximo($total);
        
        //loop nas parcelas
        $array = array();
        for($i=1; $i<=$parcela_maximo; $i++)
        {
            //definir texto e adicionar no array
            $valor = $this->calcularValorDaParcela($total, $i);
            $juros = ($this->calcularJurosDaParcela($total, $i) > 0) ? "c/ juros" : "s/ juros";
            $array[$i] = $i . "x de R$ " . \Naicheframework\Helper\Convert::toReal($valor) . " " . $juros;
        }
        
        return $array;
    }
    
    public function calcularJurosDaParcela($total, $parcel)
    {
        $fator = $this->getParcelaFator($parcel);
        return ($total * $fator);
    }
    
    public function calcularValorDaParcela($total, $parcel)
    {
        $fator = $this->getParcelaFator($parcel);
        return ($total + ($total * $fator)) / $parcel;
    }
}