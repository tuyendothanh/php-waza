<?php
	require 'simple_html_dom.php';

	// Create DOM from URL or file
	$html = file_get_html('http://phimchieurap.vn/lich-chieu/');

	// Find all images
	$articles = [];
	foreach($html->find('article') as $article) {
		$item['thumbnail'] = $article->find('div.thumbnail', 0);
		$item['title']     = $article->find('div.title', 0);
	    $item['date']    = $article->find('div.date', 0);
	    $item['tools']    = $article->find('div.tools', 0);
	    $pos = strpos($item['date']->plaintext, '05/10/2015');
	    if ($pos == True) {
	    	$articles[] = $item;
	    }
	}
	//print_r($articles); 
	foreach ($articles as $key => $item) {
		echo $item['thumbnail'] . "\n";
		echo $item['title'] . "\n";
		echo $item['date'] . "\n";
		echo $item['tools'] . "\n";
		echo '<hr>';
	}


?>