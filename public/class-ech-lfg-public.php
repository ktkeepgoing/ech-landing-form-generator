<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://#
 * @since      1.0.0
 *
 * @package    Ech_Lfg
 * @subpackage Ech_Lfg/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Ech_Lfg
 * @subpackage Ech_Lfg/public
 * @author     Toby Wong <tobywong@prohaba.com>
 */
class Ech_Lfg_Public
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{
		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/ech-lfg-public.css', array(), $this->version, 'all');
		wp_enqueue_style($this->plugin_name . '_jqueryUI', plugin_dir_url(__FILE__) . 'lib/jquery-ui-1.12.1/jquery-ui.min.css', array(), $this->version, 'all');
		wp_enqueue_style($this->plugin_name . '_timepicker', plugin_dir_url(__FILE__) . 'lib/jquery-timepicker/jquery.timepicker.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{
		wp_enqueue_script($this->plugin_name . '_jqueryUI', plugin_dir_url(__FILE__) . 'lib/jquery-ui-1.12.1/jquery-ui.min.js', array('jquery'), $this->version, false);
		wp_enqueue_script($this->plugin_name . '_timepicker', plugin_dir_url(__FILE__) . 'lib/jquery-timepicker/jquery.timepicker.min.js', array('jquery'), $this->version, false);
		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/ech-lfg-public.js', array('jquery'), $this->version, false);
	}


	// ^^^ ECH LFG shortcode
	public function display_ech_lfg($atts)
	{
		// get the general settings 
		$getBrandName = get_option('ech_lfg_brand_name');
		if (empty($getBrandName)) {
			return '<div class="code_error">Settings Error: Must set brand name at dashboard settings</div>';
		}

		$paraArr = shortcode_atts(array(
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
			'has_select_dr' => '0',				// has Select Doctor field. 0 = false, 1 = true
			'dr' => null,						// Doctor value
			'dr_code' => null,					// Doctor code
			'shop' => null,						// shop
			'shop_code' => null,				// shop MSP token
			'shop_label' => '*請選擇診所',		 // shop label
			'has_textarea' => '0',				// has textarea field. 0 = false, 1 = true
			'textarea_label' => '其他專業諮詢',	 // textarea label
			'has_hdyhau' => '0',				// has "How did you hear about us" field. 0 = false, 1 = true
			'hdyhau_item' => null,				// "How did you hear about us" items
			'brand' => $getBrandName,			// for MSP, website name value
			'tks_para' => null,					// parameters need to pass to thank you page
			// Wati data
			'wati_send' => 0,
			'wati_msg' => null

		), $atts);


		if ($paraArr['default_r_code'] == null) {
			return '<div class="code_error">shortcode error - default_r_code not specified</div>';
		}
		if (($paraArr['r'] != null) && $paraArr['r_code'] == null) {
			return '<div class="code_error">shortcode error - r_code not specified</div>';
		}
		if (($paraArr['r_code'] != null) && $paraArr['r'] == null) {
			return '<div class="code_error">shortcode error - r not specified</div>';
		}



		if ($paraArr['item'] == null) {
			return '<div class="code_error">shortcode error - item not specified</div>';
		}
		if ($paraArr['item_code'] == null) {
			return '<div class="code_error">shortcode error - item_code not specified</div>';
		}
		if ($paraArr['shop'] == null) {
			return '<div class="code_error">shortcode error - shop not specified</div>';
		}
		if ($paraArr['shop_code'] == null) {
			return '<div class="code_error">shortcode error - shop_code not specified</div>';
		}
		if ($paraArr['brand'] == null) {
			return '<div class="code_error">shortcode error - brand not specified</div>';
		}

		// Parse type into an array. Whitespace will be stripped.
		//$paraArr['r'] = array_map('trim', str_getcsv($paraArr['r'], '|'));
		$paraArr['r'] = array_map('trim', array_map('strtolower', str_getcsv($paraArr['r'], '|')) );
		$paraArr['r'] = array_filter($paraArr['r']); // remove empty value
		// Child values. Parse type into an array
		foreach ($paraArr['r'] as $key => $val) {
			$paraArr['r'][$key] = array_map('trim', str_getcsv($paraArr['r'][$key], ','));
		}


		$paraArr['r_code'] = array_map('trim', str_getcsv($paraArr['r_code'], ','));


		$paraArr['item'] = array_map('trim', str_getcsv($paraArr['item'], ','));
		$paraArr['item_code'] = array_map('trim', str_getcsv($paraArr['item_code'], ','));

		$paraArr['shop'] = array_map('trim', str_getcsv($paraArr['shop'], ','));
		$paraArr['shop_code'] = array_map('trim', str_getcsv($paraArr['shop_code'], ','));

		$has_dr = htmlspecialchars(str_replace(' ', '', $paraArr['has_select_dr']));
		if ($has_dr == "1") {
			$has_dr_bool = true;
		} else {
			$has_dr_bool = false;
		}
		$paraArr['dr'] = array_map('trim', str_getcsv($paraArr['dr'], ','));
		$paraArr['dr_code'] = array_map('trim', str_getcsv($paraArr['dr_code'], ','));



		if (count($paraArr['r_code']) != count($paraArr['r'])) {
			return '<div class="code_error">shortcode error - r_code and r count array value is not the same. They must be corresponding to each other.</div>';
		}
		if (count($paraArr['item']) != count($paraArr['item_code'])) {
			return '<div class="code_error">shortcode error - item and item_code must be corresponding to each other</div>';
		}
		if (count($paraArr['shop']) != count($paraArr['shop_code'])) {
			return '<div class="code_error">shortcode error - shop and shop_code must be corresponding to each other</div>';
		}
		if (count($paraArr['dr']) != count($paraArr['dr_code'])) {
			return '<div class="code_error">shortcode error - dr and dr_code must be corresponding to each other</div>';
		}

		$has_hdyhau = htmlspecialchars(str_replace(' ', '', $paraArr['has_hdyhau']));
		if ($has_hdyhau == "1" && empty($paraArr['hdyhau_item'])) {
			return '<div class="code_error">shortcode error - at least one or more hdyhau_items</div>';
		}


		if ($paraArr['wati_send'] == 1 && $paraArr['wati_msg'] == null) {
			return '<div class="code_error">wati_send error - wati_send enabled, wati_msg cannot be empty</div>';
		}



		$default_r = htmlspecialchars(str_replace(' ', '', $paraArr['default_r']));
		$default_r_code = htmlspecialchars(str_replace(' ', '', $paraArr['default_r_code']));
		$get_tks_para = htmlspecialchars(str_replace(' ', '', $paraArr['tks_para']));
		if ($get_tks_para == null) {
			$tks_para = "";
		} else {
			$tks_para = $get_tks_para;
		}

		$email_required = htmlspecialchars(str_replace(' ', '', $paraArr['email_required']));
		if ($email_required == "1") {
			$email_required_bool = true;
		} else {
			$email_required_bool = false;
		}

		$item_limited_num = htmlspecialchars(str_replace(' ', '', $paraArr['item_limited_num']));
		$is_item_limited = htmlspecialchars(str_replace(' ', '', $paraArr['is_item_limited']));
		if ($is_item_limited == "1") {
			$limited_bool = true;
		} else {
			$limited_bool = false;
		}

		$brand = htmlspecialchars(str_replace(' ', '', $paraArr['brand']));
		$item_label = htmlspecialchars(str_replace(' ', '', $paraArr['item_label']));
		$shop_label = htmlspecialchars(str_replace(' ', '', $paraArr['shop_label']));

		$has_textarea = htmlspecialchars(str_replace(' ', '', $paraArr['has_textarea']));
		if ($has_textarea == "1") {
			$has_textarea_bool = true;
		} else {
			$has_textarea_bool = false;
		}
		$textarea_label = htmlspecialchars(str_replace(' ', '', $paraArr['textarea_label']));

		if ($has_hdyhau == "1") {
			$has_hdyhau_bool = true;
		} else {
			$has_hdyhau_bool = false;
		}
		$paraArr['hdyhau_item'] = array_map('trim', str_getcsv($paraArr['hdyhau_item'], ','));

		// Wati 
		$wati_send = htmlspecialchars(str_replace(' ', '', $paraArr['wati_send']));
		$wati_msg = htmlspecialchars(str_replace(' ', '', $paraArr['wati_msg']));
		if ( $wati_send == 1 ) {
			$get_watiKey = get_option( 'ech_lfg_wati_key' );
			$get_watiAPI = get_option( 'ech_lfg_wati_api_domain' );

			if ( empty($get_watiKey) || empty($get_watiAPI) ) {
				return '<div class="code_error">Wati error - Wati Key or Wati API are empty. Please setup in dashboard. </div>';
			}
		}


		$ip = $_SERVER['REMOTE_ADDR'];
		if ($ip = "::1") {
			$ip = "127.0.0.1";
		} // for locolhost xampp





		if (isset($_GET['r'])) {
			$get_r = strtolower($_GET['r']);
		} else {
			$get_r = $default_r;
		}


		if (!empty($paraArr['r'])) {
			$sourceArr =  $paraArr['r'];
			foreach ($sourceArr as $key => $rValArr) {
				// Search if $get_r value exist in child array
				if (in_array($get_r, $rValArr)) {
					$r = $get_r;

					$parentArr_key = $key;
					$c_token = $paraArr['r_code'][$parentArr_key];
					break;
				}

				$r = $default_r;
				$c_token = $default_r_code;
			}
		} else {
			$r = $default_r;
			$c_token = $default_r_code;
		}

		$shop_count = count($paraArr['shop']);
		

		$rand = $this->genRandomString(); // For ePay refcode

		$output = '';
		/***** FOR TESTING OUTPUT *****/
		
		/*
		$output .= '<div><pre>';
		$output .= 'GET[r]: ' . $_GET['r'] . '<br>';
		$output .= '$r: ' . $r . '<br>';
		$output .= '$c_token: ' . $c_token . '<br>';
		//$output .= '$parentArr_key: ' . $parentArr_key . '<br>';

		$output .= '---------------------------<br>';
		$output .= 'r: ' . print_r($paraArr['r'], true) . '<br>';

		$output .= 'r_code: ' . print_r($paraArr['r_code'], true) . '<br>';
		$output .= 'default_r: ' . $default_r . '<br>';
		$output .= 'default_r_code: ' . $default_r_code . '<br>';
		$output .= 'item: ' . print_r($paraArr['item'], true) . '<br>';
		$output .= 'item code: ' . print_r($paraArr['item_code'], true) . '<br>';
		$output .= 'is_item_limited: ' . $is_item_limited . '<br>';
		$output .= 'limited_bool: ' . var_export($limited_bool, true);
		$output .= '<br>';

		$output .= 'shop: ' . print_r($paraArr['shop'], true) . '<br>';
		$output .= 'shop_code: ' . print_r($paraArr['shop_code'], true) . '<br>';
		$output .= 'brand: ' . $brand . '<br>';
		$output .= 'tks_para: ' . $tks_para . '<br>';
		$output .= '<br><br>';
		$output .= 'current r: ' . $r . ' | current token: ' . $c_token;
		$output .= '<br><br>';
		$output .= 'has_hdyhau: ' . $has_hdyhau . ' | hdyhau_item: ' . print_r($paraArr['hdyhau_item'], true);
		$output .= '</pre></div>';
		*/
		
		/***** (END) FOR TESTING OUTPUT *****/

		// *********** Custom styling ***************/
		if ( !empty(get_option( 'ech_lfg_submitBtn_color' )) || !empty(get_option( 'ech_lfg_submitBtn_hoverColor') || !empty(get_option('ech_lfg_submitBtn_text_color')) || !empty(get_option('ech_lfg_submitBtn_text_hoverColor')) ) ) {
			$output .= '<style>';
			
			$output .= '.ech_lfg_form #submitBtn { ';
				( !empty(get_option( 'ech_lfg_submitBtn_color' )) ) ? $output .= 'background:'. get_option( 'ech_lfg_submitBtn_color' ).';' : '';
				( !empty(get_option( 'ech_lfg_submitBtn_text_color' )) ) ? $output .= 'color:'. get_option( 'ech_lfg_submitBtn_text_color' ).';' : '';
			$output .= '}';

			$output .= '.ech_lfg_form #submitBtn:hover { ';
				( !empty(get_option( 'ech_lfg_submitBtn_hoverColor' )) ) ? $output .= 'background:'. get_option( 'ech_lfg_submitBtn_hoverColor' ).';' : '';
				( !empty(get_option( 'ech_lfg_submitBtn_text_hoverColor' )) ) ? $output .= 'color:'. get_option( 'ech_lfg_submitBtn_text_hoverColor' ).';' : '';
			$output .= '}';


			$output .= '</style>';
		}
		// *********** (END) Custom styling ****************/


		// *********** Check if connected to test msp api ***************/
		if ( get_option('ech_lfg_apply_test_msp') == "1" && current_user_can( 'manage_options' ) ) {
			$output .= '<div style="background: #ff6a6a;color: #fff">Please note that all the LFG plugin forms are connected to TEST MSP API</div>';
		}
		// *********** (END) Check if connected to test msp api ***************/



		// *********** Check if apply reCAPTCHA v3 ***************/
		if ( get_option('ech_lfg_apply_recapt') == "1" ) {
			$output .= '<script src="https://www.google.com/recaptcha/api.js?render='. get_option( 'ech_lfg_recapt_site_key' ) .'"></script>';
		}
		// *********** (END) Check if apply reCAPTCHA v3 ***************/

		$output .= '
		<form class="ech_lfg_form" id="ech_lfg_form" action="" method="post" data-limited-no="' . $item_limited_num . '" data-r="' . $r . '" data-c-token="' . $c_token . '" data-shop-count="' . $shop_count . '" data-ajaxurl="' . get_admin_url(null, 'admin-ajax.php') . '" data-ip="' . $ip . '" data-url="https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '" data-has-textarea="' . $has_textarea . '" data-has-select-dr="' . $has_dr . '" data-item-label="' . $item_label . '" data-tks-para="' . $tks_para . '" data-brand="' . $brand . '" data-has-hdyhau="' . $has_hdyhau . '" data-apply-recapt="'.get_option('ech_lfg_apply_recapt').'" data-recapt-site-key="'. get_option('ech_lfg_recapt_site_key') .'" data-recapt-score="'.get_option('ech_lfg_recapt_score').'" >
			<div class="lfg_formMsg"></div>
			<div class="form_row">
				<input type="hidden" name="booking_time" value="">
			</div>
		<div class="form_row customer_info">
			<div data-ech-field="last_name">
				<input type="text" name="last_name" id="last_name"  class="form-control"  placeholder="*姓氏" pattern="[ A-Za-z\u3000\u3400-\u4DBF\u4E00-\u9FFF]{1,}"  size="40" required >
			</div>
			<div data-ech-field="first_name">
				<input type="text" name="first_name" id="first_name" class="form-control" placeholder="*名字" pattern="[ A-Za-z\u3000\u3400-\u4DBF\u4E00-\u9FFF]{1,}" size="40" required >
			</div>
			<div data-ech-field="telPrefix">
				<select  class="form-control" name="telPrefix" id="tel_prefix" style="width: 100%;" required >
					<option disabled="" selected="" value="">*請選擇</option>
					<option value="+852">+852</option>
					<option value="+853">+853</option>
					<option value="+86">+86</option> 
				</select>
			</div>
			<div data-ech-field="tel">
				<input type="text" name="tel" placeholder="*電話"  class="form-control" size="30" id="tel" pattern="[0-9]{8,11}" required >
			</div>
			';

		//**** Email
		$output .= '<div data-ech-field="email">';
		if ($email_required_bool) {
			$output .= '<input type="email" name="email" id="email" placeholder="*電郵" class="form-control" size="40" required>';
		} else {
			$output .= '<input type="email" name="email" id="email" placeholder="電郵" class="form-control" size="40" >';
		}
		$output .= '</div>';
		//**** (END) Email

		$output .= '</div> <!-- form_row -->';



		$output .= '<div class="form_row">';

		//******* Choose doctor if any
		if ($has_dr_bool) {
			$output .= '<div data-ech-field="select_dr">';
			$output .= '<select  class="form-control" name="select_dr" id="select_dr" style="width: 100%;" required >';
			$output .= '<option disabled="" selected="" value="">*請選擇醫生</option>';
			for ($i = 0; $i < count($paraArr['dr']); $i++) {
				$output .= '<option value="' . $paraArr['dr_code'][$i] . '">' . $paraArr['dr'][$i] . '</option>';
			}
			$output .= '</select>';
			$output .= '</div>';
		}
		//******* (END) Choose doctor if any

		$output .= '
		<div data-ech-field="booking_date">
			<input type="text" placeholder="*預約日期" class="form-control lfg_datepicker" name="booking_date" autocomplete="off" value="" size="40" required>
		</div>
		<div data-ech-field="booking_time">
				<input type="text" placeholder="*預約時間" id="booking_time" class="form-control lfg_timepicker ui-timepicker-input" name="booking_time" autocomplete="off" value="" size="40" required="">
		</div>';

		$output .= '</div><!-- form_row -->';



		//**** Location Options
		$output .= '
		<div class="form_row">
			<div data-ech-field="shop">';
		if ($shop_count <= 3) {
			// radio
			$output .= '<label>' . $shop_label . '</label><br>';
			if ($shop_count == 1) {
				$output .= '<label class="radio_label"><input type="radio" value="' . $paraArr['shop_code'][0] . '" data-shop-text-value="' . $paraArr['shop'][0] . '" name="shop" checked onclick="return false;">' . $paraArr['shop'][0] . '</label>';
			} else {
				for ($i = 0; $i < $shop_count; $i++) {
					$output .= '<label class="radio_label"><input type="radio" value="' . $paraArr['shop_code'][$i] . '" name="shop" data-shop-text-value="' . $paraArr['shop'][$i] . '" required>' . $paraArr['shop'][$i] . '</label>';
				}
			}
		} else {
			// select
			$output .= '<select  class="form-control" name="shop" id="shop" style="width: 100%;" required >';
			$output .= '<option disabled="" selected="" value="">' . $shop_label . '</option>';
			for ($i = 0; $i < $shop_count; $i++) {
				$output .= '<option value="' . $paraArr['shop_code'][$i] . '">' . $paraArr['shop'][$i] . '</option>';
			}
			$output .= '</select>';
		}

		$output .= '
		</div>
		</div> <!-- form_row -->';
		//**** (END) Location Options





		//**** Item Options
		$output .= '
		<div class="form_row">
			<div data-ech-field="item">';

		if (count($paraArr['item']) == 1) {
			$output .= '<label>' . $item_label . '</label><br>';
			$output .= '<label class="checkbox_label"><input type="checkbox" value="' . $paraArr['item_code'][0] . '" name="item" data-text-value="' . $paraArr['item'][0] . '" checked onclick="return false;">' . $paraArr['item'][0] . '</label>';
		} else if (count($paraArr['item']) < 7) {
			$output .= '<label>' . $item_label . '</label><br>';
			for ($i = 0; $i < count($paraArr['item']); $i++) {
				$output .= '<label class="checkbox_label"><input type="checkbox" class="';
				if ($limited_bool) {
					$output .= 'limited_checkbox';
				} else {
					$output .= '';
				}
				$output .= '" value="' . $paraArr['item_code'][$i] . '" name="item" data-text-value="' . $paraArr['item'][$i] . '">' . $paraArr['item'][$i] . '</label><br>';
			}
		} else {
			// dropdown list checkbox 
			$output .= '<div class="lfg_checkbox_dropdown"><label class="lfg_dropdown_title">' . $item_label . '</label>';
			$output .= '<ul class="lfg_checkbox_dropdown_list">';
			for ($i = 0; $i < count($paraArr['item']); $i++) {
				$output .= '<li>';
				$output .= '<label class="checkbox_label"><input type="checkbox" class="';
				if ($limited_bool) {
					$output .= 'limited_checkbox';
				} else {
					$output .= '';
				}
				$output .= '" value="' . $paraArr['item_code'][$i] . '" name="item" data-text-value="' . $paraArr['item'][$i] . '">' . $paraArr['item'][$i] . '</label>';
				$output .= '</li>';
			} // for loop
			$output .= '</ul>'; //lfg_checkbox_dropdown_list
			$output .= '</div>'; // lfg_checkbox_dropdown


		} // count($paraArr['item'])
		$output .= '
			</div>
		</div> <!-- form_row -->';
		//**** (END) Item Options




		//**** TEXTAREA 
		if ($has_textarea_bool) {
			$output .= '
			<div class="form_row">
				<div data-ech-field="remarks">
					<textarea class="form-control" type="textarea" name="remarks" id="remarks" placeholder="' . $textarea_label . '" maxlength="140" rows="7"></textarea>
				</div>
			</div>
			<!-- form_row -->
			';
		}
		//**** (END) TEXTAREA 



		//**** HOW DID YOU HEAR ABOUT US
		if ($has_hdyhau_bool) {
			$output .= '<div class="form_row"><div data-ech-field="select_hdyhau">';
			$output .= '<select  class="form-control" name="select_hdyhau" id="select_hdyhau" style="width: 100%;" >';
			$output .= '<option disabled="" selected="" value="">從以下那一種途徑得知我們?</option>';
			for ($i = 0; $i < count($paraArr['hdyhau_item']); $i++) {
				$output .= '<option value="' . $paraArr['hdyhau_item'][$i] . '">' . $paraArr['hdyhau_item'][$i] . '</option>';
			}
			$output .= '</select>';
			$output .= '</div></div>';
		}
		//**** (END) HOW DID YOU HEAR ABOUT US



		$output .= ' 
			<div class="form_row">
								<div data-ech-field="info_remark">

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
	} // function display_ech_lfg()




	public function lfg_formToMSP() {

		$crData = array();
		$crData['token'] = $_POST['token'];
		$crData['source'] = $_POST['source'];
		$crData['name'] = $_POST['name'];
		$crData['user_ip'] = $_POST['user_ip'];
		$crData['website_name'] = $_POST['website_name'];
		$crData['website_url'] = $_POST['website_url'];
		$crData['enquiry_item'] = $_POST['enquiry_item'];
		$crData['tel_prefix'] =	$_POST['tel_prefix'];
		$crData['tel'] = $_POST['tel'];
		$crData['email'] = $_POST['email'];
		$crData['age_group'] =	$_POST['age_group'];
		$crData['shop_area_code'] = $_POST['shop_area_code'];
		$crData['booking_date'] = $_POST['booking_date'];
		$crData['booking_time'] = $_POST['booking_time'];
		$crData['remarks'] = $_POST['remarks'];
		

		if (get_option('ech_lfg_apply_test_msp') == "1") {
			// connect to DEV MSP API
			$result	= $this->lfg_curl('https://msp-dev.echealthcare.com/api/third_party_service/Offical/submitEnquiryForm', $crData, true);
		} else {
			// connect to LIVE MSP API
			$result	= $this->lfg_curl('https://msp.prohaba.com:8003/api/third_party_service/Offical/submitEnquiryForm', $crData, true);
		}
		

		echo $result; // return json format
		wp_die(); // prevent ajax return 0
	}


	public function lfg_recaptVerify() {
		$crData = array();
		$crData['response'] = $_POST['recapt_token'];
		$crData['secret'] = get_option('ech_lfg_recapt_secret_key');

		$result	= $this->lfg_curl('https://www.google.com/recaptcha/api/siteverify', $crData, true);
		echo $result;
		wp_die();
	}



	/****************************************************
	 * For generate epay ref code that pass to MSP and 
	 * epay landing url 
	 ****************************************************/
	public function genRandomString($length = 5) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[random_int(0, $charactersLength - 1)];
		}
		return $randomString;
	}


	private function lfg_curl($i_url, $i_fields = null, $i_isPOST = 0) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $i_url);
		curl_setopt($ch, CURLOPT_POST, $i_isPOST);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		if ($i_fields != null && is_array($i_fields))
		{
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($i_fields));
		}
		$rs = curl_exec($ch);
		curl_close($ch);
	
		return $rs;
	}



	


}
