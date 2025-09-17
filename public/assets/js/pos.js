console.log('POS.js loaded successfully');

var PRODUCTS 	= [];
var PHONE   	= [];
var ITEMS 		= [];
var BARCODE 	= false;
var GTOTAL  	= 0;
var CHANGE  	= 0;
var RECEIPT_WINDOW = null;
var CURRENT_CATEGORY = 'all';

var main_input = document.querySelector(".js-search");

// Load initial products when page loads
function loadInitialProducts() {
    var data = {};
    data.data_type = "search";
    data.text = "";
    data.category_id = CURRENT_CATEGORY;
    send_data(data);
}

// Try both events to ensure products load
document.addEventListener('DOMContentLoaded', loadInitialProducts);
window.addEventListener('load', loadInitialProducts);

// Also try immediately if DOM is already ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', loadInitialProducts);
} else {
    loadInitialProducts();
}

// Function to attach category button event listeners
function attachCategoryListeners() {
    var categoryButtons = document.querySelectorAll('.category-btn');
    console.log('Found category buttons:', categoryButtons.length);
    
    categoryButtons.forEach(button => {
        button.addEventListener('click', function() {
            var category = this.getAttribute('data-category');
            console.log('Category button clicked:', category);
            filterByCategory(category);
        });
    });
}

// Try multiple approaches to attach event listeners
document.addEventListener('DOMContentLoaded', attachCategoryListeners);
window.addEventListener('load', attachCategoryListeners);

// Also try immediately if DOM is already ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', attachCategoryListeners);
} else {
    attachCategoryListeners();
}

// Add other event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Add click event listener to products container
    var productsContainer = document.querySelector('.js-products');
    if (productsContainer) {
        productsContainer.addEventListener('click', add_item);
    }
    
    // Add event listeners for checkout and clear buttons
    var checkoutBtn = document.getElementById('checkout-btn');
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', function() {
            show_modal('amount-paid');
        });
    }
    
    var clearAllBtn = document.getElementById('clear-all-btn');
    if (clearAllBtn) {
        clearAllBtn.addEventListener('click', clear_all);
    }
    
    // Add event listeners for modal buttons
    // Amount paid modal
    var amountPaidModal = document.querySelector('.js-amount-paid-modal');
    if (amountPaidModal) {
        amountPaidModal.addEventListener('click', function(e) {
            if (e.target.getAttribute('role') === 'close-button') {
                hide_modal(e, 'amount-paid');
            }
        });
    }
    
    // Validate amount button
    var validateAmountBtn = document.getElementById('validate-amount-btn');
    if (validateAmountBtn) {
        validateAmountBtn.addEventListener('click', validate_amount_paid);
    }
    
    // Amount paid input enter key
    var amountPaidInput = document.querySelector('.js-amount-paid-input');
    if (amountPaidInput) {
        amountPaidInput.addEventListener('keyup', function(e) {
            if (e.keyCode === 13) {
                validate_amount_paid(e);
            }
        });
    }
    
    // Change modal
    var changeModal = document.querySelector('.js-change-modal');
    if (changeModal) {
        changeModal.addEventListener('click', function(e) {
            if (e.target.getAttribute('role') === 'close-button') {
                hide_modal(e, 'change');
                
                // Perform checkout if checkout data exists
                if (window.checkoutData) {
                    performCheckout(window.checkoutData);
                    window.checkoutData = null; // Clear the data
                }
                
                // Clear items after change modal is closed
                ITEMS = [];
                refresh_items_display();
                
                // Reload products
                send_data({
                    data_type:"search",
                    text:""
                });
            }
        });
    }
    
    // Search input events
    var searchInput = document.querySelector('.js-search');
    if (searchInput) {
        searchInput.addEventListener('input', search_item);
        searchInput.addEventListener('keyup', check_for_enter_key);
    }
});

function search_item(e){
    var text = e.target.value.trim();
    
    // Show loading state
    var mydiv = document.querySelector(".js-products");
    if (text.length > 0) {
        mydiv.innerHTML = `
            <div class="loading-spinner">
                <div class="spinner"></div>
            </div>
        `;
    }
    
    var data = {};
    data.data_type = "search";
    data.text = text;
    data.category_id = CURRENT_CATEGORY;
    send_data(data);
}

// Add event listener to product container to handle clicks on product images
var productsContainer = document.querySelector(".js-products");
if (productsContainer) {
    productsContainer.addEventListener("click", add_item);
}

