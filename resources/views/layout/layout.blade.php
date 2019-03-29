<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- COMMON TAGS -->
  <meta charset="utf-8">
  <title>Merge Transit</title>
  <!-- Search Engine -->
  <meta name="description" content="A Spot Market Agency is a single resource center specializing in market insight, business organization and rate optimization, dedicated to carriers in the Truckload Spot Market.">
  <meta name="image" content="">
  <!-- Schema.org for Google -->
  <meta itemprop="name" content="Merge Transit">
  <meta itemprop="description" content="A Spot Market Agency is a single resource center specializing in market insight, business organization and rate optimization, dedicated to carriers in the Truckload Spot Market.">
  <meta itemprop="image" content="">
  <!-- Open Graph general (Facebook, Pinterest & Google+) -->
  <meta name="og:title" content="Merge Transit">
  <meta name="og:description" content="A Spot Market Agency is a single resource center specializing in market insight, business organization and rate optimization, dedicated to carriers in the Truckload Spot Market.
					">
  <meta name="og:image" content="">
  <meta name="og:url" content="www.mergetransit.com">
  <meta name="og:site_name" content="Merge Transit">
  <meta name="og:locale" content="PL">
  <meta name="og:type" content="website">

  <!-- FAVICONS -->
  {{-- <link rel="apple-touch-icon" sizes="57x57" href="{{asset('assets/images/apple-icon-57x57.png')}}">
  <link rel="apple-touch-icon" sizes="60x60" href="{{asset('assets/images/apple-icon-60x60.png')}}">
  <link rel="apple-touch-icon" sizes="72x72" href="{{asset('assets/images/apple-icon-72x72.png')}}">
  <link rel="apple-touch-icon" sizes="76x76" href="{{asset('assets/images/apple-icon-76x76.png')}}">
  <link rel="apple-touch-icon" sizes="114x114" href="{{asset('assets/images/apple-icon-114x114.png')}}">
  <link rel="apple-touch-icon" sizes="120x120" href="{{asset('assets/images/apple-icon-120x120.png')}}">
  <link rel="apple-touch-icon" sizes="144x144" href="{{asset('assets/images/apple-icon-144x144.png')}}">
  <link rel="apple-touch-icon" sizes="152x152" href="{{asset('assets/images/apple-icon-152x152.png')}}">
  <link rel="apple-touch-icon" sizes="180x180" href="{{asset('assets/images/apple-icon-180x180.png')}}"> --}}
  {{-- <link rel="icon" type="image/png" sizes="192x192"  href="{{asset('assets/images/android-icon-192x192.png')}}"> --}}
  <link rel="icon" type="image/png" href="{{asset('assets/images/fav_ico.png')}}">
  {{-- <link rel="icon" type="image/png" sizes="96x96" href="{{asset('assets/images/favicon-96x96.png')}}"> --}}
  {{-- <link rel="icon" type="image/png" sizes="16x16" href="{{asset('assets/images/favicon-16x16.png')}}"> --}}

  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="msapplication-TileImage" content="{{asset('assets/images/ms-icon-144x144.png')}}">
  <meta name="theme-color" content="#ffffff">

  <link rel="stylesheet" href="https://code.jquery.com/ui/1.8.21/themes/base/jquery-ui.css" />
  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <!-- Font -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700,900&amp;subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese" rel="stylesheet">
  <!-- FANCYBOX -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.css">
  <!-- Main style -->
  
  <link href="{{asset('assets/css/font-awesome.css')}}" rel="stylesheet" type="text/css" />
  

  <link rel="stylesheet" href="{{asset('assets/css/styles_new.css')}}">
  <link rel="stylesheet" href="{{asset('assets/css/custom.css')}}">

<style>


ul li ul {
  display: none;
  white-space: nowrap;
  visibility: hidden;
  z-index: 999999;
  position: absolute;
  top: 0px;
  height: 30px;
  width: 100%;
}
ul li ul li {
  background: #00a0f0;
}
ul li ul li:hover {
  background-color: #005882;
  cursor: pointer;
}

ul li:hover > ul {
  visibility: visible;
  display: block;
}

#ui-datepicker-div {
	width:265px;
}

.my-dashboard{
	background: #80daaa;
	padding: 15px;
	border-radius: 5px;
}

.my-dashboard:hover{
	
}
	</style>
  
</head>

