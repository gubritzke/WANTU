<?php
namespace Naicheframework\Mktplace\Model;

/**
 * @author: Vitor Deco
 */
abstract class ModelAbstract
{
	public function clear()
	{
		//todas as variáveis declaradas
		$vars = array_keys(get_class_vars(get_class($this)));
	
		//loop para montar o array e limpar
		foreach( $vars as $var ) $this->$var = null;
	}
	
	public function populate($array)
	{
	    $array = (array)$array;
	    
	    //loop no array
		foreach( $array as $key=>$value )
		{
	        //definir o nome da class
	        $class_name = "\Naicheframework\Mktplace\Model\Model" . $this->convertUnderlineToUppercase($key);
	        
	        //verificar se a class existe
	        if( class_exists($class_name) )
	        {
	            //definir a class
	            $class = new $class_name();
	            
	            //definir os valores
	            $populate = $class->populate($value);
	            
	            //verificar se o método existe
	            $method_name = "add" . $this->convertUnderlineToUppercase($key);
		        if( method_exists($this, $method_name) )
		        {
		            $this->$method_name($populate);
		        }
		        
		        //verificar se o método existe
		        $method_name = "set" . $this->convertUnderlineToUppercase($key);
		        if( method_exists($this, $method_name) )
		        {
		            $this->$method_name($populate);
		        }
		    
		    } else {
		    
    		    //definir o nome do método para add o valor
    			$method_get = "get" . $this->convertUnderlineToUppercase($key);
    			$method_set = "set" . $this->convertUnderlineToUppercase($key);

    			//definir a parent class
    			$parent_class = method_exists($this, $method_get) ? get_parent_class($this->$method_get()) : null;
    			
    			//verificar se o método existe
    			if( ($parent_class != 'Naicheframework\Mktplace\Model\ModelAbstract') && method_exists($this, $method_set) )
    			{
    				$this->$method_set($value);
    			}
    			
		    }
		}
	
		return $this;
	}
	
	public function toArray()
	{
		//todas as variáveis declaradas
		$vars = array_keys(get_class_vars(get_class($this)));
		
		//loop em todas as variáveis
		$return = array();
		foreach( $vars as $var )
		{
		    //recuperar valor da variável
		    $value = $this->$var;
		    
		    //verificar se é um array
		    if( is_array($value) )
		    {
		        //loop nos valores
		        foreach( $value as $v )
		        {
		            //verificar se é um model
		            if( (get_parent_class($v) == 'Naicheframework\Mktplace\Model\ModelAbstract') )
		            {
		                //executar a função recursivamente
		                $return[$var][] = $v->toArray();
		            
		            } else {
		                
		                //salvar o array
		                $return[$var][] = $v;
		            }
		        }
		    
		    } else {
		        
		        //verificar se é um model
		        if( (get_parent_class($value) == 'Naicheframework\Mktplace\Model\ModelAbstract') )
		        {
		            //executar a função recursivamente
		            $return[$var] = $value->toArray();
		        
		        } else {
		        
		            //montar array com os valores declarados
		            $return[$var] = $value;
		            
		        }
		        
		    }
		    
		}
		
		return $return;
	}

	public function toArrayClean()
	{
	    $array = $this->toArray();
	    $array = $this->arrayFilterRecursive($array);
	    return $array;
	}
	
	public function toObject()
	{
	   return (object)$this->toArray();
	}
	
	/**
	 * Recursively filter an array
	 * @param array $array
	 * @param callable $callback
	 * @return array
	 */
	private function arrayFilterRecursive( array $array, callable $callback = null ) 
	{
	    $array = is_callable( $callback ) ? array_filter( $array, $callback ) : array_filter( $array );
	    foreach( $array as &$value ) 
	    {
	        if( is_array( $value ) ) 
	        {
	            $value = $this->arrayFilterRecursive( $value, $callback );
	        }
	    }
	
	    return $array;
	}
	
	/**
	 * converter "foo_bar" para "fooBar"
	 * @param string $string
	 * @return string
	 */
	private function convertUnderlineToUppercase($string)
	{
	    $array = explode("_", $string);
	    $array = array_map("ucfirst", $array);
	    return implode("", $array);
	}
}