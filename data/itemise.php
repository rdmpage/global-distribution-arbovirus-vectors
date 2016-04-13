<?php

		


$filename = 'references.tsv';
$file_handle = fopen($filename, "r");


while (!feof($file_handle)) 
{
	$line = fgets($file_handle);
	
	$parts = explode("\t", $line);
	
	$id = $parts[0];
	$ref = trim($parts[1]);
	
	/*
	$ref = 'J. Lopes, E. A. C. Martins, O. de Oliveira, V. de Oliveira, B. P. D. Neto, J. E. de Oliveira, Dispersion of Aedes aegypti (Linnaeus, 1762) and Aedes albopictus (Skuse, 1894) in the rural zone of North Parana State. Brazilian Archives of Biology and Technology 47, 739-746 (2004)';
	*/
	
	/*
	$ref = 'R. Barrera, J. Avila, S. Gonzalez-Tellez, Unreliable supply of potable water and elevated Aedes aegypti larval indices: a causal relationship? Journal of the American Mosquito Control Association 9, 189-195 (1993)';
	*/
	
	// V. Sinh Nam, N. Thi Yen, H. Minh Duc, T. Cong Tu, V. Trong Thang, N. Hoang Le, L. Hoang San, L. Le Loan, V. T. Que Huong, L. H. Kim Khanh, H. T. Thuy Trang, L. Z. Lam, S. C. Kutcher, J. G. Aaskov, J. A. Jeffery, P. A. Ryan, B. H. Kay, Community-based control of Aedes aegypti by using Mesocyclops in southern Vietnam. Am J Trop Med Hyg 86, 850-859 (2012)
	
//	$ref = 'G. E. Coelho, M. N. Burattini, M. D. Teixeira, F. A. B. Coutinho, E. Massad, Dynamics of the 2006/2007 dengue outbreak in Brazil. Memorias Do Instituto Oswaldo Cruz 103, 535-U537 (2008)';
	
	if (preg_match('/[\.|\?]\s+(?<journal>[\w|\s|\-|\(|\)|,|&]+)\s+(?<volume>\d+),\s+(?<spage>[a-z]?\d+)\s*-\s*(?<epage>[a-z]?\d+)\s+\((?<year>[0-9]{4})\)/iu', $ref, $m))
	{
		//print_r($m);
		
		$sql = 'UPDATE `references` SET journal="' . $m['journal'] . '", volume="' . $m['volume'] . '", spage="' . $m['spage'] . '", epage="' . $m['epage'] . '", year="' . $m['year'] . '" WHERE id="' . $id . '";';
		echo $sql . "\n";
	}
	
	
	/*
	if (preg_match('/\.\s+(?<journal>\w+((\s+\w+)+)?)\s+(?<volume>\d+),\s+(?<spage>[a-z]?\d+)\s+\((?<year>[0-9]{4})\)/u', $ref, $m))
	{
		//print_r($m);
		
		$sql = 'UPDATE `references` SET journal="' . $m['journal'] . '", volume="' . $m['volume'] . '", spage="' . $m['spage'] . '", year="' . $m['year'] . '" WHERE id="' . $id . '";';
		echo $sql . "\n";
	}
	*/
	
	/*
	// P. Nart, DENGUE/DHF UPDATES (41): 21 OCT 2002 PROMED. 2002.
	if (preg_match('/(?<journal>PROMED)\.?\s+\(?(?<year>[0-9]{4})\)?/u', $ref, $m))
	{
		//print_r($m);
		
		$sql = 'UPDATE `references` SET journal="' . $m['journal'] .  '", year="' . $m['year'] . '" WHERE id="' . $id . '";';
		echo $sql . "\n";
	}
	*/
	
	/*
	if (preg_match('/(?<journal>PROMED)\.$/u', $ref, $m))
	{
		//print_r($m);
		
		$sql = 'UPDATE `references` SET journal="' . $m['journal'] .  '" WHERE id="' . $id . '";';
		echo $sql . "\n";
	}

	if (preg_match('/\((?<journal>PROMED),\s+(\(\w+\),\s+)(?<year>[0-9]{4})\)/u', $ref, $m))
	{
		//print_r($m);
		
		$sql = 'UPDATE `references` SET journal="' . $m['journal'] .  '", year="' . $m['year'] . '" WHERE id="' . $id . '";';
		echo $sql . "\n";
	}
	*/
	
	/*
	// Plos Neglected Tropical Diseases 3, - (2009)
	if (preg_match('/(?<journal>.*)\s+(?<volume>\d+),\s+(-\s+)?\((?<year>[0-9]{4})\)/u', $ref, $m))
	{
		//print_r($m);
		
		$sql = 'UPDATE `references` SET journal="' . $m['journal'] . '", volume="' . $m['volume'] . '", year="' . $m['year'] . '" WHERE id="' . $id . '";';
		echo $sql . "\n";
	}
	*/
	//exit();

}

?>