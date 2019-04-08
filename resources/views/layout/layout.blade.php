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

	<link rel="icon" type="image/png" href="{{asset('assets/images/fav_ico.png')}}">

	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="{{asset('assets/images/ms-icon-144x144.png')}}">
	<meta name="theme-color" content="#ffffff">
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.8.21/themes/base/jquery-ui.css" />
	<!-- Bootstrap -->

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
	<!-- Font -->
	<link href="{{asset('assets/fonts/nunito_sans/stylesheet.css')}}" rel="stylesheet" type="text/css" />
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700,900&amp;subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese"
	 rel="stylesheet">
	<!-- FANCYBOX -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.css">
	<!-- Main style -->

	<link href="{{asset('assets/css/font-awesome.css')}}" rel="stylesheet" type="text/css" />


	<link rel="stylesheet" href="{{asset('assets/css/styles_new.css')}}">
	<link rel="stylesheet" href="{{asset('assets/css/custom.css')}}">
</head>

<body class="smoothscroll enable-animation">

	<div class="preloader"></div>

	<!-- header -->
	<header class="header {{isset($white_header)?'black':'white'}}">
		<a href="{{url('index')}}" class="logo"><img src="{{asset(isset($white_header)?'assets/images/logo-colored.png':'assets/images/logo-white.png')}}" alt="Marge Transit"></a>
		<button class="hamburger">
	        <span class="hamburger_bar"></span>
	        <span class="hamburger_bar"></span>
	        <span class="hamburger_bar"></span>
	    </button>
		<nav class="nav-bar">
			<ul>

				{{--
				<li><a href="{{url('index')}}">Home</a></li>
				<li><a href="/mobileapp">Mobile APP</a></li>
				<li><a href="/service">Agency Services</a></li>
				--}}
				<li><a href="/aboutus">About</a></li>
				<li><a href="/pricing">Pricing</a></li>
				<li><a href="/download">Download</a></li>
				<li><a href="/faq">FAQ</a></li>
				<li><a href="/blog">Blog</a></li>
				<li><a href="/contactus">Contact</a></li>

				@if(Auth::guest())

				<li><a href="/login" class="button bordered">&nbsp;Login&nbsp;</a></li>
				<li><a href="/sadmin/register" class="button button--dark-blue">Sign up</a></li>

				@else @if (Auth::user()->role == 0)
				<li><a href="/report">Report</a></li>
				@elseif (Auth::user()->role == 4)
				<li><a href="/sadmin" class="my-dashboard">Dashboard</a></li>
				@endif

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

	<!-- apps -->
	<section id="apps" class="apps {{isset($hide_app_section)? 'hidden':''}}">
		<div class="container home-content">
			<div class="row">
				<div class="col-sm-8 ">
					<h2 class="medium">
						Get app from Google Play & APP Store and make your job easier.
					</h2>
					<a class="get" href="https://play.google.com/store/apps/details?id=com.cleaningapp">
						<img src="{{asset('assets/images/icon-google-black.png')}}" alt="">
					</a>
					<a class="get" href="https://itunes.apple.com/us/app/merge-transit-spot-market-app/id1434054240?mt=8">
						<img src="{{asset('assets/images/icon-appstore-black.png')}}" alt="">
					</a>
				</div>
				<div class="col-sm-4 image"></div>
			</div>
		</div>
	</section>
	<!-- /apps -->

	<footer class="footer">
		<div class="container">
			<div class="row">
				<div class="col-md-7">
					<img src="{{asset('assets/images/logo-white.png')}}" alt="">
					<p>
						The Spot Market Agency, is dedicated to The Spot Market Carriers nationwide. We are a company that believes The Spot Market
						can provide for a long-term business, when procedures and systems are implemented to navigate the market rates to provide
						consistent results.
					</p>
				</div>
				<div class="col-md-5">
					<div class="row">
						<div class="col-7">
							<h4 class="f--white">
								Contact info
							</h4>
							<p>
								<i class="fas fa-map-marker-alt"></i>
								<span>Merge Transit LLC</span>
							</p>
							<p>
								<i class="fas fa-phone"></i>
								<a href='phone:8662728001'>(866) 272-8001</a>
							</p>
							<p>
								<i class="fas fa-envelope"></i>
								<a href="mailto:info@MergeTransit.com">info@MergeTransit.com</a>
							</p>
						</div>
						<div class="col-5">
							<h4 class="f--white">
								menu
							</h4>
							<ul>
								<li><a href="/aboutus">About</a></li>
								<li><a href="/contactus">Contact</a></li>
								<li>
									<a class="f--white" data-fancybox data-type="iframe" data-src="https://app.termly.io/document/privacy-policy/a935874d-2f79-4373-b6f4-76f7d3590b42"
									 href="javascript:;">Privacy policy</a>
								</li>
							</ul>
						</div>

					</div>
				</div>
				{{--
				<div class="col-md-3">

				</div>
				<div class="col-md-2">

				</div> --}}
			</div>
		</div>
	</footer>
	<section id="social" class="social">
		<div class="container home-content">
			<div class="row">
				<div class="col-md-8 col-sm-6 col-4">
					<a href="https://www.facebook.com/MergeTransit/">
						<i class="fab fa-facebook-f"></i>
					</a>
					<a href="https://www.instagram.com/merge.transit">
						<i class="fab fa-instagram"></i>
					</a>
					<a href="https://www.youtube.com/channel/UCbx7a_y07rmjhCDpQnPv_rg?view_as=subscriber">
						<i class="fab fa-youtube"></i>
					</a>
				</div>
				<div class="col-md-4 col-sm-6 col-8">
					<span>Copyright © 2019 QualityPixels. All rights reserved.</span>
				</div>
			</div>
		</div>
	</section>


	<!-- JAVASCRIPT FILES -->




	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>









	{{--
	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
	 crossorigin="anonymous"></script> --}}
	<!-- font awesome -->
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
	<script src="https://use.fontawesome.com/releases/v5.1.0/js/all.js" integrity="sha384-3LK/3kTpDE/Pkp8gTNp2gR/2gOiwQ6QaO7Td0zV76UFJVhqLl4Vl3KL1We6q6wR9"
	 crossorigin="anonymous"></script>
	<!-- ScrollMagic  -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.5/ScrollMagic.min.js"></script>
	<!-- Scroll Magic Debug -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.5/plugins/debug.addIndicators.min.js"></script>
	<!-- GreenSock TweenMax -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.18.2/TweenMax.min.js"></script>
	<!-- ScrollMagic GreenSock Plugin -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.5/plugins/animation.gsap.js"></script>
	<!-- Masonry -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/masonry/4.2.2/masonry.pkgd.min.js"></script>
	<!-- FANCYAPP -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.js" type="text/javascript" charset="utf-8"></script>
	<!-- Main script -->

	<script src='https://www.google.com/recaptcha/api.js'></script>

	<script src="{{asset('assets/js/main.js')}}" type="text/javascript" charset="utf-8"></script>

	<script>
		$(document).ready(function(){
			$("#startdate").datepicker();
			$("#enddate").datepicker();
		})
	</script>
	@stack('javascript')
</body>

</html>