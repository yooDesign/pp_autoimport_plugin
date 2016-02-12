<?php
/*
Plugin Name: Pink Powder ERP / eShop Bridge
Description: Plugin to update eshop records from ERP
Author: George Karatzas
Version: 5.2
*/
    defined('ABSPATH') or die('No script kiddies please!');
    
    if (!is_admin()) {
        return;
    } else {
        set_time_limit(0);
        while (@ob_end_flush());
        // Implicitly flush the buffer(s)
        ini_set('implicit_flush', true);
        ob_implicit_flush(true);
        include (plugin_dir_path(__FILE__) . 'functions.php');
        include (plugin_dir_path(__FILE__) . 'config.php');
        include (plugin_dir_path(__FILE__) . 'insert_new.php');
        include (plugin_dir_path(__FILE__) . 'insert_new_only.php');
        include (plugin_dir_path(__FILE__) . 'update_meta.php');
        include (plugin_dir_path(__FILE__) . 'update_meta_only.php');
        include (plugin_dir_path(__FILE__) . 'update_attributes.php');
        add_action('admin_menu', 'admin_pp_erp_setup_menu');
        function admin_pp_erp_setup_menu(){
            add_menu_page('Pink Powder ERP / eShop Bridge', 'Pink Powder ERP / eShop Bridge', 'manage_options', 'autoimport', 'autoimport_init', plugins_url('autoimport/icon.png') , 0);
        }

    }

    
    function autoimport_init(){
        echo '<style type="text/css">.fbtn{background: #595F72;}.fbtn2{background:#ADC7A6;}.fbtn:hover,.fbtn2:hover{background:#333}</style>';
        include (plugin_dir_path(__FILE__) . 'config.php');
        echo "<div class='wrap pp_erp'><h1>Pink Powder ERP / eShop Bridge</h1>";
        echo "<div style='clear:both'></div>";
        $con = mysqli_connect("localhost", $dbusername, $dbpassword, $dbname);
        ?>
<div id="progress4" style="width:100%;background:#f1f1f1;height:4px"></div>
<!-- Progress information -->
<div id="information4" style="width"></div>
<div style="clear:both"></div>
<div id="information44" style="width"></div>
<div style="clear:both"></div>
<div id="info">
  <div style="clear:both"></div>
  <div class="importantc" style="background:#fff;color:#333; padding:20px;float:left; color:#595F72">
    <?php
		$filename2 = $csvf;
        $filename4 = $csvf;
        
        if (file_exists($filename2)) {
            $csv = readCSV($csvf);
            $csv_records = count($csv) - 1;
            echo "CSV file last update: " . date("d F Y H:i:s", filemtime($csvf)) . " / " . $csv_records . " records";
        }

        ?>
  </div>
  <div style="clear:both"></div>
  <div style="margin-top:20px">
    <?php
        
        if (isset($_POST['autoimport_new'])) {
            echo '<div style=""><div id="information3"  style="width: 400px;    height: 200px; padding:30px;  background: #595F72;">
	<div style="float: left; margin-right: 20px; color: #fff;font-size: 20px;">Insert New Products: </div><div id="perc2" style="color: #fff; float:left;    text-align: left;    font-size: 30px;">0%</div>
	<div style="clear:both;    margin-bottom: 30px;"></div>
	<div style="float: left; margin-right: 20px; color: #fff;font-size: 20px;">Update Attributes: </div><div id="perc3" style="color: #fff; float:left;    text-align: left;    font-size: 30px;">0%</div>
	<div style="clear:both;    margin-bottom: 30px;"></div>
	<div style="float: left; margin-right: 20px; color: #fff;font-size: 20px;">Update Products: </div><div id="perc4" style="color: #fff; float:left;    text-align: left;    font-size: 30px;">0%</div>
	<div style="clear:both;    margin-bottom: 30px;"></div>';
            delete_old();
            insert_new_only();
            sleep(5);
            finish();
            mysqli_close($conn);
            echo "<h4>eShop Data Updated Succesfully!</h4>";
        }

        
        if (isset($_POST['autoimport_update_prices'])) {
            echo '<div style=""><div id="information3"  style="width: 400px;    height: 200px; padding:30px;  background: #595F72;">
	<div style="float: left; margin-right: 20px; color: #fff;font-size: 20px;">Insert New Products: </div><div id="perc2" style="color: #fff; float:left;    text-align: left;    font-size: 30px;">0%</div>
	<div style="clear:both;    margin-bottom: 30px;"></div>
	<div style="float: left; margin-right: 20px; color: #fff;font-size: 20px;">Update Attributes: </div><div id="perc3" style="color: #fff; float:left;    text-align: left;    font-size: 30px;">0%</div>
	<div style="clear:both;    margin-bottom: 30px;"></div>
	<div style="float: left; margin-right: 20px; color: #fff;font-size: 20px;">Update Products: </div><div id="perc4" style="color: #fff; float:left;    text-align: left;    font-size: 30px;">0%</div>
	<div style="clear:both;    margin-bottom: 30px;"></div>';
            delete_old();
            update_metas_only_prices();
            sleep(5);
            finish();
            mysqli_close($conn);
            echo "<h4>eShop Data Updated Succesfully!</h4>";
        }

        
        if (isset($_POST['autoimport_update_titles'])) {
            echo '<div style=""><div id="information3"  style="width: 400px;    height: 200px; padding:30px;  background: #595F72;">
	<div style="float: left; margin-right: 20px; color: #fff;font-size: 20px;">Insert New Products: </div><div id="perc2" style="color: #fff; float:left;    text-align: left;    font-size: 30px;">0%</div>
	<div style="clear:both;    margin-bottom: 30px;"></div>
	<div style="float: left; margin-right: 20px; color: #fff;font-size: 20px;">Update Attributes: </div><div id="perc3" style="color: #fff; float:left;    text-align: left;    font-size: 30px;">0%</div>
	<div style="clear:both;    margin-bottom: 30px;"></div>
	<div style="float: left; margin-right: 20px; color: #fff;font-size: 20px;">Update Products: </div><div id="perc4" style="color: #fff; float:left;    text-align: left;    font-size: 30px;">0%</div>
	<div style="clear:both;    margin-bottom: 30px;"></div>';
            delete_old();
            update_metas_only_titles();
            sleep(5);
            finish();
            mysqli_close($conn);
            echo "<h4>eShop Data Updated Succesfully!</h4>";
        }

        
        if (isset($_POST['autoimport_update_images'])) {
            echo '<div style=""><div id="information3"  style="width: 400px;    height: 200px; padding:30px;  background: #595F72;">
	<div style="float: left; margin-right: 20px; color: #fff;font-size: 20px;">Insert New Products: </div><div id="perc2" style="color: #fff; float:left;    text-align: left;    font-size: 30px;">0%</div>
	<div style="clear:both;    margin-bottom: 30px;"></div>
	<div style="float: left; margin-right: 20px; color: #fff;font-size: 20px;">Update Attributes: </div><div id="perc3" style="color: #fff; float:left;    text-align: left;    font-size: 30px;">0%</div>
	<div style="clear:both;    margin-bottom: 30px;"></div>
	<div style="float: left; margin-right: 20px; color: #fff;font-size: 20px;">Update Products: </div><div id="perc4" style="color: #fff; float:left;    text-align: left;    font-size: 30px;">0%</div>
	<div style="clear:both;    margin-bottom: 30px;"></div>';
            update_metas_only_images();
            sleep(5);
            finish();
            mysqli_close($conn);
            echo "<h4>eShop Data Updated Succesfully!</h4>";
        }

        
        if (isset($_POST['autoimport_update_complete'])) {
            update_metas_only_complete();
            sleep(5);
            finish();
            mysqli_close($conn);
            echo "<h4>eShop Data Updated Succesfully!</h4>";
        }

        
        if (isset($_POST['autoimport_update_attributes'])) {
            update_attributes();
            sleep(5);
            finish();
            mysqli_close($conn);
            echo "<h4>eShop Data Updated Succesfully!</h4>";
        }

        
        if (isset($_POST['restore'])) {
            import_db();
        }

        ?>
  </div>
  <div style="margin-top:20px; background:#D2D5DD; padding: 20px; width:60% ">
    <div class="importantc"   >
      <h4>Images Upload</h4>
      Important!</strong> You must firstly upload the images in order to upload new products! <br/>
      If you dont want to upload new products but just update the existing, skip the images uploading and proceed to update eshop records.<br/>
      <strong>Maximup upload images per time 20</strong></div>
    <form action="#" method="post" enctype="multipart/form-data" name="front_end_upload" style="background:rgba(255,255,255,0.3);border:none; margin-top:20px; padding:10px"   >
      <label> Attach all your images here :
        <input type="file" name="kv_multiple_attachments[]"  multiple="multiple"  >
      </label>
      <input type="submit" name="Upload"    value="Upload" >
    </form>
  </div>
  <div style="clear:both"></div>
  <div style="margin-top:10px;">
    <form action="#" method="post" class="admin_erppp" id="formp">
      <input class="submit_button fbtn" type="submit" name="autoimport_new" value="INSERT NEW" style="cursor: pointer;    cursor: hand;  float:left;       border: 0;    color: #fff;    padding: 30px;    font-size: 16px; width:200px">
    </form>
    <form action="#" method="post" class="admin_erppp" id="formp">
      <input class="submit_button fbtn" type="submit" name="autoimport_update_titles" value="UPDATE TITLES & DESCRIPTIONS" style="cursor: pointer;    cursor: hand;  float:left;       border: 0;    color: #fff;    padding: 30px;    font-size: 16px; width:300px">
    </form>
    <form action="#" method="post" class="admin_erppp" id="formp">
      <input class="submit_button fbtn" type="submit" name="autoimport_update_prices" value="UPDATE PRICES" style="cursor: pointer;    cursor: hand;  float:left;       border: 0;    color: #fff;    padding: 30px;    font-size: 16px; width:200px">
    </form>
    <form action="#" method="post" class="admin_erppp" id="formp">
      <input class="submit_button fbtn" type="submit" name="autoimport_update_images" value="UPDATE IMAGES" style="cursor: pointer;    cursor: hand;  float:left;       border: 0;    color: #fff;    padding: 30px;    font-size: 16px; width:200px">
    </form>
    <form action="#" method="post" class="admin_erppp" id="formp">
      <input class="submit_button fbtn" type="submit" name="autoimport_update_attributes" value="UPDATE ATTRIBUTES" style="cursor: pointer;    cursor: hand;  float:left;       border: 0;    color: #fff;    padding: 30px;    font-size: 16px; width:200px">
    </form>
    <br />
    <form action="#" method="post" class="admin_erppp" id="formp">
      <input class="submit_button fbtn2" type="submit" name="autoimport_update_complete" value="COMPLETE" style="cursor: pointer;    cursor: hand;  float:left;       border: 0;    color: #fff;    padding: 30px;    font-size: 16px; width:400px;  ">
    </form>
    <form action="#" method="post" class="admin_erppp" style="display:none" >
      <input class="submit_button fbtn2" type="submit" name="restore" value="RESTORE DB" style="cursor: pointer;     cursor: hand;  float:left;       border: 0;    color: #fff;    padding: 30px;    font-size: 16px; width:200px">
    </form>
  </div>
</div>
<?php
		// kv-upload.php
        $base = plugin_dir_path(__FILE__);
        
        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            while (@ob_end_flush());
            // Implicitly flush the buffer(s)
            ini_set('implicit_flush', true);
            ob_implicit_flush(true);
            
            if ($_FILES) {
                $files = $_FILES["kv_multiple_attachments"];
                
                if (count($files['name']) > 200) {
                    echo '<script>alert("You can upload maximum 20 images per time!");location.reload();</script>';
                    exit;
                }

                foreach($files['name'] as $key => $value) {
                    
                    if ($files['name'][$key]) {
                        $file = array('name' => $files['name'][$key],'type' => $files['type'][$key],'tmp_name' => $files['tmp_name'][$key],'error' => $files['error'][$key],'size' => $files['size'][$key],'upload_from' => 'erp_plugin');
                        $key2 = $key + 1;
                        $_FILES = array("kv_multiple_attachments" => $file);
                        $percent5 = intval($key2 / count($files['name']) * 100) . "%";
                        $tottime = 13 * count($files['name']);
                        $resttime = round(($tottime - 13 * $key2) / 60);
                        echo '<script language="javascript">document.getElementById("progress5").innerHTML="<div style=\"width:' . $percent5 . ';background-color:salmon;height:4px;\">&nbsp;</div>";document.getElementById("information5").innerHTML="' . $key2 . ' / ' . count($files['name']) . ' records processed.(' . $percent5 . ') - Estimated Time: ' . $resttime . ' minutes";</script>';
                        echo str_repeat(' ', 1024 * 64);
                        foreach($_FILES as $file => $array) {
                            // print_r($array);
                            $newupload = kv_handle_attachment($file, $pid, $array['name']);
                        }

                        echo '<script language="javascript">document.getElementById("information5").innerHTML="Process completed"</script>';
                    }

                }

            }

        }

        ?>
<div style="clear:both"></div>
<div style="margin-top:40px"> <strong>Pink Powder ERP / eShop Bridge</strong><br/>
  Plugin to update eshop records from ERP - Plugin version 5.2 <br/>
  Author: <a href="mailto:georgekrtzs@gmail.com">George Karatzas</a></div>
<?php
	}