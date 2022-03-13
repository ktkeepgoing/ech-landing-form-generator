<?php 
/**
 *
 * @package           ECH_Landing_Form_Generator
 * 
 * ==================================
 *     PHP functions for Ajax
 * ==================================
 * 
 */


 function lfg_proxy_ajax_to_msp() {
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
	
	$result	= lfg_curl('https://msp.prohaba.com:8003/api/third_party_service/Offical/submitEnquiryForm', $crData, true);
	return $result; // return json format
 }



 function lfg_curl($i_url, $i_fields = null, $i_isPOST = 0) {
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