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
                            <img src="{{asset('assets/images/en_badge_web_generic.png')}}" alt="">
                        </a>
                        <a  href="https://itunes.apple.com/us/app/merge-transit-spot-market-app/id1434054240?mt=8" class="get">
                            <img src="{{asset('assets/images/AppStoreIcon.png')}}" alt="">
                        </a>
                    </div>
                    
                    <button class="scroll">
                        <div class="ball">	

                        </div>
                        <span>SCROLL DOWN</span>
                    </button>
                </div>
                <div id="#phone" class="phone phone--1">
                    <img src="{{asset('assets/images/phone.png')}}" alt="phone">
                    <img  class="gif" src="{{asset('assets/images/20180708_001056.gif')}}" alt="">
                </div>
                <div class="background"></div>
            </div>
        </div>
    </section>	
    <!-- /HERO -->
    
    <!-- About --> 
    <section id="about" class="about">	
        <div class="container">	
            <div class="row">	
                <div class="col-sm-12 content--1">	
                    <h2 class="big f--blue" data-title="">
                        What Is A Spot Market Agency?
                        <span class="shadow--1">
                            what is?
                        </span>
                    </h2>
                    <p>	
                        A Spot Market Agency is a single resource center specializing in market insight, business organization and rate optimization, dedicated to carriers in the Truckload Spot Market.
                    </p>	
                </div>
                <div id="content2" class=" col-sm-6 half content--2">	
                    <h3 class="f--blue" data-title="who is?">
                        Who is The Spot Market Agency℠?
                        <span class="shadow--2">
                            who is?
                        </span>
                    </h3>
                    <p>	
                        Merge Transit is “The Spot Market Agency”℠. We are a 100% Carrier Based Agency, dedicated to small and midsize carrier companies nationwide who operate within the Truckload Spot Market. We provide the tools and resources needed to optimize a carriers operations. Our Agency employs trained Agent Admins, who assist in helping Organize a carriers back office allowing them to always have insight into details around each shipment and a detailed overview of how their business is performing. We also employ trained dedicated Carrier Agents who Optimize a carriers rate per mile when navigating the Spot Market, by acting as a dedicated resource for booking and routing of their assets, under their guidelines. 
                    </p>
                </div>
                <div class="col-sm-6">	
                    <img src="{{asset('assets/images/logo-white.png')}}" alt="">
                </div>	
                <div class="col-sm-6 phone">	
                    <img src="{{asset('assets/images/phone-black.png')}}" alt="">
                </div>
                <div id="content3" class="col-sm-6 half content--3 ">	
                    <h3 class="f--blue" data-title="Technology">
                        Our Technology For Carriers...
                        <span class="shadow--3">
                            technology
                        </span>
                    </h3>
                    <p>	
                        All of our services are built on our Mobile and Web systems. These systems allow a carrier to have real time complete transparency into their business. Storing all paperwork, shipment details and producing detailed reports that breakdown business performance in real-time. We keep you the owner on top of all the important numbers related to revenue generation at all times. You can access and add to your shipment data any time through our Mobile Application or Website and all shipments are put in by our trained Agent Admins, giving you up to the minute access. 
                    </p>
                </div>
            </div>
        </div>
    </section>
    <!-- /About -->

    <!-- STEPS -->
    <section id="steps" class="steps">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="big f--blue">
                        All Your Dispatch Needs and Business Numbers Made Simple!
                        <span>
                            business
                        </span>
                    </h2>
                </div>
                <div class="col-6">
                    <div class="step step--1">
                        <h3 class="f--blue">
                            Monitor Your Busines Numbers and
                            Have All Dispatch Details
                            <span>
                                01
                            </span>
                        </h3>
                        <p>
                            Pull custom business reports for your business, so you can always know where your numbers are. Manage dispatching and reporting easily with our Agent Admins inputting your shipment details and using our tools to stay organized. 
                        </p>
                    </div>
                    <div class="step step--2">
                        <h3 class="f--blue">
                            Register On Mobile Device
                            <span>
                                02
                            </span>
                        </h3>
                        <p>
                            Once account is created, you will be contacted by a Carrier Agent to setup your account services.
                            <br>
                            <br>
                            Once your account is setup for one of our service plans, you will have access to our Agents and Agent Admins to begin data entry for you as you book your loads. Keeping you organized without the hassle.
                        </p>
                    </div>
                    <div class="step step--3">
                        <h3 class="f--blue">
                            Send Rate Confirmations Directly
                            to Merge For Input
                            <span>
                                03
                            </span>
                        </h3>
                        <p>
                            Upon receiving Rate Confirmations, our Agents and Admins will immediatley input order and attach your rate confirmation for use.
                        </p>
                    </div>
                    <div class="step step--4">
                        <h3 class="f--blue">
                            Carriers Can Access All Shipment Info 
                            Past, Present or Future
                            <span>
                                04
                            </span>
                        </h3>
                        <p>
                            Drivers and Carriers can easily take pictures or upload documents to each order, from their phone, allowing for easy storage of BOL’s, Pictures of Shipment, Rate Confirmations, Lumper Receipts and anything else they need to keep filed with that shipment entry for sending or saving.
                        </p>
                    </div>
                    <div class="step step--5">
                        <h3 class="f--blue">
                            Calendar - Easily Access Shipments 
                            and Details
                            <span>
                                05
                            </span>
                        </h3>
                        <p>
                            Our calendar view option makes it easy for carriers and drivers to see what shipments are assigned for each day past, present or future. Easily access your dispatch and add documents or photos as needed.
                        </p>
                    </div>
                </div>
                <div class="col-6 sticky">
                    <img src="{{asset('assets/images/phone.png')}}" alt="phone">
                    <div id="stickyPhone" class="sticky__content">
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /STEPS -->

    <!-- Testimonials -->
    <section class="testimonials">
        <div class="container">
            <h2 class="big f--blue">
                Testimonials
                <span>
                    testimonials
                </span>
            </h2>
            <div class="row">
                <div id="testimonials1" class="testimonials__item">
                        <img src="{{asset('assets/images/testimonials.png')}}" alt="">
                        <p>
                            Custom Logistics (one truck, wife dispatched) started using Merge Transit in May of 2018 (my wife is now a 911 dispatcher) after 8 years of successfully running 100% spot market freight.
                        </p>
                        <p>
                            I find Justin's business approach and way of thinking compatible with mine. His posts related to business and trucking, in particular, are well thought out and articulated.
                        </p>
                        <p>
                            Frank did an outstanding job in his first month dispatching my truck. He exceeded the revenue goals we set while working with some unusual restrictions on having the truck in particular places at specific times so I could meet family obligations.
                        </p>
                        <p>
                            I will continue to use Merge Transit looking forward to a profitable partnership. I highly recommend their services.	
                        </p>
                        <strong class="f--blue">
                            Robert Codding
                            <br>
                            Custom Logistics
                            <br>
                            Florida
                        </strong>
                </div>
                <div class="testimonials__item">
                    <img src="{{asset('assets/images/testimonials.png')}}" alt="">
                    <p>
                        Sooner Trucking has been using Merge for a year and we are pleased with Frank our Carrier Agent and will highly recommend him. He answers us day or night and goes above and beyond for our Company! All Merge staff has been pleasant and easy to conduct business with.	
                    </p>	
                    <strong class="f--blue">
                        Phillip Woods
                        <br>
                        Sooner Trucking
                        <br>
                        Oklahoma
                    </strong>
                </div>
                <div class="testimonials__item">
                    <img src="{{asset('assets/images/testimonials.png')}}" alt="">
                    <p>
                        Overdrive Transport, LLC would like to send a big thank you to Merge Transit for an incredible 2017. A huge, huge thank you for being an Exceptional dispatcher but also for being a great people to work with. We would also like to thank, Merge Transit Agents for all the advice given to us along the way. Here's to a bigger and better 2018!
                    </p>					
                    <strong class="f--blue">
                        Andrew Gonzales
                        <br>
                        OverDrive Transport LLC
                        <br>
                        Texas
                    </strong>
                </div>
                <div class="testimonials__item">
                    <img src="{{asset('assets/images/testimonials.png')}}" alt="">
                    <p>
                        Merge has been great from the start and anyone not using them is missing out. They do an awesome job at finding great rates, low miles, with minimal dead head. I always feel confident that I am getting the best work and nothing is left on the table 	
                    </p>
                        
                    <strong class="f--blue">
                        Matthew McFee
                        <br>
                        McFee Trucking
                        <br>
                        Illionois
                        <br>
                    </strong>
                </div>
            </div>
        </div>
    </section>
    <!-- /Testimonials -->

    <!-- info -->
    <section class="info">
        <div class="container">
            <div class="row">
                <div class="col">
                    <h2 class="big f--blue">
                        Join us
                        <span>
                            Join us
                        </span>
                    </h2>
                    <a href="/contactus" class="f--blue">
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
                        Get app from Google Play & APP Store
                        <span class="small">
                            and make your job easier.
                        </span>
                    </h2>
                    <a href="https://play.google.com/store/apps/details?id=com.cleaningapp" class="get">
                        <img src="{{asset('assets/images/en_badge_web_generic.png')}}" alt="">
                    </a>
                    <a href="https://itunes.apple.com/us/app/merge-transit-spot-market-app/id1434054240?mt=8" class="get">
                        <img src="{{asset('assets/images/AppStoreIcon.png')}}" alt="">
                    </a>
                </div>
            </div>
        </div>
    </section>
    <!-- /GET IT FROM -->
        
@endsection
