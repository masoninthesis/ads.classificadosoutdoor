<!--Start Sidebar-->
<div class="sidebar">
    <?php
    if (cc_get_option('cc_lead') == 'on') {
        if (is_single()) {
            if (file_exists(CONTROLPATH . 'cc_leads.php')) {
                require_once(CONTROLPATH . 'cc_leads.php');
            }
        }
    }
    if (is_single()) {
        echo "<div class=\"sidebar_widget\">";
        if (file_exists(LIBRARYPATH . 'map/single_map.php')) {
            include_once(LIBRARYPATH . "map/single_map.php");
        }
        echo "</div>";
    }
    echo '<div class="clear"></div>';
    // ad widget area
    if (is_active_sidebar('add-widget-area')) :
        dynamic_sidebar('add-widget-area');
    endif;
    ?>
</div>
<!--End Sidebar-->