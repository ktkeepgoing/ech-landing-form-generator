# ech-landing-form-generator
A Wordpress plugin to generate a responsive lead form for ECH company's brand websites. It is integrated with ECH marketing system (MSP). Form data will be passed and stored in the MSP for future use.   

To generate a lead form, enter shortcode
```
[ech_lfg default_r_code="t200" r="t575, t575google | t127, T127fb" r_code="TCODETOKEN575, TCODETOKEN127" item="Item 1" item_code="ITEMCODE123" shop="Causeway Bay, Kowloon" shop_code="HK001, KW001"]
```

## Shortcode attributes
- **default_r** (String): default tcode, default is t200
- **default_r_code** (String)(*)[^1]: default tcode MSP token
- **r** (Multiple Strings)[^2]: tcode eg.`"t575, t127"`. If there are more than one tcodes using the same tcode token, use `|` to separate them. eg. `"t575,t575g|t127,t127fb"`
- **r_code** (Multiple Strings): tcode token. eg. `"TCODE1234, TCOED5678"`
- **email_required** (INT): 0 = false, 1 = true. default is 1.
- **item** (Multiple Strings)(*): item checkbox
- **item_code** (Multiple Strings)(*): item MSP token
- **item_label** (String): item label. default is "*查詢項目"
- **is_item_limited** (INT): are the items limited. 0 = false, 1 = true, default is 0
- **item_limited_num** (INT): No. of options can the user choose, default is 1
- **shop** (Multiple Strings)(*): shop
- **shop_code** (Multiple Strings)(*): shop MSP token
- **shop_label** (String): shop label. default is "*請選擇診所"
- **has_textarea** (INT): has textarea field. 0 = false, 1 = true. default is 0.
- **textarea_label** (String): textarea placeholder. default is "其他專業諮詢"
- **brand** (String)- for MSP, website name value
- **tks_para**(String): url parameter needs to pass to thank you page

Attributes values must be corresponding to each other:
1. r and r_code
2. item and item_code
3. shop and shop_code


[^1]: attribute is required
[^2]: Multiple Strings: can has multiple values, use comma to separate them

## Screenshots
![](https://github.com/ktkeepgoing/ech-landing-form-generator/blob/cc0582ab4442adb9b2e9c0a69f19fdee1421e6a7/screenshot/form.jpg)

If the shop selection field is more than 3 options or the item selection field is more than 7 options, the field is displayed as a dropdown selection, like the screenshot below
![](https://github.com/ktkeepgoing/ech-landing-form-generator/blob/cc0582ab4442adb9b2e9c0a69f19fdee1421e6a7/screenshot/more-options.jpg)
