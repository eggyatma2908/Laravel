@extends('master_front')

@section('content')

<!-- start account page -->
<div class="account-page">
    <div class="container">
        <div class="row">
            <div class="col-2">
                <img src="assets/images/image1.png" alt="image1" width="100%">
            </div>
            <div class="col-2">
                <div class="form-container">
                    <div class="form-btn">
                        <span onclick="login()">Login</span>
                        <span onclick="register()">Register</span>
                        <hr id="indicator">
                     </div>

                     <form id="loginForm" method="POST" action="{{ route('auth.login') }}">
                        @csrf
                         <input type="email" placeholder="Username" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                         <input type="password" placeholder="Password" id="password" name="password" required autocomplete="current-password">
                         <button type="submit" class="btn">Login</button>
                         <a href="">Forgot Password</a>
                     </form>

                     <form id="registerForm" method="POST" action="{{ route('auth.register') }}">
                        @csrf
                         <input type="text" placeholder="Username" id="name" name="name" value="{{ old('name') }}" autocomplete="name" autofocus>
                         <input type="email" placeholder="Email" id="email" name="email" value="{{ old('email') }}" autocomplete="email">
                         <input type="password" placeholder="Password" id="password" name="password">
                         <input type="password" placeholder="Retype Password" id="password-confirm" name="password_confirmation">
                         <button type="submit" class="btn">Register</button>
                     </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end account page -->

@endsection

@push('scripts')
    <!-- js for toggle menu (account) -->
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

    <!-- js for toggle form (account) -->
    <script>
        var loginForm = document.getElementById("loginForm");
        var registerForm = document.getElementById("registerForm");
        var indicator = document.getElementById("indicator");

        function register() {
        registerForm.style.transform = "translateX(-300px)";
        loginForm.style.transform = "translateX(-300px)";
        indicator.style.transform = "translateX(100px)";
    }

        function login() {
        registerForm.style.transform = "translateX(0px)";
        loginForm.style.transform = "translateX(0px)";
        indicator.style.transform = "translateX(0px)";
        }
    </script>
@endpush