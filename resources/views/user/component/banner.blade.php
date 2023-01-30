<section class="banner container noresponsive">
    <div class="parallax-window image" data-parallax="scroll" style="overflow: hidden; margin-bottom: -6px; position: relative;">
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

        <img src="{{ asset('images/molecular-pcr.jpg') }}" alt="" id="myVideo" />
        <div class="box content internal-content">
        <div class="text">
        <h1>
            Molecular/pcr
        </h1>

        <p style="text-align: center;">Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>

        <div class="buttonsite">
        <a
            href="#"
            class="button"
            title="See all tests">
            See all tests</a>
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

        #myVideo {
            min-width: 100%;
            min-height: 100%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translateX(-50%) translateY(-50%);
        }

        </style>
        <video autoplay muted loop id="myVideo">
            <source src="{{ asset('images/banner_video.mp4') }}" type="video/mp4">
        </video>

    </div>

    <div class="box">
        <div class="inside">
        <div class="text">
            <h1>
                Molecular/pcr
            </h1>

            <p style="text-align: center;">Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>

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
