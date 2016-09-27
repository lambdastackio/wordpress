<!-- search box -->
<div class="searchbox">
	<form role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
		<div class="input-group">
		
			<?php if( $searchresults != ('') ) : ?>
				<input type="text" class="form-control" placeholder="type keyword ..." value="<?php echo $searchresults; ?>" name="s" id="search-keyword">
			<?php else: ?>
				<input type="text" class="form-control" placeholder="type keyword ..." name="s" id="s-keyword">
			<?php endif; ?>
			
			<span class="input-group-btn">
				<input type="submit" class="btn btn-default" id="search-submit" value="<?php _e( 'Go', 'repute' ); ?>">
			</span>
		</div>
		<!-- <input type="hidden" name="post_type" value="post"> -->
	</form>
</div>
<!-- end searchbox -->
