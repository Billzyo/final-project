<?php require views_path('partials/header');?>

<style>
		
		.hide{
			display: none;
		}

		@keyframes appear{

			0%{opacity: 0;transform: translateY(-100px);}
			100%{opacity: 1;transform: translateY(0px);}
		}

	</style>
	<div class="d-flex">
		<div style="min-height:600px;" class="shadow-sm col-8 p-4">
			
			<div class="input-group mb-3"><h3> Items </h3>
			  <input onkeyup="check_for_enter_key(event)" oninput="search_item(event)" type="text" class="ms-4 form-control js-search" placeholder="Search" aria-label="Search" aria-describedby="basic-addon1" autofocus>
			  <span class="input-group-text" id="basic-addon1"><i class="fa fa-search"></i></span>
			</div>

			<div onclick="add_item(event)" class="js-products d-flex" style="flex-wrap: wrap;height: 90%;overflow-y: scroll;">
				
				
			</div>
		</div>
		
		<div class="col-4 bg-light p-4 pt-2">
			
	<div><center><h3>Cart <div class="js-item-count badge bg-primary rounded-circle">0</div></h3></center></div>
	
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
