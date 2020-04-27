<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Brumas Delivery</title>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/plugins/jQuery-ui/css/jquery-ui.min.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/plugins/jQuery-ui/css/jquery-ui.structure.min.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/plugins/jQuery-ui/css/jquery-ui.theme.min.css" type="text/css" />
		<link rel="stylesheet" href="/assets/css/bootstrap/config.css" type="text/css"/>
		<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/
		templates/default.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/widgets/restaurantItem.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/widgets/widgetItem.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo BASE_URL; ?>vendor/fortawesome/font-awesome/css/all.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo BASE_URL; ?>node_modules/rateyo/min/jquery.rateyo.min.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo BASE_URL; ?>node_modules/select2/dist/css/select2.min.css" />
		<link rel="stylesheet" href="<?php echo BASE_URL; ?>node_modules/@ttskch/select2-bootstrap4-theme/dist/select2-bootstrap4.min.css" />
		
	</head>
	<body>
		<nav class="navbar topnav navbar-expand-lg">
			<div id="navbarNav" class="container collapse navbar-collapse">
				<ul class="navbar-nav">
					<li class="nav-item menu-border">
						<a class="nav-link menu-border-active" href="<?php echo BASE_URL; ?>"><?php $this->language->get('HOME'); ?></a>
					</li>
					<li class="nav-item menu-border">
						<a class="nav-link" href="<?php echo BASE_URL; ?>contact"><?php $this->language->get('CONTACT'); ?></a>
					</li>
				</ul>
				<ul class="navbar-nav">
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><?php $this->language->get('LANGUAGE'); ?>
						<span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li>
								<a class="dropdown-item" href="<?php echo BASE_URL.'lang/set/en'; ?>">English</a>
							</li>
							<li>
								<a class="dropdown-item active" href="<?php echo BASE_URL.'lang/set/pt-br'; ?>">Português</a>
							</li>
						</ul>
					</li>
					<li class="nav-item menu-border">
						<a class="nav-link" href="<?php echo BASE_URL; ?>login"><?php $this->language->get('LOGIN'); ?></a>
					</li>
				</ul>
			</div>			
		</nav>

		<header>
			<div class="container">
				<div class="row">
					<div class="col-sm-3 logo">
						<a href="<?php echo BASE_URL; ?>"><img title="Brumas Delivery" src="<?php echo BASE_URL; ?>assets/images/logo-red.png" /></a>
					</div>
					<div class="col-sm-6 block-header-2">
						<div class="head-help"><i class="fas fa-phone-alt"></i>(11) 9999-9999</div>
						<div class="head-email"><i class="fas fa-envelope"></i>contato@<span>brumasdelivery.com.br</span></div>
						
						<div class="search-area">
							<form method="GET">
								<input type="text" name="term" required placeholder="<?php $this->language->get('SEARCHFORANITEM'); ?>" value="<?php echo (!empty($viewData['searchTerm'])? $viewData['searchTerm'] : '') ?>"/>
								<select name="category">
									<option value=""><?php $this->language->get('ALLCATEGORIES'); ?></option>
									<?php 
									foreach ($viewData['categories'] as $category) {
										$selected = (!empty($viewData['category']) && $viewData['category'] == $category['id_category']? 'selected="selected"' : '');

										echo "
											<option $selected value='".$category['id_category']."'>".$category['name']."</option>
										";

										if (count($category['subs_category']) > 0) {
											$this->loadView('pages/home/render/menuSubCategory', [
												'subs' => $category['subs_category'],
												'level' => 1,
												'to' => 'search',
												'category' => (!empty($viewData['category']) ? $viewData['category'] : '')
											]);
										}
									} 
									?>
								</select>
								<input type="submit" value="" class="btn-cc-red" id="search" />
						    </form>
						</div>
					</div>
					<div class="col-sm-3">
						<a href="<?php echo BASE_URL; ?>cart">
							<div class="cart-area">
								<div class="cart-icon">
									<div class="cart-quantity">9</div>
								</div>
								<div class="cart-total">
								<?php $this->language->get('CART'); ?>:<br/>
									<span>R$ 999,99</span>
								</div>
							</div>
						</a>
					</div>
				</div>
			</div>
		</header>
		
		<div class="category-area">
			<nav class="navbar">
				<div class="container">
					<ul class="navbar-nav flex-row">
						<li class="dropdown menu-category">
					        <a class="dropdown-toggle category-nav btn btn-cc-red" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
								<?php 
								echo (!empty($viewData['categoryFilter']) ? 'Categoria - '.end($viewData['categoryFilter'])['name'] : 'Todas as Categorias');
								?>
					        <span class="caret"></span></a>
					        <ul class="dropdown-menu">
							  <?php 
								echo "
									<li><a class='dropdown-item' href='".BASE_URL."category/enter/0'".">Todas as Categorias</a></li>
								";

							 	foreach ($viewData['categories'] as $category) {
									echo "
										<li><a class='dropdown-item' href='".BASE_URL."category/enter/".$category['id_category']."'>".$category['name']."</a></li>
									";

									if (count($category['subs_category']) > 0) {
										$this->loadView('pages/home/render/menuSubCategory', [
											'subs' => $category['subs_category'],
											'level' => 1,
											'to' => 'menu'
										]);
									}
								} 
							  ?>
					        </ul>
						  </li>
						<?php
							if (!empty($viewData['categoryFilter'])) {
								foreach ($viewData['categoryFilter'] as $item) {
									echo "
										<li class='category-item'><a href='".BASE_URL."category/enter/".$item['id_category']."' class='nav text-dark text-decoration-none'>".$item['name']."<span><img src=".BASE_URL.'assets/images/breadcrumb.png'." width='20'></span></a></li>
									";
								}
							} else {
								echo "
										<li class='category-item'><a href='".BASE_URL."category/enter/0"."' class='nav text-dark text-decoration-none'>Todas as Categorias <span><img src=".BASE_URL.'assets/images/breadcrumb.png'." width='20'></span></a></li>
									";
							}
						?>
					</ul>
				</div>
			</nav>
		</div>
		<section>
			<div class="container">
				<div class="row">
				  <div class="col-sm-3">
				  	<aside>
				  		<h1><?php $this->language->get('FILTERBY'); ?>:</h1>
				  		<div class="filter-area">
							<form method="GET">
								<input type="hidden" name="term" value="<?php echo (!empty($viewData['searchTerm']) ? $viewData['searchTerm'] : '') ?>">
								<input type="hidden" name="category" value="<?php echo (!empty($viewData['category']) ? $viewData['category'] : '') ?>">

								<div class="filter-box">
									<div class="filter-title"><?php $this->language->get('NEIGHBORHOODS'); ?></div>
									<div class="filter-content">
										<?php
											foreach ($viewData['filters']['neighborhoods'] as $neighborhood) {
												$checked = (!empty($viewData['filtersSelected']['neighborhood']) && in_array($neighborhood['id_neighborhood'], $viewData['filtersSelected']['neighborhood']))? 'checked="checked"' : '';

												$noneItem = (empty($neighborhood['count']) ? 'none-item' : '');

												echo "
												<div class='filter-item form-check'>
													
													<div class='float-left'>
														<label class='form-check-label'>
															<input type='checkbox' class='form-check-input' name='filters[neighborhood][]' id='box-neighborhood' value='".$neighborhood['id_neighborhood']."' $checked>".$neighborhood['name']."
														</label>
													</div>
													<div class='float-right $noneItem'>(".$neighborhood['count'].")
													</div>
												</div>
												";
											}
										?>
									</div>
								</div>
						
								<div class="filter-box">
									<div class="filter-title">
										<?php $this->language->get('RATINGS'); ?>
									</div>									

									<div class="filter-content">
										<div class="filter-item formm-check">
											<div class="float-left">
												<?php  
												$noneItem = (empty($viewData['filters']['totalRatingsByStars']) ? 'none-item' : '');
												?>
												
												<div class="rating-filter-page-home rating"></div>
												<input type="hidden" name="filters[rating]" class="rating-page-home" value="<?php echo $viewData['filters']['ratingSelected'] ?>">
											</div>
											<div class='float-right <?php echo $noneItem; ?>'>
												(<?php echo $viewData['filters']['totalRatingsByStars']; ?>)
											</div>
										</div>
									</div>
								</div>
								<div class="filter-box">
									<div class="filter-title"><?php $this->language->get('PROMOTIONS'); ?></div>
									<div class="filter-content">
										<div class="filter-item form-check">
											<div class="float-left">
												<label class="form-check-label">
													<?php  
														$checked = (!empty($viewData['filtersSelected']['promotion']) && in_array(0, $viewData['filtersSelected']['promotion']))? 'checked="checked"' : '';

														$noneItem = (empty($viewData['filters']['restaurantsInPromotion']) ? 'none-item' : '');
													?>
													<input type="checkbox" class="form-check-input" name="filters[promotion][]" value="0" id="box-promotion" <?php echo $checked; ?>>
													<?php echo $this->language->get('INPROMOTION'); ?>
												</label>
											</div>
											<div class="float-right <?php echo $noneItem; ?>">(<?php echo $viewData['filters']['restaurantsInPromotion']; ?>)</div>
										</div>
									</div>
								</div>

								<div class="filter-box">
									<div class="filter-title"><?php $this->language->get('OPERATION'); ?></div>
									<div class="filter-content">
										<?php
											foreach ($viewData['filters']['weekDays'] as $weekDay) {
												$checked = (!empty($viewData['filtersSelected']['weekDay']) && in_array($weekDay['id_week_day'], $viewData['filtersSelected']['weekDay']))? 'checked="checked"' : '';

												$noneItem = (empty($weekDay['count']) ? 'none-item' : '');

												echo "
												<div class='filter-item form-check'>
													
													<div class='float-left'>
														<label class='form-check-label'>
															<input type='checkbox' class='form-check-input' name='filters[weekDay][]' id='box-week-day' value='".$weekDay['id_week_day']."' $checked>".$weekDay['name']."
														</label>
													</div>
													<div class='float-right $noneItem'>(".$weekDay['count'].")
													</div>
												</div>
												";
											}
										?>
										
										<div class="border-filter"></div>

										<div class="filter-item form-check">
											<div class="row no-gutters">
												<div class="col-md-6 text-left">
													<label class="form-check-label">
														<?php  
															$checked = (!empty($viewData['filtersSelected']['status']) && in_array(1, $viewData['filtersSelected']['status']))? 'checked="checked"' : '';

															$noneItem = (empty($viewData['filters']['restaurantsOpen']) ? 'none-item' : '');
															
															echo "
															<input type='checkbox' class='form-check-input' name='filters[status][]' value='1' id='box-status-open' $checked>Aberto
															";
														?>
													</label>
												</div>
												<div class="col-md-6 text-right <?php echo $noneItem; ?>">(<?php echo $viewData['filters']['restaurantsOpen']; ?>)</div>
											</div>
										</div>

										<div class="filter-item form-check">
											<div class="row no-gutters">
												<div class="col-md-6 text-left">
													<label class="form-check-label">
														<?php  
															$checked = (!empty($viewData['filtersSelected']['status']) && in_array(0, $viewData['filtersSelected']['status']))? 'checked="checked"' : '';

															$noneItem = (empty($viewData['filters']['restaurantsClosed']) ? 'none-item' : '');

															echo "
															<input type='checkbox' class='form-check-input' name='filters[status][]' value='0' id='box-status-closed' $checked>Fechado
															";
														?>
													</label>
												</div>
												<div class="col-md-6 text-right <?php echo $noneItem; ?>">(<?php echo $viewData['filters']['restaurantsClosed']; ?>)</div>
											</div>
										</div>
									</div>
								</div>

								<div class="filter-box">
									<div class="filter-title"><?php $this->language->get('PAYMENTTYPES'); ?></div>
									<div class="filter-content">
										<select class="payment-type" name="filters[paymentType][]" multiple="multiple">
										<?php
											$selectedAll = empty(array_filter($viewData['filtersSelected']['paymentType'])) ? true : false;		

											foreach ($viewData['filters']['paymentTypes'] as $paymentType) {
												
												$selected = (!empty($viewData['filtersSelected']['paymentType']) && in_array($paymentType['id_payment_types'], $viewData['filtersSelected']['paymentType']))? 'selected="selected"' : '';

												echo "
												<option class='form-check-input' value='".$paymentType['id_payment_types']."' $selected>".$paymentType['name']."</option>
												";

											}

											// Option default 
											if ($selectedAll) {
												echo "
												<option value='' selected='selected'>Todos os Tipos</option>";
											}
										?>
										</select>
									</div>
								</div>

							</form>
				  		</div>

				  		<div class="widget">
				  			<h1><?php $this->language->get('FEATUREDS'); ?></h1>
				  			<div class="widget_body">
								<?php $this->loadView('widgets/widgetItem', ['listWidgets' => $viewData['sidebarWidgetsFeatured']]) ?>
				  			</div>
				  		</div>
				  	</aside> 
				  </div>
				  <!-- Restaurants -->
				  <div class="col-sm-9">
					  <?php $this->loadView($viewPatch, $viewData); ?>
				  </div>
				</div>
	    	</div>
	    </section>
	    <footer>
	    	<div class="container">
	    		<div class="row">
				  <div class="col-sm-4">
				  	<div class="widget">
			  			<h1><?php $this->language->get('ONSALE'); ?></h1>
			  			<div class="widget_body">
						  	<?php $this->loadView('widgets/widgetItem', ['listWidgets' => $viewData['footerWidgetsFeatured']]) ?>
			  			</div>
			  		</div>
				  </div>
				  <div class="col-sm-4">
				  	<div class="widget">
			  			<h1><?php $this->language->get('TOPRATEDS'); ?></h1>
			  			<div class="widget_body">
			  				<?php $this->loadView('widgets/widgetItem', ['listWidgets' => $viewData['widgetsPromotion']]) ?>
			  			</div>
			  		</div>
				  </div>
				  <div class="col-sm-4">
				  	<div class="widget">
			  			<h1><?php $this->language->get('NEW'); ?></h1>
			  			<div class="widget_body">
							<?php $this->loadView('widgets/widgetItem', ['listWidgets' => $viewData['widgetsTopRated']]) ?>
			  			</div>
			  		</div>
				  </div>
				</div>
			</div>
	    	<div class="sub-area">
	    		<div class="container">
	    			<div class="row">
						<div class="col-xs-12 col-sm-8 offset-sm-2 no-padding">
							<form id="formNewsletter">
								<input type="email" id="email" class="sub-email" placeholder="<?php $this->language->get('SUBSCRIBETEXT'); ?>">
								<input type="submit" value="<?php $this->language->get('SUBSCRIBEBUTTON'); ?>" name="subscribe" id="subscribe" class="btn-cc-red">
							</form>
						</div>
					</div>
	    		</div>
	    	</div>
	    	<div class="links">
	    		<div class="container">
	    			<div class="row">
						<div class="col-sm-4 logo-footer">
							<a href="<?php echo BASE_URL; ?>"><img title="Brumas Delivery" src="<?php echo BASE_URL; ?>assets/images/logo-red.png" /></a>
							<br/><br/>
							<strong>Impossível viver sem, a qualquer hora de qualquer lugar!</strong><br/><br/>
						</div>
						<div class="col-sm-8 link-groups">
							<div class="row">
								<div class="col-sm-4">
									<h3><?php $this->language->get('CATEGORIES'); ?></h3>
									<ul>
										<li><a href="#">Categoria X</a></li>
										<li><a href="#">Categoria X</a></li>
										<li><a href="#">Categoria X</a></li>
										<li><a href="#">Categoria X</a></li>
										<li><a href="#">Categoria X</a></li>
										<li><a href="#">Categoria X</a></li>
									</ul>
								</div>
								<div class="col-sm-4">
									<h3><?php $this->language->get('INFORMATIONS'); ?></h3>
									<ul>
										<li><a href="#">Menu 1</a></li>
										<li><a href="#">Menu 2</a></li>
										<li><a href="#">Menu 3</a></li>
										<li><a href="#">Menu 4</a></li>
										<li><a href="#">Menu 5</a></li>
										<li><a href="#">Menu 6</a></li>
									</ul>
								</div>
								<div class="col-sm-4">
									<h3><?php $this->language->get('INFORMATIONS'); ?></h3>
									<ul>
										<li><a href="#">Menu 1</a></li>
										<li><a href="#">Menu 2</a></li>
										<li><a href="#">Menu 3</a></li>
										<li><a href="#">Menu 4</a></li>
										<li><a href="#">Menu 5</a></li>
										<li><a href="#">Menu 6</a></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
	    		</div>
	    	</div>
	    	<div class="copyright">
	    		<div class="container">
	    			<div class="row">
						<div class="col-sm-6">© <span>Brumas Delivery</span> - <?php $this->language->get('ALLRIGHTRESERVED'); ?>.</div>
						<div class="col-sm-6">
							<div class="payments">
								<img src="<?php echo BASE_URL; ?>assets/images/visa.png" />
								<img src="<?php echo BASE_URL; ?>assets/images/visa.png" />
								<img src="<?php echo BASE_URL; ?>assets/images/visa.png" />
								<img src="<?php echo BASE_URL; ?>assets/images/visa.png" />
							</div>
						</div>
					</div>
	    		</div>
			</div>
			
			<!-- Modal -->
			<div class="modal fade" id="modalNewsletter" tabindex="-1" role="dialog" aria-labelledby="modalNewsletter" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h3 class="modal-title float-left">NewsLetter</h3>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
						</div>
						<div class="modal-body">
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-cc-red" data-dismiss="modal">Entendi</button>
						</div>
					</div>
				</div>
			</div>
	    </footer>
		<script type="text/javascript">
			var BASE_URL = '<?php echo BASE_URL; ?>';
		</script>
		<script type="text/javascript" src="<?php echo BASE_URL; ?>node_modules/jquery/dist/jquery.min.js"></script>
		<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/plugins/jQuery-ui/js/jquery-ui.min.js"></script>
		<script type="text/javascript" src="<?php echo BASE_URL; ?>node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
		<script type="text/javascript" src="<?php echo BASE_URL; ?>node_modules/rateyo/min/jquery.rateyo.min.js"></script>
		<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/templates/default.js"></script>
		<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/widgets/widgetItem.js"></script>
		<script type="text/javascript" src="<?php echo BASE_URL; ?>node_modules/select2/dist/js/select2.min.js"></script>
	</body>
</html>
