<form method="get" class="search-form" id="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<input type="search" class="search-field" placeholder="<?php _e('Search form', 'fukasawa'); ?>" name="s" id="s" /> 
	<a id="searchsubmit" class="search-button" onclick="document.getElementById('search-form').submit(); return false;"><div class="genericon genericon-search"></div></a>
</form>