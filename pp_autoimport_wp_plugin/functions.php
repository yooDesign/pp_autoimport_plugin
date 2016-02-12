<?php
defined('ABSPATH') or die('No script kiddies please!');
function readCSV($csvFile)
{
	$file_handle = fopen($csvFile, 'r');
	while (!feof($file_handle)) {
		$line_of_text[] = fgetcsv($file_handle, 0, ';');
	}
	fclose($file_handle);
	return $line_of_text;
}
function getskus($conn)
{
	$SKUS = array();
	$sql = "SELECT * FROM wp_postmeta where meta_key='_sku'";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$SKUS[] = $row['meta_value'];
		}
	}
	return $SKUS;
}
function getpostid($conn, $SKU)
{
	$sql = "SELECT * FROM wp_postmeta where meta_key='_sku' AND meta_value='$SKU'";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$post_id = $row['post_id'];
		}
	}
	return $post_id;
}
function insert_post($conn, $mypost)
{
	$keys = '';
	$vals = '';
	foreach($mypost as $key => $p) {
		$keys.= $key . ",";
		$vals.= $p . ",";
	}
	$keys = substr($keys, 0, -1);
	$vals = substr($vals, 0, -1);
	// $vals=addslashes($vals);
	$vals = mysqli_real_escape_string($conn, $vals);
	$sql = "INSERT INTO wp_posts (" . $keys . ") VALUES ('" . $vals . "')";
	if (mysqli_query($conn, $sql)) {
		// echo "New pos<br/>";
	}
	else {
		echo "Error: " . $sql . "<br />" . mysqli_error($conn);
	}
	$id = mysqli_insert_id($conn);
	return $id;
}
function update_post($conn, $post_id, $mypost)
{
	$keys = '';
	$vals = '';
	$sd = '';
	foreach($mypost as $key => $p) {
		$sd.= $key . "=" . $p . ",";
		$keys.= $key . ",";
		$vals.= $p . ",";
	}
	$keys = substr($keys, 0, -1);
	$vals = substr($vals, 0, -1);
	$sd = substr($sd, 0, -1);
	// $sd=mysqli_real_escape_string($conn,$sd);
	$sql = "UPDATE wp_posts SET " . $sd . " WHERE ID=$post_id";
	if ($conn->query($sql) === TRUE) {
		// echo "New record<br/>";
	}
	else {
		echo "Error: " . $sql . "<br />" . mysqli_error($conn);
	}
}
function upd_postmeta($conn, $post_id, $metas)
{
	$keys = '';
	$vals = '';
	$sd = '';
	foreach($metas as $key => $p) {
		if ($p != '') {
			$pp = mysqli_real_escape_string($conn, $p);
			$sql = "SELECT * FROM wp_postmeta where meta_key='$key' AND meta_value='$p' AND post_id=$post_id";
			$result = $conn->query($sql);
			if ($result->num_rows > 0) {
				$sql1 = "UPDATE wp_postmeta SET meta_value='$pp' WHERE meta_key='$key' AND post_id=$post_id";
				if ($conn->query($sql1) === TRUE) {
					// echo "Record updated successfully";
				}
				else {
					echo "A Error updating record: " . $conn->error;
				}
			}
			else {
				$sql2 = "INSERT INTO wp_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, $post_id, '$key', '$pp')";
				if ($conn->query($sql2) === TRUE) {
					// echo "B Record inserted successfully";
				}
				else {
					echo "B Error inserting record: " . $conn->error;
				}
			}
		}
	}
}
function get_images($conn, $SKU, $model)
{
	$gall = array();
	$im = array();
	if (strpos($model, ";") === false) {
		$md[] = $model;
	}
	else {
		$y = explode(";", $model);
		foreach($y as $yyy) {
			$md[] = str_replace(" ", "", $yyy);
		}
	}
	$im1 = strtolower($SKU) . '.jpg';
	$sql1 = "SELECT * FROM wp_posts where guid LIKE '%" . $im1 . "%' AND post_type='attachment'";
	$result1 = $conn->query($sql1);
	if ($result1->num_rows > 0) {
		while ($row1 = $result1->fetch_assoc()) {
			$image1_ID = $row1['ID'];
			array_push($gall, $image2_ID);
		}
		$r = true;
	}
	else {
		$r = false;
	}
	$im2 = strtolower($SKU) . '\_a.jpg';
	$sql2 = "SELECT * FROM wp_posts where guid LIKE '%" . $im2 . "%' AND post_type='attachment'";
	$result2 = $conn->query($sql2);
	if ($result2->num_rows > 0) {
		while ($row2 = $result2->fetch_assoc()) {
			$image2_ID = $row2['ID'];
			array_push($gall, $image2_ID);
		}
	}
	$im3 = strtolower($SKU) . '\_b.jpg';
	$sql3 = "SELECT * FROM wp_posts where guid LIKE '%" . $im3 . "%' AND post_type='attachment'";
	$result3 = $conn->query($sql3);
	if ($result3->num_rows > 0) {
		while ($row3 = $result3->fetch_assoc()) {
			$image3_ID = $row3['ID'];
			array_push($gall, $image3_ID);
		}
	}
	$im4 = strtolower($SKU) . '\_d.jpg';
	$sql4 = "SELECT * FROM wp_posts where guid LIKE '%" . $im4 . "%' AND post_type='attachment'";
	$result4 = $conn->query($sql4);
	if ($result4->num_rows > 0) {
		while ($row4 = $result4->fetch_assoc()) {
			$image4_ID = $row4['ID'];
			array_push($gall, $image4_ID);
		}
	}
	foreach($md as $mdimg) {
		if ($mdimg != '') {
			$im5 = strtolower($SKU) . '_' . $mdimg . '.jpg';
			$sql5 = "SELECT * FROM wp_posts where guid LIKE '%" . $im5 . "%' AND post_type='attachment'";
			$result5 = $conn->query($sql5);
			if ($result5->num_rows > 0) {
				while ($row5 = $result5->fetch_assoc()) {
					$image5_ID = $row5['ID'];
					array_push($gall, $image5_ID);
				}
			}
		}
	}
	if ($image2_ID || $image5_ID) {
		$gallery = implode(",", $gall);
	}
	else {
		$gallery = '';
	}
	$im[0] = $image1_ID;
	$im[1] = $gallery;
	if ($r) {
		return $im;
	}
	else {
		return false;
	}
}
function sku_existsq($SKU)
{
	include (plugin_dir_path(__FILE__) . 'config.php');

	$con = mysqli_connect("localhost", $dbusername, $dbpassword, $dbname);
	$sku_ = mysqli_query($con, "SELECT * FROM wp_postmeta where meta_key='_sku' AND  meta_value='" . $SKU . "'");
	if (mysqli_num_rows($sku_) > 0) {
		while ($row = mysqli_fetch_array($sku_)) {
			$post_id = $row['post_id'];
		}
		$sku_exists = true;
	}
	else {
		$sku_exists = false;
	}
	return $sku_exists;
}
function display_array($arr)
{
	echo "<br/>";
	foreach($arr as $k => $a) {
		if ($k == 0) {
			foreach($a as $key => $aa) {
				echo $key . " - " . $aa . "<br/>";
			}
		}
	}
}
function seoUrl($string)
{
	// Lower case everything
	$string = strtolower($string);
	// Make alphanumeric (removes all other characters)
	$string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
	// Clean up multiple dashes or whitespaces
	$string = preg_replace("/[\s-]+/", " ", $string);
	// Convert whitespaces and underscore to dash
	$string = preg_replace("/[\s_]/", "-", $string);
	return $string;
}
function fattribute($pre, $taxonomy_name, $term, $postid)
{
	global $wpdb;
	$attr_friendly = $pre . seoUrl($term);
	// echo $colors_a."-".$attr_friendly."<br/>";
	$myrows2 = $wpdb->get_results("SELECT * FROM wp_terms where slug='$attr_friendly'", OBJECT);
	if (!empty($myrows2)) {
		$_exists = true;
		$term_id = $myrows2[0]->term_id;
		$term_id2 = $term_id;
	}
	else {
		$_exists = false;
	}
	if (!$_exists) {
		wp_insert_term($term, // the term
		$taxonomy_name, // the taxonomy
		array(
			'slug' => $attr_friendly
		));
		$myrows3 = $wpdb->get_results("SELECT * FROM wp_terms where slug='$attr_friendly'", OBJECT);
		if (!empty($myrows3)) {
			$_exists2 = true;
			$term_id2 = $myrows3[0]->term_id;
		}
		else {
			$_exists2 = false;
			$term_id2 = $term_id;
		}
	}
	wp_set_object_terms($postid, array(
		(int)$term_id2
	) , $taxonomy_name);
}
function fattribute2($pre, $taxonomy_name, $term2, $postid)
{
	global $wpdb;
	foreach($term2 as $t) {
		$attr_friendly = $pre . seoUrl($t);
		// echo $colors_a."-".$attr_friendly."<br/>";
		// wp_remove_object_terms( $postid, $attr_friendly, $taxonomy_name );
		$myrows2 = $wpdb->get_results("SELECT * FROM wp_terms where slug='$attr_friendly'", OBJECT);
		if (!empty($myrows2)) {
			$_exists = true;
			$term_id = $myrows2[0]->term_id;
			$term_id2 = $term_id;
		}
		else {
			$_exists = false;
		}
		if (!$_exists) {
			wp_insert_term($t, // the term
			$taxonomy_name, // the taxonomy
			array(
				'slug' => $attr_friendly
			));
			$myrows3 = $wpdb->get_results("SELECT * FROM wp_terms where slug='$attr_friendly'", OBJECT);
			if (!empty($myrows3)) {
				$_exists2 = true;
				$term_id2 = $myrows3[0]->term_id;
			}
			else {
				$_exists2 = false;
				$term_id2 = $term_id;
			}
		}
		$ttt[] = $term_id2;
		$ttt2 = array_map('intval', $ttt);
		$ttt2 = array_unique($ttt2);
	}
	wp_set_object_terms($postid, $ttt2, $taxonomy_name);
	//
}
function clean_taxonomies($taxonomy, $post_id, $slug_name)
{
	include (plugin_dir_path(__FILE__) . 'config.php');

	$con = mysqli_connect("localhost", $dbusername, $dbpassword, $dbname);
	$query_ = mysqli_query($con, "SELECT * FROM wp_terms where slug='" . $slug_name . "'");
	while ($row = mysqli_fetch_array($query_)) {
		$query2_ = mysqli_query($con, "SELECT * FROM wp_term_taxonomy where term_id='" . $row['term_id'] . "'");
		while ($row2 = mysqli_fetch_array($query2_)) {
			mysqli_query($con, "DELETE FROM wp_term_relationships WHERE object_id='$post_id' AND term_taxonomy_id='" . $row2['term_taxonomy_id'] . "'");
			mysqli_query($con, "UPDATE `" . $dbname . "`.`wp_term_taxonomy` SET `count`='0' WHERE term_taxonomy_id='" . $row2['term_taxonomy_id'] . "'");
		}
	}
	// mysqli_query($con,"DELETE FROM wp_term_relationships WHERE object_id='$post_id'");
}
function insert_attribute($post_id)
{
	global $wpdb;
	$taxonomies = array(
		"pa_color"
	);
	foreach($taxonomies as $taxns) {
		$arrr = array();
		$myrows4 = $wpdb->get_results("SELECT * FROM wp_term_relationships where object_id=$post_id", OBJECT);
		if (!empty($myrows4)) {
			foreach($myrows4 as $r4) {
				$term_taxonomy_id2 = $r4->term_taxonomy_id;
				echo "@" . $term_taxonomy_id2 . "@";
				$myrows5 = $wpdb->get_results("SELECT * FROM wp_term_taxonomy where term_taxonomy_id=$term_taxonomy_id2 AND taxonomy='$taxns'", OBJECT);
				if (!empty($myrows5)) {
					echo $myrows5[0]->term_id;
					$tsj = $myrows5[0]->term_id;;
					$myrows6 = $wpdb->get_results("SELECT * FROM wp_terms where term_id=$tsj", OBJECT);
					if (!empty($myrows6)) {
						$vall = $myrows6[0]->name;
					}
					$arrr = array(
						"name" => $taxns,
						"value" => $vall,
						"position" => 0,
						"is_visible" => 1,
						"is_variation" => 1,
						"is_taxonomy" => 1
					);
					$big_arr = array();
				}
				print_r($arrr);
			}
		}
		$fnalarr = array(
			$taxns => array(
				$arrr
			)
		);
		update_post_meta($post_id, "_product_attributes", maybe_serialize($fnalarr));
	}
}
function variable_product($post_id, $post_title, $post_date, $post_author, $post_content, $post_excerpt, $post_status, $comment_status, $ping_status, $post_modified, $temaxia, $price)
{
	$variable1 = array(
		'post_title' => 'My post'
	);
	$post_id_variable1 = wp_insert_post($variable1);
	$post_title1 = "Variation #" . $post_id_variable1 . " of " . $post_title;
	$post_name1 = "product-" . $post_id . "-variation-2";
	$variable2 = array(
		'post_title' => 'My post'
	);
	$post_id_variable2 = wp_insert_post($variable2);
	$post_title2 = "Variation #" . $post_id_variable2 . " of " . $post_title;
	$post_name2 = "product-" . $post_id . "-variation-3";
	$defaults_variable1 = array(
		'ID' => $post_id_variable1,
		'post_date' => $post_date,
		'post_date_gmt' => $post_date,
		'post_author' => $post_author,
		'post_content' => $post_content,
		'post_excerpt' => $post_excerpt,
		'post_title' => $post_title1,
		'post_status' => $post_status,
		'comment_status' => $comment_status,
		'ping_status' => $ping_status,
		'post_name' => $post_name1,
		'post_modified' => $post_modified,
		'post_modified_gmt' => $post_modified,
		'post_parent' => $post_id,
		'post_type' => 'product_variation'
	);
	wp_update_post($defaults_variable1);
	$defaults_variable2 = array(
		'ID' => $post_id_variable2,
		'post_date' => $post_date,
		'post_date_gmt' => $post_date,
		'post_author' => $post_author,
		'post_content' => $post_content,
		'post_excerpt' => $post_excerpt,
		'post_title' => $post_title2,
		'post_status' => $post_status,
		'comment_status' => $comment_status,
		'ping_status' => $ping_status,
		'post_name' => $post_name2,
		'post_modified' => $post_modified,
		'post_modified_gmt' => $post_modified,
		'post_parent' => $post_id,
		'post_type' => 'product_variation'
	);
	wp_update_post($defaults_variable2);
	$max_price = $price + 1;
	$min_price = $price;
	if ($temaxia < 10) {
		$temxcount = 16;
	}
	if ($temaxia > 9 && $temaxia < 100) {
		$temxcount = 17;
	}
	if ($temaxia > 100 && $temaxia < 1000) {
		$temxcount = 18;
	}
	$p_attrs = 'a:2:{s:8:"pa_color";a:6:{s:4:"name";s:8:"pa_color";s:5:"value";s:0:"";s:8:"position";s:1:"0";s:10:"is_visible";i:0;s:12:"is_variation";i:0;s:11:"is_taxonomy";i:1;}s:42:"%cf%84%ce%b5%ce%bc%ce%ac%cf%87%ce%b9%ce%b1";a:6:{s:4:"name";s:14:"Î¤ÎµÎ¼Î¬Ï‡Î¹Î±";s:5:"value";s:' . $temxcount . ':"' . $temaxia . ' Î¤ÎµÎ¼Î¬Ï‡Î¹Î±";s:8:"position";s:1:"1";s:10:"is_visible";i:0;s:12:"is_variation";i:1;s:11:"is_taxonomy";i:0;}}';
	update_post_meta($post_id_variable1, "attribute_%cf%84%ce%b5%ce%bc%ce%ac%cf%87%ce%b9%ce%b1", $temaxia . "-%cf%84%ce%b5%ce%bc%ce%ac%cf%87%ce%b9%ce%b1");
	update_post_meta($post_id_variable2, "attribute_%cf%84%ce%b5%ce%bc%ce%ac%cf%87%ce%b9%ce%b1", '');
	update_post_meta($post_id_variable1, "_price", $min_price);
	update_post_meta($post_id_variable2, "_price", $max_price);
	update_post_meta($post_id, "_max_regular_price_variation_id", $post_id_variable2);
	update_post_meta($post_id, "_min_regular_price_variation_id", $post_id_variable1);
	update_post_meta($post_id, "_max_variation_regular_price", $max_price);
	update_post_meta($post_id, "_min_variation_regular_price", $min_price);
	update_post_meta($post_id, "_max_price_variation_id", $post_id_variable2);
	update_post_meta($post_id, "_min_price_variation_id", $post_id_variable1);
	update_post_meta($post_id, "_min_variation_price", $min_price);
	update_post_meta($post_id, "_max_variation_price", $max_price);
	wp_set_object_terms($post_id, array(
		4
	) , 'product_type');
}
function remove_quotes($s)
{
	$s = str_replace('"', "", $s);
	return $s;
}
function get_extended_desc($full_desc, $NEWCOLLECTION, $cat, $color, $sku, $colour_var, $sku_tag, $sku_pattern)
{
	include (plugin_dir_path(__FILE__) . 'config.php');

	$con = mysqli_connect("localhost", $dbusername, $dbpassword, $dbname);
	// $coll                = str_replace(' ', '', $NEWCOLLECTION);
	// $coll                = strtoupper($coll);
	// $collection_title    = $collections[$coll]['Name'];
	// $collection_desc     = $collections[$coll]['Desc'];
	// $colorclass          = $collections[$coll]['Class'];
	$cat2 = $cat;
	$cat = strtolower($cat);
	// //Match It With
	$myrows_matchitC = "";
	$product_collection = substr($sku, 0, 1);
	$pr_color = $color;
	$matchwith_products = "";
	$youmaylike_products = "";
	$related_products = "";
	if ($cat == "necklaces") {
		$sku_ = mysqli_query($con, "SELECT ID,sku,type_layer,bcolor,cat FROM wp_posts WHERE type_layer LIKE '$NEWCOLLECTION' AND (cat LIKE '%Bracelets%' OR cat LIKE '%Earrings%') AND bcolor='$pr_color' ORDER BY RAND() LIMIT 0,4");
		if ($sku_) {
			$a_a = mysqli_num_rows($sku_);
			// echo $row['ID']."<br />";
			while ($row = mysqli_fetch_array($sku_)) {
				$matchwith_products.= $row['ID'] . ",";
			}
			$matchwith_products = rtrim($matchwith_products, ',');
		}
		else {
			$a_a = 0;
		}
	}
	if ($cat == "earrings") {
		$sku_ = mysqli_query($con, "SELECT ID,sku,type_layer,bcolor,cat FROM wp_posts WHERE type_layer='$NEWCOLLECTION' AND cat LIKE '%Necklaces%' AND bcolor='$pr_color' ORDER BY RAND() LIMIT 0,4");
		if ($sku_) {
			$a_a = mysqli_num_rows($sku_);
			while ($row = mysqli_fetch_array($sku_)) {
				$matchwith_products.= $row['ID'] . ",";
			}
			$matchwith_products = rtrim($matchwith_products, ',');
		}
		else {
			$a_a = 0;
		}
	}
	if ($cat == "bracelets") {
		$sku_ = mysqli_query($con, "SELECT ID,sku,type_layer,bcolor,cat FROM wp_posts WHERE type_layer='$NEWCOLLECTION' AND (cat LIKE '%Earrings%' OR cat LIKE '%Necklaces%') AND bcolor='$pr_color' ORDER BY RAND() LIMIT 0,4");
		if ($sku_) {
			$a_a = mysqli_num_rows($sku_);
			while ($row = mysqli_fetch_array($sku_)) {
				$matchwith_products.= $row['ID'] . ",";
			}
			$matchwith_products = rtrim($matchwith_products, ',');
		}
		else {
			$a_a = 0;
		}
	}
	// echo $matchwith_products."<br />";
	$skuB_ = mysqli_query($con, "SELECT ID,sku FROM wp_posts WHERE colour_var='$colour_var' AND sku!='$sku' ORDER BY RAND() LIMIT 0,4");
	if ($skuB_) {
		$a_b = mysqli_num_rows($skuB_);
		while ($rowB = mysqli_fetch_array($skuB_)) {
			$youmaylike_products.= $rowB['ID'] . ",";
		}
		$youmaylike_products = rtrim($youmaylike_products, ',');
	}
	else {
		$a_b = 0;
	}
	// ////LAYER IT////
	if ($sku_tag == "s") {
		$layA = $sku_pattern . 't';
		$layB = $sku_pattern . 'r';
		$layC = strtoupper($sku_pattern) . 'T';
		$layD = strtoupper($sku_pattern) . 'R';
		// $myrows_layerit = mysqli_query($con,"SELECT ID,sku FROM wp_posts WHERE type_layer='t' OR type_layer='r' ORDER BY RAND()");
	}
	if ($sku_tag == "r") {
		$layA = $sku_pattern . 't';
		$layB = $sku_pattern . 's';
		$layC = strtoupper($sku_pattern) . 'T';
		$layD = strtoupper($sku_pattern) . 'S';
		// $myrows_layerit = mysqli_query($con,"SELECT ID,sku FROM wp_posts WHERE type_layer='t' OR type_layer='s' ORDER BY RAND()");
	}
	if ($sku_tag == "t") {
		// $myrows_layerit = mysqli_query($con,"SELECT ID,sku FROM wp_posts WHERE type_layer='r' OR type_layer='s' ORDER BY RAND()");
		$layA = $sku_pattern . 's';
		$layB = $sku_pattern . 'r';
		$layC = strtoupper($sku_pattern) . 'S';
		$layD = strtoupper($sku_pattern) . 'R';
	}
	if (($sku_tag == "t" || $sku_tag == "s" || $sku_tag == "r") && ($cat == "necklaces")) {
		$myrows_layerit = mysqli_query($con, "SELECT ID,sku FROM wp_posts WHERE (sku LIKE '$layA%' OR sku LIKE '$layB%' OR sku LIKE '$layC%' OR sku LIKE '$layD%') AND type_layer='$NEWCOLLECTION' AND cat LIKE '$cat2' ORDER BY RAND() LIMIT 0,4");
	}
	// echo $NEWCOLLECTION."<br/>";
	if ($sku_tag != "") {
		if ($myrows_layerit) {
			$a_c = mysqli_num_rows($myrows_layerit);
			// echo $a_c;
			while ($rowBa = mysqli_fetch_array($myrows_layerit)) {
				$related_products.= $rowBa['ID'] . ",";
			}
			$related_products = rtrim($related_products, ',');
		}
	}
	else {
		$a_c = 0;
	}
	// echo $cat2;
	// echo $layA.":".$sku.":".$related_products."<br/>";
	if ($a_a > 0 || $a_b > 0 || $a_c > 0) {
		$str2 = '[vc_row css=".vc_custom_1414684365511{margin-bottom: 100px !important;}" type="in_container" parallax_speed="1"]
[vc_column width="1/1"]';
		if ($a_c > 0) {
			$str2.= '[vc_column_text]<p style="text-align: center;"><span style="color: #141414;">L&nbsp;A&nbsp;Y&nbsp;E&nbsp;R&nbsp;&nbsp;I&nbsp;T</span></p>[/vc_column_text]
[thb_product product_sort="by-id" cat="bracelets,collection,earrings,necklaces,rings" carousel="yes" item_count="' . $a_c . '" columns="4" product_ids="' . $related_products . '"][thb_gap height="40"]';
		}
		if ($a_b > 0) {
			$str2.= '[vc_column_text]<p style="text-align: center;"><span style="color: #141414;">Y&nbsp;O&nbsp;U&nbsp;&nbsp;M&nbsp;A&nbsp;Y&nbsp;&nbsp;A&nbsp;L&nbsp;S&nbsp;O&nbsp;&nbsp;L&nbsp;I&nbsp;K&nbsp;E</span></p>
[/vc_column_text][thb_product product_sort="by-id" cat="bracelets,collection,earrings,necklaces,rings" carousel="yes" item_count="' . $a_b . '" columns="4" product_ids="' . $youmaylike_products . '"][thb_gap height="40"]';
		}
		if ($a_a > 0) {
			$str2.= '[vc_column_text]<p style="text-align: center;"><span style="color: #141414;">M&nbsp;A&nbsp;T&nbsp;C&nbsp;H&nbsp;&nbsp;I&nbsp;T&nbsp;&nbsp;W&nbsp;I&nbsp;T&nbsp;H</span></p>
[/vc_column_text][thb_product product_sort="by-id" cat="bracelets,collection,earrings,necklaces,rings" carousel="yes" item_count="' . $a_a . '" columns="4" product_ids="' . $matchwith_products . '"][thb_gap height="40"]';
		}
		$str2.= '[/vc_column][/vc_row]';
	}
	else {
		$str2 = '<span class="downdesc">PP</span>';
	}
	return $str2;
}
function finish()
{
	include (plugin_dir_path(__FILE__) . 'config.php');

	$con = mysqli_connect("localhost", $dbusername, $dbpassword, $dbname);
	mysqli_query($con, "DELETE FROM wp_posts WHERE post_title='erp_post_erp'");
	mysqli_query($con, "DELETE pm FROM wp_postmeta pm LEFT JOIN wp_posts wp ON wp.ID = pm.post_id WHERE wp.ID IS NULL");
	echo "<script>alert('Operation completed!');</script>";
}
function delete_old()
{
	include (plugin_dir_path(__FILE__) . 'config.php');

	$con = mysqli_connect("localhost", $dbusername, $dbpassword, $dbname);
	$mfrecs = readCSV($csvf);
	foreach($mfrecs as $mfrec) {
		$mfrecs2[] = $mfrec['2'];
	}
	$all_recs = mysqli_query($con, "SELECT * FROM wp_posts WHERE post_type='product'");
	while ($row = mysqli_fetch_array($all_recs)) {
		$sku = $row['sku'];
		if (!in_array($sku, $mfrecs2) && $row['ID'] != 319316 && $row['ID'] != 320143) {
			$tobedeleted[] = $row['ID'];
		}
	}
	if (!empty($tobedeleted)) {
		foreach($tobedeleted as $del) {
			mysqli_query($con, "DELETE FROM `" . $dbname . "`.`wp_posts` WHERE `wp_posts`.`ID` = '$del'");
			mysqli_query($con, "UPDATE `" . $dbname . "`.`wp_posts` SET `post_parent`=0 WHERE `wp_posts`.`post_parent`=$del");
			mysqli_query($con, "DELETE FROM `" . $dbname . "`.`wp_postmeta` WHERE `wp_postmeta`.`post_id` = '$del'");
			mysqli_query($con, "DELETE FROM `" . $dbname . "`.`wp_term_relationships` WHERE `wp_term_relationships`.`object_id` = '$del'");
		}
	}
}
function get_extended_desc2($full_desc, $NEWCOLLECTION, $cat, $color, $sku, $colour_var, $sku_tag, $sku_pattern)
{
	include (plugin_dir_path(__FILE__) . 'config.php');

	$con = mysqli_connect("localhost", $dbusername, $dbpassword, $dbname);
	$cat2 = $cat;
	$cat = strtolower($cat);
	// //Match It With
	$myrows_matchitC = "";
	$product_collection = substr($sku, 0, 1);
	$pr_color = $color;
	$matchwith_products = "";
	$youmaylike_products = "";
	$related_products = "";
	if ($cat == "necklaces") {
		$metav1 = 'Bracelets,' . $NEWCOLLECTION . ',' . $pr_color;
		$metav2 = 'Earrings,' . $NEWCOLLECTION . ',' . $pr_color;
		$sku_ = mysqli_query($con, "SELECT * FROM wp_postmeta WHERE 							 
							 (meta_key='pr_matchlayer' AND meta_value LIKE '%$metav1%')
							   OR  (meta_key='pr_matchlayer' AND meta_value LIKE '%$metav2%')
									  ORDER BY RAND() LIMIT 0,4");
		if ($sku_) {
			$a_a = mysqli_num_rows($sku_);
			// echo $row['ID']."<br />";
			while ($row = mysqli_fetch_array($sku_)) {
				$matchwith_products.= $row['post_id'] . ",";
			}
			$matchwith_products = rtrim($matchwith_products, ',');
		}
		else {
			$a_a = 0;
		}
	}
	if ($cat == "earrings") {
		$metav1 = 'Bracelets,' . $NEWCOLLECTION . ',' . $pr_color;
		$metav2 = 'Necklaces,' . $NEWCOLLECTION . ',' . $pr_color;
		$sku_ = mysqli_query($con, "SELECT * FROM wp_postmeta WHERE 							 
							 (meta_key='pr_matchlayer' AND meta_value ='$metav1')
							   OR  (meta_key='pr_matchlayer' AND meta_value ='$metav2')
									  ORDER BY RAND() LIMIT 0,4");
		if ($sku_) {
			$a_a = mysqli_num_rows($sku_);
			while ($row = mysqli_fetch_array($sku_)) {
				$matchwith_products.= $row['post_id'] . ",";
			}
			$matchwith_products = rtrim($matchwith_products, ',');
		}
		else {
			$a_a = 0;
		}
	}
	if ($cat == "bracelets") {
		$metav1 = 'Earrings,' . $NEWCOLLECTION . ',' . $pr_color;
		$metav2 = 'Necklaces,' . $NEWCOLLECTION . ',' . $pr_color;
		$sku_ = mysqli_query($con, "SELECT * FROM wp_postmeta WHERE 							 
							 (meta_key='pr_matchlayer' AND meta_value ='$metav1')
							   OR  (meta_key='pr_matchlayer' AND meta_value ='$metav2')
									  ORDER BY RAND() LIMIT 0,4");
		if ($sku_) {
			$a_a = mysqli_num_rows($sku_);
			while ($row = mysqli_fetch_array($sku_)) {
				$matchwith_products.= $row['post_id'] . ",";
			}
			$matchwith_products = rtrim($matchwith_products, ',');
		}
		else {
			$a_a = 0;
		}
	}
	// echo "##".$matchwith_products."<br />";
	$skuB_ = mysqli_query($con, "SELECT * FROM wp_postmeta WHERE 
					  (meta_key='pr_designcode' AND meta_value='$colour_var') 
					  AND post_id!='" . $post_id . "'  ORDER BY RAND() LIMIT 0,4");
	if ($skuB_) {
		$a_b = mysqli_num_rows($skuB_);
		while ($rowB = mysqli_fetch_array($skuB_)) {
			$youmaylike_products.= $rowB['post_id'] . ",";
		}
		$youmaylike_products = rtrim($youmaylike_products, ',');
	}
	else {
		$a_b = 0;
	}
	// echo $youmaylike_products."<br />";
	// ////LAYER IT////
	if ($sku_tag == "s") {
		$layA = $sku_pattern . 't';
		$layB = $sku_pattern . 'r';
		$layC = strtoupper($sku_pattern) . 'T';
		$layD = strtoupper($sku_pattern) . 'R';
		// $myrows_layerit = mysqli_query($con,"SELECT ID,sku FROM wp_posts WHERE type_layer='t' OR type_layer='r' ORDER BY RAND()");
	}
	if ($sku_tag == "r") {
		$layA = $sku_pattern . 't';
		$layB = $sku_pattern . 's';
		$layC = strtoupper($sku_pattern) . 'T';
		$layD = strtoupper($sku_pattern) . 'S';
		// $myrows_layerit = mysqli_query($con,"SELECT ID,sku FROM wp_posts WHERE type_layer='t' OR type_layer='s' ORDER BY RAND()");
	}
	if ($sku_tag == "t") {
		// $myrows_layerit = mysqli_query($con,"SELECT ID,sku FROM wp_posts WHERE type_layer='r' OR type_layer='s' ORDER BY RAND()");
		$layA = $sku_pattern . 's';
		$layB = $sku_pattern . 'r';
		$layC = strtoupper($sku_pattern) . 'S';
		$layD = strtoupper($sku_pattern) . 'R';
	}
	if (($sku_tag == "t" || $sku_tag == "s" || $sku_tag == "r") && ($cat == "necklaces")) {
		$sjds = array();
		$myrows_layerit2 = mysqli_query($con, "SELECT * FROM wp_postmeta WHERE (meta_key='_sku' AND meta_value LIKE '$layA%') OR
								  (meta_key='_sku' AND meta_value LIKE '$layB%') OR
								  (meta_key='_sku' AND meta_value LIKE '$layC%') OR
								  (meta_key='_sku' AND meta_value LIKE '$layD%') ORDER BY RAND()");
		// AND (meta_key='pr_collection' AND meta_value='$NEWCOLLECTION')
		// AND (meta_key='pr_cat' AND meta_value LIKE '$cat2')
	}
	// echo $NEWCOLLECTION."<br/>";
	if ($sku_tag != "") {
		$ly = 0;
		if ($myrows_layerit2) {
			$a_c2 = mysqli_num_rows($myrows_layerit2);
			while ($rowBa2 = mysqli_fetch_array($myrows_layerit2)) {
				// $//related_products .= $rowBa['post_id'] . ",";
				$sjds[] = $rowBa2['post_id'];
			}
			foreach($sjds as $l) {
				if ($ly < 4) {
					$metav3 = $cat2 . ',' . $NEWCOLLECTION;
					// echo $l."-".$metav3;
					$myrows_layerit3 = mysqli_query($con, "SELECT * FROM wp_postmeta WHERE post_id=" . $l . " AND 
												(meta_key='pr_matchlayer' AND meta_value LIKE '%$metav3%') ");
					if ($myrows_layerit3) {
						$a_c = mysqli_num_rows($myrows_layerit3);
						// echo $a_c;
						while ($rowBa = mysqli_fetch_array($myrows_layerit3)) {
							$related_products.= $rowBa['post_id'] . ",";
							$ly++;
							// echo $related_products;
						}
					}
				}
			}
			$related_products = rtrim($related_products, ',');
		}
		else {
			$a_c = 0;
		}
	}
	else {
		$a_c = 0;
	}
	// echo $cat2;
	// echo $layA.":".$sku.":".$related_products."<br/>";
	if ($a_a > 0 || $a_b > 0 || $a_c > 0) {
		$str2 = '[vc_row css=".vc_custom_1414684365511{margin-bottom: 100px !important;}" type="in_container" parallax_speed="1"]
[vc_column width="1/1"]';
		if ($a_c > 0) {
			$str2.= '[vc_column_text]<p style="text-align: center;"><span style="color: #141414;">L&nbsp;A&nbsp;Y&nbsp;E&nbsp;R&nbsp;&nbsp;I&nbsp;T</span></p>[/vc_column_text]
[thb_product product_sort="by-id" cat="bracelets,collection,earrings,necklaces,rings" carousel="yes" item_count="' . $a_c . '" columns="4" product_ids="' . $related_products . '"][thb_gap height="40"]';
		}
		if ($a_b > 0) {
			$str2.= '[vc_column_text]<p style="text-align: center;"><span style="color: #141414;">Y&nbsp;O&nbsp;U&nbsp;&nbsp;M&nbsp;A&nbsp;Y&nbsp;&nbsp;A&nbsp;L&nbsp;S&nbsp;O&nbsp;&nbsp;L&nbsp;I&nbsp;K&nbsp;E</span></p>
[/vc_column_text][thb_product product_sort="by-id" cat="bracelets,collection,earrings,necklaces,rings" carousel="yes" item_count="' . $a_b . '" columns="4" product_ids="' . $youmaylike_products . '"][thb_gap height="40"]';
		}
		if ($a_a > 0) {
			$str2.= '[vc_column_text]<p style="text-align: center;"><span style="color: #141414;">M&nbsp;A&nbsp;T&nbsp;C&nbsp;H&nbsp;&nbsp;I&nbsp;T&nbsp;&nbsp;W&nbsp;I&nbsp;T&nbsp;H</span></p>
[/vc_column_text][thb_product product_sort="by-id" cat="bracelets,collection,earrings,necklaces,rings" carousel="yes" item_count="' . $a_a . '" columns="4" product_ids="' . $matchwith_products . '"][thb_gap height="40"]';
		}
		$str2.= '[/vc_column][/vc_row]';
	}
	else {
		$str2 = '';
	}
	// echo $str2;
	return $str2;
}
?>