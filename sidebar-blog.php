<!--Start Sidebar-->
<div class="sidebar">
    <?php
    if (is_active_sidebar('blog-widget-area')) :
        dynamic_sidebar('blog-widget-area');
    endif;
    ?>    
</div>