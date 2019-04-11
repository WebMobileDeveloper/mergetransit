@extends("layout.layout") 
@section("contents")
<!-- HERO -->
<section class="hero">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="hero-wrapper">
                    <div class="hero-content">
                        <div class="title f--white">
                            Merge Transit
                        </div>
                        <div class="title small f--white">
                            - The Spot Market Agency
                        </div>
                        <div class="sub-title f--white">
                            Services and Systems made for those behind the wheel, not behind a desk.
                        </div>
                        <div class="button__box">
                            {{--
                            <a href="/service#organization" class="button button--dark-blue f--white">
                                Organization
                            </a>
                            <a href="/service#optymization" class="button button--dark-blue f--white">
                                Optimization
                            </a> --}}
                            <a href="https://play.google.com/store/apps/details?id=com.cleaningapp" class="get">
                                <img src="{{asset(isset($white_header)? 'assets/images/icon-google-black.png' : 'assets/images/icon-google-white.png')}}" alt="">
                            </a>
                            <a href="https://itunes.apple.com/us/app/merge-transit-spot-market-app/id1434054240?mt=8" class="get">
                                <img src="{{asset(isset($white_header)? 'assets/images/icon-appstore-black.png' : 'assets/images/icon-appstore-white.png')}}" alt="">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <button class="scroll"><div class="ball"></div></button>
            <div class="background">
                <video autoplay muted loop id="myVideo">
                    <source src="{{asset('assets/images/backgrounds/back.mp4')}}" type="video/mp4">
                  </video>
            </div>
        </div>
    </div>
</section>
<!-- /HERO -->

<!-- cheap -->
<section id="cheap" class="cheap">
    <div class="container home-content">
        <div class="row">
            <div class="col-sm-12 ">
                <h2 class="big">
                    What is CHEAP FREIGHT?
                </h2>
                <p>
                    It is a unique question that requires knowing your own business numbers and not something another person can dictate for
                    anyone else!
                </p>
                <p>
                    Use the Merge Transit app and Organization Services to begin tracking YOUR businesses true Revenue and Cost to Operate, so
                    you can know what Cheap Freight is to YOU. Take advantage of our Optimization Services to get the most
                    profitable results out of the Spot Market!
                </p>

            </div>
        </div>
    </div>
</section>
<!-- /cheap -->

<!-- tracking -->
<section id="tracking" class="tracking">
    <div class="container home-content">
        <div class="row">
            <div class="col-sm-6 ">
                <h4 class="f--red">Features</h4>
                <h2 class="big f--white">
                    Detailed Load Tracking and Reporting
                </h2>
                <p class="f--white">
                    Have every load stored and organized for your business, allowing for easy reporting and document retrieval.
                </p>
            </div>
            <div class="col-sm-6 image"></div>
        </div>
    </div>
</section>
<!-- /tracking -->

<!-- know -->
<section id="know" class="know">
    <div class="container home-content">
        <div class="row">
            <div class="col-sm-6 image hide-mobile"></div>
            <div class="col-sm-6 ">
                <h4 class="f--red">Features</h4>
                <h2 class="big">
                    Know Your RPM, PPM and CPM in Real-Time
                </h2>
                <p>
                    Knowing your numbers is the start to good business decisions. Know all business metrics in real-time as you enter shipments
                    and cost.
                </p>
            </div>
            <div class="col-sm-6 image show-mobile"></div>
        </div>
    </div>
</section>
<!-- /know -->

<!-- custom -->
<section id="custom" class="custom">
    <div class="container home-content">
        <div class="row">
            <div class="col-sm-6 ">
                <h4 class="f--red">Features</h4>
                <h2 class="big f--white">
                    Custom Scanning Feature For All Paperwork and Receipts
                </h2>
                <p class="f--white">
                    No more using 3 or more apps for business management. We have it all in one place for you. Scan BOL's, Receipts, etc; without
                    needing to go anywhere else.
                </p>
            </div>
            <div class="col-sm-6 image"></div>
        </div>
    </div>
</section>
<!-- /custom -->

<!-- invoice -->
<section id="invoice" class="invoice">
    <div class="container home-content">
        <div class="row">
            <div class="col-sm-6 image hide-mobile"></div>
            <div class="col-sm-6">
                <h4 class="f--red">Features</h4>
                <h2 class="big">
                    Create and Send Invoices. Easily Track Days to Pay
                </h2>
                <p>
                    Create invoices with one click. You can easily generate invoices for your business and send with all supporting docs when
                    shipments are complete. Also, track days to pay once sent.
                </p>
            </div>
            <div class="col-sm-6 image show-mobile"></div>

        </div>
    </div>
