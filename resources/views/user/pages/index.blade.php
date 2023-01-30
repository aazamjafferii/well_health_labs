@extends('user.layout.master')

@section('content')

<section id="content" class="understanding-diabetes">
    <section id="home">
        <section class="banner container noresponsive">
        <div
            class="parallax-window image"
            data-parallax="scroll" style="overflow: hidden; margin-bottom: -6px; position: relative;">
            {{-- style="overflow: hidden; margin-bottom: -6px; position: relative;"> --}}
            <style>


                #myVideo {
                    min-width: 100%;
                    min-height: 100%;
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translateX(-50%) translateY(-50%);
                }

                .content {
                    z-index: 1;
                    position: relative;
                }

                </style>
            <video autoplay muted loop id="myVideo">
                <source src="{{ asset('images/banner_video.mp4') }}" type="video/mp4">
            </video>
            <div class="box content">
            <div class="text">
                <h1>
                {{-- <span
                    ><p>
                    High complexity Molecular diagnostics liboratory
                    </p> </span
                ><br /> --}}

                High complexity Molecular diagnostics liboratory
                </h1>

                <p style="text-align: center;">Getting a lab test is easy. Generally, we'll have you in and out
                in 15 minutes. Many test results will be available in 24-72
                hours.</p>

                <div class="buttonsite">
                <a
                    href="#"
                    class="button"
                    title="Book an Appointment">
                    Book an Appointment</a>
                </div>
            </div>
            </div>
        </div>
        </section>

        <section class="banner container responsive">
            <div
                class="parallax-window image"
                style="overflow: hidden; margin-bottom: -5px; position: relative;">
            <style>
                /* * {
                  box-sizing: border-box;
                } */

                #myVideo {
                    min-width: 100%;
                    min-height: 100%;
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translateX(-50%) translateY(-50%);
                }

                /* .content {
                    z-index: 1;
                    position: relative;
                } */

                </style>
                <video autoplay muted loop id="myVideo">
                    <source src="{{ asset('images/banner_video.mp4') }}" type="video/mp4">
                </video>

            </div>

            <div class="box">
                <div class="inside">
                <div class="text">
                    <h1>
                    {{-- <span
                        ><p>
                        We are closed on November 24th in Observance of
                        Thanksgiving.
                        </p> </span
                    ><br /> --}}

                    High complexity Molecular diagnostics liboratory
                    </h1>

                    Getting a lab test is easy. Generally, we'll have you in and out
                    in 15 minutes. Many test results will be available in 24-72
                    hours.
                    <div class="buttonsite">
                    <a
                        href="#"
                        class="button"
                        title="Book an Appointment">
                        Book an Appointment</a>
                    </div>
                </div>
                </div>
            </div>
        </section>

        {{-- new section add --}}
        <div class="et_pb_section et_pb_section_1 medical_book_section et_section_regular">
            <div class="et_pb_row et_pb_row_0 et_pb_row_fullwidth et_pb_equal_columns et_pb_gutters1 et_pb_row_4col">
                <div class="et_pb_column et_pb_column_1_4 et_pb_column_0  et_pb_css_mix_blend_mode_passthrough">
                    <div class="et_pb_module et_pb_cta_0 et_pb_promo  et_pb_text_align_left et_pb_bg_layout_dark et_pb_no_bg">
                        <div class="et_pb_promo_description">
                            <h2 class="et_pb_module_header">LAB Houston</h2>
                            <div>
                                <p>7557 South Fwy, Suit 7557 Houston, TX 77021
                                   <br/> P: 346.273.4500 F: 346.275.1700
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="et_pb_column et_pb_column_1_4 et_pb_column_1  et_pb_css_mix_blend_mode_passthrough">
                    <div class="et_pb_module et_pb_cta_1 et_pb_promo  et_pb_text_align_left et_pb_bg_layout_dark et_pb_no_bg">
                        <div class="et_pb_promo_description">
                            <h2 class="et_pb_module_header">Lab Dallas</h2>
                            <div>
                                <p>
                                    350 Westpark Way Suite, 100B Euless, TX 76040 <br/>P: 817.786. F: 346.275.1700
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="et_pb_column et_pb_column_1_4 et_pb_column_2  et_pb_css_mix_blend_mode_passthrough">
                    <div class="et_pb_module et_pb_cta_2 et_pb_promo  et_pb_text_align_left et_pb_bg_layout_dark et_pb_no_bg">
                        <div class="et_pb_promo_description">
                            <h2 class="et_pb_module_header">Opening Hours</h2>
                            <div>
                                <p>Mon – Fri: 8am – 5pm<br>Saturday: 9am– 5pm<br>Sunday: 11am – 4pm</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="et_pb_column et_pb_column_1_4 et_pb_column_3  et_pb_css_mix_blend_mode_passthrough et-last-child">
                    <div class="et_pb_module et_pb_cta_3 et_pb_promo  et_pb_text_align_left et_pb_bg_layout_dark et_pb_no_bg">
                        <div class="et_pb_promo_description">
                            <h2 class="et_pb_module_header">Call Us</h2>
                            <div>
                                <div class="contact-address contact-media"><span>Phone: (123) 123-1234</span></div>
                                <div class="contact-address contact-media">Don't Delay, Call Now</div>
                                {{-- <div class="contact-address contact-media"><span> Hempstead, NY 11550</span></div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- new section add --}}

        <section class="categories">
        <div class="inside96">
            <h2>Lab Testing For Any And Every Need</h2>

            <div class="info">
            Select a category for testing options that will help <br />
            guide you on your path to wellness.
            </div>

            <section class="pages flex" style="">
            <a
                class="general-health"
                href="#"
                title="Urinalysis- uti w/drug resistance, std/sti"
                ><article class="category">
                <div
                    class="image"
                    style="
                    background-image: url(wp-content/uploads/2018/08/wellness.jpg);">
                    <div class="icon">
                    <img
                        width="43"
                        height="42"
                        src="{{ asset('wp-content/uploads/2018/08/wellness-icon.png') }}"
                        alt=""/>
                    </div>
                </div>

                <h3 style="font-size: 25px;">Urinalysis- uti w/ drug resistance</h3>
                <div class="text">
                    Well healths labs offers best testing results.
                    <img
                    width="16"
                    height="16"
                    src="{{ asset('images/link-button.png') }}"
                    alt=""/>
                </div></article>
            </a>

            <a class="dna" href="#" title="Toxicology">
                <article class="category">
                <div
                    class="image"
                    style="
                    background-image: url(wp-content/uploads/2018/08/dna.jpg);">
                    <div class="icon">
                    <img
                        width="40"
                        height="40"
                        src="{{ asset('wp-content/uploads/2018/08/dna-icon.png') }}"
                        alt=""
                    />
                    </div>
                </div>

                <h3 style="font-size: 25px;">Toxicology</h3>
                <div class="text">
                    Well healths labs offers best testing results.
                    <img
                    width="16"
                    height="16"
                    src="{{ asset('images/link-button.png') }}"
                    alt=""
                    />
                </div>
            </article>
            </a>

            <a class="std" href="#" title="Respiratory Pathogens"
                ><article class="category">
                <div
                    class="image"
                    style="
                    background-image: url(wp-content/uploads/2018/08/sexual-health.jpg);">
                    <div class="icon">
                    <img
                        width="43"
                        height="42"
                        src="{{ asset('wp-content/uploads/2018/08/sexual-health-icon.png') }}"
                        alt=""
                    />
                    </div>
                </div>

                <h3 style="font-size: 25px;">Respiratory Pathogens</h3>
                <div class="text">
                    Well healths labs offers best testing results.
                    <img
                    width="16"
                    height="16"
                    src="{{ asset('images/link-button.png') }}"
                    alt=""
                    />
                </div>
            </article>
            </a>

            <a
                class="drugs-alcohol"
                href="#"
                title="Gastrointestinal Pathogens"
                ><article class="category">
                <div
                    class="image"
                    style="
                    background-image: url(wp-content/uploads/2018/08/drugs-alcohol.jpg);">
                    <div class="icon">
                    <img
                        width="43"
                        height="42"
                        src="{{ asset('wp-content/uploads/2018/08/drugs-alcohol-icon.png') }}"
                        alt=""
                    />
                    </div>
                </div>

                <h3 style="font-size: 25px;">Gastrointestinal Pathogens</h3>
                <div class="text">
                    Well healths labs offers best testing results.
                    <img
                    width="16"
                    height="16"
                    src="{{ asset('images/link-button.png') }}"
                    alt=""
                    />
                </div>
            </article></a>

            <a
                class="additional-tests"
                href="#"
                title="Wound Pathogens"
                ><article class="category">
                <div
                    class="image"
                    style="
                    background-image: url(wp-content/uploads/2018/08/more-tests.jpg);">
                    <div class="icon">
                    <img
                        width="43"
                        height="42"
                        src="{{ asset('wp-content/uploads/2018/08/more-tests-icon.png') }}"
                        alt=""
                    />
                    </div>
                </div>

                <h3 style="font-size: 25px;">Wound Pathogens</h3>
                <div class="text">
                    Well healths labs offers best testing results.
                    <img
                    width="16"
                    height="16"
                    src="{{ asset('images/link-button.png') }}"
                    alt=""
                    />
                </div>
            </article>
            </a>

            <a class="covid-19" href="#" title="Hair, Nail/Paronychia pathogens"
                ><article class="category">
                <div
                    class="image"
                    style="
                    background-image: url(wp-content/uploads/2021/12/imagen-banner-covid.jpg);">
                    <div class="icon">
                    <img
                        width="42"
                        height="42"
                        src="{{ asset('wp-content/uploads/2021/12/icono-covid-mini.png') }}"
                        alt=""
                    />
                    </div>
                </div>

                <h3 style="font-size: 25px;">Hair, Nail/ Paronychia pathogens</h3>
                <div class="text">
                    Well healths labs offers best testing results.
                    <img
                    width="16"
                    height="16"
                    src="{{ asset('images/link-button.png') }}"
                    alt=""
                    />
                </div>
                </article
            ></a>
            </section>
        </div>
        </section>

        <section class="column3">
        <div class="inside">
            <article class="content-text">
            <h2>How it works</h2>

            fast, reliable results you can trust
            </article>

            <article class="columns container">
            <div class="column third">
                <div class="number" style="background-color: #8dc63f">1</div>
                <div class="icon">
                <a
                    href="#"
                    title="Choose your nearest location"
                    ><img
                    width="65"
                    height="81"
                    src="{{ asset('images/icon1.png') }}"
                    alt="Choose your nearest location"/>
                </a>
                </div>
                <a
                href="#"
                title="Choose your nearest location">
                <div class="text">
                    Choose your<br />
                    nearest location
                </div>
            </a>
            </div>

            <div class="column third">
                <div class="number" style="background-color: #13a89e">2</div>
                <div class="icon">
                <a href="#" title="Select your test"
                    ><img
                    width="77"
                    height="87"
                    src="{{ asset('images/icon2.png') }}"
                    alt="Select your test"
                /></a>
                </div>
                <a href="#" title="Select your test">
                    <div class="text">
                    Select<br />
                    your test
                </div>
            </a>
            </div>

            <div class="column third">
                <div class="number" style="background-color: #f26735">3</div>
                <div class="icon">
                <a
                    href="#"
                    title="Schedule an appointment"
                    ><img
                    width="97"
                    height="86"
                    src="{{ asset('images/icon3.png') }}"
                    alt="Schedule an appointment"/>
                </a>
                </div>
                <a
                href="#"
                title="Schedule an appointment"
                ><div class="text">
                    Schedule an<br />
                    appointment
                </div></a>
            </div>
            </article>

            <div class="buttonsite">
                <a href="#" class="button" title="Contact Us">Contact Us</a>
            </div>
        </div>
        </section>

        <section class="popular-test">
        <div class="inside">
            <article class="text">
            <h2>Our most popular tests</h2>
            You have questions about your health. We can help you get answers,
            fast. In and out and on your way to a healthier life.
            </article>

            <section class="tests">
            <article class="test">
                <div class="image">
                <img
                    width="43"
                    height="42"
                    src="{{ asset('wp-content/themes/altn2018/img/more-tests-icon.png') }}"
                    alt=""/>
                </div>
                <div class="name">
                <a
                    href="#"
                    title="Covid-19">
                    Covid-19</a>
                </div>
                {{-- <div class="price"></div> --}}
                <div class="button-side">
                <a
                    href="#"
                    class="buy"
                    title="Learn More about Annual Check-Up Panel">
                    Learn More</a>
                </div>
            </article>

            <article class="test">
                <div class="image">
                <img
                    width="43"
                    height="42"
                    src="{{ asset('wp-content/themes/altn2018/img/more-tests-icon.png') }}"
                    alt=""/>
                </div>
                <div class="name">
                <a
                    href="#"
                    title="Gastrointestinal">
                    Gastrointestinal</a>
                </div>
                {{-- <div class="price"></div> --}}
                <div class="button-side">
                <a
                    href="#"
                    class="buy"
                    title="Learn More about STD Panel, Comprehensive">
                    Learn More</a>
                </div>
            </article>

            <article class="test">
                <div class="image">
                <img
                    width="43"
                    height="42"
                    src="{{ asset('wp-content/themes/altn2018/img/more-tests-icon.png') }}"
                    alt=""/>
                </div>
                <div class="name">
                <a
                    href="#"
                    title="Uti (incl. drug resistance)"
                    >Uti (incl. drug resistance)</a>
                </div>
                {{-- <div class="price"></div> --}}
                <div class="button-side">
                <a
                    href="#"
                    class="buy"
                    title="Learn More about Paternity Informational (Non-Legal)">
                    Learn More</a>
                </div>
            </article>

            <article class="test">
                <div class="image">
                <img
                    width="43"
                    height="42"
                    src="{{ asset('wp-content/themes/altn2018/img/more-tests-icon.png') }}"
                    alt=""/>
                </div>
                <div class="name">
                <a
                    href="#"
                    title="Sti/std">
                    Sti/std</a>
                </div>
                {{-- <div class="price"></div> --}}
                <div class="button-side">
                <a
                    href="#"
                    class="buy"
                    title="Learn More about Cholesterol (Lipid) Panel">
                    Learn More</a>
                </div>
            </article>

            <article class="test">
                <div class="image">
                <img
                    width="43"
                    height="42"
                    src="{{ asset('wp-content/themes/altn2018/img/more-tests-icon.png') }}"
                    alt=""/>
                </div>
                <div class="name">
                <a
                    href="#"
                    title="diabetes/obesity">
                    diabetes/obesity</a>
                </div>
                {{-- <div class="price"></div> --}}
                <div class="button-side">
                <a
                    href="#"
                    class="buy"
                    title="Learn More about 5-Panel Instant*">
                    Learn More</a>
                </div>
            </article>
            </section>
        </div>
        </section>

        <section class="column2" style="margin-bottom: -10px;">
        <div
            class="parallax-window image"
            data-parallax="scroll"
            data-image-src="{{ asset('wp-content/uploads/2018/08/anylabtestnow-people.jpg') }}">
            <div class="inside">
            <article class="columns container">
                <div class="column middle even">
                <article class="content-text" style="margin-left: 15px; margin-right: 15px;">
                    <h2>Advantages</h2>

                    <div class="text">
                    - Quicker turnaround times <br/>
                    - Supprot 24/7, 365 days a year <br/>
                    - No commitment required <br/>
                    - Medical assistants provided <br/>
                    - Training provided <br/>
                    - Direct contact with physicians, face to face<br/>
                    </div>

                    <div class="buttonsite">
                    <a href="#" class="button" title="Contact Us">
                        Contact Us
                    </a>
                    </div>
                </article>
                </div>

                <div class="column middle" >
                <article class="content-text" style="margin-left: 15px; margin-right: 15px; margin-bottom:">
                    <h2>Get your results</h2>

                    <div class="text" style="margin-bottom: 110px;">
                    Well Healths Labs the ability  to process over  24 tests every four hours. We will provide testing  results  to the  employees and company in the less than  48 hours.
                    </div>

                    <div class="buttonsite">
                    <a
                        href="#"
                        class="button"
                        title="Contact Us"
                        >Contact Us</a
                    >
                    </div>
                </article>
                </div>
            </article>
            </div>
        </div>
        </section>

        <section class="testimonials">
        <div
            class="parallax-window image"
            data-parallax="scroll"
            data-image-src="{{ asset('images/unnamed.png') }}">
            <div class="inside">
            <section class="reviews">
                <div class="slider">
                <ul>
                    <li>
                    <div class="text">
                        "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum."
                    </div>
                    <div class="name">Eric G.</div>
                    <div class="location">Newark, DE</div>
                    </li>

                    <li>
                    <div class="text">
                        “Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.”
                    </div>
                    <div class="name">Heather</div>
                    <div class="location">Austin, TX</div>
                    </li>

                    <li>
                    <div class="text">
                        “Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.”
                    </div>
                    <div class="name">Bob</div>
                    <div class="location">Sarasota, FL</div>
                    </li>

                    <li>
                    <div class="text">
                        “Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.”
                    </div>
                    <div class="name">Virginia Jones, J.D., SPHR</div>
                    <div class="location">
                        Human Resources Manager<br />
                        Durham, NC
                    </div>
                    </li>

                    <li>
                    <div class="text">
                        “Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.”
                    </div>
                    <div class="name">James Siegel<br />UglyFitness™</div>
                    <div class="location">President<br />Wauwatosa, WI</div>
                    </li>

                    <li>
                    <div class="text">
                        “Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.”
                    </div>
                    <div class="name">
                        K.L.<br />
                        Physician
                    </div>
                    <div class="location">Chandler, Arizona</div>
                    </li>

                    <li>
                    <div class="text">
                        “Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.”
                    </div>
                    <div class="name">Chanel B.</div>
                    <div class="location">Nashville, TN</div>
                    </li>
                </ul>
                </div>
            </section>
            </div>
        </div>
        </section>
    </section>
</section>

@endsection