function send_data(data)
{

	var ajax = new XMLHttpRequest();

	ajax.addEventListener('readystatechange',function(e){

		if(ajax.readyState == 4){

			
			if(ajax.status == 200)
			{
				if(ajax.responseText.trim() != "") {
					handle_result(ajax.responseText);
				} else {
					if(BARCODE){
						alert("that item was not found");
					}
				}
			} else {
				console.log("An error occured. Err Code:"+ajax.status+" Err message:"+ajax.statusText);
				console.log(ajax);
				
				// Show user-friendly error message
				if (ajax.status === 0) {
					alert("Network error: Unable to connect to server. Please check your connection.");
				} else if (ajax.status === 500) {
					alert("Server error: Please try again or contact support.");
				} else {
					alert("Error " + ajax.status + ": " + ajax.statusText);
				}
			}

			//clear main input if enter was pressed
			if(BARCODE){
				main_input.value = "";
				main_input.focus();
			}

			BARCODE = false;

		}
		
	});

	ajax.open('post','index.php?pg=ajax',true);
	ajax.setRequestHeader('Content-Type', 'application/json');
	ajax.send(JSON.stringify(data));
}

function handle_result(result){
	
	console.log('AJAX Response received:', result);
	
	try {
		var obj = JSON.parse(result);
		if(typeof obj != "undefined"){

		//valid json
		if(obj.data_type == "search")
		{
			PRODUCTS = obj.data || [];
			console.log('Products received:', PRODUCTS.length, 'Current category:', CURRENT_CATEGORY);
			console.log('Product details:', PRODUCTS);
			
			// Display products directly (server already filtered by category)
			displayProducts(PRODUCTS);

				if(BARCODE && PRODUCTS.length == 1){
				setTimeout(() => {
					add_item_from_index(0);
				}, PRODUCTS.length * 100);
			}
		}
		else if(obj.data_type == "checkout")
		{
			console.log('Checkout response received:', obj);
			
			// Handle successful checkout
			if(obj.data && obj.data.includes("successfully")) {
				console.log('Checkout successful!');
				
				// Clear the cart
				ITEMS = [];
				GTOTAL = 0;
				CHANGE = 0;
				refresh_items_display();
				
				// Hide any open modals
				hide_modal(true, 'change');
				
				// Show success message
				alert("Transaction completed successfully!");
			} else {
				console.log('Checkout failed or unknown response');
			}
		}
		
	}
	} catch (error) {
		console.error('Error parsing JSON response:', error);
		console.error('Raw response:', result);
		alert('Error processing server response. Please try again.');
	}

}

function product_html(data,index)
{
	return `
		<div class="product-card" data-index="${index}">
			<div class="product-image-container">
				<img index="${index}" src="${data.image}" class="product-image" alt="${data.description}">
			</div>
			<div class="product-info">
				<div class="product-title">${data.description}</div>
				<div class="product-price">K${data.amount}</div>
			</div>
		</div>
	`;
}

// Handle image loading
function handleImageLoad(img) {
	img.classList.add('loaded');
}

// Handle image errors
function handleImageError(img) {
	img.classList.add('error');
	img.src = 'assets/images/no_image.jpg';
}

// Category filtering function
function filterByCategory(category) {
	console.log('Filtering by category:', category);
	CURRENT_CATEGORY = category;
	
	// Update active button
	document.querySelectorAll('.category-btn').forEach(btn => {
		btn.classList.remove('active');
	});
	document.querySelector(`[data-category="${category}"]`).classList.add('active');
	
	// Show loading state
	var mydiv = document.querySelector(".js-products");
	mydiv.innerHTML = `
		<div class="loading-spinner">
			<div class="spinner"></div>
		</div>
	`;
	
	// Send request to get products for this category
	var data = {};
	data.data_type = "search";
	data.text = "";
	data.category_id = category;
	console.log('Sending AJAX data:', data);
	send_data(data);
}

// Function to display products with animation
function displayProducts(products) {
	var mydiv = document.querySelector(".js-products");
	
	// Clear the container completely
	mydiv.innerHTML = "";
	
	if (products.length > 0) {
		// Build all HTML at once to avoid duplicates
		var allHTML = "";
		for (var i = 0; i < products.length; i++) {
			allHTML += product_html(products[i], i);
		}
		
		// Set all HTML at once
		mydiv.innerHTML = allHTML;
		
		// Add image load/error event listeners after HTML is set
		var images = mydiv.querySelectorAll('.product-image');
		images.forEach(function(img) {
			img.addEventListener('load', function() {
				handleImageLoad(this);
			});
			img.addEventListener('error', function() {
				handleImageError(this);
			});
		});
	} else {
		// Show empty state
		mydiv.innerHTML = `
			<div class="empty-state">
				<i class="fa fa-search"></i>
				<h5>No products found</h5>
				<p>No products available in this category</p>
			</div>
		`;
	}
}

