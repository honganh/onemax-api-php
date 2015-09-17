<?php
namespace Onemax\Api\Connection;
use Onemax\Api\Connection\OnemaxException;
class Decoder 
{
	public static function parse($json, &$error=null) 
	{		
		$data = null;		
		try 
		{
			$data = @json_decode($json);
			if( is_object($data) ) 
			{
				$statusCode = isset($data->result->code) ? $data->result->code : 404;	
				if($statusCode == 200) 
				{
					unset($data->result);
				} 
				else 
				{					
					$error = isset($data->result->errors) ? $data->result->errors : null;
					$data = null;
					if(isset($error->url)) unset($error->url);
					if(isset($error->method)) unset($error->method);
					if(isset($error->_format)) unset($error->_format);
				}
			} 
			else {
				$data = null;
			}
		} 
		catch(Exception $e) {			
			$data = null;
		}
		return $data;
	}
	
}
