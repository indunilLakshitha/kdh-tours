@extends('fe')

@section('content')
    <!-- Banner Section Start -->
    <section class="rid-banner-style-1">
        <div class="container">
            <div class="row">
                <div class="col-sm-7 col-md-7 offset-sm-5 offset-md-5">
                    <div class="banner-info">
                        <h3 class="font-size-65 primary-color">{{__('fe.vehicle')}}</h3>
                        <h1 class="text-white">For Rent <span class="font-size-130">150$/Day</span></h1>
                        <a href="contact.html" class="btn">Book Now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Banner Section End -->

    <!-- Filter Section Start -->
    <section class="rid-filter-1">
        <div class="container">
            <h2 class="visually-hidden">Rental{{__('fe.vehicle')}} Search</h2>
            <div class="filter-box">
                <div class="row">
                    <div class="col-md-3 align-items-center align-items-center d-flex">
                        <div class="rid-dropdown">
                            <label for="bike-brand" class="font-size-17 d-block">{{__('fe.vehicle')}} Brand</label>
                            <select class="rid-bike-brand" id="bike-brand" name="bike-brand">
                                <option value="0">Any Brand</option>
                                <option value="1">HIGH ROOF</option>
                                <option value="2">FLAT ROOF</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3 align-items-center align-items-cente d-flex">
                        <div class="rid-dropdown">
                            <label for="bike-type" class="font-size-17 d-block">{{__('fe.vehicle')}} Type</label>
                            <select class="rid-bike-type" id="bike-type" name="bike-type">
                                <option value="0">Any Brand</option>
                                <option value="1">AC</option>
                                <option value="2">NON A/C</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3 align-items-center align-items-center d-flex">
                        <div class="rid-dropdown">
                            {{-- <label for="bike-price" class="font-size-17 d-block">{{__('fe.vehicle')}} Price</label>
                            <select class="rid-bike-price" id="bike-price" name="bike-price">
                                <option value="volvo">Any Brand</option>
                                <option value="saab">Saab</option>
                                <option value="fiat">Fiat</option>
                                <option value="audi">Audi</option>
                            </select> --}}
                        </div>
                    </div>

                    <div class="col-md-3 align-self-end">
                        <button type="submit" class="btn">SEARCH FOR {{__('fe.vehicle')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Filter Section End -->

    <!--  How-it-work Section Start-->
    <div class="rid-how-it-work sec-space">
        <div class="container">
            <h2 class="text-center">How it Works?</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="rid-info-box text-center">
                        <i class="flaticon-magnifying-glass-search"></i>
                        <h4>Find the Right{{__('fe.vehicle')}}</h4>
                        <p class="text-secondary">Repellendus facilisi ultrices ad culpa qui sit adipiscing! Quidem
                            bibendum quisque. Tempus, maxime repudianda.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="rid-info-box text-center">
                        <i class="flaticon-booking"></i>
                        <h4>Book it Online</h4>
                        <p class="text-secondary">Sem eget occaecati viverra, corporis aliquam iste laboriosam corrupti
                            tristique. Occaecat tempor! Torquent malesuada.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="rid-info-box text-center">
                        <i class="flaticon-bike"></i>
                        <h4>Enjoy your Ride</h4>
                        <p class="text-secondary">Blandit modi porro? Sequi sem pharetra duis rhoncus amet ipsum
                            faucibus iusto, labore nisl, porttitor ultrices.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--  How-it-work Section End-->

    <!--  Skill Section Start -->
    <div class="rid-skill sec-space">
        <div class="row">
            <div class="col-lg-6 col-md-12 bg-bike">
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="skill-info">
                    <div class="col-12">
                        <div class="skill-info-text">
                            <h2 class="text-white">World Top 100{{__('fe.vehicle')}} For You</h2>
                            <p class="text-white">Fermentum assumenda, nostrud semper tellus reprehenderit, auctor
                                aliquip officia, adipiscing! Sapien consequuntur consectetuer facere potenti?
                                Incididuntmontes praesent, qui. Venenatis, consequuntur nobis pede.</p>
                            <p class="text-white"> Harum incidunt mollis natus dui quas, massa irure cursus odit
                                molestias nemo a cursus. Metus. Mollit irure posuere eget, sociis, aliquip, ipsum tempus
                                turpis. Mollitia, sunt, egestas montes! Sollicitudin! Hendrerit rhoncu.
                            </p>
                        </div>
                        <!-- Progressbar -->
                        <div class="rid-progressbar d-flex">
                            <div class="circlechart" data-percentage="90">
                                <h2>KDH High Roof</h2>
                            </div>

                            <div class="circlechart" data-percentage="80">
                                <h2>KDH</h2>
                            </div>

                            <div class="circlechart" data-percentage="60">
                                <h2>Van</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--  Skill Section End -->

    <!-- Rentals Section Start -->
    <div class="rid-rentals-1 sec-space">
        <div class="container">

            <h2 class="text-center">Most Popular Destinations</h2>
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="rentals-single">
                        <div class="rentals-single-img">
                            <a href="rental-details.html">
                                <img src="{{ asset('front/images/destination/destination_01.jpg') }}"
                                    alt="GTC Origine{{__('fe.vehicle')}}">
                            </a>
                        </div>
                        <div class="rentals-single-info">
                            <div class="rid-row d-flex justify-content-between align-items-center">
                                <div class="rentals-single-info-title">
                                    <a href="rental-details.html">
                                        <h4 class="mb-0">GTC Origine{{__('fe.vehicle')}}</h4>
                                    </a>
                                    <i class="icofont-location-pin"></i>
                                    <span class="font-size-16">London, UK</span>
                                </div>
                                <div class="rentals-single-info-price">
                                    <h4 class="mb-0">$100</h4>
                                    <span class="font-size-16">DAY</span>
                                </div>
                            </div>
                            <div class="rid-row d-flex justify-content-between align-items-center">
                                <div class="rentals-single-info-btn">
                                    <a href="contact.html" class="btn">Book Now</a>
                                </div>
                                <div class="rentals-single-info-rating">
                                    <span class="font-size-14">1555 Reviews</span>
                                    <i class="icofont-star"></i>
                                    <span class="font-size-16-sb">4.9</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="rentals-single">
                        <div class="rentals-single-img">
                            <a href="rental-details.html">
                                <img src="{{ asset('front/images/destination/destination_02.jpg') }}"
                                    alt="Tesla Electric{{__('fe.vehicle')}}">
                            </a>
                        </div>
                        <div class="rentals-single-info">
                            <div class="rid-row d-flex justify-content-between align-items-center">
                                <div class="rentals-single-info-title">
                                    <a href="rental-details.html">
                                        <h4 class="mb-0">Tesla Electric{{__('fe.vehicle')}}</h4>
                                    </a>
                                    <i class="icofont-location-pin"></i>
                                    <span class="font-size-16">New York, USA</span>
                                </div>
                                <div class="rentals-single-info-price">
                                    <h4 class="mb-0">$155</h4>
                                    <span class="font-size-16">DAY</span>
                                </div>
                            </div>
                            <div class="rid-row d-flex justify-content-between align-items-center">
                                <div class="rentals-single-info-btn">
                                    <a href="contact.html" class="btn">Book Now</a>
                                </div>
                                <div class="rentals-single-info-rating">
                                    <span class="font-size-14">991 Reviews</span>
                                    <i class="icofont-star"></i>
                                    <span class="font-size-16-sb">5.0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="rentals-single">
                        <div class="rentals-single-img">
                            <a href="rental-details.html">
                                <img src="{{ asset('front/images/destination/destination_03.jpg') }}"
                                    alt="Men's Race{{__('fe.vehicle')}}">
                            </a>
                        </div>
                        <div class="rentals-single-info">
                            <div class="rid-row d-flex justify-content-between align-items-center">
                                <div class="rentals-single-info-title">
                                    <a href="rental-details.html">
                                        <h4 class="mb-0">Men's Race{{__('fe.vehicle')}}</h4>
                                    </a>
                                    <i class="icofont-location-pin"></i>
                                    <span class="font-size-16">San Francisco ,California, USA</span>
                                </div>
                                <div class="rentals-single-info-price">
                                    <h4 class="mb-0">$90</h4>
                                    <span class="font-size-16">DAY</span>
                                </div>
                            </div>
                            <div class="rid-row d-flex justify-content-between align-items-center">
                                <div class="rentals-single-info-btn">
                                    <a href="contact.html" class="btn">Book Now</a>
                                </div>
                                <div class="rentals-single-info-rating">
                                    <span class="font-size-14">1254 Reviews</span>
                                    <i class="icofont-star"></i>
                                    <span class="font-size-16-sb">4.2</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="rentals-single">
                        <div class="rentals-single-img">
                            <a href="rental-details.html">
                                <img src="{{ asset('front/images/destination/destination_04.jpg') }}"
                                    alt="BTC Motor{{__('fe.vehicle')}}">
                            </a>
                        </div>
                        <div class="rentals-single-info">
                            <div class="rid-row d-flex justify-content-between align-items-center">
                                <div class="rentals-single-info-title">
                                    <a href="rental-details.html">
                                        <h4 class="mb-0">BTC Motor{{__('fe.vehicle')}}</h4>
                                    </a>
                                    <i class="icofont-location-pin"></i>
                                    <span class="font-size-16">Moscow, Russia</span>
                                </div>
                                <div class="rentals-single-info-price">
                                    <h4 class="mb-0">$100</h4>
                                    <span class="font-size-16">DAY</span>
                                </div>
                            </div>
                            <div class="rid-row d-flex justify-content-between align-items-center">
                                <div class="rentals-single-info-btn">
                                    <a href="contact.html" class="btn">Book Now</a>
                                </div>
                                <div class="rentals-single-info-rating">
                                    <span class="font-size-14">1555 Reviews</span>
                                    <i class="icofont-star"></i>
                                    <span class="font-size-16-sb">5.0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="rentals-single">
                        <div class="rentals-single-img">
                            <a href="rental-details.html">
                                <img src="{{ asset('front/images/destination/destination_05.jpg') }}"
                                    alt="Yamaha 155cc{{__('fe.vehicle')}}">
                            </a>
                        </div>
                        <div class="rentals-single-info">
                            <div class="rid-row d-flex justify-content-between align-items-center">
                                <div class="rentals-single-info-title">
                                    <a href="rental-details.html">
                                        <h4 class="mb-0">Yamaha 155cc{{__('fe.vehicle')}}</h4>
                                    </a>
                                    <i class="icofont-location-pin"></i>
                                    <span class="font-size-16">Barcelona, Spain</span>
                                </div>
                                <div class="rentals-single-info-price">
                                    <h4 class="mb-0">$750</h4>
                                    <span class="font-size-16">DAY</span>
                                </div>
                            </div>
                            <div class="rid-row d-flex justify-content-between align-items-center">
                                <div class="rentals-single-info-btn">
                                    <a href="contact.html" class="btn">Book Now</a>
                                </div>
                                <div class="rentals-single-info-rating">
                                    <span class="font-size-14">853 Reviews</span>
                                    <i class="icofont-star"></i>
                                    <span class="font-size-16-sb">5.0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="rentals-single">
                        <div class="rentals-single-img">
                            <a href="rental-details.html">
                                <img src="{{ asset('front/images/destination/destination_06.jpg') }}"
                                    alt="Ladies Scooter">
                            </a>
                        </div>
                        <div class="rentals-single-info">
                            <div class="rid-row d-flex justify-content-between align-items-center">
                                <div class="rentals-single-info-title">
                                    <a href="rental-details.html">
                                        <h4 class="mb-0">Ladies scooter</h4>
                                    </a>
                                    <i class="icofont-location-pin"></i>
                                    <span class="font-size-16">Amsterdam, Netherlands</span>
                                </div>
                                <div class="rentals-single-info-price">
                                    <h4 class="mb-0">$155</h4>
                                    <span class="font-size-16">DAY</span>
                                </div>
                            </div>
                            <div class="rid-row d-flex justify-content-between align-items-center">
                                <div class="rentals-single-info-btn">
                                    <a href="contact.html" class="btn">Book Now</a>
                                </div>
                                <div class="rentals-single-info-rating">
                                    <span class="font-size-14">1214 Reviews</span>
                                    <i class="icofont-star"></i>
                                    <span class="font-size-16-sb">4.7</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- Rentals Section End -->

    <!-- Testimonial Section Start -->
    <section class="testimonial-section sec-space">
        <div class="container">
            <h2 class="text-center">Happy Customer</h2>
        </div>
        <div class="rid-testimonial">
            <div class="container">
                <div class="rid-slider-one-col">
                    <div class="rid-single-testimonial">
                        <div class="row">
                            <div class="col-lg-5 col-md-5">
                                <div class="testimonial-photo">
                                    <img src="{{ asset('front/images/testimonial/testimonial-01.jpg') }}"
                                        alt="Alaina Gillespy">
                                </div>
                            </div>

                            <div class="col-lg-7 col-md-7 align-items-center">
                                <div class="testimonial-info">
                                    <h4 class="testimonial-name">Backey Tompson</h4>
                                    <p class="testimonial-designation">Ceo of Atardam</p>

                                    <p class="rid-test-desc">Maboriosam in a nesciung eget magna dapibus disting
                                        tloctio in the find it per odiy.Maboriosam in a nesciung eget magna dapibus
                                        disting tloctio in the find it per odiy.Maboriosam in a tloctio in the find it
                                        per odiy.</p>
                                    <div class="rid-rating">
                                        <i class="icofont-star"></i>
                                        <i class="icofont-star"></i>
                                        <i class="icofont-star"></i>
                                        <i class="icofont-star"></i>
                                        <i class="icofont-star"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="rid-single-testimonial">
                        <div class="row">
                            <div class="col-lg-5 col-md-5">
                                <div class="testimonial-photo">
                                    <img src="{{ asset('front/images/testimonial/testimonial-02.jpg') }}"
                                        alt="Lucas Stuart">
                                </div>
                            </div>

                            <div class="col-lg-7 col-md-7">
                                <div class="testimonial-info">
                                    <h4 class="testimonial-name">Lucas Stuart</h4>
                                    <p class="testimonial-designation">Director</p>

                                    <p class="rid-test-desc">Lorem ipsum dolor sit amet, ducimus eveniet explicabo
                                        consectetur adipisicing elit. Dolorem ducimus eveniet explicabo in ipsum
                                        necessitatibus, nisi numquam provident quas quos reiciendis saepe sequi sunt.
                                        Adipisci doloribus.</p>
                                    <div class="rid-rating">
                                        <i class="icofont-star"></i>
                                        <i class="icofont-star"></i>
                                        <i class="icofont-star"></i>
                                        <i class="icofont-star"></i>
                                        <i class="icofont-star"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="rid-single-testimonial">
                        <div class="row">
                            <div class="col-lg-5 col-md-5">
                                <div class="testimonial-photo">
                                    <img src="{{ asset('front/images/testimonial/testimonial-03.jpg') }}"
                                        alt="Himika Rex">
                                </div>
                            </div>

                            <div class="col-lg-7 col-md-7">
                                <div class="testimonial-info">
                                    <h4 class="testimonial-name">Himika Rex</h4>
                                    <p class="testimonial-designation">Manager</p>

                                    <p class="rid-test-desc">Maboriosam in a nesciung eget magna dapibus disting
                                        tloctio in the find it per odiy.Maboriosam in a nesciung eget magna dapibus
                                        disting tloctio in the find it per odiy.Maboriosam in a tloctio in the find it
                                        per odiy.</p>
                                    <div class="rid-rating">
                                        <i class="icofont-star"></i>
                                        <i class="icofont-star"></i>
                                        <i class="icofont-star"></i>
                                        <i class="icofont-star"></i>
                                        <i class="icofont-star"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Testimonial Section End -->

    <!-- Our Gallery Section Start -->
    <section class="rid-gallery sec-space">
        <div class="container">
            <h2 class="text-center">Our Gallery</h2>
            <!-- Gallery Start -->
            <div class="row">
                <div class="col-lg-4 col-md-6" id="image-popups">
                    <div class="rid-gallery-single popup-gallery">
                        <a href="{{ asset('front/images/gallery/gallery-1.jpg') }}"
                            class="gallery-img rid-popup-link" data-effect="mfp-zoom-in">
                            <img src="{{ asset('front/images/gallery/gallery-1.jpg') }}" alt="Leox Electic{{__('fe.vehicle')}}">
                        </a>
                    </div>

                    <div class="rid-gallery-single popup-gallery">
                        <a href="{{ asset('front/images/gallery/gallery-4.jpg') }}"
                            class="gallery-img rid-popup-link" data-effect="mfp-zoom-in">
                            <img src="{{ asset('front/images/gallery/gallery-4.jpg') }}" alt="Women Yellow Scooter">
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="rid-gallery-single popup-gallery">
                        <a href="{{ asset('front/images/gallery/gallery-5.jpg') }}"
                            class="gallery-img rid-popup-link" data-effect="mfp-zoom-in">
                            <img src="{{ asset('front/images/gallery/gallery-5.jpg') }}" alt="Women Yellow Scooter">
                        </a>
                    </div>

                    <div class="rid-gallery-single popup-gallery">
                        <a href="{{ asset('front/images/gallery/gallery-2.jpg') }}"
                            class="gallery-img rid-popup-link" data-effect="mfp-zoom-in">
                            <img src="{{ asset('front/images/gallery/gallery-2.jpg') }}" alt="Leox Electic{{__('fe.vehicle')}}">
                        </a>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="rid-gallery-single popup-gallery">
                        <a href="{{ asset('front/images/gallery/gallery-3.jpg') }}"
                            class="gallery-img rid-popup-link" data-effect="mfp-zoom-in">
                            <img src="{{ asset('front/images/gallery/gallery-3.jpg') }}" alt="Leox Electic{{__('fe.vehicle')}}">
                        </a>
                    </div>

                    <div class="rid-gallery-single popup-gallery">
                        <a href="{{ asset('front/images/gallery/gallery-6.jpg') }}"
                            class="gallery-img rid-popup-link" data-effect="mfp-zoom-in">
                            <img src="{{ asset('front/images/gallery/gallery-6.jpg') }}" alt="Women Yellow Scooter">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Our Gallery Section End -->

    <!-- Video Section Start -->
    <section class="rid-video-1">
        <div class="video-banner">
            <div class="container">
                <h2 class="visually-hidden">Video</h2>
                <div class="rid-video-play text-center">
                    <a class="popup-youtube" href="https://www.youtube.com/watch?v=FdrNFEbcsRs">
                        <i class="flaticon-play"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <!-- Video Section End -->

    <!-- Our{{__('fe.vehicle')}} Section Start -->
    <section class="rid-our-bike sec-space">
        <div class="container">
            <h2 class="text-center">Why Our{{__('fe.vehicle')}}s?</h2>
            <div class="row">
                <div class="col-lg-6">
                    <div class="rid-info-box-2 d-flex align-items-center">
                        <div class="rid-info-box-icon ">
                            <div class="rid-info-box-icon position-relative">
                                <i class="flaticon-history"></i>
                            </div>
                        </div>
                        <div class="rid-info-box-text">
                            <h4>Immediate Booking</h4>
                            <p>Our calendars are updated in real time. Select your dates and book online.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="rid-info-box-2 d-flex align-items-center">
                        <div class="rid-info-box-icon ">
                            <div class="rid-info-box-icon position-relative">
                                <i class="flaticon-globe"></i>
                            </div>
                        </div>
                        <div class="rid-info-box-text">
                            <h4>Online Booking</h4>
                            <p>Have your plans changed? Don't worry, you can change the date or cancel your reservation
                                without paying any additional fees.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="rid-info-box-2 d-flex align-items-center">
                        <div class="rid-info-box-icon ">
                            <div class="rid-info-box-icon position-relative">
                                <i class="flaticon-refund"></i>
                            </div>
                        </div>
                        <div class="rid-info-box-text">
                            <h4>Refundable Booking</h4>
                            <p>You will not find the same vehicle for the same dates at a better price, guaranteed!</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="rid-info-box-2 d-flex align-items-center">
                        <div class="rid-info-box-icon ">
                            <div class="rid-info-box-icon position-relative">
                                <i class="flaticon-call-center"></i>
                            </div>
                        </div>
                        <div class="rid-info-box-text">
                            <h4>24/7 Assistance Support</h4>
                            <p>Our multilingual customer service team is here to help you have a great vacation.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Our{{__('fe.vehicle')}} Section End -->

    <!-- FAQ Section Start -->
    <section class="rid-faq sec-space">
        <div class="container">
            <!-- FAQ Title -->
            <h2 class="text-center">Frequenly Ask & Questions.</h2>
            <!-- FAQ  -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="rid-accordion" id="faqWrap1">
                        <div class="accordion-item position-relative">
                            <h6 class="accordion-header" id="headingOne">
                                <button class="rid-accordion-btn" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    How can i select a {{__('fe.vehicle')}} rent?
                                </button>
                            </h6>
                            <div id="collapseOne" class="accordion-collapse collapse show"
                                aria-labelledby="headingOne" data-bs-parent="#faqWrap1">
                                <div class="accordion-body">
                                    <p>Eu do, laudantium suspendisse purus dolore, suscipit aperiam voluptates assumenda
                                        adipisci, voluptas aspernatur?</p>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item position-relative">
                            <h6 class="accordion-header" id="headingTwo">
                                <button class="rid-accordion-btn collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    How to reserved a {{__('fe.vehicle')}} here?
                                </button>
                            </h6>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                                data-bs-parent="#faqWrap1">
                                <div class="accordion-body">
                                    <p>Eu do, laudantium suspendisse purus dolore, suscipit aperiam voluptates assumenda
                                        adipisci, voluptas aspernatur?</p>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item position-relative">
                            <h6 class="accordion-header" id="headingThree">
                                <button class="rid-accordion-btn collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseThree" aria-expanded="false"
                                    aria-controls="collapseThree">
                                    How can i drop the rental {{__('fe.vehicle')}}?
                                </button>
                            </h6>
                            <div id="collapseThree" class="accordion-collapse collapse"
                                aria-labelledby="headingThree" data-bs-parent="#faqWrap1">
                                <div class="accordion-body">
                                    <p>Eu do, laudantium suspendisse purus dolore, suscipit aperiam voluptates assumenda
                                        adipisci, voluptas aspernatur?</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="rid-accordion" id="faqWrap2">
                        <div class="accordion-item position-relative">
                            <h6 class="accordion-header" id="headingFour">
                                <button class="rid-accordion-btn" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
                                    What happen if i cridh the {{__('fe.vehicle')}}?
                                </button>
                            </h6>
                            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour"
                                data-bs-parent="#faqWrap2">
                                <div class="accordion-body">
                                    <p>Eu do, laudantium suspendisse purus dolore, suscipit aperiam voluptates assumenda
                                        adipisci, voluptas aspernatur?</p>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item position-relative">
                            <h6 class="accordion-header" id="headingFive">
                                <button class="rid-accordion-btn collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseFive" aria-expanded="false"
                                    aria-controls="collapseFive">
                                    Do you have VIP access to airport?
                                </button>
                            </h6>
                            <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive"
                                data-bs-parent="#faqWrap2">
                                <div class="accordion-body">
                                    <p>Eu do, laudantium suspendisse purus dolore, suscipit aperiam voluptates assumenda
                                        adipisci, voluptas aspernatur?</p>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item position-relative">
                            <h6 class="accordion-header" id="headingSix">
                                <button class="rid-accordion-btn collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                    What happen if i cridh the {{__('fe.vehicle')}}?
                                </button>
                            </h6>
                            <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix"
                                data-bs-parent="#faqWrap2">
                                <div class="accordion-body">
                                    <p>Eu do, laudantium suspendisse purus dolore, suscipit aperiam voluptates assumenda
                                        adipisci, voluptas aspernatur?</p>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item position-relative">
                            <h6 class="accordion-header" id="headingSeven">
                                <button class="rid-accordion-btn collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseSeven" aria-expanded="false"
                                    aria-controls="collapseSeven">
                                    What do I need to rent a vehicle on your website?
                                </button>
                            </h6>
                            <div id="collapseSeven" class="accordion-collapse collapse"
                                aria-labelledby="headingSeven" data-bs-parent="#faqWrap2">
                                <div class="accordion-body">
                                    <p>Eu do, laudantium suspendisse purus dolore, suscipit aperiam voluptates assumenda
                                        adipisci, voluptas aspernatur?</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- FAQ Section End -->

    <!-- Phone Section Start-->
    <section class="rid-phone-app sec-space">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 d-none d-lg-block">
                    <img src="{{ asset('front/images/phone-app.png') }}" alt="phone-application">
                </div>
                <div class="col-lg-7 d-flex align-items-center">
                    <div class="rid-phone-info">
                        <h2>Rent Faster & Easy</h2>
                        <div class="rid-info-box3 d-flex">
                            <div class="rid-info-box3-number">
                                <h3 class="font-size-40">01.</h3>
                            </div>
                            <div class="rid-info-box3-text">
                                <h3 class="font-size-40">Download Our Apps</h3>
                                <p>Dapibus eaque quisquam! Exercitation, donec quaerat eleifend voluptate! Doloremque.
                                    Volutpat nesciunt.</p>
                            </div>
                        </div>

                        <div class="rid-info-box3 d-flex">
                            <div class="rid-info-box3-number">
                                <h3 class="font-size-40">02.</h3>
                            </div>
                            <div class="rid-info-box3-text">
                                <h3 class="font-size-40">Instant Rentals, Feel Good</h3>
                                <p>Dapibus eaque quisquam! Exercitation, donec quaerat eleifend voluptate! Doloremque.
                                    Volutpat nesciunt.</p>
                            </div>
                        </div>

                        <div class="rid-info-box3 d-flex">
                            <div class="rid-info-box3-number">
                                <h3 class="font-size-40">03.</h3>
                            </div>
                            <div class="rid-info-box3-text">
                                <h3 class="font-size-40">All Time GPS Support</h3>
                                <p>Dapibus eaque quisquam! Exercitation, donec quaerat eleifend voluptate! Doloremque.
                                    Volutpat nesciunt.</p>
                            </div>
                        </div>
                        <div class="rid-phone-app-btns d-flex">
                            <a class="btn d-flex align-items-center me-4" href="index.html">
                                <i class="flaticon-google-play"></i>
                                <h5 class="text-white"><span class="font-size-16-sb d-block">Available On</span>Google
                                    Play</h5>
                            </a>

                            <a class="btn btn-secondary d-flex align-items-center" href="index.html">
                                <i class="flaticon-apple-logo"></i>
                                <h5 class="text-white"><span class="font-size-16-sb d-block">Available On</span>Apple
                                    Store</h5>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Phone Section End-->

    <!-- Post Section Start -->
    <section class="rid-posts-1 sec-space">
        <div class="container">
            <!-- Rid Post Title -->
            <h2 class="text-center">Latest Posts</h2>
            <!-- Rid Post Section -->
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="rid-posts-single">
                        <div class="rid-post-img">
                            <a href="blog-details.html">
                                <img src="{{ asset('front/images/post/post-01.jpg') }}"
                                    alt="Bike lovers deserve better">
                            </a>
                        </div>
                        <div class="rid-post-info">
                            <div class="post-meta">
                                <a href="blog-details.html">
                                    <i class="flaticon-user"></i>
                                    <span>By James Smith</span>
                                </a>
                                <a href="blog-details.html">
                                    <i class="flaticon-folder"></i>
                                    <span>Motorcycle</span>
                                </a>
                            </div>
                            <a href="blog-details.html">
                                <h4>{{__('fe.vehicle')}} lovers deserve better</h4>
                            </a>
                            <p>Tristique donec sociosqu molestie eleifend donec! Luctus! Eros maxime molestiae. Vero,
                                officiapl corpent.</p>
                            <a class="btn rid-post-btn" href="blog-details.html">Read More</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="rid-posts-single">
                        <div class="rid-post-img">
                            <a href="blog-details.html">
                                <img src="{{ asset('front/images/post/post-02.jpg') }}" alt="Enjoy with Roads">
                            </a>
                        </div>
                        <div class="rid-post-info">
                            <div class="post-meta">
                                <a href="blog-details.html">
                                    <i class="flaticon-user"></i>
                                    <span>By James Smith</span>
                                </a>
                                <a href="blog-details.html">
                                    <i class="flaticon-folder"></i>
                                    <span>Motorcycle</span>
                                </a>

                            </div>
                            <a href="blog-details.html">
                                <h4>Enjoy with Roads</h4>
                            </a>
                            <p>Sollicitudin suscipit penatibus leo vero venenatis ipsam occaecati? Irure facilis.</p>
                            <a class="btn rid-post-btn" href="blog-details.html">Read More</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="rid-posts-single">
                        <div class="rid-post-img">
                            <a href="blog-details.html">
                                <img src="{{ asset('front/images/post/post-03.jpg') }}"
                                    alt="Choose your Favorite{{__('fe.vehicle')}}">
                            </a>
                        </div>
                        <div class="rid-post-info">
                            <div class="post-meta">
                                <a href="blog-details.html">
                                    <i class="flaticon-user"></i>
                                    <span>By James Smith</span>
                                </a>

                                <a href="blog-details.html">
                                    <i class="flaticon-folder"></i>
                                    <span>Motorcycle</span>
                                </a>
                            </div>
                            <a href="blog-details.html">
                                <h4>Choose your Favorite{{__('fe.vehicle')}} </h4>
                            </a>
                            <p>Cumque magnam, distinctio class facilisi deleniti! Eos ea sociosqu sit assumenda elit
                                deleniti.</p>
                            <a class="btn rid-post-btn" href="blog-details.html">Read More</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="rid-posts-single">
                        <div class="rid-post-img">
                            <a href="blog-details.html">
                                <img src="{{ asset('front/images/post/post-04.jpg') }}" alt="KTM 2021 RC 125">
                            </a>
                        </div>
                        <div class="rid-post-info">
                            <div class="post-meta">
                                <a href="blog-details.html">
                                    <i class="flaticon-user"></i>
                                    <span>By James Smith</span>
                                </a>
                                <a href="blog-details.html">
                                    <i class="flaticon-folder"></i>
                                    <span>Motorcycle</span>
                                </a>
                            </div>
                            <a href="blog-details.html">
                                <h4>KTM 2021 RC 125</h4>
                            </a>
                            <p>Sollicitudin suscipit laboriosam felis penatibus leo vero venenatis ipsam occaecati?
                                Irure facilis.</p>
                            <a class="btn rid-post-btn" href="blog-details.html">Read More</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="rid-posts-single">
                        <div class="rid-post-img">
                            <a href="blog-details.html">
                                <img src="{{ asset('front/images/post/post-05.jpg') }}" alt="The{{__('fe.vehicle')}} is a Half {{__('fe.vehicle')}}">
                            </a>
                        </div>
                        <div class="rid-post-info">
                            <div class="post-meta">
                                <a href="blog-details.html">
                                    <i class="flaticon-user"></i>
                                    <span>By James Smith</span>
                                </a>

                                <a href="blog-details.html">
                                    <i class="flaticon-folder"></i>
                                    <span>Motorcycle</span>
                                </a>
                            </div>
                            <a href="blog-details.html">
                                <h4>The{{__('fe.vehicle')}} is a Half {{__('fe.vehicle')}}</h4>
                            </a>
                            <p>Conubia libero, proin recusandae cursus dicta labore ratione unde adipisci proin aperiam
                                mollitia.</p>
                            <a class="btn rid-post-btn" href="blog-details.html">Read More</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="rid-posts-single">
                        <div class="rid-post-img">
                            <a href="blog-details.html">
                                <img src="{{ asset('front/images/post/post-06.jpg') }}"
                                    alt="Wise Rent with no Dents">
                            </a>
                        </div>
                        <div class="rid-post-info">
                            <div class="post-meta">
                                <a href="blog-details.html">
                                    <i class="flaticon-user"></i>
                                    <span>By James Smith</span>
                                </a>
                                <a href="blog-details.html">
                                    <i class="flaticon-folder"></i>
                                    <span>Motorcycle</span>
                                </a>
                            </div>
                            <a href="blog-details.html">
                                <h4>Wise Rent with no Dents</h4>
                            </a>
                            <p>Perferendis magnam minus viverra! Dictum ex culpa officiis deleniti, assumenda.</p>
                            <a class="btn rid-post-btn" href="blog-details.html">Read More</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Post Section End -->

    <!-- Statistics Section Start -->
    <section class="rid-statistics sec-space">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="rid-counter d-sm-flex">
                        <div class="rid-counter-icon">
                            <i class="flaticon-crowd"></i>
                        </div>
                        <div class="rid-counter-text">
                            <h4 class="counter">55991</h4>
                            <span>Happy Customers</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="rid-counter d-sm-flex">
                        <div class="rid-counter-icon">
                            <i class="flaticon-helmet"></i>
                        </div>
                        <div class="rid-counter-text">
                            <h4 class="counter">25469</h4>
                            <span>{{__('fe.vehicle')}} In Garage</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="rid-counter d-sm-flex">
                        <div class="rid-counter-icon">
                            <i class="flaticon-odometer-for-kilometers-and-speed-control"></i>
                        </div>
                        <div class="rid-counter-text">
                            <h4 class="counter">214561542</h4>
                            <span>Total Kilometer/Mil</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="rid-counter d-sm-flex">
                        <div class="rid-counter-icon">
                            <i class="flaticon-world"></i>
                        </div>
                        <div class="rid-counter-text">
                            <h4 class="counter">47856</h4>
                            <span>Online Solutions</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Statistics Section End -->
@endsection
