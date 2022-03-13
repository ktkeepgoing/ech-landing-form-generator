jQuery(document).ready(function(){

    /*********** Select form type ***********/
    jQuery('input[type=radio][name=lfg_form_type]').on('change', function() {
        if (this.value == 'booking') {
            jQuery(".datetime_row").css("display", "flex");
            jQuery(".location_row").css("display", "flex");
            jQuery('#booking_date').prop('required',true);
            jQuery('#booking_time').prop('required',true);
        }
        else if (this.value == 'enquiry') {
            jQuery(".datetime_row").css("display", "none");
            jQuery(".location_row").css("display", "none");
            jQuery('#booking_date').prop('required',false);
            jQuery('#booking_time').prop('required',false);
        }
    });
    /*********** (END) Select form type ***********/




    /*********** Checkbox limitation ***********/
    var limit = jQuery('.ech_lfg_form').data("limited-no");
    jQuery('.ech_lfg_form input.limited_checkbox').on('change', function(evt) {
	   if(jQuery(".ech_lfg_form input.limited_checkbox[name='item']:checked").length > limit) {
	       this.checked = false;
	   }
	});
    /*********** (END) Checkbox limitation ***********/

    /*********** Datepicker & Timepicker ***********/
    jQuery('.lfg_timepicker').timepicker({'minTime': '9:30am','maxTime': '6:30pm'});
    jQuery( ".lfg_datepicker" ).datepicker({ beforeShowDay: nosunday, dateFormat: 'yy-mm-dd', minDate: 3});
    jQuery('#ui-datepicker-div').addClass('skiptranslate notranslate');
    /*********** (END) Datepicker & Timepicker ***********/


    /*********** Checkbox dropdown list ***********/
    if (jQuery(".lfg_checkbox_dropdown")[0]) { // if class exist

        jQuery(".lfg_checkbox_dropdown").click(function () {
            jQuery(this).toggleClass("lfg_is_active");
        });
        
        jQuery(".lfg_checkbox_dropdown ul").click(function(e) {
            e.stopPropagation();
        });

        
        
        jQuery('.ech_lfg_form input.limited_checkbox').on('change', function(evt) {
            var tempArr = [];
            jQuery(".ech_lfg_form input.limited_checkbox[name='item']:checked").each(function(){
                var itemText = jQuery(this).parent().text();
                tempArr[tempArr.length] = itemText;
            });
            //console.log(tempArr);
            if(tempArr.length == 0) {
                var item_label = jQuery("#ech_lfg_form").data("item-label");
                jQuery(".lfg_dropdown_title").html(item_label);
            } else {
                jQuery(".lfg_dropdown_title").html(tempArr.join());
            }
            

            // auto scroll up
            if(jQuery(".ech_lfg_form input.limited_checkbox[name='item']:checked").length == limit) {
                jQuery(".lfg_checkbox_dropdown").toggleClass("lfg_is_active");
            }
        });

    } 
    /*********** (END) Checkbox dropdown list ***********/
    

    /*********** Form Submit ***********/
    jQuery('#ech_lfg_form').on("submit", function(e){
        e.preventDefault();

        var r = jQuery(this).data("r");
        var c_token = jQuery(this).data("c-token");
        var ip = jQuery(this).data("ip");
        var url = jQuery(this).data("url");
        var ajaxurl = jQuery(this).data("ajaxurl");
        var tks_para = jQuery(this).data("tks-para");
        var shop_count = jQuery(this).data("shop-count");
        var brand = jQuery(this).data("brand");
        var has_textarea = jQuery(this).data("has-textarea");
        var has_media_lead = jQuery(this).data("has-media-lead");

        var items = [];
		jQuery.each(jQuery("#ech_lfg_form input[name='item']:checked"), function(){
			items.push(jQuery(this).val());
		});


		var _name = jQuery("#ech_lfg_form #last_name").val() + " " + jQuery("#ech_lfg_form #first_name").val(),
			_user_ip = ip,
			_source = r,
			_token = c_token,
			_website_name = brand,
			_website_url = url,
			_tel_prefix = jQuery("#ech_lfg_form #tel_prefix").val(),
			_tel = jQuery("#ech_lfg_form #tel").val(),
			_email = jQuery("#ech_lfg_form #email").val(),
			_age_group = jQuery("#ech_lfg_form #age").val(),
            _remarks = "";

        
        var _booking_date = "";
        var _booking_time = "";
        var _shop_area_code = "";

        /******* Form Type Selection *******/
        var form_type = jQuery('input[name=lfg_form_type]:checked', '#ech_lfg_form').val();
        if (form_type == "booking") {            
            _remarks += " | 提交類型: " + form_type;
            _booking_date = jQuery("#ech_lfg_form .lfg_datepicker").val(),
            _booking_time = jQuery("#ech_lfg_form .lfg_timepicker").val();

            if (shop_count <=3){
                _shop_area_code = jQuery('input[name=shop]:checked', '#ech_lfg_form').val();
            } else {
                _shop_area_code = jQuery('#shop').val();
            }
        } else if (form_type == "enquiry") {
            _remarks += " | 提交類型: " + form_type;
            _booking_date = "";
            _booking_time = "";
            _shop_area_code = "KLN15";
        }
        /******* (END)Form Type Selection *******/
        
        if(has_media_lead == 1) {
            _remarks += " | 社交媒體: " + jQuery("#media_lead").val();
        }


        if(has_textarea == 1) {
            _remarks += " | 其他諮詢: " + jQuery("#remarks").val();
        }


        /****************** FORM VALIDATION ******************/
            if(( _tel_prefix == "+852" && _tel.length != 8 ) || ( _tel_prefix == "+853" && _tel.length != 8 ) ) {
                jQuery(".lfg_formMsg").html("+852, +853電話必需8位數字(沒有空格)");
                return false;
            } else if((_tel_prefix == "+86" && _tel.length != 11)) {
                jQuery(".lfg_formMsg").html("+86電話必需11位數字(沒有空格)");
                return false;
            } else {
                var checked_item_count = jQuery("#ech_lfg_form input[name='item']:checked").length;
                if( checked_item_count == 0) {
                    jQuery(".lfg_formMsg").html("請選擇咨詢項目");
                    return false;
                } else {
                    jQuery("#ech_lfg_form #submitBtn").prop('disabled', true);
                    jQuery(".lfg_formMsg").html("提交中...");
                    jQuery("#ech_lfg_form #submitBtn").html("提交中...");

                    var data = {'action': 'LFG_formToMSP',
						'token': _token, 
						'source': _source, 
						'name': _name, 
						'user_ip': _user_ip,
						'website_name': _website_name,
						'website_url': _website_url,
						'enquiry_item': items,
						'tel_prefix': _tel_prefix,
						'tel': _tel,
						'email': _email,
						'age_group': _age_group,
						'shop_area_code': _shop_area_code,
						'booking_date': _booking_date,
						'booking_time': _booking_time,
						'remarks': _remarks
					};

                    jQuery.post(ajaxurl, data, function(msg) {
                        var jsonObj = JSON.parse(msg);
                        console.log(msg);
                        console.log(jsonObj.result);
                        if (jsonObj.result == 0) {
                            var origin   = window.location.origin;
                            if (tks_para != null) {
                                window.location.replace(origin+'/primecare/thanks?prod='+tks_para);
                            } else {
                                window.location.replace(origin+'/primecare/thanks');
                            }                         
                        } else {
                            alert("無法提交閣下資料, 請重試");
                            location.reload(true);
                        }
                    });  // end post ajax

                } // checked_item_count
            }//_tel_prefix
    }); // onclick
    /*********** (END) Form Submit ***********/

});



function nosunday(date) {
    var day = date.getDay(); 
    return [(day > 0), ''];	
}