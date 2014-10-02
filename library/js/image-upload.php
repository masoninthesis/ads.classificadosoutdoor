<script type="text/javascript">  
jQuery(document).ready(function(){
    //AJAX Upload
    jQuery('.image_upload_button').each(function(){

        var clickedObject = jQuery(this);
        var clickedID = jQuery(this).attr('id');	
        new AjaxUpload(clickedID, {
            action: '<?php echo admin_url("admin-ajax.php"); ?>',
            name: clickedID, // File upload name
            data: { // Additional data to send
                action: 'of_ajax_post_action',
                type: 'upload',
                data: clickedID },
            autoSubmit: true, // Submit file after selection
            responseType: false,
            onChange: function(file, extension){},
            onSubmit: function(file, extension){
                //clickedObject.text('Uploading'); // change button text, when user selects file	
                this.disable(); // If you want to allow uploading only 1 file at time, you can disable upload button
                /*interval = window.setInterval(function(){
                    var text = clickedObject.text();
                    if (text.length < 13){	clickedObject.text(text + '.'); }
                    else { clickedObject.text('Uploading'); } 
                }, 200);*/
            },
                 onComplete: function(file, response) {

                //window.clearInterval(interval);
                //clickedObject.text('Upload Image');	
                this.enable(); // enable upload button

                // If there was an error
                if(response.search('Upload Error') > -1){
                    var buildReturn = '<span class="upload-error">' + response + '</span>';
                    jQuery(".upload-error").remove();
                    clickedObject.parent().after(buildReturn);

                }
                else{
                    var buildReturn = '<img class="hide meta-image" id="image_'+clickedID+'" src="'+response+'" width="285" height="250" alt="" />';
                    jQuery(".upload-error").remove();
                    jQuery("#image_" + clickedID).remove();	
                    clickedObject.parent().after(buildReturn);
                    jQuery('img#image_'+clickedID).fadeIn();
                    clickedObject.next('div').fadeIn();
                    clickedObject.parent().prev('input').val(response);
                }
            }
        });

    });
    //AJAX Remove (clear option value)
    jQuery('.image_reset_button').click(function(){

        var clickedObject = jQuery(this);
        var clickedID = jQuery(this).attr('id');
        var theID = jQuery(this).attr('title');	

        var ajax_url = '<?php echo admin_url("admin-ajax.php"); ?>';

        var data = {
            action: 'of_ajax_post_action',
            type: 'image_reset',
            data: theID
        };

        jQuery.post(ajax_url, data, function(response) {
            var image_to_remove = jQuery('#image_' + theID);
            var button_to_hide = jQuery('#reset_' + theID);
            image_to_remove.fadeOut(500,function(){ jQuery(this).remove(); });
            button_to_hide.fadeOut();
            clickedObject.parent().prev('input').val('');
        });

        return false; 

    }); 



});

</script>