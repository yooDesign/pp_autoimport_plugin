<?php
defined('ABSPATH') or die('No script kiddies please!');
function update_metas_only_titles()
{ //routine_A
	global $wpdb;
	global $csvf;
	global $dbhostname;
	global $dbname;
	global $dbusername;
	global $dbpassword;
	$conn = mysqli_connect($dbhostname, $dbusername, $dbpassword, $dbname);
	$csv_master = readCSV($csvf);
	$SKUS = getskus($conn);
	$recordscount = count($csv_master); 
	
	foreach($csv_master as $key => $a) { //routine_B
		$end_rec = $recordscount;
		
		$percent = number_format(($key / ($end_rec - 1) * 100) , 2) . "%";
		$percent_a = $key / ($end_rec - 1) * 100 . "%";
		echo '<script language="javascript">document.getElementById("perc2").innerHTML="' . $percent . '";</script>';
		// This is for the buffer achieve the minimum size in order to flush data
		echo str_repeat(' ', 1024);
        
		 
		$SKU = $SORT = $TITLE = $SHORT_DESCRIPTION = $FULL_DESCRIPTION = $LAUNCHED_DATE_A = $focuskw = "";
		$SKU = $a[2];
		$TITLE = $a[3];
		$TITLE = str_replace(",", "", $TITLE);
		$TITLE = str_replace("'", "", $TITLE);
		$TITLE = preg_replace(array(
			'/\s{2,}/',
			'/[\t\n]/'
		) , ' ', $TITLE);
		if ($SKU == "" || $TITLE == "" || $TITLE == "NULL" || $a[29] == "") {
			continue;
		}
		$exist = "";
		if (in_array($SKU, $SKUS)) {
			$exist = true;
		}
		else {
			$exist = false;
		}
		if ($exist) {
			$post_id = getpostid($conn, $SKU);
		}
		else {
			continue;
		}
		// /if ($name!="" && $name!="NULL"){//routine_C
		if ($TITLE != "" && $TITLE != "NULL" && $a[29] != "NULL") { //routine_C
			// ///POSTS///////
			// /////READ MASTER'S FILE FIELDS///////////////////////////////
			$SORT = $a[0];
			$SHORT_DESCRIPTION = $a[4];
			$SHORT_DESCRIPTION = remove_quotes($SHORT_DESCRIPTION);
			$FULL_DESCRIPTION = $a[5];
			$FULL_DESCRIPTION = remove_quotes($FULL_DESCRIPTION);
			$LAUNCHED_DATE_A = $a[6];
			$LAUNCHED_DATE_A = str_replace("/", "-", $LAUNCHED_DATE_A);
			$LAUNCHED_DATE = date("Y-m-d H:i:s", strtotime($LAUNCHED_DATE_A));
			 $NEWCOLLECTION = $a[9];
			 $CATEGORY = $a[14];
			$sku_tag = "";
			$sku_pattern = "";
			if ($CATEGORY == "Necklaces T") {
				$sku_tag = 't';
			}
			if ($CATEGORY == "Necklaces R") {
				$sku_tag = 'r';
			}
			if ($CATEGORY == "Necklaces S") {
				$sku_tag = 's';
			}
			$third = substr($SKU, 2, 1);
			$fourth = substr($SKU, 3, 1);
			if (strtolower($third) == $sku_tag) {
				$sku_pattern = substr($SKU, 0, 2);
				$sku_pattern = strtolower($sku_pattern);
			}
			if (strtolower($fourth) == $sku_tag) {
				$sku_pattern = substr($SKU, 0, 3);
				$sku_pattern = strtolower($sku_pattern);
			}
			 $CATEGORY2 = str_replace(" T", "", $CATEGORY);
			$CATEGORY2 = str_replace(" R", "", $CATEGORY2);
			$CATEGORY2 = str_replace(" S", "", $CATEGORY2);
			$NEWCOLOUR = $a[33];
			$DESIGN = $a[34];
			$strfocus = $TITLE;
			$wordsfocus = str_word_count($strfocus, 1);
			$lastWords = array_slice($wordsfocus, -5, 5);
			$focuskw = $lastWords[sizeof($lastWords) - 2] . ' ' . $lastWords[sizeof($lastWords) - 1];
			$post_content22 = $FULL_DESCRIPTION . " # " . $focuskw;
			// $post_content23 = get_extended_desc($post_content22, $NEWCOLLECTION, $CATEGORY2, $NEWCOLOUR, $SKU, $DESIGN, $sku_tag, $sku_pattern);
			$post_content23 = get_extended_desc2($post_content22, $NEWCOLLECTION, $CATEGORY2, $NEWCOLOUR, $SKU, $DESIGN, $sku_tag, $sku_pattern, $post_id);
			// ///////////////////////////////////////////////////
			$post_content23 = mysqli_real_escape_string($conn, $post_content23);
			$defaults=array();
			
			
			$post_name = strtolower($TITLE);
			$post_name = str_replace(", ", " ", $post_name);
			$post_name = str_replace("&amp;", " ", $post_name);
			$post_name = str_replace("&", " ", $post_name);
			$post_name = str_replace("  ", " ", $post_name);
			$post_name = str_replace(" ", "-", $post_name);
			$post_name = str_replace("--", "-", $post_name);
			$post_name = $SKU . "-" . $post_name;
			
			 
			$post_name=substr($post_name,0,40);
			if(substr($post_name, -1)=="-"){$post_name=substr($post_name,0,-1);}
			
			$defaults = array(
				'post_content' => '"' . $post_content23 . '"',
				'post_excerpt' => '"' . $SHORT_DESCRIPTION . '"',
				'post_title' => '"' . $TITLE . '"',
				'post_date' => '"' . $LAUNCHED_DATE . '"',
				'post_date_gmt' => '"' . $LAUNCHED_DATE . '"',
				'menu_order' => $SORT,
				'post_name' => '"' . $post_name . '"',
			);
			update_post($conn, $post_id, $defaults);
			$metas=array();
			$metas = array("full_desc" => $post_content22);
			upd_postmeta($conn, $post_id, $metas);
		} //routine_C
	} //routine_B
	mysqli_close($conn);
} //routine_A


