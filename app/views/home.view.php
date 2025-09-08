<?php require views_path('partials/header');?>

<style>
		.hide{
			display: none;
		}

		@keyframes appear{
			0%{opacity: 0;transform: translateY(-100px);}
			100%{opacity: 1;transform: translateY(0px);}
		}

		@keyframes slideInUp {
			0% {
				opacity: 0;
				transform: translateY(30px);
			}
			100% {
				opacity: 1;
				transform: translateY(0);
			}
		}

		@keyframes pulse {
			0% { transform: scale(1); }
			50% { transform: scale(1.05); }
			100% { transform: scale(1); }
		}

		@keyframes bounce {
			0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
			40% { transform: translateY(-10px); }
			60% { transform: translateY(-5px); }
		}

		/* Modern Product Grid */
		.products-grid {
			display: grid;
			grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
			gap: 25px;
			padding: 25px 0;
			height: 90%;
			overflow-y: auto;
			scrollbar-width: thin;
			scrollbar-color: #007bff #f1f1f1;
			background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
			border-radius: 15px;
			margin: 10px;
		}

		.products-grid::-webkit-scrollbar {
			width: 8px;
		}

		.products-grid::-webkit-scrollbar-track {
			background: #f1f1f1;
			border-radius: 10px;
		}

		.products-grid::-webkit-scrollbar-thumb {
			background: #007bff;
			border-radius: 10px;
		}

		.products-grid::-webkit-scrollbar-thumb:hover {
			background: #0056b3;
		}

		/* Product Card Styling */
		.product-card {
			background: linear-gradient(145deg, #ffffff, #f8f9fa);
			border-radius: 20px;
			box-shadow: 0 8px 25px rgba(0,0,0,0.1);
			transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
			overflow: hidden;
			position: relative;
			animation: slideInUp 0.6s ease-out;
			animation-fill-mode: both;
			cursor: pointer;
		}

		.product-card:hover {
			transform: translateY(-10px) scale(1.02);
			box-shadow: 0 20px 40px rgba(0,0,0,0.15);
		}

		.product-card::before {
			content: '';
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			height: 4px;
			background: linear-gradient(90deg, #007bff, #28a745, #ffc107, #dc3545);
			opacity: 0;
			transition: opacity 0.3s ease;
		}

		.product-card:hover::before {
			opacity: 1;
		}

		/* Enhanced Product Image Styling */
		.product-image-container {
			position: relative;
			overflow: hidden;
			border-radius: 15px 15px 0 0;
			background: linear-gradient(135deg, #f8f9fa, #e9ecef);
		}

		.product-image {
			width: 100%;
			height: 200px;
			object-fit: cover;
			transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
			border-radius: 15px 15px 0 0;
			filter: brightness(1) contrast(1.1) saturate(1.1);
		}

		.product-card:hover .product-image {
			transform: scale(1.08) rotate(1deg);
			filter: brightness(1.1) contrast(1.2) saturate(1.3);
		}

		/* Image Overlay Effect */
		.product-image-container::after {
			content: '';
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background: linear-gradient(45deg, rgba(0,123,255,0.1), rgba(40,167,69,0.1));
			opacity: 0;
			transition: opacity 0.3s ease;
			border-radius: 15px 15px 0 0;
		}

		.product-card:hover .product-image-container::after {
			opacity: 1;
		}

		/* Add to Cart Icon Overlay */
		.product-image-container::before {
			content: '\f217';
			font-family: 'Font Awesome 5 Free';
			font-weight: 900;
			position: absolute;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%) scale(0);
			color: white;
			font-size: 24px;
			background: rgba(0,123,255,0.9);
			width: 50px;
			height: 50px;
			border-radius: 50%;
			display: flex;
			align-items: center;
			justify-content: center;
			transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
			z-index: 2;
		}

		.product-card:hover .product-image-container::before {
			transform: translate(-50%, -50%) scale(1);
		}

		.product-info {
			padding: 20px;
			text-align: center;
			background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
			border-top: 1px solid #e9ecef;
		}

		.product-title {
			font-size: 16px;
			font-weight: 600;
			color: #2c3e50;
			margin-bottom: 12px;
			line-height: 1.4;
			text-transform: uppercase;
			letter-spacing: 0.5px;
		}

		.product-price {
			font-size: 24px;
			font-weight: 700;
			color: #28a745;
			text-shadow: 0 2px 4px rgba(0,0,0,0.1);
			background: linear-gradient(45deg, #28a745, #20c997);
			-webkit-background-clip: text;
			-webkit-text-fill-color: transparent;
			background-clip: text;
		}

		/* Product Card Hover Effects */
		.product-card:hover .product-info {
			background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
		}

		.product-card:hover .product-title {
			color: #007bff;
		}

		/* Search Bar Enhancement */
		.search-container {
			position: relative;
			margin-bottom: 30px;
		}

		.search-input {
			border-radius: 25px;
			border: 2px solid #e9ecef;
			padding: 15px 50px 15px 20px;
			font-size: 16px;
			transition: all 0.3s ease;
			background: linear-gradient(145deg, #ffffff, #f8f9fa);
		}

		.search-input:focus {
			border-color: #007bff;
			box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
			transform: translateY(-2px);
		}

		.search-icon {
			position: absolute;
			right: 15px;
			top: 50%;
			transform: translateY(-50%);
			color: #6c757d;
			font-size: 18px;
		}

		/* Category Filter Buttons */
		.category-filters {
			display: flex;
			gap: 10px;
			justify-content: center;
			flex-wrap: wrap;
		}

		.category-btn {
			background: linear-gradient(145deg, #ffffff, #f8f9fa);
			border: 2px solid #e9ecef;
			border-radius: 25px;
			padding: 12px 20px;
			font-weight: 600;
			font-size: 14px;
			color: #6c757d;
			transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
			cursor: pointer;
			position: relative;
			overflow: hidden;
			text-transform: uppercase;
			letter-spacing: 0.5px;
		}

		.category-btn::before {
			content: '';
			position: absolute;
			top: 0;
			left: -100%;
			width: 100%;
			height: 100%;
			background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
			transition: left 0.5s;
		}

		.category-btn:hover::before {
			left: 100%;
		}

		.category-btn:hover {
			transform: translateY(-2px);
			box-shadow: 0 8px 20px rgba(0,0,0,0.15);
			border-color: #007bff;
			color: #007bff;
		}

		.category-btn.active {
			background: linear-gradient(145deg, #007bff, #0056b3);
			border-color: #007bff;
			color: white;
			box-shadow: 0 6px 15px rgba(0,123,255,0.3);
		}

		.category-btn.active:hover {
			background: linear-gradient(145deg, #0056b3, #004085);
			transform: translateY(-2px);
			box-shadow: 0 8px 20px rgba(0,123,255,0.4);
		}

		.category-btn i {
			margin-right: 8px;
			font-size: 16px;
		}

		/* Category Button Animations */
		.category-btn {
			animation: slideInUp 0.6s ease-out;
			animation-fill-mode: both;
		}

		.category-btn:nth-child(1) { animation-delay: 0.1s; }
		.category-btn:nth-child(2) { animation-delay: 0.2s; }
		.category-btn:nth-child(3) { animation-delay: 0.3s; }

		/* Cart Enhancements */
		.cart-container {
			background: linear-gradient(145deg, #f8f9fa, #e9ecef);
			border-radius: 20px;
			padding: 25px;
			box-shadow: 0 10px 30px rgba(0,0,0,0.1);
		}

		.cart-header {
			text-align: center;
			margin-bottom: 25px;
		}

		.cart-count {
			background: linear-gradient(45deg, #007bff, #0056b3);
			color: white;
			border-radius: 50%;
			width: 40px;
			height: 40px;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			font-weight: bold;
			font-size: 18px;
			animation: pulse 2s infinite;
		}

		/* Loading Animation */
		.loading-spinner {
			display: flex;
			justify-content: center;
			align-items: center;
			height: 200px;
		}

		.spinner {
			width: 50px;
			height: 50px;
			border: 5px solid #f3f3f3;
			border-top: 5px solid #007bff;
			border-radius: 50%;
			animation: spin 1s linear infinite;
		}

		@keyframes spin {
			0% { transform: rotate(0deg); }
			100% { transform: rotate(360deg); }
		}

		/* Empty State */
		.empty-state {
			text-align: center;
			padding: 60px 20px;
			color: #6c757d;
		}

		.empty-state i {
			font-size: 64px;
			margin-bottom: 20px;
			opacity: 0.5;
		}

		/* Image Loading State */
		.product-image {
			background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
			background-size: 200% 100%;
			animation: loading 1.5s infinite;
		}

		.product-image.loaded {
			animation: none;
			background: none;
		}

		@keyframes loading {
			0% { background-position: 200% 0; }
			100% { background-position: -200% 0; }
		}

		/* Image Error State */
		.product-image.error {
			background: linear-gradient(135deg, #f8f9fa, #e9ecef);
			display: flex;
			align-items: center;
			justify-content: center;
			font-size: 48px;
			color: #6c757d;
		}

		.product-image.error::after {
			content: '\f03e';
			font-family: 'Font Awesome 5 Free';
			font-weight: 900;
		}


		/* Responsive Design */
		@media (max-width: 1200px) {
			.products-grid {
				grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
				gap: 20px;
			}
		}

		@media (max-width: 768px) {
			.products-grid {
				grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
				gap: 15px;
				padding: 15px 0;
			}
			
			.product-card {
				border-radius: 15px;
			}
			
			.product-image {
				height: 160px;
			}

			.product-image-container::before {
				width: 40px;
				height: 40px;
				font-size: 18px;
			}
		}

		@media (max-width: 480px) {
			.products-grid {
				grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
				gap: 12px;
			}
			
			.product-image {
				height: 140px;
			}

			.product-info {
				padding: 15px;
			}

			.product-title {
				font-size: 14px;
			}

			.product-price {
				font-size: 20px;
			}

			.category-filters {
				gap: 8px;
			}

			.category-btn {
				padding: 10px 16px;
				font-size: 12px;
			}

			.category-btn i {
				font-size: 14px;
				margin-right: 6px;
			}
		}

		/* Staggered Animation for Product Cards */
		.product-card:nth-child(1) { animation-delay: 0.1s; }
		.product-card:nth-child(2) { animation-delay: 0.2s; }
		.product-card:nth-child(3) { animation-delay: 0.3s; }
		.product-card:nth-child(4) { animation-delay: 0.4s; }
		.product-card:nth-child(5) { animation-delay: 0.5s; }
		.product-card:nth-child(6) { animation-delay: 0.6s; }
		.product-card:nth-child(7) { animation-delay: 0.7s; }
		.product-card:nth-child(8) { animation-delay: 0.8s; }
		.product-card:nth-child(9) { animation-delay: 0.9s; }
		.product-card:nth-child(10) { animation-delay: 1.0s; }
	</style>
	<div class="d-flex">
		<div style="min-height:600px;" class="shadow-sm col-8 p-4">
			
			<div class="search-container">
				<h3 class="mb-3"><i class="fa fa-shopping-bag text-primary"></i> Products</h3>
				<div class="position-relative">
					<input onkeyup="check_for_enter_key(event)" oninput="search_item(event)" type="text" class="form-control search-input js-search" placeholder="Search products or scan barcode..." autofocus>
					<i class="fa fa-search search-icon"></i>
				</div>
				
				<!-- Category Filter Buttons -->
				<div class="category-filters mt-3">
					<button onclick="filterByCategory('all')" class="category-btn active" data-category="all">
						<i class="fa fa-th-large"></i> ALL
					</button>
					<button onclick="filterByCategory('meals')" class="category-btn" data-category="meals">
						<i class="fa fa-utensils"></i> MEALS
					</button>
					<button onclick="filterByCategory('drinks')" class="category-btn" data-category="drinks">
						<i class="fa fa-coffee"></i> DRINKS
					</button>
				</div>
			</div>

			<div onclick="add_item(event)" class="js-products products-grid">
				<div class="loading-spinner">
					<div class="spinner"></div>
				</div>
			</div>
		</div>
		
		<div class="col-4 cart-container">
			
			<div class="cart-header">
				<h3><i class="fa fa-shopping-cart text-primary"></i> Cart</h3>
				<div class="js-item-count cart-count">0</div>
			</div>
	
	<div class="table-responsive" style="height:400px;overflow-y: scroll;">
		<table class="table table-striped table-hover">
			<tr>
				<th>Image</th><th>Description</th><th>Amount</th>
			</tr>
			
			<tbody class="js-items">
				 
			</tbody>
		</table>
	</div>

	<!-- Phone Number Input Field -->
	<div class="mb-3">
		<label for="phoneNumber" class="form-label">Phone Number:</label>
		<input type="text" class="form-control" id="phone-input" name="phone" placeholder="Enter your phone number">
	</div>

	<div class="js-gtotal alert alert-danger" style="font-size:30px">Total: K0.00</div>
	<div class="">
		<button onclick="show_modal('amount-paid')" class="btn btn-success my-2 w-100 py-4">Checkout</button>
		<button onclick="clear_all()" class="btn btn-primary my-2 w-100">Clear All</button>
	</div>
</div>

	</div>	

<!--modals-->

	<!--enter amount modal-->
	<div role="close-button" onclick="hide_modal(event,'amount-paid')" class="js-amount-paid-modal hide" style="animation: appear .5s ease;background-color: #000000bb; width: 100%;height: 100%;position: fixed;left:0px;top:0px;z-index: 4;">

		<div style="width:500px;min-height:200px;background-color:white;padding:10px;margin:auto;margin-top:100px">
			<h4>Checkout <button role="close-button" onclick="hide_modal(event,'amount-paid')" class="btn btn-danger float-end p-0 px-2">X</button></h4>
			<br>
			<input onkeyup="if(event.keyCode == 13)validate_amount_paid(event)" type="text" class="js-amount-paid-input form-control" placeholder="Enter amount paid">
			<br>
			<button role="close-button" onclick="hide_modal(event,'amount-paid')" class="btn btn-secondary">Cancel</button>
			<button onclick="validate_amount_paid(event)" class="btn btn-primary float-end">Next</button>
		</div>
	</div>
	<!--end enter amount modal-->

	<!--change modal-->
	<div role="close-button" onclick="hide_modal(event,'change')" class="js-change-modal hide" style="animation: appear .5s ease;background-color: #000000bb; width: 100%;height: 100%;position: fixed;left:0px;top:0px;z-index: 4;">

		<div style="width:500px;min-height:200px;background-color:white;padding:10px;margin:auto;margin-top:100px">
			<h4>Change: <button role="close-button" onclick="hide_modal(event,'change')" class="btn btn-danger float-end p-0 px-2">X</button></h4>
			<br>
			<div class="js-change-input form-control text-center" style="font-size:60px">0.00</div>
			<br>
			<center><button role="close-button" onclick="hide_modal(event,'change')" class="js-btn-close-change btn btn-lg btn-secondary">Continue</button></center>
		</div>
	</div>
	<!--end change modal-->

	
<!--end modals-->

<script src="assets/js/pos.js"></script>

<?php require views_path('partials/footer');?>
