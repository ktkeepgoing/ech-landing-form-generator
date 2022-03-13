<?php
/**
 * Include file - Used in ECH_Landing_Form_Generator plugin
 * 
 * Contains: 
 * 
 * 
 * 
 * @link       https://primecare.com.hk/
 * @since      1.0.0 *
 * @package    ECH_Landing_Form_Generator
 * 
 */

require_once('ajax.php');

function register_ech_lfg_styles(){

	wp_enqueue_script('LFG_jqueryUI_js', plugins_url('/assets/lib/jquery-ui-1.12.1/jquery-ui.min.js', __DIR__), array('jquery'), '1.0.0', true);
	wp_enqueue_style('LFG_jqueryUI_css', plugins_url("/assets/lib/jquery-ui-1.12.1/jquery-ui.min.css", __DIR__), false, '1.1.0', 'all');

	wp_enqueue_script('LFG_timepicker_js', plugins_url('/assets/lib/jquery-timepicker/jquery.timepicker.min.js', __DIR__), array('jquery'), '1.1.0', true);
	wp_enqueue_style('LFG_timepicker_css', plugins_url("/assets/lib/jquery-timepicker/jquery.timepicker.css", __DIR__), false, '1.1.0', 'all');


	wp_register_style( 'ech_lfg_style', plugins_url('/assets/css/ech-lfg.css', __DIR__), false, '1.1.0', 'all');
	wp_register_script( 'ech_lfg_script', plugins_url('/assets/js/ech-lfg.js', __DIR__), array('jquery'), '1.0.0', true);

}

function enqueue_ech_lfg_styles() {
	wp_enqueue_style( 'LFG_jqueryUI_css' );
	wp_enqueue_script( 'LFG_jqueryUI_js');

	wp_enqueue_style( 'LFG_timepicker_css' );
	wp_enqueue_script( 'LFG_timepicker_js');

	wp_enqueue_style( 'ech_lfg_style' );
	wp_enqueue_script( 'ech_lfg_script');
}

