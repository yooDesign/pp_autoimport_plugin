<?php
defined('ABSPATH') or die('No script kiddies please!');
function update_metas()
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
		echo '<script language="javascript">document.getElementById("perc4").innerHTML="' . $percent . '";</script>';
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
			$SORT = $a[0];
			$SHORT_DESCRIPTION = $a[4];
			$SHORT_DESCRIPTION = remove_quotes($SHORT_DESCRIPTION);
			$FULL_DESCRIPTION = $a[5];
			$FULL_DESCRIPTION = remove_quotes($FULL_DESCRIPTION);
			$LAUNCHED_DATE_A = $a[6];
			$LAUNCHED_DATE_A = str_replace("/", "-", $LAUNCHED_DATE_A);
			$LAUNCHED_DATE = date("Y-m-d H:i:s", strtotime($LAUNCHED_DATE_A));
			$SEASON = $a[7];
			$COLLECTION = $a[8];
			$NEWCOLLECTION = $a[9];
			$LIFESTYLE_IMAGE = $a[12];
			$LIFESTYLE_IMAGE = remove_quotes($LIFESTYLE_IMAGE);
			$LIFESTYLE_IMAGE = str_replace(" ", "", $LIFESTYLE_IMAGE);
			$MODEL_IMAGE = $a[13];
			$MODEL_IMAGE = remove_quotes($MODEL_IMAGE);
			$MODEL_IMAGE = str_replace(" ", "", $MODEL_IMAGE);
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
			$QUANTITY = $a[32];
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
			$MODEL = $a[13];
			$ims = get_images($conn, $SKU, $MODEL);
			$strfocus = $TITLE;
			$wordsfocus = str_word_count($strfocus, 1);
			$lastWords = array_slice($wordsfocus, -5, 5);
			$focuskw = $lastWords[sizeof($lastWords) - 2] . ' ' . $lastWords[sizeof($lastWords) - 1];
			$post_content22 = $FULL_DESCRIPTION . " # " . $focuskw;
			// $post_content23 = get_extended_desc($post_content22, $NEWCOLLECTION, $CATEGORY2, $NEWCOLOUR, $SKU, $DESIGN, $sku_tag, $sku_pattern);
			$post_content23 = get_extended_desc2($post_content22, $NEWCOLLECTION, $CATEGORY2, $NEWCOLOUR, $SKU, $DESIGN, $sku_tag, $sku_pattern, $post_id);
			// ///////////////////////////////////////////////////
			$post_content23 = mysqli_real_escape_string($conn, $post_content23);
			/*mysqli_query($conn, "UPDATE wp_posts SET
			post_titlet='$TITLE',
			post_content='$post_content23',
			post_excerpt='$SHORT_DESCRIPTION',
			post_date='$LAUNCHED_DATE',
			post_date_gmt='$LAUNCHED_DATE',
			post_modified='$LAUNCHED_DATE',
			post_modified_gmt='$LAUNCHED_DATE',
			menu_order = $key WHERE ID=" . $post_id);
			*/
			$defaults = array(
				'post_content' => '"' . $post_content23 . '"',
				'post_excerpt' => '"' . $SHORT_DESCRIPTION . '"',
				'post_title' => '"' . $TITLE . '"',
				'post_date' => '"' . $LAUNCHED_DATE . '"',
				'post_date_gmt' => '"' . $LAUNCHED_DATE . '"',
				'menu_order' => $SORT
			);
			update_post($conn, $post_id, $defaults);
			// P O S T M E T A //
			// ////////////////////////
			$metas = array(
				"_sku" => $SKU,
				"_visibility" => "visible",
				"_edit_last" => "12",
				"_stock_status" => "instock",
				"_downloadable" => "no",
				"_virtual" => "no",
				"_tax_status" => "taxable",
				"_featured" => "no",
				"_usa_price_method" => "manual",
				"_europe_price_method" => "manual",
				"_united-kingdom_price_method" => "manual",
				"_regular_price" => $RPOUNDS,
				"_price" => $RPOUNDS,
				"_weight" => $WEIGHT,
				"_length" => $SIZE,
				"_wholesale_price" => $WPOUNDS,
				"_yoast_wpseo_focuskw" => $focuskw,
				"_yoast_wpseo_title" => $TITLE,
				"_yoast_wpseo_metadesc" => $TITLE . ". " . $SHORT_DESCRIPTION . "-" . $focuskw,
				"_usa_price" => $RUSD,
				"_europe_price" => $REURO,
				"_united-kingdom_price" => $RPOUNDS,
				"_usa_regular_price" => $RUSD,
				"_europe_regular_price" => $REURO,
				"_united-kingdom_regular_price" => $RPOUNDS,
				"_usa_wholesale_price" => $WUSD,
				"_europe_wholesale_price" => $WEURO,
				"_united-kingdom_wholesale_price" => $WPOUNDS,
				"full_desc" => $post_content22,
				"boxed_product_image" => "on",
				"_wpb_vc_js_status" => "true",
				"_wpb_vc_js_interface_version" => "0",
				"extended_product_page" => "on",
				"sizing_guide" => "off",
				"thumbnail_product_image" => "on",
				"_manage_stock" => "no",
				"related_products" => "off",
				"slide_template" => "default",
				"_thumbnail_id" => $ims[0],
				"_product_image_gallery" => $ims[1]
			);
			upd_postmeta($conn, $post_id, $metas);
		} //routine_C
	} //routine_B
} //routine_A