<body class="smoothscroll enable-animation">

    <div class="preloader">
		
    </div>
    
    <!-- header -->
	<header class="header" >
	    <a href="{{url('index')}}" class="logo">
	        <img src="{{asset('assets/images/logo-white.png')}}" alt="Marge Transit">
	    </a>
	    <button class="hamburger">
	        <span class="hamburger_bar"></span>
	        <span class="hamburger_bar"></span>
	        <span class="hamburger_bar"></span>
	    </button>
	    <nav>                
	        <ul>
	            <li>
	                <a class="" href="{{url('index')}}">
	                    Home
	                </a>
	            </li>
	            <li>
	                <a href="/service">
	                    Agency Services
	                </a>
	            </li>
	            <li>
	                <a href="/mobileapp">
	                    Mobile APP
	                </a>
	            </li>
				<li>
					<a href="/aboutus" >
						About Merge Transit
					</a>
				</li>
				<li>
					<a href="/contactus">
						Contact Us 
					</a>
				</li>

                @if(Auth::guest())
                    
                @else
                    
                    @if(Auth::user()->role==0)
					<li><a  href="/report">Report</a></li>
					@endif
					@if(Auth::user()->role==4)
					<li><a  href="/sadmin" class="my-dashboard">Dashboard</a></li>
					@endif
                @endif
                @if(Auth::guest())
                    <li class="first-special">
                        <a href="/sadmin/register" class="button button--dark-blue">
                            Sign up
                        </a>
                    </li>
                    <li>
                        <a href="/login" class="button button--dark-blue">
                            Login
                        </a>
                    </li>
                @else
                    <li>
						<?php
							$user = App\User::find(Auth::user()->id);
							//if($user->role == 0){
								// $customer = App\Customer::where('email',$user->email)->get();
								//  $login_user = $customer[0];
							//}else{
								$login_user = $user;
							//}
						?>
                        <a class="dropdown-toggle" href="#">
                            <i class="fa fa-user"></i>{{$login_user->firstname}} {{$login_user->lastname}}
                        </a>
                        <ul class="dropdown-menu">
                            <li class="dropdown">
                            	<a class="dropdown-to" href="#">
                                	<i class="fa fa-user"></i> {{$login_user->firstname}} {{$login_user->lastname}} 
                            	</a>
                            </li>
                            <li class="dropdown">
                                <a class="dropdown-to" href="{{url('/change-password')}}">
                                    <i class="fa fa-lock"></i> Change Password
                                </a>
                            </li>
                            <li class="dropdown">
                                <a class="dropdown-to" href="{{url('logout')}}">
                                    <i class="fa fa-sign-out"></i> Log Out
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                
	        </ul>
	    </nav>
	</header>
	<!--/ -->


@yield("contents")

<footer class="footer">
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-3">
					<img src="{{asset('assets/images/logo-white.png')}}" alt="">
				</div>
				<div class="col-md-3">
					<h3 class="f--white">
						Marge Transit
					</h3>
					<p>
						The Spot Market Agency, is dedicated to The Spot Market Carriers nationwide. We are a company that believes The Spot Market can provide for a long-term business, when procedures and systems are implemented to navigate the market rates to provide consistent results.
					</p>
				</div>
				<div class="col-md-3">
					<h3 class="f--white">
						Contact info
					</h3>
					<p>
						<i class="fas fa-map-marker-alt"></i>
						<span>
							Merge Transit LLC
						</span>
					</p>
					<p>
						<i class="fas fa-phone"></i>
						<a href='phone:8662728001'>
							(866) 272-8001
						</a>
					</p>
					<p>
						<i class="fas fa-envelope"></i>
						<a href="mailto:info@MergeTransit.com">
							info@MergeTransit.com
						</a>
					</p>
				</div>
				<div class="col-md-3">
					<h3 class="f--white">
						Social Media
					</h3>
					<ul>
						<li>
							<a href="https://www.facebook.com/MergeTransit/">
								<i class="fab fa-facebook-f"></i>
								<span>
									facebook
								</span>
							</a>
						</li>
						<li>
							<a href="https://www.instagram.com/merge.transit">
								<i class="fab fa-instagram"></i>
								<span>
									instagram
								</span>
							</a>
						</li>
						<li>
							<a href="https://www.youtube.com/watch?v=wLVWUSbRduM">
								<i class="fab fa-youtube"></i>
								<span>
									youtube
								</span>
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</footer>


    <a class="terms f--white" data-fancybox data-type="iframe" data-src="https://app.termly.io/document/privacy-policy/a935874d-2f79-4373-b6f4-76f7d3590b42" href="javascript:;">
        TERMS AND SERVICES
     </a>
	<!-- JAVASCRIPT FILES -->
	
	<script
	  src="https://code.jquery.com/jquery-3.3.1.min.js"
	  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
	  crossorigin="anonymous"></script>
	<!-- font awesome -->
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"  ></script>
	<script defer src="https://use.fontawesome.com/releases/v5.1.0/js/all.js" integrity="sha384-3LK/3kTpDE/Pkp8gTNp2gR/2gOiwQ6QaO7Td0zV76UFJVhqLl4Vl3KL1We6q6wR9" crossorigin="anonymous"></script>
	<!-- ScrollMagic  -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.5/ScrollMagic.min.js"></script>
	<!-- Scroll Magic Debug -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.5/plugins/debug.addIndicators.min.js"></script>
	 <!-- GreenSock TweenMax -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.18.2/TweenMax.min.js"></script>
	<!-- ScrollMagic GreenSock Plugin -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.5/plugins/animation.gsap.js"></script>
	<!-- Masonry -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/masonry/4.2.2/masonry.pkgd.min.js" async defer></script>
	<!-- FANCYAPP -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.js" type="text/javascript" charset="utf-8" async defer></script>
	<!-- Main script -->

	<script src='https://www.google.com/recaptcha/api.js'></script>

	<script src="{{asset('assets/js/main.js')}}" type="text/javascript" charset="utf-8" async defer></script>

	
	<script>
		$(document).ready(function(){
			$("#startdate").datepicker();
			$("#enddate").datepicker();
		})
	</script>
	@stack('javascript')
</body>

</html>