<?php
namespace Application\Classes;

/**
 * @NAICHE | Deco
 */
class Convert
{
	/**
	 * convert number to real
	 * @return float
	 */
	public static function toReal($number) 
	{
		return number_format($number, 2, ',', '.');
	}
	
	/**
	 * add bold in partial title
	 * @return string
	 */
	public static function titleBold($str, $words=2, $direction='right')
	{
		$array = explode(' ', $str);
	
		$offset = ($direction != 'right') ? $words : $words*-1;
		$part1 = array_slice($array, 0, $offset);
		$part2 = array_slice($array, $offset);
	
		$part1 = implode(' ', $part1);
		$part2 = implode(' ', $part2);
	
		if( ($direction == 'right') )
		{
			$return = $part1 . " <b>" . $part2 . "</b>";
		} else {
			$return = "<b>" . $part1 . "</b> " . $part2;
		}
	
		return $return;
	}
	
	/**
	 * format text with paragraphs
	 * @param string $subject
	 * @return string
	 */
	public static function textFormat($subject)
	{
		return str_replace(PHP_EOL, '<br/>', $subject);
	}
	
	/**
	 * limit of words in text
	 * @return string
	 */
	public static function textLimitWords($str, $words=12)
	{
		$str = strip_tags($str);
		$array = explode(' ', $str);
		if( count($array) <= $words ) return $str;
	
		$text = array_slice($array, 0, $words);
		$text = implode(' ', $text) . '...';
		return $text;
	}
	
	/**
	 * convert link in iframe
	 * @return string|false
	 */
	public static function youtubeLink($url) 
	{
	    if( strpos($url, 'youtube.com')===false ) return false;
	    
        return preg_replace(
            "/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i",
            "<iframe style=\"border:0;\" width=\"100%\" height=\"340px\" src=\"//www.youtube.com/embed/$2\" allowfullscreen></iframe>",
            $url
        );
    }
    
    /**
	 * @param string $date
	 * @return string
     */
    public static function date($date)
    {
    	//hoje
    	if( date('Ymd', strtotime($date)) == date('Ymd') )
    	{
    		$return = "Hoje às " . date('H:i', strtotime($date));
    	
    	//ontem
    	} elseif( date('Ymd', strtotime($date)) == date('Ymd', strtotime('-1 day')) )
    	{
    		$return = "Ontem às " . date('H:i', strtotime($date));
    		
    	//default
    	} else 
    	{
    		$return = date('d/m/Y', strtotime($date)) . " | " . date('H:i', strtotime($date));
    	}
    	
    	return $return;
    }
    
    /**
     * @param string $date
     * @return string
     */
    public static function dateFormat($date)
    {
    	$months = \Application\Classes\Constants::getMonths();
    	
    	$day = date('d', strtotime($date));
    	$month = date('m', strtotime($date));
    	$year = date('Y', strtotime($date));

    	//example 15. Fev. 2017
    	$return = $day . '. ' . substr($months[$month], 0, 3) . '. ' . $year;
    	
    	return $return;
    }
    
   
    
    /**
     * gera códigos para padronizar 
     * @param string $input
     * @return string
     */
    public static function code($input, $length=5, $insert_before=null, $insert_after=null)
    {
    	return $insert_before . str_pad($input, $length, 0, STR_PAD_LEFT) . $insert_after;
    }
    
    /**
     * verifica se uma imagem existe, caso não existir, gera uma imagem contendo as iniciais do nome
     * @param string $image
     * @param string $name
     * @return string
     */
    public static function imageLink($image, $name)
    {
    	$image_path = str_replace('./', $_SERVER['DOCUMENT_ROOT'] . '/', $image);
    	if( @is_array(getimagesize($image_path)) )
    	{
    		return $image;
    	
    	} else {
    		$words = explode(' ', $name);
    		$letters = !empty($words[0][0]) ? $words[0][0] : null;
    		$letters .= !empty($words[1][0]) ? $words[1][0] : null;
    		return 'http://placehold.it/300/f2f2f2/777?text=' . mb_strtoupper($letters);
    	}
    }
    
    /**
     * Remove a acentuação de uma string
     * @param string $var
     * @param bool $whitespace
     * @return string
     */
    public static function removeEspecialChars($var, $whitespace=false)
    {
    	$var = trim($var);
    
    	$var = str_replace(array("/{&aacute;}/","/{&Aacute;}/","/{&agrave;}/","/{&Agrave;}/","/{&atilde;}/","/{&Atilde;}/"),"a",$var);
    	$var = str_replace(array("/{&eacute;}/","/{&Eacute;}/","/{&egrave;}/","/{&Egrave;}/"),"e",$var);
    	$var = str_replace(array("/{&oacute;}/","/{&Oacute;}/","/{&ograve;}/","/{&Ograve;}/","/{&otilde;}/","/{&Otilde;}/"),"o",$var);
    	$var = str_replace(array("/{&uacute;}/","/{&Uacute;}/","/{&ugrave;}/","/{&Ugrave;}/"),"u",$var);
    	$var = str_replace(array("/{&iacute;}/","/{&Iacute;}/","/{&igrave;}/","/{&Igrave;}/"),"i",$var);
    	$var = str_replace("/{&ccedil;}/","c}/",$var);
    
    	$var = preg_replace("(á|Á|à|À|ã|Ã)","a",$var);
    	$var = preg_replace("(é|É|è|È|ê|Ê)","e",$var);
    	$var = preg_replace("(ó|Ó|ò|Ò|ô|Ô|õ|Õ)","o",$var);
    	$var = preg_replace("(ú|Ú|ù|Ù)","u",$var);
    	$var = preg_replace("(í|Í|ì|Ì)","i",$var);
    
    	$var = str_replace("ç","c",$var);
    	$var = str_replace("Ç","C",$var);
    	$var = preg_replace('/[^a-zA-Z0-9\s_-]/','',$var);
    
    	if ( !$whitespace ) $var = str_replace(array(' ','__'),'-',$var);
    
    	return $var;
    }
}