jQuery(document).ready(function(){
    var pkg_price = 0.00;
    var feature_h = jQuery('#feature_h');
    var feature_c = jQuery('#feature_c');
    var spkg_price = jQuery('#pkg_price');
    var categoryh = 0;
    var categoryc = 0;
    var totalCate = 0;
    var feature_price = jQuery('#feature_price');
    var result_price = jQuery('#result_price');
    var totalPrice = 0;
    var totalCost = jQuery('#total_price');
    //Start price-select click
    jQuery('#featured').hide();
    jQuery('input:radio[name=price_select]').click(function(){
        
           //Featured Categories
        var is_featured = jQuery(this).parent('label').find('input[name="is_featured"]').val();
        jQuery('#loading').show();        
        if(is_featured == 1){            
            window.setTimeout(function(){ 
                jQuery('#featured').show(); 
                jQuery('#loading').hide();
            }, 500);
        }
        else{
            jQuery('#loading').show();
            window.setTimeout(function(){ 
                jQuery('#featured').hide();   
                jQuery('#loading').hide();
            }, 500);
        }
        var is_recurring = jQuery(this).parent('label').find('input[name="is_recurring"]').val();
        //Validate Payment cycle
        var validate_cycle = jQuery('#billing');
        if(is_recurring == 1){
            validate_cycle.val(1);
        }
        else{
           validate_cycle.val(0);
        }
        
        pkg_price = jQuery('input:radio[name=price_select]:checked').val();                            
        //Find each value from category
        var f_home = jQuery(this).parent('label').find('input[name="f_home"]').val();
        var f_cate = jQuery(this).parent('label').find('input[name="f_cate"]').val();
                                
        var fhome = jQuery('#fhome');
        var fcat = jQuery('#fcat');
        //Set text for category label
        jQuery('#loading').show();
        window.setTimeout(function(){ 
            fhome.text(f_home);
            fcat.text(f_cate);
            jQuery('#loading').hide();
        }, 500); 
        //Set values for categories
        feature_h.val(parseFloat(f_home));                         
        feature_c.val(parseFloat(f_cate));                  
    }); //End price_select click
    //Start
    jQuery('input:checkbox[name=feature_h]').change(function(){                        
        jQuery('#loading').show();
        categoryh = this.checked ? feature_h.val() : 0;
        window.setTimeout(function(){                           
            totalCate = parseFloat(categoryh) + parseFloat(categoryc);
            feature_price.text(parseFloat(totalCate));
            totalPrice = parseFloat(totalCate) + parseFloat(pkg_price);
            result_price.text(parseFloat(totalPrice)); 
            totalCost.val(totalPrice) ;
            jQuery('#loading').hide();
        }, 500);
    });//End
    //Start                    
    jQuery('input:checkbox[name=feature_c]').change(function(){
        jQuery('#loading').show();
        categoryc = this.checked ? feature_c.val() : 0;
        window.setTimeout(function(){                          
            totalCate = parseFloat(categoryh)+parseFloat(categoryc);
            feature_price.text(parseFloat(totalCate));
            totalPrice = parseFloat(totalCate) + parseFloat(pkg_price);
            result_price.text(parseFloat(totalPrice));
            totalCost.val(totalPrice) ;
            jQuery('#loading').hide();
        }, 500);
    }); //End
    //Start 
    jQuery('input:radio[name=price_select]').click(function(){
        jQuery('#loading').show();
        if(jQuery('#feature_h').is(':checked')){
            categoryh = this.checked ? feature_h.val() : 0; 
        }
        if(jQuery('#feature_c').is(':checked')){
            categoryc = this.checked ? feature_c.val() : 0;  
        }
        window.setTimeout(function(){ 
            spkg_price.text(pkg_price);
            totalCate = parseFloat(categoryh) + parseFloat(categoryc);
            feature_price.text(totalCate);
            totalPrice = parseFloat(totalCate) + parseFloat(pkg_price);
            result_price.text(totalPrice);            
            totalCost.val(totalPrice) ;
            jQuery('#loading').hide();                            
        }, 500);        
     var price_title = jQuery(this).parent('label').find('input[name="price_title"]').val();     
     jQuery('#package_title').val(price_title);
     
     //Set valu for listing active period
     var validity = jQuery(this).parent('label').find('input[name="validity"]').val(); 
     var validity_per = jQuery(this).parent('label').find('input[name="validity_per"]').val(); 
     jQuery('#package_validity').val(validity);
     jQuery('#package_validity_per').val(validity_per);
     
     //Set value for package type
     var pkg_type = jQuery(this).parent('label').find('input[name="pkg_type"]').val(); 
     jQuery('#package_type').val(pkg_type);
    });//End
});       
