@extends('master_front')

@section('content')
   <!-- start single product details -->
   <div class="small-container single-product">
       <div class="row">
           <div class="col-2">
               <img src="{{ asset('storage/assets/images/gallery/600x600').'/'.$product->imageUploaded[0]->name }}" alt="{{ $product->slug }}" width="100%" id="productImg">

               <div class="small-img-row">
                   @foreach ($product->imageUploaded as $item)
                        <div class="small-img-col">
                            <img src="{{ asset('storage/assets/images/gallery/600x600').'/'.$item->name }}" alt="{{ $item->name }}" width="100" class="small-img">
                        </div>
                   @endforeach
               </div>

           </div>
           <div class="col-2">
               <input type="hidden" name="product_id" id="product_id" value="{{ $product->id }}">
               <input type="hidden" name="price" id="price" value="{{ $product->price }}"> 
               <p>Home / T-Shirt</p>
               <h1>{{ $product->title}}</h1>
               <h4 class="totalPrice"></h4>
               <select name="size" id="size">
                   <option>Select Size</option>
                   <option>XXL</option>
                   <option>XL</option>
                   <option>Large</option>
                   <option>Medium</option>
                   <option>Small</option>
               </select>
               <input type="number" id="quantity" name="quantity" value="1" min="1" onkeypress="return (event.charCode == 8 || event.charCode == 0) ? null : event.charCode >= 48 && event.charCode <= 57">
               <a href="#" class="btn" id="addToCart">Add To Cart</a>
               
               <h3>Product Details <i class="fa fa-indent"></i></h3>
               <br>
               <p>{{ $product->description }}</p>
           </div>
       </div>
   </div>
   <!-- end single product details -->

   <!-- start title -->
   <div class="small-container">
       <div class="row row-2">
           <h2>Related Products</h2>
           <p>View More</p>
       </div>
   </div>
   <!-- end title -->

   <div class="small-container">
      <!-- start all products products -->
      <div class="row">
        <div class="col-4">
            <img src="{{ asset('assets/images/product-1.jpg') }}" alt="product-1">
            <h4>Red Printed T-shirt</h4>
            <div class="rating">
               <i class="fa fa-star"></i>
               <i class="fa fa-star"></i>
               <i class="fa fa-star"></i>
               <i class="fa fa-star"></i>
               <i class="fa fa-star-o"></i>
            </div>
            <p>$50.00</p>
         </div>
         <div class="col-4">
            <img src="{{ asset('assets/images/product-2.jpg') }}" alt="product-2">
            <h4>Red Printed T-shirt</h4>
            <div class="rating">
               <i class="fa fa-star"></i>
               <i class="fa fa-star"></i>
               <i class="fa fa-star"></i>
               <i class="fa fa-star"></i>
               <i class="fa fa-star-o"></i>
            </div>
            <p>$50.00</p>
         </div>
         <div class="col-4">
            <img src="{{ asset('assets/images/product-3.jpg') }}" alt="product-3">
            <h4>Red Printed T-shirt</h4>
            <div class="rating">
               <i class="fa fa-star"></i>
               <i class="fa fa-star"></i>
               <i class="fa fa-star"></i>
               <i class="fa fa-star"></i>
               <i class="fa fa-star-half-o"></i>
            </div>
            <p>$50.00</p>
         </div>
         <div class="col-4">
            <img src="{{ asset('assets/images/product-4.jpg') }}" alt="product-4">
            <h4>Red Printed T-shirt</h4>
            <div class="rating">
               <i class="fa fa-star"></i>
               <i class="fa fa-star"></i>
               <i class="fa fa-star"></i>
               <i class="fa fa-star"></i>
               <i class="fa fa-star-o"></i>
            </div>
            <p>$50.00</p>
         </div>
      </div>
      <!-- end all products products -->
   </div>
@endsection

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
@endpush

@push('scripts')
    <!-- js for toggle menu (product details) -->
    <script>
        var MenuItems = document.getElementById("MenuItems");
    
        MenuItems.style.maxHeight = "0px";
    
        function menuToggle() {
        if(MenuItems.style.maxHeight == "0px") {
            MenuItems.style.maxHeight = "200px";
        } else {
            MenuItems.style.maxHeight = "0px";
        }
        }
    </script>

   <!-- js for product details gallery -->
    <script>
        var productImg = document.getElementById("productImg");
        var smallImg = document.getElementsByClassName("small-img");

        smallImg[0].onclick = function () {
            productImg.src = smallImg[0].src;
        }
        smallImg[1].onclick = function () {
            productImg.src = smallImg[1].src;
        }
        smallImg[2].onclick = function () {
            productImg.src = smallImg[2].src;
        }
        smallImg[3].onclick = function () {
            productImg.src = smallImg[3].src;
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(document).ready(function () {
            let product_id = $('#product_id').val();
            let quantity = $('#quantity').val();
            let size = $('#size option:selected').val();
            let is_checkout = 0;
            let price = $('#price').val();

            $('h4.totalPrice').text('$'+setTotal(price, quantity)+'.00');

            $('#addToCart').on("click", function(e) {
                e.preventDefault();
                let data = {
                    product_id : product_id,
                    quantity : quantity,
                    size : size,
                    total : setTotal(price, quantity),
                    is_checkout : is_checkout,
                    _token : "{{ csrf_token() }}",
                };
                
                $.ajax({
                    url: '{{ route("cart.post") }}',
                    type: "POST",
                    data: data,
                    success: function(res) {
                        let response = JSON.parse(res)
                        console.log(response)
                        
                        toastr.success(response.message)
                    },
                    error : function(err) {
                        if (Array.isArray(err.responseJSON.message)){
                            err.responseJSON.message.forEach(function(v) {
                                toastr.error(v)
                            })
                        } else {
                            toastr.error(err.responseJSON.message)
                        }
                    }
                })
            })

        $('#quantity').on('change', function (e) {
            quantity = $(this).val();
            $('h4.totalPrice').text('$'+setTotal(price, quantity)+'.00');
        })

        $('#size').on('change', function(e) {
            size = $(this).val();
        })

    })

    function setTotal(price, quantity)
    {
        return price * quantity
    }
    </script>
@endpush