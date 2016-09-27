<?php get_header(); ?>

	<!-- PAGE CONTENT -->
	<div class="page-content page-error text-center">
		<div class="container">
			<div class="row">
				<div class="col-md-6 col-md-offset-3">
					<h1>404</h1>
					<h2>OOPS! PAGE NOT FOUND</h2>
					<hr />
					<div class="error-description">
						<p>The page you were looking for could not be found, use search form below to find the page you are looking for</p>
					</div>
					
					<!-- search box -->
					<div class="searchbox">
						<form role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
							<div class="input-group input-group-lg">
								<?php if ( $searchresults != ('') ) : ?>
									<input type="text" class="form-control" placeholder="enter keyword ..." value="<?php echo $searchresults; ?>" name="searchKeyword" id="search-keyword">
								<?php else: ?>
									<input type="text" class="form-control" placeholder="enter keyword ..." name="searchKeyword" id="search-keyword">
								<?php endif; ?>
								
								<span class="input-group-btn">
									<button type="submit" class="btn btn-default" id="search-submit">
										<i class="fa fa-search"></i> <?php _e( 'Search', 'repute' ); ?>
									</button>
								</span>
							</div>
							<input type="hidden" name="post_type" value="post">
						</form>
					</div>
					<!-- end searchbox -->

				</div>
			</div>
		</div>
	</div>
	<!-- END PAGE CONTENT -->

<?php get_footer(); ?>