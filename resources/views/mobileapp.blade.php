
@extends("layout.layout")
@section("contents")
	<!-- HERO -->
	<section class="hero mobileapp">
		<div class="container-fluid">	
				<div class="googleplaybutton" >
						<a  href="https://play.google.com/store/apps/details?id=com.cleaningapp" class="get">
							<img src="{{asset('assets/images/icon-google-black.png')}}" alt="">
						</a>
						<a href="https://itunes.apple.com/us/app/merge-transit-spot-market-app/id1434054240?mt=8" class="get">
							<img src="{{asset('assets/images/icon-appstore-black.png')}}" alt="">
						</a>
				</div>
			<div class="row">
				<div id="hero__wrapper" class="hero__wrapper mobileapp-page">
					<h1 class="f--white">
						Merge Transit
						<br>
						"The Spot Market Agency" ™
					</h1>
					<strong class="f--white">
						Services and Systems made for those behind the wheel,
						<br>
						not behind a desk.
					</strong>
					<div class="button__box">
						<a href="/service#organization" class="button button--dark-blue f--white">
							Organization
						</a>
						<a href="/service#optymization" class="button button--dark-blue f--white">
							Optimization
						</a>
						<a  href="https://play.google.com/store/apps/details?id=com.cleaningapp" class="get">
							<img src="{{asset('assets/images/icon-google-black.png')}}" alt="">
						</a>
						<a href="https://itunes.apple.com/us/app/merge-transit-spot-market-app/id1434054240?mt=8" class="get">
							<img src="{{asset('assets/images/icon-appstore-black.png')}}" alt="">
						</a>
					</div>
					
					<button class="scroll">
						<div class="ball">	

						</div>
						<span>SCROLL DOWN</span>
					</button>
				</div>
				
				<div id="app_video" class="embeded_video">
					{{-- <iframe width="560" height="315" src="https://www.youtube.com/embed/rdDAbEChwXc" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe> --}}
					<iframe width="560" height="315" src="https://www.youtube.com/embed/ZL9KWB__-e4" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
				</div>
				<div class="background"></div>
				
				
			</div>
		</div>
	</section>	
	<!-- /HERO -->

	<!-- info -->
	<section class="info">
		<div class="container">
			<div class="row">
				<div class="col">
					<h2 class="big f--black-blue">
						Join us
						<span>
							Join us
						</span>
					</h2>
					<a href="/contactus" class="f--black-blue">
						Join
					</a>
				</div>
			</div>	
		</div>
	</section>
	<!-- / info -->
	
		<!-- GET IT FROM  -->
		<section class="google">
			<div class="container">
				<div class="row">
					<div class="col-md-12 col-sm-12 getin">
						<h2 class="big f--white">
								 Download The App Today! 
                        <!--span class="small">
                            and make your job easier.
                        </span -->
						</h2>
						<a href="https://play.google.com/store/apps/details?id=com.cleaningapp" class="get">
							<img src="{{asset('assets/images/icon-google-black.png')}}" alt="">
						</a>
						<a href="https://itunes.apple.com/us/app/merge-transit-spot-market-app/id1434054240?mt=8" class="get">
							<img src="{{asset('assets/images/icon-appstore-black.png')}}" alt="">
						</a>
					</div>
				</div>
			</div>
		</section>
		<!-- /GET IT FROM -->
		
@endsection