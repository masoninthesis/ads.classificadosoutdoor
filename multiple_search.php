<form id="advance_search" action="<?php echo home_url('/'.CC_SEARCH.'/'); ?>" method="get">
    <div class="search_item first">
        <input type="text" class="search_for" placeholder="<?php echo SEARCH1; ?>" name="search_for" value=""/>
    </div>
    <div class="search_item second">   
        <?php
        $taxonomies = array(CUSTOM_CAT_TYPE);
        $args = array('orderby' => 'count', 'hide_empty' => 0);

        $myterms = get_terms($taxonomies, $args);
        $output = "<select class=\"select\" name='select_cat' title=\"Select Category\">";
        $output .= "<option value=''>".SLT_CAT."</option>";
        foreach ($myterms as $term) {
            $root_url = get_bloginfo('url');
            $term_taxonomy = $term->taxonomy;
            $term_slug = $term->slug;
            $term_name = $term->name;
            $termid = $term->term_taxonomy_id;
            $link = $root_url . '/' . $term_taxonomy . '/' . $term_slug;
            $output .="<option value='" . $termid . "'>" . $term_name . "</option>";
        }
        $output .="</select>";
        echo $output;
        ?>
    </div>
    <div class="search_item third">
        <input type="text" placeholder="<?php echo SEARCH2; ?>" class="search_loc" name="search_loc" />
        <input type="submit" class="search_submit" value="<?php echo SEARCH; ?>"/>
    </div>
</form>