<?php

interface JSONEncodable
{
	public function JSONEncode();
}

class JSONEncoder
{
	#
	# JSON encodes an array of JSONEncodable objects.
	#
	public static function EncodeArray($arr)
	{
		$encodedArr = array();
		for($i = 0; $i < count($arr); $i++)
		{
			if($arr[$i] instanceof JSONEncodable)
			{
				$json = $arr[$i]->JSONEncode();
				array_push($encodedArr, $json);
			}
			else
				throw new Exception('Not all members of the array are JSONEncodable.');
		}
		
		return "[" . implode(",", $encodedArr) . "]";
	}
}

?>