function ech_lfg_fun($atts){
	$paraArr = shortcode_atts( array(	
		'default_r'	=> 't200',				// default r
		'default_r_code' => null,			// default r token
		'r' => null,						// tcode	
		'r_code' => null,					// tcode token
		'email_required' => '1',			// email_required. 0 = false, 1 = true
		'item' => null,						// item checkbox
		'item_code' => null,				// item MSP token
		'item_label' => '*查詢項目',		 // item label
		'is_item_limited' => '0',			// are the items limited. 0 = false, 1 = true
		'item_limited_num' => '1',			// No. of options can the user choose
		'shop' => null,						// shop
		'shop_code' => null,				// shop MSP token
		'shop_label' => '*請選擇診所',		 // shop label
		'has_textarea' => '0',				// has textarea field. 0 = false, 1 = true
		'textarea_label' => '其他專業諮詢',	 // textarea label
		'brand' => null,					// for MSP, website name value
		'tks_para' => null					// parameters need to pass to thank you page

	), $atts );

	if ($paraArr['default_r_code'] == null) {
		return "<h4>Error - default_r_code not specified</h4>";
	}
	if (($paraArr['r'] != null)&&$paraArr['r_code'] == null) {
		return "<h4>Error - r_code not specified</h4>";
	}
	if (($paraArr['r_code'] != null)&&$paraArr['r'] == null) {
		return "<h4>Error - r not specified</h4>";
	}

	if ($paraArr['item'] == null) {
		return "<h4>Error - item not specified</h4>";
	}
	if ($paraArr['item_code'] == null) {
		return "<h4>Error - item_code not specified</h4>";
	}
	if ($paraArr['shop'] == null) {
		return "<h4>Error - shop not specified</h4>";
	}
	if ($paraArr['shop_code'] == null) {
		return "<h4>Error - shop_code not specified</h4>";
	}
	if ($paraArr['brand'] == null) {
		return "<h4>Error - brand not specified</h4>";
	}

	// Parse type into an array. Whitespace will be stripped.
	$paraArr['r'] = array_map( 'trim', str_getcsv( $paraArr['r'], ',' ) );
	$paraArr['r_code'] = array_map( 'trim', str_getcsv( $paraArr['r_code'], ',' ) );
	$paraArr['item'] = array_map( 'trim', str_getcsv( $paraArr['item'], ',' ) );
	$paraArr['item_code'] = array_map( 'trim', str_getcsv( $paraArr['item_code'], ',' ) );
	$paraArr['shop'] = array_map( 'trim', str_getcsv( $paraArr['shop'], ',' ) );
	$paraArr['shop_code'] = array_map( 'trim', str_getcsv( $paraArr['shop_code'], ',' ) );


	
	if (count($paraArr['item']) != count($paraArr['item_code'])) {
		return "<h4>Error - item and item_code must be corresponding to each other</h4>";
	}
	if (count($paraArr['shop']) != count($paraArr['shop_code'])) {
		return "<h4>Error - shop and shop_code must be corresponding to each other</h4>";
	}
	

	$default_r = htmlspecialchars(str_replace(' ', '', $paraArr['default_r']));
	$default_r_code = htmlspecialchars(str_replace(' ', '', $paraArr['default_r_code']));
	$get_tks_para = htmlspecialchars(str_replace(' ', '', $paraArr['tks_para']));
	if($get_tks_para == null) { $tks_para=""; } else { $tks_para = $get_tks_para; }

	$email_required = htmlspecialchars(str_replace(' ', '', $paraArr['email_required']));
	if ($email_required == "1") { $email_required_bool = true; } else { $email_required_bool = false; }

	$item_limited_num = htmlspecialchars(str_replace(' ', '', $paraArr['item_limited_num']));
	$is_item_limited = htmlspecialchars(str_replace(' ', '', $paraArr['is_item_limited']));
	if ($is_item_limited == "1") { $limited_bool = true; } else { $limited_bool = false; }

	$brand = htmlspecialchars(str_replace(' ', '', $paraArr['brand']));
	$item_label = htmlspecialchars(str_replace(' ', '', $paraArr['item_label']));
	$shop_label = htmlspecialchars(str_replace(' ', '', $paraArr['shop_label']));

	$has_textarea = htmlspecialchars(str_replace(' ', '', $paraArr['has_textarea']));
	if ($has_textarea == "1") { $has_textarea_bool = true; } else { $has_textarea_bool = false; }
	$textarea_label = htmlspecialchars(str_replace(' ', '', $paraArr['textarea_label']));




	$ip = $_SERVER['REMOTE_ADDR'];
	if ($ip = "::1") { $ip = "127.0.0.1"; } // for locolhost xampp

	


	
	if(isset($_GET['r'])) {
		$get_r = $_GET['r'];
	} else {
		$get_r = "";
	}


	if(!empty($paraArr['r'][0])) {
		//$output .= "not empty <br>";
		if(in_array($get_r, $paraArr['r'])) {
			//get_r exist in array
			$key = array_search($get_r, $paraArr['r']);
			$r = $paraArr['r'][$key];
			$c_token = $paraArr['r_code'][$key];
			
		} else {
			$r = $default_r;
			$c_token = $default_r_code;
		}
	} else {
		//$output .= "empty <br>";
		$r = $default_r;
		$c_token = $default_r_code;
	}
	

	$shop_count = count($paraArr['shop']);


	/***** FOR TESTING OUTPUT *****/
	/*
	$output .= '<div>';
	$output .= 'GET_r: ' . $_GET['r'] . '<br>';
	$output .= 'r: ' . print_r( $paraArr['r'], true  ) . '<br>';
	$output .= 'r_code: ' . print_r( $paraArr['r_code'], true  ) . '<br>';
	$output .= 'default_r: ' . $default_r . '<br>';
	$output .= 'default_r_code: ' . $default_r_code . '<br>';
	$output .= 'item: ' . print_r( $paraArr['item'], true  ). '<br>';
	$output .= 'item code: '. print_r( $paraArr['item_code'], true  ). '<br>';
	$output .= 'is_item_limited: '. $is_item_limited. '<br>';
	$output .= 'limited_bool: ' . var_export($limited_bool, true);
	$output .= '<br>';

	$output .= 'shop: '. print_r( $paraArr['shop'], true  ). '<br>';
	$output .= 'shop_code: '. print_r( $paraArr['shop_code'], true  ). '<br>';
	$output .= 'brand: '. $brand. '<br>';
	$output .= 'tks_para: ' . $tks_para . '<br>';
	$output .= '<br><br>';
	$output .= 'current r: ' . $r . ' | current token: '.$c_token;
	$output .= '</div>';
	*/
	/***** (END) FOR TESTING OUTPUT *****/

	
	

	
	$output .= '
	<div class="bookDoc_div">
    <a href="https://booking.echealthcare.com/app-medical/doctor?searchKey=匯兒兒科醫務中心&brandId=317&mspTokenWeb='.$c_token.'&mspTokenApp='.$c_token.'&refSource=Landing_Page_Button" target="_blank">
        <img src="https://www.primecare.com.hk/wp-content/uploads/2021/12/bookDoc_S.png" alt="即時預約">
    </a>
</div>

    <div class="lfg_formMsg"></div>
    <form class="ech_lfg_form" id="ech_lfg_form" action="" method="post" data-limited-no="'.$item_limited_num.'" data-r="'.$r.'" data-c-token="'.$c_token.'" data-shop-count="'.$shop_count.'" data-ajaxurl="'.get_admin_url(null, 'admin-ajax.php').'" data-ip="'.$ip.'" data-url="https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'" data-has-textarea="'.$has_textarea.'" data-item-label="'.$item_label.'" data-tks-para="'.$tks_para.'" data-brand="'.$brand.'">
       <div class="form_row">
           <div>
               <input type="text" name="last_name" id="last_name"  class="form-control"  placeholder="*姓氏" pattern="[ A-Za-z\u3000\u3400-\u4DBF\u4E00-\u9FFF]{1,}"  size="40" required >
           </div>
           <div>
               <input type="text" name="first_name" id="first_name" class="form-control" placeholder="*名字" pattern="[ A-Za-z\u3000\u3400-\u4DBF\u4E00-\u9FFF]{1,}" size="40" required >
           </div>
           <div>
               <select  class="form-control" name="telPrefix" id="tel_prefix" style="width: 100%;" required >
                   <option disabled="" selected="" value="">*請選擇</option>
                   <option value="+852">+852</option>
                   <option value="+853">+853</option>
                   <option value="+86">+86</option> 
               </select>
           </div>
           <div>
               <input type="text" name="tel" placeholder="*電話"  class="form-control" size="30" id="tel" pattern="[0-9]{8,11}" required >
           </div>
		';

			/***** Email */
			$output .='<div>';
			if ($email_required_bool) {
				$output .= '<input type="email" name="email" id="email" placeholder="*電郵" class="form-control" size="40" required>';
			} else {
				$output .= '<input type="email" name="email" id="email" placeholder="電郵" class="form-control" size="40" >';
			}
			$output .= '</div>';
			/***** (END) Email */
        
        $output .= '</div> <!-- form_row -->';
		


	   $output .= '
       <div class="form_row">
           <div>
               <input type="text" placeholder="*預約日期" id="booking_date" class="form-control lfg_datepicker" name="booking_date" autocomplete="off" value="" size="40" required>
           </div>
           <div>
                <input type="text" placeholder="*預約時間" id="booking_time" class="form-control lfg_timepicker" name="booking_date" autocomplete="off" value="" size="40" required>
           </div>
       </div><!-- form_row -->';


		/***** Location Options */
       $output .= '
	   <div class="form_row">
           <div>';
		if ($shop_count <= 3) {
			// radio
			$output .='<label>'.$shop_label.'</label><br>';
			if($shop_count == 1 ){
				$output .= '<label class="radio_label"><input type="radio" value="'.$paraArr['shop_code'][0].'" data-shop-text-value="'.$paraArr['shop'][0].'" name="shop" checked onclick="return false;">'.$paraArr['shop'][0].'</label>';
			} else {
				for($i=0 ; $i < $shop_count; $i++) {
					$output .= '<label class="radio_label"><input type="radio" value="'.$paraArr['shop_code'][$i].'" name="shop" data-shop-text-value="'.$paraArr['shop'][$i].'" required>'.$paraArr['shop'][$i].'</label>';
				}
			}
		} else {
			// select
			$output .= '<select  class="form-control" name="shop" id="shop" style="width: 100%;" required >';
				$output .= '<option disabled="" selected="" value="">'.$shop_label.'</option>';
				for($i=0 ; $i < $shop_count; $i++) {
					$output .='<option value="'.$paraArr['shop_code'][$i].'">'.$paraArr['shop'][$i].'</option>';
				}
            $output .='</select>';
		}
		
		$output .='
           </div>
        </div> <!-- form_row -->';
		/***** (END) Location Options */



		/***** Item Options */
		$output .= '
		<div class="form_row">
			<div>';
 
		 if(count($paraArr['item']) == 1 ){
			 $output .= '<label>'.$item_label.'</label><br>';
			 $output .= '<label class="checkbox_label"><input type="checkbox" value="'.$paraArr['item_code'][0].'" name="item" data-text-value="'.$paraArr['item'][0].'" checked onclick="return false;">'.$paraArr['item'][0].'</label>';

		 } else if(count($paraArr['item']) < 7) {
			 $output .= '<label>'.$item_label.'</label><br>';
			 for($i=0 ; $i < count($paraArr['item']); $i++) {
				 $output .= '<label class="checkbox_label"><input type="checkbox" class="';
				 if ($limited_bool){
					 $output .= 'limited_checkbox';
				 } else {
					 $output .= '';
				 }
				 $output .= '" value="'.$paraArr['item_code'][$i].'" name="item" data-text-value="'.$paraArr['item'][$i].'">'.$paraArr['item'][$i].'</label><br>';
			 }
			 
		 } else { 
			// dropdown list checkbox 
			$output .='<div class="lfg_checkbox_dropdown"><label class="lfg_dropdown_title">' . $item_label.'</label>';
				$output .= '<ul class="lfg_checkbox_dropdown_list">';
					for($i=0 ; $i < count($paraArr['item']); $i++) {
						$output .= '<li>';
							$output .= '<label class="checkbox_label"><input type="checkbox" class="';
							if ($limited_bool){
								$output .= 'limited_checkbox';
							} else {
								$output .= '';
							}
							$output .= '" value="'.$paraArr['item_code'][$i].'" name="item" data-text-value="'.$paraArr['item'][$i].'">'.$paraArr['item'][$i].'</label>';
						$output .= '</li>';
					} // for loop
				$output .= '</ul>'; //lfg_checkbox_dropdown_list
			$output .= '</div>'; // lfg_checkbox_dropdown
			

		 }// count($paraArr['item'])
		 $output .='
			</div>
		 </div> <!-- form_row -->';
		 /***** (END) Item Options */




		 /***** Textarea */
		 if ($has_textarea_bool) {
			 $output .='
			 <div class="form_row">
				 <div>
					 <textarea class="form-control" type="textarea" name="remarks" id="remarks" placeholder="'.$textarea_label.'" maxlength="140" rows="7"></textarea>
				 </div>
			 </div>
			 <!-- form_row -->
			 ';
		 }
		 /***** (END) Textarea */
       $output .= ' 
       
   
       <div class="form_row">
           <div>
               <p class="redWord">本中心將與您聯絡確認詳情，方為確實是次預約。</p>
               <label><input type="checkbox" class="agree"  value="agreed_policy" name="info_remark[]" checked required > * 本人已閱讀並同意有關 <a href="https://echealthcare.com/zh/privacy-policy"   target="_blank">私隱政策聲明</a>。</label>
               <small> *必需填寫</small>
           </div>
       </div><!-- form_row -->
   
       <div class="form_row">
           <button type="submit" value="提交" id= "submitBtn" >提交</button>
       </div><!-- form_row -->
   </form>
   '; 

   
   
	return $output;
}


add_action('wp_ajax_LFG_formToMSP', 'LFG_formToMSP');
add_action('wp_ajax_nopriv_LFG_formToMSP', 'LFG_formToMSP');
function LFG_formToMSP() {
	$result =  lfg_proxy_ajax_to_msp();
	echo $result;
	wp_die();
}