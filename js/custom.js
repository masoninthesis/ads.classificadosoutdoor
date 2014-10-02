/*--------DDsmoothmenu Initialization--------*/
ddsmoothmenu.init({
    mainmenuid: "menu", //menu DIV id
    orientation: 'h', //Horizontal or vertical menu: Set to "h" or "v"
    classname: 'ddsmoothmenu', //class added to menu's outer DIV
    //customtheme: ["#1c5a80", "#18374a"],
    contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
});

jQuery(document).ready(function($) {
    $("#content div").hide(); // Initially hide all content
    $("#tabs li:first").attr("id", "current"); // Activate first tab
    $("#content div:first").fadeIn(); // Show first tab content

    $('#tabs a').click(function(e) {
        e.preventDefault();
        $("#content div").hide(); //Hide all content
        $("#tabs li").attr("id", ""); //Reset id's
        $(this).parent().attr("id", "current"); // Activate this
        $('#' + $(this).attr('title')).fadeIn(); // Show content for current tab
    });
});

//Flexslider
jQuery(window).load(function() {
    jQuery('.flexslider').flexslider();
});
jQuery(function($) {
    $('.select').dropkick();
});

//IE8 Place holder

jQuery(document).ready(function($) {

    if (!Modernizr.input.placeholder) {

        $('[placeholder]').focus(function() {
            var input = $(this);
            if (input.val() == input.attr('placeholder')) {
                input.val('');
                input.removeClass('placeholder');
            }
        }).blur(function() {
            var input = $(this);
            if (input.val() == '' || input.val() == input.attr('placeholder')) {
                input.addClass('placeholder');
                input.val(input.attr('placeholder'));
            }
        }).blur();
        $('[placeholder]').parents('form').submit(function() {
            $(this).find('[placeholder]').each(function() {
                var input = $(this);
                if (input.val() == input.attr('placeholder')) {
                    input.val('');
                }
            })
        });
    }
});

//Tipsy
jQuery(function() {
    jQuery(".crousel_thumb").each(function() {
        jQuery(this).tipsy();
    });
});

jQuery(document).ready(function() {
    jQuery(".crousel_thumb").each(function() {
        jQuery(this).attr("title", jQuery(this).attr("data-title"));
    });
});