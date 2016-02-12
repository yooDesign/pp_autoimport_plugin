<?php
defined('ABSPATH') or die('No script kiddies please!');
function update_attributes()
{ //routine_A
	global $wpdb;
	global $csvf;
	global $dbhostname;
	global $dbname;
	global $dbusername;
	global $dbpassword;
	$retail_arr=array();$wholesale_arr=array();
	$conn = mysqli_connect($dbhostname, $dbusername, $dbpassword, $dbname);
	$SKUS = getskus($conn);
	$csv_master = readCSV($csvf);
	$recordscount = count($csv_master);
	foreach($csv_master as $key => $a) { //routine_B
		// if($key<1300){continue;}
		$end_rec = $recordscount;
		$percent = number_format(($key / ($end_rec - 1) * 100) , 2) . "%";
		 
		echo '<script language="javascript">document.getElementById("perc3").innerHTML="' . $percent . '";</script>';
		// This is for the buffer achieve the minimum size in order to flush data
		echo str_repeat(' ', 1024 * 4);
		$SKU = $SORT = $TITLE = $SHORT_DESCRIPTION = $FULL_DESCRIPTION = $LAUNCHED_DATE_A = $SEASON = $COLLECTION = $NEWCOLLECTION = $LIFESTYLE_IMAGE = $MODEL_IMAGE = $CATEGORY = $sku_tag = $sku_pattern = $third = $fourth = $COLOUR_ID = $STYLE_ID = $OCCASION_ID = $STONE_ID = $COLOUR_VARIATIONS = $LAYERED_WITH = $MATCH_WITH = $WEURO = $WPOUNDS = $WUSD = $REURO = $RPOUNDS = $RUSD = $QUANTITY = $NEWCOLOUR = $DESIGN = $WHOLESALE = $RETAIL = $WEIGHT = $SIZE = $focuskw = "";
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
			$SHORT_DESCRIPTION = $a[4];
			$SHORT_DESCRIPTION = remove_quotes($SHORT_DESCRIPTION);
			$FULL_DESCRIPTION = $a[5];
			$FULL_DESCRIPTION = remove_quotes($FULL_DESCRIPTION);
			$SEASON = $a[7];
			$COLLECTION = $a[8];
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
			$CATEGORY = str_replace(" T", "", $CATEGORY);
			$CATEGORY = str_replace(" R", "", $CATEGORY);
			$CATEGORY = str_replace(" S", "", $CATEGORY);
			$COLOUR_ID = $a[15];
			$COLOUR_ID = remove_quotes($COLOUR_ID);
			$COLOUR_ID = str_replace("; ", ";", $COLOUR_ID);
			$STYLE_ID = $a[16];
			$STYLE_ID = remove_quotes($STYLE_ID);
			$STYLE_ID = str_replace("; ", ";", $STYLE_ID);
			$OCCASION_ID = $a[17];
			$OCCASION_ID = remove_quotes($OCCASION_ID);
			$STONE_ID = $a[18];
			$STONE_ID = remove_quotes($STONE_ID);
			$STONE_ID = str_replace("; ", ";", $STONE_ID);
			$COLOUR_VARIATIONS = $a[19];
			$COLOUR_VARIATIONS = remove_quotes($COLOUR_VARIATIONS);
			$COLOUR_VARIATIONS = str_replace("; ", ";", $COLOUR_VARIATIONS);
			$NEWCOLOUR = $a[33];
			$DESIGN = $a[34];
			$WHOLESALE = $a[35];
			$RETAIL = $a[36];
			$WEIGHT = $a[10];
			if (substr($WEIGHT, 0, 1) == ".") {
				$WEIGHT = "0" . $WEIGHT;
			}
			$SIZE = $a[11];
			if (substr($SIZE, 0, 1) == ".") {
				$SIZE = "0" . $SIZE;
			}
			if ($SIZE == NULL || $SIZE == "NULL") {
				$SIZE = "";
			}
			if ($WEIGHT == NULL || $WEIGHT == "NULL") {
				$WEIGHT = "";
			}
			if ($WHOLESALE == '1') {
				$wholesale_arr[] = $post_id;
			}
			if ($RETAIL == '1') {
				$retail_arr[] = $post_id;
			}
			// T A X O N O M I E S //
			// //////////////////////////
			$colors = array();
			$stones = array();
			$stones_arr = array();
			$colors_arr = array();
			$colors = explode(";", $COLOUR_ID);
			$stones = explode(";", $STONE_ID);
			$slug = $slug2 = "";
			foreach($colors as $colors_a) {
				$colors_arr[] = $colors_a;
				$slug = "clr_" . seoUrl($colors_a);
				clean_taxonomies('pa_colour', $post_id, $slug);
			}
			fattribute2("clr_", "pa_colour", $colors_arr, $post_id);
			foreach($stones as $stones_a) {
				$stones_arr[] = $stones_a;
				$slug2 = "stn_" . seoUrl($stones_a);
				clean_taxonomies('pa_stone', $post_id, $slug2);
			}
			fattribute2("stn_", "pa_stone", $stones_arr, $post_id);
			clean_taxonomies('pa_occasion', $post_id, 'occ_' . seoUrl($OCCASION_ID));
			fattribute("occ_", "pa_occasion", $OCCASION_ID, $post_id);
			clean_taxonomies('pa_season', $post_id, 'ssn_' . seoUrl($SEASON));
			fattribute("ssn_", "pa_season", $SEASON, $post_id);
			clean_taxonomies('pa_collection', $post_id, 'coll_' . seoUrl($NEWCOLLECTION));
			fattribute("coll_", "pa_collection", $NEWCOLLECTION, $post_id);
			clean_taxonomies('pa_style', $post_id, 'stl_' . seoUrl($STYLE_ID));
			fattribute("stl_", "pa_style", $STYLE_ID, $post_id);
			clean_taxonomies('pa_new-colour', $post_id, 'nclr_' . seoUrl($NEWCOLOUR));
			fattribute("nclr_", "pa_new-colour", $NEWCOLOUR, $post_id);
			/*
			clean_taxonomies('pa_designcode',$post_id,'dcd_'.seoUrl($DESIGN));
			fattribute("dcd_","pa_designcode",$DESIGN,$post_id);
			*/
			$attributes_data = array(
				'pa_colour' => array(
					'name' => 'pa_colour',
					'value' => '',
					'position' => '0',
					'is_visible' => '0',
					'is_variation' => '0',
					'is_taxonomy' => '1'
				) ,
				'pa_stone' => array(
					'name' => 'pa_stone',
					'value' => '',
					'position' => '0',
					'is_visible' => '0',
					'is_variation' => '0',
					'is_taxonomy' => '1'
				) ,
				'pa_season' => array(
					'name' => 'pa_season',
					'value' => '',
					'position' => '0',
					'is_visible' => '0',
					'is_variation' => '0',
					'is_taxonomy' => '1'
				) ,
				'pa_style' => array(
					'name' => 'pa_style',
					'value' => '',
					'position' => '0',
					'is_visible' => '0',
					'is_variation' => '0',
					'is_taxonomy' => '1'
				) ,
				'pa_new-colour' => array(
					'name' => 'pa_new-colour',
					'value' => '',
					'position' => '0',
					'is_visible' => '0',
					'is_variation' => '0',
					'is_taxonomy' => '1'
				) ,
				'pa_collection' => array(
					'name' => 'pa_collection',
					'value' => '',
					'position' => '0',
					'is_visible' => '0',
					'is_variation' => '0',
					'is_taxonomy' => '1'
				) ,
				'pa_occasion' => array(
					'name' => 'pa_occasion',
					'value' => '',
					'position' => '0',
					'is_visible' => '0',
					'is_variation' => '0',
					'is_taxonomy' => '1'
				)
			);
			update_post_meta($post_id, "_product_attributes", $attributes_data);
			$pr_matchlayer = $CATEGORY . ',' . $NEWCOLLECTION . ',' . $NEWCOLOUR;
			$metas = array(
				"pr_matchlayer" => $pr_matchlayer,
				"pr_designcode" => $DESIGN
			);
			upd_postmeta($conn, $post_id, $metas);
		} //routine_C
	} //routine_B
	update_option('exclude_retailers', $retail_arr);
	update_option('exclude_wholesale', $wholesale_arr);
} //routine_A

?>