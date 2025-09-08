var PRODUCTS 	= [];
var PHONE   	= [];
var ITEMS 		= [];
var BARCODE 	= false;
var GTOTAL  	= 0;
var CHANGE  	= 0;
var RECEIPT_WINDOW = null;

var main_input = document.querySelector(".js-search");

function search_item(e){
    var text = e.target.value.trim();
    console.log("Searching for:", text); // Add this line
    var data = {};
    data.data_type = "search";
    data.text = text;
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
	
	//console.log(result);.
	var obj = JSON.parse(result);
	if(typeof obj != "undefined"){

		//valid json
		if(obj.data_type == "search")
		{

			var mydiv = document.querySelector(".js-products");

			mydiv.innerHTML = "";
			PRODUCTS = [];

			var mydiv = document.querySelector(".js-products");
			if(obj.data != "")
			{
				
				PRODUCTS = obj.data;
				for (var i = 0; i < obj.data.length; i++) {
					
					mydiv.innerHTML += product_html(obj.data[i],i);
				}

				if(BARCODE && PRODUCTS.length == 1){
					add_item_from_index(0);
				}
			}
		}
		
	}

}

function product_html(data,index)
{

	return `
		<!--card-->
		<div class="card m-2 border-0 mx-auto" style="min-width: 190px;max-width: 190px;">
			<a href="#">
				<img index="${index}" src="${data.image}" class="w-100 rounded border">
			</a>
			<div class="p-2">
				<div class="">${data.description}</div>
				<div class="" style="font-size:20px"><b>K${data.amount}</b></div>
			</div>
		</div>
		<!--end card-->
		`;

			
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

		//check if items exists
		for (var i = ITEMS.length - 1; i >= 0; i--) {
			
			if(ITEMS[i].id == PRODUCTS[index].id)
			{
				ITEMS[i].qty += 1;
				ITEMS[i].phone = phone;
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

	if(e.target.tagName == "IMG"){
		var index = e.target.getAttribute("index");

		add_item_from_index(index);
	}
}

function refresh_items_display()
{

	var item_count = document.querySelector(".js-item-count");
	item_count.innerHTML = ITEMS.length;

	var items_div = document.querySelector(".js-items");
	items_div.innerHTML = "";
	var grand_total = 0;

	for (var i = ITEMS.length - 1; i >= 0; i--) {

		items_div.innerHTML += item_html(ITEMS[i],i);
		grand_total += (ITEMS[i].qty * ITEMS[i].amount);
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
		mydiv.classList.remove("hide");

		mydiv.querySelector(".js-change-input").innerHTML = CHANGE;
		mydiv.querySelector(".js-btn-close-change").focus();
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

		//remove unwanted information
		var ITEMS_NEW = [];
		for (var i = 0; i < ITEMS.length; i++) {
			
			var tmp = {};
			tmp.id = ITEMS[i]['id'];
			tmp.qty = ITEMS[i]['qty'];

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

		// Send receipt SMS after checkout
		var receipt_no = get_receipt_no();
		if (userPhone && receipt_no) {
			var xhr = new XMLHttpRequest();
			xhr.open("POST", "public/send_receipt.php", true);
			xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			xhr.onreadystatechange = function() {
				if (xhr.readyState === 4) {
					if (xhr.status === 200) {
						console.log("Receipt SMS sent successfully");
					} else {
						console.error("Failed to send receipt SMS");
					}
				}
			};
			xhr.send("receipt_no=" + encodeURIComponent(receipt_no) + "&phone=" + encodeURIComponent(userPhone));
		}

		//open receipt page
		print_receipt({
			company:'Pos System',
			amount:amount,
			change:CHANGE,
			gtotal:GTOTAL,
			data:ITEMS
		});

		//clear items
		ITEMS = [];
		refresh_items_display();

		//reload products
		send_data({

			data_type:"search",
			text:""
		});
	}

function print_receipt(obj)
{
	// Generate the receipt number before sending the data
	var receipt_no = get_receipt_no(); // Assuming get_receipt_no() is available in the frontend
	obj.receipt_no = receipt_no; // Add the receipt number to the object

	var vars = JSON.stringify(obj);

	RECEIPT_WINDOW = window.open('index.php?pg=print&vars='+vars,'printpage',"width=500px;");

	setTimeout(close_receipt_window,2000);
}

function close_receipt_window()
{
	RECEIPT_WINDOW.close();
}

send_data({

	data_type:"search",
	text:""
});
