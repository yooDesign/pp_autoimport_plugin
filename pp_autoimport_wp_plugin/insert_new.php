<?php
defined('ABSPATH') or die('No script kiddies please!');
function insert_new()
{ //function
	global $wpdb;
	global $csvf;
	global $dbhostname;
	global $dbname;
	global $dbusername;
	global $dbpassword;
	$conn = mysqli_connect($dbhostname, $dbusername, $dbpassword, $dbname);
	$csv_master = readCSV($csvf);
	$nop = 0;
	$recordscount = count($csv_master);
	$SKUS = getskus($conn);
	foreach($csv_master as $key => $a) { //routine_B
		$end_rec = $recordscount;
		$percent = number_format(($key / ($end_rec - 1) * 100) , 2) . "%";
		$percent_a = $key / ($end_rec - 1) * 100 . "%";
		echo '<script language="javascript">document.getElementById("perc2").innerHTML="' . $percent . '";</script>';
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
			$donts[] = $SKU . "\r\n";
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
			continue;
		}
		else {
			$sku_exists = false;
			$my_post = array(
				'post_title' => 'erp_post_erp'
			);
			$MODEL = $a[13];
			$ims = get_images($conn, $SKU, $MODEL);
			if (!$ims) {
				$donts[] = $SKU . "\r\n";
				continue;
			}
			else {
				$post_id = insert_post($conn, $my_post);
			}
		}
		// /if ($name!="" && $name!="NULL"){//routine_C
		if ($TITLE != "" && $TITLE != "NULL" && $a[29] != "NULL") { //routine_C
			// ///POSTS///////
			// /////READ MASTER'S FILE FIELDS///////////////////////////////
			$SHORT_DESCRIPTION = $a[4];
			$SHORT_DESCRIPTION = remove_quotes($SHORT_DESCRIPTION);
			$FULL_DESCRIPTION = $a[5];
			$FULL_DESCRIPTION = remove_quotes($FULL_DESCRIPTION);
			$LAUNCHED_DATE_A = $a[6];
			$LAUNCHED_DATE_A = str_replace("/", "-", $LAUNCHED_DATE_A);
			$LAUNCHED_DATE = date("Y-m-d H:i:s", strtotime($LAUNCHED_DATE_A));
			$QUANTITY = $a[32];
			$WHOLESALE = $a[35];
			$RETAIL = $a[36];
			$WEIGHT = $a[10];
			$SIZE = $a[11];
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
			$strfocus = $TITLE;
			$wordsfocus = str_word_count($strfocus, 1);
			$lastWords = array_slice($wordsfocus, -5, 5);
			$focuskw = $lastWords[sizeof($lastWords) - 2] . ' ' . $lastWords[sizeof($lastWords) - 1];
			// ////////////////SET ADDDITIONAL VARIABLES TO BE STORED A//////////////
			// $post_content2   = $FULL_DESCRIPTION . " # " . $focuskw;
			// $post_content = get_extended_desc($post_content2, $WEIGHT, $SIZE, $NEWCOLLECTION, $CATEGORY, $NEWCOLOUR, $SKU, $DESIGN, $sku_tag, $sku_pattern);
			$post_content = $FULL_DESCRIPTION . " # " . $focuskw;
			$post_author = 12;
			$post_date = date("Y-m-d H:i:s");
			$full_desc = $FULL_DESCRIPTION;
			$post_excerpt = $SHORT_DESCRIPTION;
			$post_title = $TITLE;
			$post_status = "publish";
			$comment_status = "closed";
			$ping_status = "closed";
			$post_name = strtolower($TITLE);
			$post_name = str_replace(", ", " ", $post_name);
			$post_name = str_replace("&amp;", " ", $post_name);
			$post_name = str_replace("&", " ", $post_name);
			$post_name = str_replace("  ", " ", $post_name);
			$post_name = str_replace(" ", "-", $post_name);
			$post_name = str_replace("--", "-", $post_name);
			$post_name = $SKU . "-" . $post_name;
			$post_modified = date("Y-m-d H:i:s");
			$post_parent = 0;
			$Guid = "http://www.pinkpowder/?post_type=product&#038;p=" . $post_id;
			$post_type = "product";
			// ///////////////////////////////////////////////////
			// ///IMAGES//////////////////////////
			// ///IMAGES END//////////////////////////
			$defaults = array(
				'post_date' => '"' . $LAUNCHED_DATE . '"',
				'post_date_gmt' => '"' . $LAUNCHED_DATE . '"',
				'post_author' => $post_author,
				'post_content' => '"' . $post_content . '"',
				'post_excerpt' => '"' . $post_excerpt . '"',
				'post_title' => '"' . $post_title . '"',
				'post_status' => '"' . $post_status . '"',
				'comment_status' => '"' . $comment_status . '"',
				'ping_status' => '"' . $ping_status . '"',
				'post_name' => '"' . $post_name . '"',
				'post_modified' => '"' . $LAUNCHED_DATE . '"',
				'post_modified_gmt' => '"' . $LAUNCHED_DATE . '"',
				'post_parent' => $post_parent,
				'post_type' => '"' . $post_type . '"',
				'guid' => '"' . $Guid . '"'
			);
			update_post($conn, $post_id, $defaults);
			$metas = array(
				"_sku" => $SKU,
				"_thumbnail_id" => $ims[0],
				"_product_image_gallery" => $ims[1]
			);
			upd_postmeta($conn, $post_id, $metas);
			if ($CATEGORY == "Necklaces" || $CATEGORY == "Bracelets" || $CATEGORY == "Earrings" || $CATEGORY == "Rings" || $CATEGORY == "Key Chains" || $CATEGORY == "Cufflinks" || $CATEGORY == "Hand Chains" || $CATEGORY == "Body Chains") {
				$parent_category = "Jewellery";
				$child1_category = $CATEGORY;
				$child2_category = false;
			}
			if ($CATEGORY == "Body Chains") {
				echo "BC";
			}
			if ($CATEGORY == "Towel" || $CATEGORY == "Tote" || $CATEGORY == "Iphone Case" || $CATEGORY == "Case" || $CATEGORY == "Beach Bags" || $CATEGORY == "Wallets" || $CATEGORY == "Towels" || $CATEGORY == "Weekend Bags" || $CATEGORY == "Pillow" || $CATEGORY == "Clutch") {
				$parent_category = "Accessories";
				$child1_category = "Beach Essentials";
				$child2_category = $CATEGORY;
			}
			$cat_ids = array();
			// 1.Parent Category
			$parent_category_friendly = seoUrl($parent_category);
			$term = term_exists($parent_category, 'product_cat');
			if ($term === 0 || $term !== null || !$term) {
				wp_insert_term($parent_category, // the term
				'product_cat', // the taxonomy
				array(
					'slug' => $parent_category_friendly,
					'parent' => 0
				));
			}
			$term = term_exists($parent_category, 'product_cat');
			$term_id_parent = array(
				(int)$term['term_id']
			);
			$term_id_parent2 = $term['term_id'];
			$cat_ids[] = $term['term_id'];
			// //2.Child Category
			$child1_category_friendly = seoUrl($child1_category);
			$term2 = term_exists($child_category1, 'product_cat');
			if ($term2 === 0 || $term2 !== null || !$term2) {
				wp_insert_term($child1_category, // the term
				'product_cat', // the taxonomy
				array(
					'slug' => $child1_category_friendly,
					'parent' => (int)$term['term_id']
				));
			}
			$term2 = term_exists($child1_category, 'product_cat');
			$term_id2 = array(
				(int)$term2['term_id']
			);
			$cat_ids[] = $term2['term_id'];
			// //3.Child Category
			if ($child2_category) {
				$child2_category_friendly = seoUrl($child2_category);
				$term3 = term_exists($child2_category, 'product_cat');
				if ($term3 === 0 || $term3 !== null || !$term3) {
					wp_insert_term($child2_category, // the term
					'product_cat', // the taxonomy
					array(
						'slug' => $child2_category_friendly,
						'parent' => $term2['term_id']
					));
				}
				$term3 = term_exists($child2_category, 'product_cat');
				$term_id3 = array(
					(int)$term3['term_id']
				);
				$cat_ids[] = $term3['term_id'];
			}
			$cat_ids2 = array_map('intval', $cat_ids);
			$cat_ids2 = array_unique($cat_ids2);
			$term_taxonomy_ids = wp_set_object_terms($post_id, $cat_ids2, 'product_cat');
		} //routine_C
	} //routine_B
	file_put_contents('donts.txt', $donts);
} //funvtion