<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Brumas Delivery</title>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/bootstrap/config.css" type="" />
		<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/
		templates/headerFooter.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/pages/restaurant/restaurant.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/widgets/restaurantItemSmall.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo BASE_URL; ?>vendor/fortawesome/font-awesome/css/all.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo BASE_URL; ?>node_modules/rateyo/min/jquery.rateyo.min.css" type="text/css" />
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
		
		
		<section>
			<div class="container-full">
				<div class="row">
				  <!-- Plates -->
				  <?php $this->loadView($viewPatch, $viewData); ?>	  
				</div>
	    	</div>
	    </section>
	    <footer>
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
	    </footer>
		<script type="text/javascript">
			var BASE_URL = '<?php echo BASE_URL; ?>';
			var maxFilterPrice = parseInt(<?php echo (!empty($viewData['filters']['maxFilterPrice']))? $viewData['filters']['maxFilterPrice'] : 0; ?>);
		</script>
		<script type="text/javascript" src="<?php echo BASE_URL; ?>node_modules/jquery/dist/jquery.min.js"></script>
		<script type="text/javascript" src="<?php echo BASE_URL; ?>node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
		<script type="text/javascript" src="<?php echo BASE_URL; ?>node_modules/rateyo/min/jquery.rateyo.min.js"></script>
		<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/templates/headerFooter.js"></script>
		<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/pages/restaurant/restaurant.js"></script>
	</body>
</html>
