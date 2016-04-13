<?php

// Parse list of references in supplementary material

$filename = 'SI.txt';
$file_handle = fopen($filename, "r");

$state = 0;

$ref = '';

while (!feof($file_handle)) 
{
	$line = fgets($file_handle);
	
	switch ($state)
	{
		case 0:
			if (preg_match('/^(\d+)/', $line, $m))
			{
				$ref = rtrim($line);
				
				if (preg_match('/PROMED/', $line))
				{
					if (preg_match('/^(?<id>\d+)\.\s+(?<citation>.*)/u', $ref, $m))
					{
						//echo $m['id'] . "\t" . $m['citation'] . "\n";
					
						$sql = 'REPLACE INTO `references`(id,citation) VALUES(' . $m['id'] . ',"' . addcslashes($m['citation'], '"') . '");';
						echo $sql . "\n"; 
					}				
				}
				else
				{
					$state = 1;
				}
			}
			break;
			
		case 1:
			if (preg_match('/\d\t/', $line, $m))
			{
				//echo $line; 
				//exit();
			}
			else
			{		
				$ref .= ' ' . trim($line);
				if (preg_match('/(;|[0-9]{4}\.)$/', $line))
				{
					// done
				
					$ref = rtrim($ref, ';');
				
					//echo $ref;
				
					if (preg_match('/^(?<id>\d+)\.\s+(?<citation>.*)/u', $ref, $m))
					{
						//echo $m['id'] . "\t" . $m['citation'] . "\n";
					
						$sql = 'REPLACE INTO `references`(id,citation) VALUES(' . $m['id'] . ',"' . addcslashes($m['citation'], '"') . '");';
						echo $sql . "\n"; 
					}
					
				
					//echo "\n-------\n";
					$state = 0;
				}
			}
			break;
			
		default:
			break;
	}
}



?>
		
		