function item_html(data, index) {
return `
	<!--item-->
	<tr>
		<td style="width:110px"><img src="${data.image}" class="rounded border" style="width:100px;height:100px"></td>
		<td class="text-primary">
			${data.description}
			<div class="input-group my-3" style="max-width:150px">
				<span index="${index}" onclick="change_qty('down',event)" class="input-group-text" style="cursor: pointer;"><i class="fa fa-minus text-primary"></i></span>
				<input index="${index}" onblur="change_qty('input',event)" type="text" class="form-control text-primary" placeholder="1" value="${data.qty}">
				<input index="${index}" onblur="change_phone_number('input',event)" type="text" class="form-control text-primary" placeholder="Phone" value="${data.phone}">
				<span index="${index}" onclick="change_qty('up',event)" class="input-group-text" style="cursor: pointer;"><i class="fa fa-plus text-primary"></i></span>
			</div>
		</td>
		<td style="font-size:20px">
			<b>K${data.amount}</b>
			<button onclick="clear_item(${index})" class="float-end btn btn-danger btn-sm"><i class="fa fa-times"></i></button>
		</td>
	</tr>
	<!--end item-->
`;
}


function add_item_from_index(index)
{
	if (!PRODUCTS[index]) {
		console.error('No product found at index:', index);
		return;
	}
	
	// Add visual feedback
	var productCard = document.querySelector(`[data-index="${index}"]`);
	if (productCard) {
		productCard.style.transform = 'scale(0.95)';
		productCard.style.boxShadow = '0 0 20px rgba(0,123,255,0.5)';
		setTimeout(() => {
			productCard.style.transform = '';
			productCard.style.boxShadow = '';
		}, 200);
	}

		//check if items exists
		for (var i = ITEMS.length - 1; i >= 0; i--) {
			
			if(ITEMS[i].id == PRODUCTS[index].id)
			{
				ITEMS[i].qty += 1;
				refresh_items_display();
				return;
			}
		}

		var temp = PRODUCTS[index];
		temp.qty = 1;

		ITEMS.push(temp);
		refresh_items_display();
}

function add_item(e)
{
	var index = null;

	// Check if clicked element is an image
	if(e.target.tagName == "IMG"){
		index = e.target.getAttribute("index");
	} 
	// Check if clicked element is inside a product card
	else {
		var productCard = e.target.closest('.product-card');
		if (productCard) {
			index = productCard.getAttribute('data-index');
		}
	}
	
	if (index !== null) {
		add_item_from_index(index);
	}
}

function refresh_items_display()
{
	var item_count = document.querySelector(".js-item-count");
	console.log('Cart count element found:', item_count);
	console.log('Current ITEMS length:', ITEMS.length);
	
	if (item_count) {
		var old_count = parseInt(item_count.innerHTML) || 0;
		var new_count = ITEMS.length;
		
		console.log('Updating cart count from', old_count, 'to', new_count);
		item_count.innerHTML = new_count;
		
		// Add animation to cart count
		if (new_count > old_count) {
			item_count.style.animation = 'bounce 0.6s ease';
			setTimeout(() => {
				item_count.style.animation = '';
			}, 600);
		}
	} else {
		console.error('Cart count element not found!');
	}

	var items_div = document.querySelector(".js-items");
	items_div.innerHTML = "";
	var grand_total = 0;

	if (ITEMS.length === 0) {
		items_div.innerHTML = `
			<tr>
				<td colspan="3" class="text-center text-muted py-4">
					<i class="fa fa-shopping-cart fa-2x mb-2"></i><br>
					Your cart is empty
				</td>
			</tr>
		`;
	} else {
	for (var i = ITEMS.length - 1; i >= 0; i--) {
		items_div.innerHTML += item_html(ITEMS[i],i);
		grand_total += (ITEMS[i].qty * ITEMS[i].amount);
		}
	}
	
	var gtotal_div = document.querySelector(".js-gtotal");
	gtotal_div.innerHTML = "Total: K" + grand_total.toFixed(2);
	GTOTAL = grand_total;

}

function clear_all()
{

	if(!confirm("Are you sure you want to clear all items in the list??!!"))
		return;

	ITEMS = [];
	refresh_items_display();

}

function clear_item(index)
{

	if(!confirm("Remove item??!!"))
		return;

	ITEMS.splice(index,1);
	refresh_items_display();

}

function change_qty(direction,e)
{

	var index = e.currentTarget.getAttribute("index");
	if(direction == "up")
	{
		ITEMS[index].qty += 1;
	}else
	if(direction == "down")
	{
		ITEMS[index].qty -= 1;
	}else{

		ITEMS[index].qty = parseInt(e.currentTarget.value);
	}

	//make sure its not less than 1
	if(ITEMS[index].qty < 1)
	{
		ITEMS[index].qty = 1;
	}

	refresh_items_display();
}