function update_metas_only_prices()
{ //routine_A
	global $wpdb;
	global $csvf;
	global $dbhostname;
	global $dbname;
	global $dbusername;
	global $dbpassword;
	$conn = mysqli_connect($dbhostname, $dbusername, $dbpassword, $dbname);
	$csv_master = readCSV($csvf);
	$SKUS = getskus($conn);
	$recordscount = count($csv_master); 
	
	foreach($csv_master as $key => $a) { //routine_B
		$end_rec = $recordscount;
		
		$percent = number_format(($key / ($end_rec - 1) * 100) , 2) . "%";
		$percent_a = $key / ($end_rec - 1) * 100 . "%";
		echo '<script language="javascript">document.getElementById("perc2").innerHTML="' . $percent . '";</script>';
		// This is for the buffer achieve the minimum size in order to flush data
		echo str_repeat(' ', 1024);
        
		$SKU = $WEURO = $WPOUNDS = $WUSD = $REURO = $RPOUNDS = $RUSD ='';
		$SKU = $a[2];
		$TITLE = $a[3];
		 
		if ($SKU == "" || $TITLE == "" || $TITLE == "NULL" || $a[29] == "") {
			continue;
		}
		$exist = "";
		if (in_array($SKU, $SKUS)) {
			$exist = true;
		}
		else {
			$exist = false;
		}
		if ($exist) {
			$post_id = getpostid($conn, $SKU);
		}
		else {
			continue;
		}
		// /if ($name!="" && $name!="NULL"){//routine_C
		if ($TITLE != "" && $TITLE != "NULL" && $a[29] != "NULL") { //routine_C
			// ///POSTS///////
			// /////READ MASTER'S FILE FIELDS///////////////////////////////
			 
			$WEURO = $a[26];
			$WEURO = str_replace('.0', '', $WEURO);
			$WPOUNDS = $a[27];
			$WPOUNDS = str_replace('.0', '', $WPOUNDS);
			$WUSD = $a[28];
			$WUSD = str_replace('.0', '', $WUSD);
			$REURO = $a[29];
			$REURO = str_replace('.0', '', $REURO);
			// $REURO=round($REURO/1.2);
			$RPOUNDS = $a[30];
			$RPOUNDS = str_replace('.0', '', $RPOUNDS);
			// $RPOUNDS=round($RPOUNDS/1.2);
			$RUSD = $a[31];
			$RUSD = str_replace('.0', '', $RUSD);
			$metas=array(); 
			// P O S T M E T A //
			// ////////////////////////
			$metas = array(
				"_sku" => $SKU,
				"_regular_price" => $RPOUNDS,
				"_price" => $RPOUNDS,
				"_wholesale_price" => $WPOUNDS,
				"_usa_price" => $RUSD,
				"_europe_price" => $REURO,
				"_united-kingdom_price" => $RPOUNDS,
				"_usa_regular_price" => $RUSD,
				"_europe_regular_price" => $REURO,
				"_united-kingdom_regular_price" => $RPOUNDS,
				"_usa_wholesale_price" => $WUSD,
				"_europe_wholesale_price" => $WEURO,
				"_united-kingdom_wholesale_price" => $WPOUNDS
			);
			upd_postmeta($conn, $post_id, $metas);
		} //routine_C
	} //routine_B
	mysqli_close($conn);
} //routine_A


