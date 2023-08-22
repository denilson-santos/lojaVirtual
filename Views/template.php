<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Brumas Tec</title>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/jquery-ui.min.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/jquery-ui.structure.min.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/jquery-ui.theme.min.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/bootstrap.min.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo BASE_URL; ?>vendor/fortawesome/font-awesome/css/all.css" type="text/css" />
	</head>
	<body>
		<nav class="navbar topnav">
			<div class="container">
				<ul class="nav navbar-nav">
					<li class="active"><a href="<?php echo BASE_URL; ?>"><?php $this->language->get('HOME'); ?></a></li>
					<li><a href="<?php echo BASE_URL; ?>contact"><?php $this->language->get('CONTACT'); ?></a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php $this->language->get('LANGUAGE'); ?>
						<span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="<?php echo BASE_URL.'lang/set/en'; ?>">English</a></li>
							<li><a href="<?php echo BASE_URL.'lang/set/pt-br'; ?>">Português</a></li>
						</ul>
					</li>
					<li><a href="<?php echo BASE_URL; ?>login"><?php $this->language->get('LOGIN'); ?></a></li>
				</ul>
			</div>
		</nav>
		<header>
			<div class="container">
				<div class="row">
					<div class="col-sm-3 logo">
						<a href="<?php echo BASE_URL; ?>"><img title="Brumas Tec" src="<?php echo BASE_URL; ?>assets/images/logo.png" /></a>
					</div>
					<div class="col-sm-6 block-header-2">
						<div class="head_help">(11) 9999-9999</div>
						<div class="head_email">contato@<span>brumastec.com.br</span></div>
						
						<div class="search_area">
							<form method="GET">
								<input type="text" name="term" required placeholder="<?php $this->language->get('SEARCHFORANITEM'); ?>" value="<?php echo (!empty($viewData['searchTerm'])? $viewData['searchTerm'] : '') ?>"/>
								<select name="category">
									<option value=""><?php $this->language->get('ALLCATEGORIES'); ?></option>
									<?php 
									foreach ($viewData['categories'] as $category) {
										$selected = (!empty($viewData['category']) && $viewData['category'] == $category['id']? 'selected="selected"' : '');

										echo "
											<option $selected value='".$category['id']."'>".$category['name']."</option>
										";

										if (count($category['subs_category']) > 0) {
											$this->loadView('menuSubCategory', [
												'subs' => $category['subs_category'],
												'level' => 1,
												'to' => 'search',
												'category' => (!empty($viewData['category']) ? $viewData['category'] : '')
											]);
										}
									} 
									?>
								</select>
								<input type="submit" value="" class="btn-green" id="search" />
						    </form>
						</div>
					</div>
					<div class="col-sm-3">
						<a href="<?php echo BASE_URL; ?>cart">
							<div class="cartarea">
								<div class="carticon">
									<div class="cartqt">9</div>
								</div>
								<div class="carttotal">
								<?php $this->language->get('CART'); ?>:<br/>
									<span>R$ 999,99</span>
								</div>
							</div>
						</a>
					</div>
				</div>
			</div>
		</header>
		<!-- <div class="categoryarea">
			<nav class="navbar">
				<div class="container">
					<ul class="nav navbar-nav">
						<li class="dropdown">
					        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php $this->language->get('SELECTCATEGORY'); ?>
					        <span class="caret"></span></a>
					        <ul class="dropdown-menu">
							  <?php 
							 	foreach ($viewData['categories'] as $category) {
									echo "
										<li><a href='".BASE_URL."category/enter/".$category['id']."'>".$category['name']."</a></li>
									";

									if (count($category['subs_category']) > 0) {
										$this->loadView('menuSubCategory', [
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
										<li><a href='".BASE_URL."category/enter/".$item['id']."'>".$item['name']."</a></li>
									";
								}
							}
						?>
					</ul>
				</div>
			</nav>
		</div> -->
		<br>
		<br>
		<section>
			<div class="container">
				<div class="row">
					<div class="col-sm-9">
						<?php $this->loadViewInTemplate($viewName, $viewData); ?>
				  </div>
					<div class="col-sm-3">
						<aside>
							<h1><?php $this->language->get('FILTERBY'); ?>:</h1>
							<div class="filterArea">
							<form method="GET">
								<input type="hidden" name="term" value="<?php echo (!empty($viewData['searchTerm']) ? $viewData['searchTerm'] : '') ?>">
								<input type="hidden" name="category" value="<?php echo (!empty($viewData['category']) ? $viewData['category'] : '') ?>">

								<div class="filter-box">
									<div class="filter-title"><?php $this->language->get('BRANDS'); ?></div>
									<div class="filter-content">
										<?php
											foreach ($viewData['filters']['brands'] as $brand) {
												$checked = (!empty($viewData['filtersSelected']['brand']) && in_array($brand['id'], $viewData['filtersSelected']['brand']))? 'checked="checked"' : '';

												$noneItem = (empty($brand['count']) ? 'none-item' : '');

												echo "
												<div class='filter-item form-check'>
													
													<div class='pull-left'>
														<label class='form-check-label'>
															<input type='checkbox' class='form-check-input' name='filters[brand][]' id='box-brand' value='".$brand['id']."' $checked>".$brand['name']."
														</label>
													</div>
													<div class='pull-right $noneItem'>(".$brand['count'].")
													</div>
												</div>
												";
											}
										?>
									</div>
								</div>
								<div class="filter-box">
									<div class="filter-title">
										<?php $this->language->get('PRICE'); ?>
									</div>
									<div class="filter-content" id="filter-price">
										<input type="hidden" id="range-price0" name="filters[rangePrice0]" value="<?php echo $viewData['filters']['rangePrice0'] ?>">
										<input type="hidden" id="range-price1" name="filters[rangePrice1]" value="<?php echo $viewData['filters']['rangePrice1'] ?>">
										<input type="text" id="amount" readonly>
										<div id="slider-range"></div>
									</div>
								</div>
								<div class="filter-box">
									<div class="filter-title">
										<?php $this->language->get('RATING'); ?>
									</div>
									<div class="filter-content">
										<div class="filter-item formm-check">
											<div class="pull-left">
												<label for="box-rating-0">
													<?php  
														$checked = (!empty($viewData['filtersSelected']['rating']) && in_array(0, $viewData['filtersSelected']['rating']))? 'checked="checked"' : '';

														$noneItem = (empty($viewData['filters']['ratingsByStars'][0]) ? 'none-item' : '');
													?>
													<input type="checkbox" name="filters[rating][]" class="form-check-input" value="0" id="box-rating-0" <?php echo $checked; ?>>
													<?php echo $this->language->get('NORATING') ?>
												</label>
											</div>
											<div class='pull-right <?php echo $noneItem; ?>'>
												(<?php echo $viewData['filters']['ratingsByStars'][0]; ?>)
											</div>
										</div>
										<?php 
										for($q=1; $q<=5; $q++) { 
											$checked = (!empty($viewData['filtersSelected']['rating']) && in_array($q, $viewData['filtersSelected']['rating']))? 'checked="checked"' : '';

											$noneItem = (empty($viewData['filters']['ratingsByStars'][$q]) ? 'none-item' : '');
											
											echo "
											<div class='filter-item formm-check'>
												<div class='pull-left'>
													<label for='box-rating-$q'>
													<input type='checkbox' name='filters[rating][]' class='form-check-input' value='$q' id='box-rating-$q' $checked>";
											
											for($i=1; $i <= $q; $i++) {
												echo "
												<img src='".BASE_URL."assets/images/star.png' width='20'>";
											}
												
											echo "</label>
												</div>
												<div class='pull-right total-star $noneItem'>(".$viewData['filters']['ratingsByStars'][$q].")</div>
											</div>";
										}
										?>	
									</div>
								</div>
								<div class="filter-box">
									<div class="filter-title"><?php $this->language->get('PROMOTION'); ?></div>
									<div class="filter-content">
										<div class="filter-item form-check">
											<div class="pull-left">
												<label class="form-check-label">
													<?php  
														$checked = (!empty($viewData['filtersSelected']['promotion']) && in_array(0, $viewData['filtersSelected']['promotion']))? 'checked="checked"' : '';

														$noneItem = (empty($viewData['filters']['totalProductsInPromotion']) ? 'none-item' : '');
													?>
													<input type="checkbox" class="form-check-input" name="filters[promotion][]" value="0" id="box-promotion" <?php echo $checked; ?>>
													<?php echo $this->language->get('INPROMOTION'); ?>
												</label>
											</div>
											<div class="pull-right <?php echo $noneItem; ?>">(<?php echo $viewData['filters']['totalProductsInPromotion']; ?>)</div>
										</div>
									</div>
								</div>
								<div class="filter-box">
									<div class="filter-title"><?php $this->language->get('OPTIONS'); ?></div>
									<div class="filter-content">
										<?php foreach ($viewData['filters']['options'] as $option) {		
											echo "
												<div class='option-filter'>
													<div class='option-name'>
														<strong>".$option['name']."</strong>
													</div>";

													foreach ($option['values'] as $value) {
														$checked = (!empty($viewData['filtersSelected']['option']) && in_array(strtolower($value['name']), $viewData['filtersSelected']['option']))? 'checked="checked"' : '';

														echo "
														<div class='filter-item form-check'>
															<div class='pull-left'>
																<label class='form-check-label'>
																	<input type='checkbox' class='form-check-input' name='filters[option][]' id='filter-options-".strtolower($value['name'])."' value='".strtolower($value['name'])."' $checked>
																	".$value['name']."
																</label>
															</div>
															<div class='pull-right'>
																(".$value['amount_by_value'].")
															</div>
														</div>
														";
													}
												echo 
												"</div>";									
										}
										?>
									</div>
								</div>

							</form>
							</div>

							<div class="widget">
								<h1><?php $this->language->get('FEATUREDPRODUCTS'); ?></h1>
								<div class="widget_body">
								<?php $this->loadView('widgetItem', ['listWidgets' => $viewData['sidebarWidgetsFeatured']]) ?>
								</div>
							</div>
						</aside>
					</div>
				</div>
	    	</div>
	    </section>
	    <footer>
	    	<div class="container">
	    		<div class="row">
				  <div class="col-sm-4">
				  	<div class="widget">
			  			<h1><?php $this->language->get('FEATUREDPRODUCTS'); ?></h1>
			  			<div class="widget_body">
						  	<?php $this->loadView('widgetItem', ['listWidgets' => $viewData['footerWidgetsFeatured']]) ?>
			  			</div>
			  		</div>
				  </div>
				  <div class="col-sm-4">
				  	<div class="widget">
			  			<h1><?php $this->language->get('ONSALEPRODUCTS'); ?></h1>
			  			<div class="widget_body">
			  				<?php $this->loadView('widgetItem', ['listWidgets' => $viewData['widgetsPromotion']]) ?>
			  			</div>
			  		</div>
				  </div>
				  <div class="col-sm-4">
				  	<div class="widget">
			  			<h1><?php $this->language->get('TOPRATEDPRODUCTS'); ?></h1>
			  			<div class="widget_body">
							<?php $this->loadView('widgetItem', ['listWidgets' => $viewData['widgetsTopRated']]) ?>
			  			</div>
			  		</div>
				  </div>
				</div>
			</div>
	    	<div class="sub-area">
	    		<div class="container">
	    			<div class="row">
						<div class="col-xs-12 col-sm-8 col-sm-offset-2 no-padding">
							<form id="formNewsletter">
								<input type="email" id="email" class="sub-email" placeholder="<?php $this->language->get('SUBSCRIBETEXT'); ?>">
								<input type="submit" value="<?php $this->language->get('SUBSCRIBEBUTTON'); ?>" name="subscribe" id="subscribe" class="btn-green">
							</form>
						</div>
					</div>
	    		</div>
	    	</div>
	    	<div class="links">
	    		<div class="container">
	    			<div class="row">
						<div class="col-sm-4 logo-footer">
							<a href="<?php echo BASE_URL; ?>"><img src="<?php echo BASE_URL; ?>assets/images/logo.png" /></a><br/><br/>
							<strong>Slogan da Loja Virtual</strong><br/><br/>
							Endereço da Loja Virtual
						</div>
						<div class="col-sm-8 linkgroups">
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
						<div class="col-sm-6">© <span>Brumas Tec</span> - <?php $this->language->get('ALLRIGHTRESERVED'); ?>.</div>
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
							<h3 class="modal-title pull-left">NewsLetter</h3>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
						</div>
						<div class="modal-body">
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-green" data-dismiss="modal">Entendi</button>
						</div>
					</div>
				</div>
			</div>
	    </footer>
		<script type="text/javascript">
			var BASE_URL = '<?php echo BASE_URL; ?>';
			var maxFilterPrice = <?php echo (!empty($viewData['filters']['maxFilterPrice']))? $viewData['filters']['maxFilterPrice'] : 0; ?>;
		</script>
		<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/jquery-3.4.1.min.js"></script>
		<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/jquery-ui.min.js"></script>
		<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/script.js"></script>
	</body>
</html>