</section>
<!-- /invoice -->

<!-- direction -->
<section id="direction" class="direction">
    <div class="container home-content">
        <div class="row">
            <div class="col-sm-6 image hide-mobile"></div>
            <div class="col-sm-6 ">
                <h4 class="f--red">Features</h4>
                <h2 class="big f--white">
                    Built-In Directions and Map For Each Shipment
                </h2>
                <p class="f--white">
                    Have a shipment booked and entered for today? Just click Get Directions and get turn by turn navigation to your shippers,
                    consignee's or for get route plan before arriving.
                </p>
            </div>
            <div class="col-sm-6 image show-mobile"></div>
        </div>
    </div>
</section>
<!-- /direction -->

<!-- service -->
<section id="service" class="service">
    <div class="container home-content">
        <div class="row">
            <div class="col-sm-12 ">
                <h2 class="big f--dark-blue">
                    Full Service Dispatch - Optimization Program
                </h2>
            </div>
        </div>
    </div>
</section>
<!-- /service -->

{{-- service-carousel --}}
<section id="service-carousel" class="service-carousel">
    <div class="container home-content">
        <div class="row">
            <div class="col-sm-12">
                <div id="carousel-container" class="carousel slide" data-ride="carousel">
                    <ul class="carousel-indicators">
                        <li data-target="#carousel-container" data-slide-to="0" class="active"></li>
                        <li data-target="#carousel-container" data-slide-to="1"></li>
                        <li data-target="#carousel-container" data-slide-to="2"></li>
                        <li data-target="#carousel-container" data-slide-to="3"></li>
                        <li data-target="#carousel-container" data-slide-to="4"></li>
                    </ul>
                    <div class="carousel-inner">
                        <div class="carousel-item item1 active ">
                            <div class="row">
                                <div class="col-sm-6 ">
                                    <h2 class="small">
                                        Dedicated Carrier Agent to perform all Dispatch Duties
                                    </h2>
                                    {{-- <p>
                                        No more using 3 or more apps for business management. We have it all in one place for you. Scan BOL's, Receipts, etc; without
                                        needing to go anywhere else.
                                    </p> --}}
                                </div>
                                <div class="col-sm-6 image"></div>
                            </div>
                        </div>
                        <div class="carousel-item item2">
                            <div class="row">
                                <div class="col-sm-6 ">
                                    <h2 class="small">
                                        Optimized data backed dispatching using SONAR, RateView, etc.
                                    </h2>
                                    {{-- <p>
                                        No more using 3 or more apps for business management. We have it all in one place for you. Scan BOL's, Receipts, etc; without
                                        needing to go anywhere else.
                                    </p> --}}
                                </div>
                                <div class="col-sm-6 image"></div>
                            </div>
                        </div>
                        <div class="carousel-item item3">
                            <div class="row">
                                <div class="col-sm-6 ">
                                    <h2 class="small">
                                        Improved total mile RPM through strategic routing
                                    </h2>
                                    {{-- <p>
                                        No more using 3 or more apps for business management. We have it all in one place for you. Scan BOL's, Receipts, etc; without
                                        needing to go anywhere else.
                                    </p> --}}
                                </div>
                                <div class="col-sm-6 image"></div>
                            </div>
                        </div>
                        <div class="carousel-item item4">
                            <div class="row">
                                <div class="col-sm-6 ">
                                    <h2 class="small">
                                        Handle negotations and broker communication under carrier guidance
                                    </h2>
                                    {{-- <p>
                                        No more using 3 or more apps for business management. We have it all in one place for you. Scan BOL's, Receipts, etc; without
                                        needing to go anywhere else.
                                    </p> --}}
                                </div>
                                <div class="col-sm-6 image"></div>
                            </div>
                        </div>
                        <div class="carousel-item item5">
                            <div class="row">
                                <div class="col-sm-6 ">
                                    <h2 class="small">
                                        Will handle all paperwork needed for your business and input into Merge TMS.
                                    </h2>
                                    {{-- <p>
                                        No more using 3 or more apps for business management. We have it all in one place for you. Scan BOL's, Receipts, etc; without
                                        needing to go anywhere else.
                                    </p> --}}
                                </div>
                                <div class="col-sm-6 image"></div>
                            </div>
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#carousel-container" data-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </a>
                    <a class="carousel-control-next" href="#carousel-container" data-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </a>
                </div>
            </div>
        </div>

    </div>
</section>
{{-- /service-carousel --}}
@endsection