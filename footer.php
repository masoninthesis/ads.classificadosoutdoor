<!--End Cotent Wrapper-->
<div class="clear"></div>
<!--Start Footer Wrapper-->
<div class="footer_wrapper">
    <div class="container_24">
        <div class="grid_24">
            <!--Start Footer-->
            <div class="footer">
                <?php
                /**
                 * Footer widgets 
                 */
                get_sidebar('footer');
                ?>
            </div>
            <!--Start Footer-->
        </div>
        <div class="clear"></div>
    </div>
</div>
<!--End Footer Wrapper-->
<div class="clear"></div>
<div class="footer_bottom">
    <div class="container_24">
        <div class="grid_24">
            <?php
                if(cc_get_option('cc_yahoo') != '' ||
                cc_get_option('cc_blogger') != '' ||
                cc_get_option('cc_facebook') != '' ||
                cc_get_option('cc_twitter') != '' ||
                cc_get_option('cc_rss') != '' ||
                cc_get_option('cc_plusone') != '' ||
                cc_get_option('cc_youtube') != '' ||
                cc_get_option('cc_pinterest') != '' ){
                    $social_exist = false;
            ?>
            <div class="grid_12 alpha">
                <div class="bottom_content">
                    <ul class="social_icon">
                        <?php if(cc_get_option('cc_yahoo') != ''){ ?>
                        <li><a  href="<?php echo cc_get_option('cc_yahoo'); ?>"><img src="<?php echo TEMPLATEURL.'/images/yahoo.png'; ?>" title="Yahoo" alt="yahoo"/></a></li>
                        <?php } ?>
                        <?php if(cc_get_option('cc_blogger') != ''){ ?>
                        <li><a  href="<?php echo cc_get_option('cc_blogger'); ?>"><img src="<?php echo TEMPLATEURL.'/images/blogger.png'; ?>" title="Blogger" alt="blogger"/></a></li>
                        <?php } ?>
                        <?php if(cc_get_option('cc_facebook') != ''){ ?>
                        <li><a  href="<?php echo cc_get_option('cc_facebook'); ?>"><img src="<?php echo TEMPLATEURL.'/images/facebook.png'; ?>" title="Facebook" alt="facebook"/></a></li>
                        <?php } ?>
                        <?php if(cc_get_option('cc_twitter') != ''){ ?>
                        <li><a  href="<?php echo cc_get_option('cc_twitter'); ?>"><img src="<?php echo TEMPLATEURL.'/images/twitter.png'; ?>" title="Twitter" alt="twitter"/></a></li>
                        <?php } ?>
                        <?php if(cc_get_option('cc_rss') != ''){ ?>
                        <li><a  href="<?php echo cc_get_option('cc_rss'); ?>"><img src="<?php echo TEMPLATEURL.'/images/rss.png'; ?>" title="Rss" alt="rss"/></a></li>
                        <?php } ?>
                        <?php if(cc_get_option('cc_youtube') != ''){ ?>
                        <li><a  href="<?php echo cc_get_option('cc_youtube'); ?>"><img src="<?php echo TEMPLATEURL.'/images/youtube.png'; ?>" title="Youtube" alt="youtube"/></a></li>
                        <?php } ?>
                        <?php if(cc_get_option('cc_plusone') != ''){ ?>
                        <li><a  href="<?php echo cc_get_option('cc_plusone'); ?>"><img src="<?php echo TEMPLATEURL.'/images/google.png'; ?>" title="Google Plus" alt="google plus"/></a></li>
                        <?php } ?>
                        <?php if(cc_get_option('cc_pinterest') != ''){ ?>
                        <li><a  href="<?php echo cc_get_option('cc_pinterest'); ?>"><img src="<?php echo TEMPLATEURL.'/images/pinterest.png'; ?>" title="Pinterest" alt="pinterest"/></a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <?php }else { $social_exist = true; } ?>
            <div class="grid_12 omega">
                <div class="bottom_content">
                    <?php 
                    if($social_exist == true){
                        $style = "left;";
                    }
                    ?>
                    <p style="float:<?php echo $style; ?>" class="copyright"><?php if(cc_get_option('cc_footertext') != ''){ echo cc_get_option('cc_footertext');} else{ ?>&copy; <?php echo date('Y'); ?> Clasificados Outoor. Todos los derechos reservados.<?php } ?></p>
                </div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
</div>
<div class="clear"></div>
</div>
<?php wp_footer(); ?>
<div style="visibility: hidden; display: block; clear: both;width: 100%; height: 20px;"></div>
</body>
</html>
