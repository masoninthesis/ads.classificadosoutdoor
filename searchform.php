<form role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
  <div>
    <input type="text" onfocus="if (this.value == '<?php echo ucfirst(SEARCH); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php echo ucfirst(SEARCH); ?>';}"  value="<?php echo ucfirst(SEARCH); ?>" name="s" id="s" />
    <input type="submit" id="searchsubmit" value="" />
  </div>
</form>