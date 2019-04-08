
@extends("layout.layout")
@section("contents")
        	<!-- HERO -->
	<section class="hero">
		<div class="container-fluid">	
			<div class="row">
				<div id="hero__wrapper" class="hero__wrapper">
					<h1 class="f--white">
						Merge Transit
						<br>
						"The Spot Market Agency" ™
					</h1>
					<strong class="f--white">
						PROVIDING CARRIERS RESOURCES FOR THE SPOT MARKET
					</strong>
				</div>

				<div id="#phone" class="phone phone--1 phone--2">
					<img src="{{asset('assets/images/phone.png')}}" alt="phone">
					<img  class="gif" src="{{asset('assets/images/20180708_001056.gif')}}" alt="">
				</div>
				<div class="background"></div>
				<button class="scroll">
					<div class="ball">	

					</div>
					<span>SCROLL DOWN</span>
				</button>
			</div>
		</div>
	</section>	
	<!-- /HERO -->
	
	<!-- section full -->
	<section id="section-full-1" class="section-full">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<h2 class="big f--black-blue">
						Our Vision and Principles
 						<span class="shadow--vision">
 							our vision
 						</span>
					</h2>
				</div>
				<div class="col-md-5">
					<p>
						It is our belief that knowledge/data is power and systems and procedures are the foundation for business success. Our vision for our business is to create tools and provide resources that allow smaller companies to function as larger organizations without hurting their bottom-line. Having the proper resources and tools available, a smaller carrier can better predict performance and consistently outperform the market in a directed manner. Whether your goal is to be a single Owner Operator or build a fleet, we want to make sure we help provide specialized tools and resources that help monitor and produce optimized revenue results for your business.
					</p>
				</div>
				<div class="col-md-7">
					<p>
						Merge Transit - The Spot Market Agency, is dedicated to The Spot Market Carriers nationwide. We are a company that believes The Spot Market can provide for a long-term business, when procedures and systems are implemented to navigate the market rates to provide consistent results. We also believe that organizational procedures that allow for real-time monitoring of production is essential to the success of a company operating on the highs and lows of The Spot Market. This is why we have developed our business...To help the small individuals, who make up the large majority of trucking companies supplying our country today. Our business focuses on business reporting, file/document management and rate per mile optimization within The Spot Market. The value of a specialized partner is unmatched for what it can do for your business. With our tools and Carrier Agents, we will organize your operation as well as help provide day to day insight into The Spot Market trends as needed.
					</p>
				</div>
			</div>
		</div>
	</section>
	<!-- /section full -->

	<!-- principles -->
	<section class="section-full principles">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<h2 class="big f--black-blue principles">
						Core Principles
 						<span>
 							core principles
 						</span>
					</h2>
				</div>
				<div class="col-12">
					<ul class="f--dark-blue">
						<li>
							Be The Spot Market Experts For Every Carrier We Serve.
						</li>
						<li>
							Make a Meaningful Impact on Carriers Businesses Through 
							Organization and Optimization.
						</li>
						<li>
							Understand The Importance of The Individual Carrier Needs and 
							The Responsibility of Being a Dedicated Carrier Agent.
						</li>
						<li>
							Always Give Honest Feedback and Insight Internally 
							and Externally
						</li>
						<li>
							Always Be Planning
						</li>
					</ul>
				</div>
			</div>
		</div>
	</section>
	<!-- /principles -->

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