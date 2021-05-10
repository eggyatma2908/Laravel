@extends('master_front')

@section('content')
<div class="small-container">
    <div class="row row-2">
        <h2>All Products</h2>
        <select>
            <option value="">Default Shorting</option>
            <option value="">Short by price</option>
            <option value="">Short by popularity</option>
            <option value="">Short by rating</option>
            <option value="">Short by sale</option>
        </select>
    </div>
   <!-- start all products products -->
   <div class="row">
      @foreach ($products as $item)
         <div class="col-4">
            <a href="{{ route('products.details', $item->id) }}"><img src="{{ asset('storage/assets/images/gallery/1080x1440').'/'.$item->imageUploaded[0]->name }}" alt="{{ $item->slug }}"></a>
            <a href="{{ route('products.details', $item->id) }}"><h4>{{ $item->title }}</h4></a>
            <div class="rating">
               @for ($i = 0; $i < 5; $i++)
                  <i class="fa fa-star{{ $item->rating <= $i ? '-o' : '' }}"></i>
               @endfor
            </div>
            <p>${{ $item->price }}.00</p>
         </div>
      @endforeach
   </div>
   <!-- end all products products -->

   <!-- <div class="page-btn">
       <span>1</span>
       <span>2</span>
       <span>3</span>
       <span>4</span>
       <span>&#8594;</span>
   </div> -->
</div>
@endsection

@push('scripts')
    <!-- js for toggle menu (products) -->
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
@endpush