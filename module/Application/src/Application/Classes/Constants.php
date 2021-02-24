<?php
namespace Application\Classes;


/**
 * @NAICHE | Deco
 */
class Constants
{
    public static function getStates()
    {
    	return array("AC"=>"Acre", "AL"=>"Alagoas", "AM"=>"Amazonas", "AP"=>"Amapá","BA"=>"Bahia","CE"=>"Ceará","DF"=>"Distrito Federal","ES"=>"Espírito Santo","GO"=>"Goiás","MA"=>"Maranhão","MT"=>"Mato Grosso","MS"=>"Mato Grosso do Sul","MG"=>"Minas Gerais","PA"=>"Pará","PB"=>"Paraíba","PR"=>"Paraná","PE"=>"Pernambuco","PI"=>"Piauí","RJ"=>"Rio de Janeiro","RN"=>"Rio Grande do Norte","RO"=>"Rondônia","RS"=>"Rio Grande do Sul","RR"=>"Roraima","SC"=>"Santa Catarina","SE"=>"Sergipe","SP"=>"São Paulo","TO"=>"Tocantins");
    }
    
    public static function getMonths($month = false)
    {
    	$return = array();
    	$return['01'] = 'Janeiro';
    	$return['02'] = 'Fevereiro';
    	$return['03'] = 'Março';
    	$return['04'] = 'Abril';
    	$return['05'] = 'Maio';
    	$return['06'] = 'Junho';
    	$return['07'] = 'Julho';
    	$return['08'] = 'Agosto';
    	$return['09'] = 'Setembro';
    	$return['10'] = 'Outubro';
    	$return['11'] = 'Novembro';
    	$return['12'] = 'Dezembro';
    	return ($month === false) ? $return : $return[$month];
    }
    
    public static function getYears($start='now', $end='1930')
    {
    	//define o ano atual
    	if( $start == 'now' ) $start = date('Y');
    	if( $end == 'now' ) $end = date('Y');
    	
    	//array com os anos
    	$return = array();
    	
    	//loop em todos os anos
    	for( $i=$start; $i>=$end; $i-- )
    	{
    		$return[$i] = $i;
    	}
    	
    	return $return;
    }
   
}