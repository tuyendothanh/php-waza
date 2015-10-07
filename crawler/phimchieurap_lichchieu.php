<?php
	require 'simple_html_dom.php';

	// Create DOM from URL http://phimchieurap.vn/lich-chieu/
	$html_lichchieu = file_get_html('http://phimchieurap.vn/lich-chieu/');

	$ajax_filter_sphim = [];
	foreach($html_lichchieu->find('div[id=sphim] div[class=select-box-dropdown] div[class=item]') as $html_data_value) {
		$html_img = $html_data_value->find('img', 0);
		if(isset($html_img->src)) {
	    	foreach($html_data_value->find('div[data-value]') as $element) {
	    		//echo $element . '<br>';
	    		//echo $element->find('div[class=citem-title]', 0);
	    		$ajax_filter_sphim[] = $element->getAttribute('data-value');
	    		//echo end($ajax_filter_sphim) . '<br>';
	    	}
		}

	}
	
	$ajax_filter_srap = [];
	foreach($html_lichchieu->find('div[id=srap] div[class=select-box-dropdown] div[class=item]') as $html_data_value) {
		$html_img = $html_data_value->find('img', 0);
		if(isset($html_img->src)) {
	    	foreach($html_data_value->find('div[data-value]') as $element) {
	    		//echo $element . '<br>';
	    		//echo $element->find('div[class=citem-title]', 0);
	    		//echo $element->getAttribute('data-value');
	    		$ajax_filter_srap[] = $element->getAttribute('data-value');
	    		//echo end($ajax_filter_srap) . '<br>';
	    	}
		}
	}

	$ajax_filter_sdate = [];
	foreach($html_lichchieu->find('div[id=sdate] div[class=select-box-dropdown] div[class=item]') as $html_data_value) {
		foreach($html_data_value->find('div[data-value]') as $element) {
			//echo $element->getAttribute('data-value') . '<br>';
	    	$ajax_filter_sdate[] = $element->getAttribute('data-value');
	    	//echo end($ajax_filter_sdate) . '<br>';
		}
	}

	$ajax_filter_slocation = [];
	foreach($html_lichchieu->find('div[id=slocation] div[class=select-box-dropdown] div[class=item]') as $html_data_value) {
		foreach($html_data_value->find('div[data-value]') as $element) {
			$slocation = $element->getAttribute('data-value');
			if ( $slocation !== "0" ) {
				//echo $element->getAttribute('data-value') . '<br>';
	    		$ajax_filter_slocation[] = $element->getAttribute('data-value');
	    		//echo end($ajax_filter_slocation) . '<br>';
			}
		}
	}
	
	$counter = 0;
	foreach ($ajax_filter_srap as $srap) {
		foreach ($ajax_filter_sdate as $sdate) {
			foreach ($ajax_filter_slocation as $slocation) {
				foreach ($ajax_filter_sphim as $sphim) {
					$params_array = array('ajax_type'=>'lich-chieu-filter',
						'ajax_filter_sphim'=>$sphim,
						'ajax_filter_srap'=>$srap,
						'ajax_filter_sdate'=>$sdate,
						'ajax_filter_slocation'=>$slocation);

					$params_json_encode = json_encode($params_array);

					$params_base64_encode = base64_encode($params_json_encode);

					$my_url = sprintf('http://phimchieurap.vn/ajax/%s/', $params_base64_encode);
					//echo sprintf('<a href="%s">%s</a>', $my_url, $my_url); //$my_url . '<br>';
					//continue;

					// Create DOM from URL or file
					$html = file_get_html($my_url);
					//echo $html;

					$session_cumrap = [];
					$session_index = 0;
					
					while (true) {
						$session_rap = [];
						$html_cumrap_text = $html->find('div.session-cumrap div[class=title]', 0);
						if (!$html_cumrap_text) {
							break;
						}
						$session_cumrap_text = $html_cumrap_text->plaintext;
						$session_rap[] = $session_cumrap_text;
						//$session_cumrap_img = $html->find('div.session-cumrap img', 0)->src;
						//$session_rap[] = $session_cumrap_img;
						$html_rap = $html->find('div[class=session-rap] a', $session_index, 0);
						$html_clearfix = $html->find('div[class=sessions clearfix]', $session_index);
						if ( $html_rap && $html_clearfix ) {
							$session_rap[] = $html_rap->plaintext;

							$time_text = [];
							$times_counter = 0;
							foreach($html_clearfix->find('div[class=times] div[class=time]') as $html_time) {
								$times_counter++;
								$time_text[] = $html_time->find('a', 0)->plaintext;
							}
							$session_rap[] = $times_counter;
							$session_cumrap[] = array_merge($session_rap, $time_text);
						}
						else {
							break;
						}
						$session_index++;
					}
					if ($session_cumrap) {
						foreach ($session_cumrap as $key => $value) {
							echo implode(",", $value) . '<br>';
						}
						$counter++;
						if ($counter>10) {
							exit();
						}
					}



				}
			}
		}
	}
	

	

	//echo ($session_cumrap_text) ;


	/*
	// Find all images
	$articles = [];
	foreach($html->find('article') as $article) {
		$item['thumbnail'] = $article->find('div.thumbnail', 0);
		$item['title']     = $article->find('div.title', 0);
	    $item['date']    = $article->find('div.date', 0);
	    $item['tools']    = $article->find('div.tools', 0);
	    $match_result = preg_match('#16/10/2015#', $item['date']);
	    if ($match_result == 1) {
	    	$articles[] = $item;
	    }
	    echo $item['date'];
	}
	//print_r($articles); 
	foreach ($articles as $key => $item) {
		echo $item['thumbnail'] . "\n";
		echo $item['title'] . "\n";
		echo $item['date'] . "\n";
		echo $item['tools'] . "\n";
		echo '<hr>';
	}
	*/

?>