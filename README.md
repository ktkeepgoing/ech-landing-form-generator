# ech-landing-form-generator
### with WATI auto send Whatsapp msg function
A Wordpress plugin to generate a responsive lead form for ECH company's brand websites. It is integrated with ECH marketing system (MSP). Form data will be passed and stored in the MSP for future use.   


## Usage
To generate a lead form, you need to enter a brand name in dashboard setting page first. It is a value required to pass to MSP. Then, you may copy the below shortcode sample to start generate a lead form. 
```
[ech_lfg default_r_code="t200" r="t575, t575google | t127, T127fb" r_code="TCODETOKEN575, TCODETOKEN127" item="Item 1" item_code="ITEMCODE123" shop="Causeway Bay, Kowloon" shop_code="HK001, KW001"]
```

## Shortcode attributes
Based on the form requirments and MSP campaigns, change the attributes or values if necessary.

- **default_r** (String): default tcode, default is t200
- **default_r_code** (String)(*)[^1]: default tcode MSP token
- **r** (Multiple Strings)[^2]: tcode eg.`"t575, t127"`. If there are more than one tcodes using the same tcode token, use `|` to separate them. Eg. `"t575,t575g|t127,t127fb"`. All case insensitive.
- **r_code** (Multiple Strings): tcode token. Eg. `"TCODE1234, TCOED5678"`
- **email_required** (INT): 0 = false, 1 = true. Default is 1.
- **item** (Multiple Strings)(*)[^1]: item checkbox
- **item_code** (Multiple Strings)(*)[^1]: item MSP token
- **item_label** (String): item label. Default is "*查詢項目"
- **is_item_limited** (INT): are the items limited. 0 = false, 1 = true. Default is 0
- **item_limited_num** (INT): No. of options can the user choose. Default is 1
- **shop** (Multiple Strings)(*)[^1]: shop
- **shop_code** (Multiple Strings)(*)[^1]: shop MSP token
- **shop_label** (String): shop label. Default is "*請選擇診所"
- **has_textarea** (INT): has textarea field. 0 = false, 1 = true. Default is 0.
- **textarea_label** (String): textarea placeholder. Default is "其他專業諮詢"
- **has_hdyhau** (INT): has "How did you hear about us" field. 0 = false, 1 = true. Default is 0. 
- **hdyhau_item** (Multiple Strings)(*): "How did you hear about us" items. Eg. `"Facebook, Google"`
- **brand** (String): This will override the global setting "brand name" value which is set in the dashboard. 
- **tks_para**(String): url parameter needs to pass to thank you page, usually product/treatment name. It is used for traffic tracking. Eg. `https://xxx.com/thanks?prod=TKS_PARA_VALUE`
- **wati_send**(INT): enable or disable the WATI auto send Whatsapp msg function. 0 = disable, 1 = enable. Default is 0.
- **wati_msg**(String): insert wati msg template name (provided by marketers)

Below attributes values must be corresponding to each other, otherwise no form will be generated:
1. r and r_code
2. item and item_code
3. shop and shop_code


[^1]: attribute is required
[^2]: Multiple Strings: can has multiple values, use comma to separate them


### Remarks
- If the shop selection field is more than 3 options or the item selection field is more than 7 options, those fields will be displayed as a dropdown selection.
