<header id="header" class="rid-header-style-1">

    <div class="rid-header-top">
        <div class="container">
            <div class="row">
                <div class="col-sm-9 col-md-10 col-8">
                    <div class="rid-contact">

                        <div class="business-contact">
                            <a href="tel:800-567-8990">
                                <i class="flaticon-telephone-call me-2"></i>
                                <span class="mr-40">
                                    800-567-8990
                                </span>
                            </a>
                        </div>

                        <div class="business-hour">
                            <div class="business-hour-mob">
                                <i class="flaticon-clock me-2" data-bs-toggle="tooltip"
                                    title="Hours of Operation: 7:00 am - 9:00 pm (Mon - Sun)"
                                    data-bs-placement="bottom"></i>
                                <span class="d-none"> Hours of Operation: 7:00 am - 9:00 pm (Mon - Sun)</span>
                            </div>

                            <div class="business-hour-des">
                                <i class="flaticon-clock me-2 d-none d-md-inline"></i>
                                <span class="d-none d-md-inline">Hours of Operation: 7:00 am - 9:00 pm (Mon -
                                    Sun)</span>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-sm-3 col-md-2 col-4 d-flex justify-content-end align-items-center">
                    <a href="rentals.html" class="mr-40">
                        <i class="flaticon-avatar"></i>
                    </a>
                    <button class="rid-offcanvas-btn" aria-label="Main Menu Icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="rid-header-bottom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-sm-4 col-md-4 col-4">
                    <div class="logo">
                        <a href="index.html">
                            <img src="{{ asset('front/images/logo.svg') }}" alt="Ridexo Logo">
                        </a>
                    </div>
                </div>
                <div class="col-sm-8 col-md-8 col-8 d-flex justify-content-end align-items-center">
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-secondary font-size-15" id="CurrencySwitcher"
                            data-bs-toggle="dropdown" aria-expanded="true" data-bs-reference="parent">EUR</button>
                        <button type="button"
                            class="rid-drop-dark btn btn-secondary dropdown-toggle dropdown-toggle-split"
                            id="rid_currency" data-bs-toggle="dropdown" aria-expanded="false"
                            data-bs-reference="parent">
                            <span class="visually-hidden">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu rid-currency-menu" aria-labelledby="rid_currency">
                            <li><a class="dropdown-item" href="index.html">USD</a></li>
                            <li><a class="dropdown-item" href="index.html">BDT</a></li>
                            <li><a class="dropdown-item" href="index.html">CAD</a></li>
                            <li><a class="dropdown-item" href="index.html">AUD</a></li>
                        </ul>
                    </div>

                    <div class="btn-group ms-2">
                        <button type="button" class="btn btn-outline-secondary flag" id="languageSwitcher"
                            data-bs-toggle="dropdown" aria-expanded="true" data-bs-reference="parent">
                            <img src="{{ asset('front/images/flag.png') }}" alt="English Language Flag"><span
                                class="ms-1 font-size-15">ENGLISH</span>
                        </button>
                        <button type="button"
                            class="rid-drop-dark btn btn-secondary dropdown-toggle dropdown-toggle-split"
                            id="rid-language" data-bs-toggle="dropdown" aria-expanded="false"
                            data-bs-reference="parent">
                            <span class="visually-hidden">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu rid-language-menu" aria-labelledby="rid-language">
                            <li>
                                <a class="dropdown-item" href="index.html">
                                    <img src="{{ asset('front/images/spnish-flag.png') }}" alt="spanish flag">
                                    Spanish
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="index.html">
                                    <img src="{{ asset('front/images/russian-flag.png') }}" alt="russian falg">
                                    Russian
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="index.html">
                                    <img src="{{ asset('front/images/japan-flag.png') }}" alt="japan flag">
                                    Japanese
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="index.html">
                                    <img src="{{ asset('front/images/france-flag.png') }}" alt="france flag">
                                    French
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="ms-2 d-flex align-items-center">
                        <a href="contact.html" class="btn add-rental-desktop">Add Rental Company</a>
                        <a href="contact.html" class="btn add-rental-mobile" data-bs-toggle="tooltip"
                            title="Add Rental Company" data-bs-placement="bottom">
                            <i class="icofont-plus"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!--  header End  -->