function check_for_enter_key(e)
{

	if(e.keyCode == 13)
	{
		BARCODE = true;
		search_item(e);
	}
}

function show_modal(modal)
{
	if(modal == "amount-paid"){

		if(ITEMS.length == 0){

			alert("Please add at least one item to the cart");
			return;
		}
		var mydiv = document.querySelector(".js-amount-paid-modal");
		mydiv.classList.remove("hide");

		mydiv.querySelector(".js-amount-paid-input").value = "";
		mydiv.querySelector(".js-amount-paid-input").focus();
	}else
	if(modal == "change"){

		var mydiv = document.querySelector(".js-change-modal");
		
		if (mydiv) {
			mydiv.classList.remove("hide");
			
			mydiv.querySelector(".js-change-input").innerHTML = CHANGE;
			mydiv.querySelector(".js-btn-close-change").focus();
		}
	}


}

function hide_modal(e,modal)
{
	if(e == true || e.target.getAttribute("role") == "close-button")
	{
		if(modal == "amount-paid"){
			var mydiv = document.querySelector(".js-amount-paid-modal");
			mydiv.classList.add("hide");
		}else 
		if(modal == "change"){
			var mydiv = document.querySelector(".js-change-modal");
			mydiv.classList.add("hide");
		}		
				
	}

}

	function validate_amount_paid(e)
	{
		var amount = e.currentTarget.parentNode.querySelector(".js-amount-paid-input").value.trim();
		
		if(amount == "")
		{
			alert("Please enter a valid amount");
			document.querySelector(".js-amount-paid-input").focus();
			return;
		}

		amount = parseFloat(amount);
		if(amount < GTOTAL){

			alert("Amount must be higher or equal to the total");
			return;
		}

		CHANGE = amount - GTOTAL ;
		CHANGE = CHANGE.toFixed(2);

		hide_modal(true,'amount-paid');
		show_modal('change');

		// Store checkout data for later use when change modal is closed
		window.checkoutData = {
			amount: amount,
			change: CHANGE,
			gtotal: GTOTAL,
			items: ITEMS.slice() // Create a copy of ITEMS
		};
	}

function performCheckout(checkoutData) {
	//remove unwanted information
	var ITEMS_NEW = [];
	for (var i = 0; i < checkoutData.items.length; i++) {
		
		var tmp = {};
		tmp.id = checkoutData.items[i]['id'];
		tmp.qty = checkoutData.items[i]['qty'];

		ITEMS_NEW.push(tmp);
	}

	// Function to get the phone number from the specific input field
	function get_phone_from_input() {
	    var phoneInput = document.getElementById("phone-input");  // Get the phone number input field by ID
	    return phoneInput.value;  // Get the value entered by the user
	}

	// Function to add phone to each item in the cart
	function add_phone_to_items(phone) {
	    // Loop through each item in ITEMS_NEW and add the phone number
	    for (var i = 0; i < ITEMS_NEW.length; i++) {
	        ITEMS_NEW[i].phone = phone;  // Add the phone number to each item
	    }
	}

	// Send cart data through AJAX
	function send_cart_data(phone) {
	    // Add the phone number to each item in the cart
	    add_phone_to_items(phone);

	    // Send the cart data including the phone number
	    send_data({
	        data_type: "checkout",
	        text: ITEMS_NEW
	    });
	}

	// Example Usage: Get the phone from the input field and send cart data
	var userPhone = get_phone_from_input();  // Get phone number from the input field
	if (userPhone) {  // Ensure phone number is not empty
	    send_cart_data(userPhone);
	} else {
	    alert("Please enter a phone number.");  // Alert if phone number is empty
	}

	// Receipt SMS will be sent by the backend during checkout

	//open receipt page
	print_receipt({
		company:'Pos System',
		amount: checkoutData.amount,
		change: checkoutData.change,
		gtotal: checkoutData.gtotal,
		data: checkoutData.items
	});
}

function print_receipt(obj)
{
	try {
		var vars = JSON.stringify(obj);

		RECEIPT_WINDOW = window.open('index.php?pg=print&vars='+vars,'printpage',"width=500px;");
		
		if (RECEIPT_WINDOW) {
			setTimeout(close_receipt_window, 2000);
		} else {
			console.error('Failed to open receipt window - popup blocked?');
			alert('Receipt window could not be opened. Please check if popups are blocked.');
		}
	} catch (error) {
		alert('Error generating receipt: ' + error.message);
	}
}

function close_receipt_window()
{
	RECEIPT_WINDOW.close();
}

send_data({

	data_type:"search",
	text:""
});