function update_metas_only_images()
{ //routine_A
	global $wpdb;
	global $csvf;
	global $dbhostname;
	global $dbname;
	global $dbusername;
	global $dbpassword;
	$conn = mysqli_connect($dbhostname, $dbusername, $dbpassword, $dbname);
	$csv_master = readCSV($csvf);
	$SKUS = getskus($conn);
	$recordscount = count($csv_master); 
	
	foreach($csv_master as $key => $a) { //routine_B
		$end_rec = $recordscount;
		$metas=array();
		$percent = number_format(($key / ($end_rec - 1) * 100) , 2) . "%";
		$percent_a = $key / ($end_rec - 1) * 100 . "%";
		echo '<script language="javascript">document.getElementById("perc2").innerHTML="' . $percent . '";</script>';
		// This is for the buffer achieve the minimum size in order to flush data
		echo str_repeat(' ', 1024);
        
		$SKU = $TITLE = $MODEL ='';
		$SKU = $a[2];
		$TITLE = $a[3];
		$MODEL = $a[13];
		 
		if ($SKU == "" || $TITLE == "" || $TITLE == "NULL" || $a[29] == "") {
			continue;
		}
		$exist = "";
		if (in_array($SKU, $SKUS)) {
			$exist = true;
		}
		else {
			$exist = false;
		}
		if ($exist) {
			$post_id = getpostid($conn, $SKU);
		}
		else {
			continue;
		}
		// /if ($name!="" && $name!="NULL"){//routine_C
		if ($TITLE != "" && $TITLE != "NULL" && $a[29] != "NULL") { //routine_C
			// ///POSTS///////
			// /////READ MASTER'S FILE FIELDS///////////////////////////////
			 
			 
			 $ims = get_images($conn, $SKU, $MODEL);
			 
			 if($ims[0]!='' && $ims[1]!=''){
			 $metas = array(
				"_thumbnail_id" => $ims[0],
				"_product_image_gallery" => $ims[1]
			);}
			 
			 if($ims[0]!='' && $ims[1]==''){
			 $metas = array(
				"_thumbnail_id" => $ims[0]
			);}
			 
			 if($ims[0]==''){
			 $metas = array();}
			 
			  
			if(!empty($metas)){ 
			upd_postmeta($conn, $post_id, $metas);
			}
			 
			 
		} //routine_C
	} //routine_B
	mysqli_close($conn);
} //routine_A


function update_metas_only_complete()
{ //routine_A
	global $wpdb;
	global $csvf;
	global $dbhostname;
	global $dbname;
	global $dbusername;
	global $dbpassword;
	$conn = mysqli_connect($dbhostname, $dbusername, $dbpassword, $dbname);
	$csv_master = readCSV($csvf);
	$retail_arr=array();$wholesale_arr=array();
	$SKUS = getskus($conn);
	$recordscount = count($csv_master); 
	
	foreach($csv_master as $key => $a) { //routine_B
		$end_rec = $recordscount;
		$metas=array();
		$percent = number_format(($key / ($end_rec - 1) * 100) , 2) . "%";
		$percent_a = $key / ($end_rec - 1) * 100 . "%";
		echo '<script language="javascript">document.getElementById("perc2").innerHTML="' . $percent . '";</script>';
		// This is for the buffer achieve the minimum size in order to flush data
		echo str_repeat(' ', 1024);
        
		$SKU = $TITLE = $MODEL ='';
		$SKU = $a[2];
		$TITLE = $a[3];
		
		 
		if ($SKU == "" || $TITLE == "" || $TITLE == "NULL" || $a[29] == "") {
			continue;
		}
		$exist = "";
		if (in_array($SKU, $SKUS)) {
			$exist = true;
		}
		else {
			$exist = false;
		}
		if ($exist) {
			$post_id = getpostid($conn, $SKU);
		}
		else {
			continue;
		}
		// /if ($name!="" && $name!="NULL"){//routine_C
		if ($TITLE != "" && $TITLE != "NULL" && $a[29] != "NULL") { //routine_C
			// ///POSTS///////
			// /////READ MASTER'S FILE FIELDS///////////////////////////////
			 $WHOLESALE = $a[35];
			$RETAIL = $a[36];
		if ($WHOLESALE == '1') {
				$wholesale_arr[] = $post_id;
			}
			if ($RETAIL == '1') {
				$retail_arr[] = $post_id;
			}
			 
			 
			 
		} //routine_C
	} //routine_B
	update_option('exclude_retailers', $retail_arr);
	         update_option('exclude_wholesale', $wholesale_arr);
	mysqli_close($conn);
} //routine_A