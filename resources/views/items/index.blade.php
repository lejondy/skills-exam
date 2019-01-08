<!DOCTYPE html>
<html lang="en">
<head>
		<meta charset="UTF-8">
	    <title>skills Exam</title>
	    <meta name="csrf-token" content="{{ csrf_token() }}" />
	    <!-- Bootstrap core CSS -->
		<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
		<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				<ul id="form-errors"></ul>
				
			   <form action="/tasks" method="post" id="form-cart">
				      <div class="form-group">
				        <label for="product_name">Product name</label>
				        <input type="text" class="form-control" id="product_name"  name="product_name" }}">
				      </div>
				      <div class="form-group">
				        <label for="quantity">Quantity </label>
				        <input type="text" class="form-control" id="quantity" name="quantity"">
				      </div>

				      <div class="form-group">
				        <label for="price">Price per item </label>
				        <input type="text" class="form-control" id="price" name="description">
				      </div>
				      <button type="submit" class="btn btn-primary">Submit</button>
			    </form>
			</div>
			<hr>
			<div class="col-md-12">
			   <h2>Product list <img id="ajax-loader" src="{{ asset('img/ajax-loader.gif')}}" alt=""></h2>
			   <div class="table-responsive">
			      <table id="mytable" class="table table-bordred table-striped">
			         <thead>
			            <th>Product name</th>
			            <th>Quantity</th>
			            <th>Price</th>
			            <th>Datetime</th>
			            <th>Total value number</th>
			            <th>Action</th>
			         </thead>
			         <tbody id="item-list-body">
			           
			         </tbody>
			      </table>
			   </div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
		
	   <div class="modal-dialog">
	   		<div class="form-modal"></div>
	      <div class="modal-content">
	         <div class="modal-header">
	            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
	            <h4 class="modal-title custom_align" id="Heading">Edit Product</h4>
	         </div>
	         {{--  --}}
	         <div class="modal-body">
	           <div class="form-group">
		        <label for="product_name">Product name</label>
		        <input type="text" class="form-control" id="mod_product_name"  name="product_name" }}">
		      	</div>
		      <div class="form-group">
		        <label for="quantity">Quantity </label>
		        <input type="text" class="form-control" id="mod_quantity" name="quantity"">
		      </div>

		      <div class="form-group">
		        <label for="price">Price per item </label>
		        <input type="text" class="form-control" id="mod_price" name="description">
		      </div>
	         </div>
			{{--  --}}
	         <div class="modal-footer ">
	            <button type="button" class="btn btn-warning btn-lg" style="width: 100%;"><span class="glyphicon glyphicon-ok-sign"></span> Update</button>
	         </div>
	      </div>
	      <!-- /.modal-content --> 
	   </div>
	   <!-- /.modal-dialog --> 
	</div>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script>


    	$(function() {
    	function getActionButtons() {
    		var actionButtons = [
    		'<td>',
    		 '<button data-id="{uniqueK}" class="btn btn-primary btn-xs edit-button" data-title="Edit" data-toggle="modal",data-target="#edit" >',
    		 '<span class="glyphicon glyphicon-pencil"></span></button>',
    		 '</td>'
    		];

    		var strbtn = actionButtons.join("\n");

    		return strbtn;
    	};

    	var populateData = function(data) {
    		
    		if(data.length == 0) return;

    		data.sort(function(a, b){
    		  return new Date(a.created_at) - new Date(b.created_at);
    		});
    		var tBodyElement = [];
    		$.each( data, function( key, item ) {
    			var trElement  = [];
    			trElement.push("<tr>");
  				trElement.push("<td>{product_name}</td>".replace(/{product_name}/g, item.product_name));
  				trElement.push("<td>{quantity}</td>".replace(/{quantity}/g, item.quantity));
  				trElement.push("<td>{price}</td>".replace(/{price}/g, item.price));
  				trElement.push("<td>{created_at}</td>".replace(/{created_at}/g, item.created_at));
  				var tvalue = item.price * item.quantity;
  				trElement.push("<td>{total_value}</td>".replace(/{total_value}/g, tvalue));
  				var actbtn = getActionButtons();
  				actbtn = actbtn.replace(/{uniqueK}/g, item.id);
  				trElement.push("{action}".replace(/{action}/g, actbtn));
  				trElement.push("</tr>")
  				tBodyElement.push(trElement.join("\n"));
			});

			$('#form-cart')[0].reset();

			$("#item-list-body").html(tBodyElement.join("\n"));
    	};
    	

    	$.ajaxSetup({
    	    headers: {
    	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    	    }
    	});


    	$('#form-cart').submit(function(e) {
    		e.preventDefault();
    		var payload = {
    			product_name: $.trim($("#product_name").val()),
    			quantity : $.trim($("#quantity").val()),
    			price :  $.trim($("#price").val()),
    		}

    		var hasErrors = formValidation();
    		
    		if (hasErrors) return false;

	    	$.ajax({
		        type:'POST',
		        url:'/items',
		        data: payload,
		        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		        success:function(data){
		        	populateData(data);
		        }
	     	});

    	});
		//first load
    	$.ajax({
	        type:'GET',
	        url:'/ajaxdata',
	        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
	        success:function(data){
	        	populateData(data);
	        	$('#ajax-loader').hide();
	        }
     	});
   		});

    	function formValidation() {

    		var liElement = [];
    		var errorCount = 0;

    		var product_name = $.trim($("#product_name").val());
    		var quantity = $.trim($("#quantity").val());
    		var price = $.trim($("#price").val());

    		if (product_name == "") {
    			errorCount++;
    			liElement.push("<li>Product name must not be empty</li>");
    		}

    		if (quantity == "") {
    			errorCount++;
    			liElement.push("<li>Quantity must not be empty</li>");
    		}
    		 
    		if(isNaN(quantity)){
				errorCount++;
    			liElement.push("<li>Quantity must be a number </li>");
    		}

    		if (price == "") {
    			errorCount++;
    			liElement.push("<li>Price must not be empty</li>");
    		}

			if(isNaN(price)){
				errorCount++;
				liElement.push("<li>Price must be a number </li>");
			}

    		
    		$("#form-errors").html(liElement.join('\n'));

    		return errorCount;
    	}
    	
    </script>


</body>
</html>


