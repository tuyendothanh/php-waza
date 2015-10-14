<?php
	require 'simple_html_dom.php';

	// Create DOM from URL http://phimchieurap.vn/lich-chieu/
	$html_lichchieu = file_get_html('http://phimchieurap.vn/lich-chieu/');

	$ajax_filter_sphim = [];
	$ajax_filter_sposter = [];
	foreach($html_lichchieu->find('div[id=sphim] div[class=select-box-dropdown] div[class=item]') as $html_data_value) {
		$html_img = $html_data_value->find('img', 0);
		if(isset($html_img->src)) {
	    	foreach($html_data_value->find('div[data-value]') as $element) {
	    		$key = $element->getAttribute('data-value');
	    		$value = $element->find('div[class=citem-title]', 0);
	    		if ($value) {
	    			$ajax_filter_sphim[$key] = $value->plaintext;
	    			//echo end($ajax_filter_sphim) . '<br>';
	    		}
	    		$poster_thumnail = $element->find('div[class=citem-thumbnail] img', 0);
	    		if ($poster_thumnail) {
	    			$ajax_filter_sposter[$key] = $poster_thumnail->src;
	    			//echo $ajax_filter_sposter[$key] . '<br>';
	    		}
	    		else {
	    			$ajax_filter_sposter[$key] = "";
	    		}
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
				$key = $element->getAttribute('data-value');
	    		$value = $element->find('div[class=visible-dropdown] div', 0);
	    		if ($value) {
	    			$ajax_filter_slocation[$key] = $value->plaintext;
	    			//echo $value->plaintext . '<br>';
	    		}
	    		//$ajax_filter_slocation[] = $element->getAttribute('data-value');
			}
		}
	}

	$phim_infos = [];

	$pagination_last = 1;
	$html_phim_dang_chieu = file_get_html('http://phimchieurap.vn/phim/danh-muc/phim-dang-chieu/');
	$pagination = $html_phim_dang_chieu->find('ul[class=pagination]', 0);
	if ($pagination) {
		$pagination_last = $pagination->getAttribute('data-last-page');
	}

	for ($i=1; $i <= $pagination_last; $i++) {
		$my_url = sprintf('http://phimchieurap.vn/phim/danh-muc/phim-dang-chieu/page/%s/', $i);
		if ($i==1) {
			$my_url = 'http://phimchieurap.vn/phim/danh-muc/phim-dang-chieu/';
		}
		$html_phim_info = file_get_html($my_url);
		foreach ($html_phim_info->find('article') as $html_post) {
			$posts = $html_post->getAttribute('class');
			preg_match("/[0-9]+/", $posts, $kphims);
			if ($kphims) {
				$kphim = [0];
				$html_title = $html_post->find('h3[class=title]', 0);
				if ($html_title) {
					$html_link = $html_title->find('a', 0);
					if ($html_link ) {
						//$phim_info_links[$kphim] = $html_link->href; //$my_url;
						$html_phim_info = file_get_html($html_link->href);
						if ( $html_phim_info ) {
							$article_tag = sprintf('article[class=post-%s]', $kphim);
							$html_infos = $html_phim_info->find($article_tag, 0);
							if ( $html_infos ) {
								$html_title = $html_infos->find('h1[class=title]', 0);
								$item['title']     = $html_title->plaintext;
							    $item['excerpt']    = $html_infos->find('div[class=excerpt] p', 0);
							    $item['date'] = $html_infos->find('div[class="phim-date-mobile]', 0)->plaintext;
							    $item['thoigian']    = $html_infos->find('div[class=infos] div', 0);
							    $item['theloai']    = $html_infos->find('div[class=infos] div', 1);
							    $item['daodien']    = $html_infos->find('div[class=infos] div', 2);
							    $item['sanxuat']    = $html_infos->find('div[class=infos] div', 3);
							    $item['acts']    = $html_infos->find('div[class=acts]', 0);
							    $item['content']    = $html_infos->find('div[class=content]', 0);

							    $html_imdb_id = $html_infos->find('div[class=tools] dl dt span', 0);
							    $imdb_id = $html_imdb_id->getAttribute('data-imdb');
							    $params_imdb = array('ajax_type'=>'imdb', 'ajax_imdb_id'=>$imdb_id);
								$params_imdb_json_encode = json_encode($params_imdb);
								$params_imdb_base64_encode = base64_encode($params_imdb_json_encode);
								$imdb_url = sprintf('http://phimchieurap.vn/ajax/%s/', $params_imdb_base64_encode);
								$html_imdb = file_get_html($imdb_url);
								$item['imdb'] = json_decode($html_imdb->plaintext, true)['score'];

							    $phim_infos[$kphim] = $item;
							    /*
							    echo $item['title'] . '\t' . 
							    	$item['excerpt'] . '\t' . 
							    	$item['date'] . 
							    	$item['thoigian']->plaintext . 
							    	$item['theloai']->plaintext . 
							    	$item['daodien']->plaintext . 
							    	$item['sanxuat']->plaintext . 
							    	$item['imdb'] . 
							    	$item['acts'] . 
							    	$item['content'] . 
							    	'<br>';
							    */
							}
						}

					}
				}
			}
		}
	}

	$counter = 0;
	
	foreach ($ajax_filter_sdate as $sdate) {
		$file_path="./csv/{$sdate}.csv";
		$file=new SplFileObject($file_path,"w");

		$export_header=array("Phim", "Poster", "Địa Điểm", "Rạp", "Số Xuất Chiếu", "Ngày Chiếu");
		$file->fputcsv($export_header);

		echo '<table border="1"><thead><tr>';
		foreach ($export_header as $value) {
			echo '<th>' . $value . '</th>';
		}
		echo '<th colspan="30">Giờ Chiếu</th>';
		echo '</tr></thead><tbody>';

		foreach ($ajax_filter_sphim as $kphim => $sphim) {
			set_time_limit(120);
			// check link
			$linkOK = false;
			$params_array = array('ajax_type'=>'lich-chieu-filter',
							'ajax_filter_sphim'=>$kphim,
							'ajax_filter_srap'=>"0",
							'ajax_filter_sdate'=>$sdate,
							'ajax_filter_slocation'=>"0");
			$params_json_encode = json_encode($params_array);
			$params_base64_encode = base64_encode($params_json_encode);
			$my_url = sprintf('http://phimchieurap.vn/ajax/%s/', $params_base64_encode);
			$html = file_get_html($my_url);
			if ( $html ) {
				if ( $html->find('div.session-cumrap div[class=title]', 0) ) {
					$linkOK = true;
				}
			}
			if (!$linkOK) {
				continue;
			}

			foreach ($ajax_filter_slocation as $kloc => $sloc) {
				//echo "$sphim/$ajax_filter_sposter[$kphim]/$sloc/$sdate" . '<br>';
				$params_array = array('ajax_type'=>'lich-chieu-filter',
							'ajax_filter_sphim'=>$kphim,
							'ajax_filter_srap'=>"0",
							'ajax_filter_sdate'=>$sdate,
							'ajax_filter_slocation'=>$kloc);

				$params_json_encode = json_encode($params_array);

				$params_base64_encode = base64_encode($params_json_encode);

				$my_url = sprintf('http://phimchieurap.vn/ajax/%s/', $params_base64_encode);
				//echo sprintf('<a href="%s">%s</a>', $my_url, $my_url); //$my_url . '<br>';
				//continue;

				// Create DOM from URL or file
				$html = file_get_html($my_url);
				//echo $html;

				$session_sphim = [];
				$session_index = 0;
				
				while (true) {
					$session_rap = [];
					$html_cumrap_text = $html->find('div.session-cumrap div[class=title]', 0);
					if (!$html_cumrap_text) {
						break;
					}
					$session_rap[] = html_entity_decode($sphim, ENT_QUOTES, 'UTF-8');
					$session_rap[] = $ajax_filter_sposter[$kphim];
					$session_rap[] = $sloc;
					//$session_cumrap_img = $html->find('div.session-cumrap img', 0)->src;
					//$session_rap[] = $session_cumrap_img;
					$html_rap = $html->find('div[class=session-rap] a', $session_index, 0);
					$html_clearfix = $html->find('div[class=sessions clearfix]', $session_index);
					if ( $html_rap && $html_clearfix ) {
						$session_rap[] = html_entity_decode($html_rap->plaintext, ENT_QUOTES, 'UTF-8');

						$time_text = [];
						$times_counter = 0;
						foreach($html_clearfix->find('div[class=times] div[class=time]') as $html_time) {
							$times_counter++;
							$time_text[] = trim(preg_replace('/\t+/', '', $html_time->find('a', 0)->plaintext));
						}
						$session_rap[] = $times_counter;
						$session_rap[] = $sdate;
						$session_sphim[] = array_merge($session_rap, $time_text);
					}
					else {
						break;
					}
					$session_index++;
				}
				if ($session_sphim) {
					foreach ($session_sphim as $key => $value) {
						//echo implode(",", $value) . '<br>';
						echo '<tr>';
						foreach ($value as $fld) {
							echo '<th>' . $fld . '</th>';
						}
						$value_phim_info = $phim_infos[$kphim];
						foreach ($phim_infos as $key_phim_info => $value_phim_info) {
							/*
							echo $item['title'] . '\t' . 
							    	$item['excerpt'] . '\t' . 
							    	$item['date'] . 
							    	$item['thoigian']->plaintext . 
							    	$item['theloai']->plaintext . 
							    	$item['daodien']->plaintext . 
							    	$item['sanxuat']->plaintext . 
							    	$item['imdb'] . 
							    	$item['acts'] . 
							    	$item['content'];
							*/
						}
						echo '</tr>';
						$file->fputcsv($value);
					}
					$counter++;
				}
			}
		}
		echo '</tbody></table>';
	}
	echo "<hr>";
	echo $counter . ' dòng';

?>