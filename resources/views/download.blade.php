@extends("layout.layout") 
@section("contents")
<!-- download -->
<section id="download" class="download">
	<div class="container home-content">
		<div class="row">
			<div class="col-sm-12">
				<h1 class="big">download</h1>
				<h2 class="small">Get app from Google Play & APP Store <br>and make your job easier.</h2>
				<a href="https://play.google.com/store/apps/details?id=com.cleaningapp" class="get">
						<img src="{{asset('assets/images/icon-google-black.png')}}" alt="">
					</a>
				<a href="https://itunes.apple.com/us/app/merge-transit-spot-market-app/id1434054240?mt=8" class="get">
						<img src="{{asset('assets/images/icon-appstore-black.png')}}" alt="">
					</a>
			</div>
			<div class="col-sm-12 image">
				<img src="{{asset('assets/images/content/download.jpg')}}" alt="">
			</div>
		</div>
	</div>
</section>
<!-- /download -->

@endsection