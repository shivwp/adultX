@extends('frontend.main')
@section('content')
    <div id="carouselExampleIndicators" class="carousel slide vertical slide-wrapper" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active">
                <span> 01 </span>
            </li>
            <li data-target="#carouselExampleIndicators" data-slide-to="1">02</li>
            <li data-target="#carouselExampleIndicators" data-slide-to="2">03</li>
            <li data-target="#carouselExampleIndicators" data-slide-to="3">04</li>
        </ol>
        <div class="carousel-inner" role="listbox">
            <div class="carousel-item active">
                <div class="first-slide-wrapper">
                    <div class="container">
                        <div class="firstslide-textwrapp" carousel-caption>
                            <h1 class="first-slideheading">
                                Sex Is A Bit Like A <span>Secret</span>
                            </h1>
                            <p class="first-slidepara">
                                There are many variations of passages of Lorem Ipsum
                                available, but the majority have suffered alteration in some
                                form, by injected humour.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="first-slide-wrapper">
                    <div class="container">
                        <div class="firstslide-textwrapp" carousel-caption>
                            <h1 class="first-slideheading">
                                Sex Is A Bit Like A <span>Secret</span>
                            </h1>
                            <p class="first-slidepara">
                                There are many variations of passages of Lorem Ipsum
                                available, but the majority have suffered alteration in some
                                form, by injected humour.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="first-slide-wrapper">
                    <div class="container">
                        <div class="firstslide-textwrapp" carousel-caption>
                            <h1 class="first-slideheading">
                                Sex Is A Bit Like A <span>Secret</span>
                            </h1>
                            <p class="first-slidepara">
                                There are many variations of passages of Lorem Ipsum
                                available, but the majority have suffered alteration in some
                                form, by injected humour.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="first-slide-wrapper">
                    <div class="container">
                        <div class="firstslide-textwrapp" carousel-caption>
                            <h1 class="first-slideheading">
                                Sex Is A Bit Like A <span>Secret</span>
                            </h1>
                            <p class="first-slidepara">
                                There are many variations of passages of Lorem Ipsum
                                available, but the majority have suffered alteration in some
                                form, by injected humour.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <a class="carousel-control-prev up" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <i class="fas fa-chevron-up fa-2x" aria-hidden="true"></i>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next down" href="#carouselExampleIndicators" role="button" data-slide="next">
            <i class="fas fa-chevron-down fa-2x" aria-hidden="true"></i>
            <span class="sr-only">Next</span>
        </a>
    </div>

    <!-- websbar-wraepper start -->
    <div class="onlinebg-wrapper">
        <div class="container">
            <div class="websbar-wraepper">
                <div class="row">
                    <div class="col-sm-2 col-md-2">
                        <div class="icon-wrapper">
                            <img src="./image/chat.png" alt="" class="icon-wrap" />
                            <span>SMS Texting & DM’s</span>
                        </div>
                    </div>
                    <div class="col-sm-2 col-md-2">
                        <div class="icon-wrapper">
                            <img src="./image/phone.png" alt="" class="icon-wrap" />
                            <span>Phone Sex</span>
                        </div>
                    </div>
                    <div class="col-sm-2 col-md-2">
                        <div class="icon-wrapper">
                            <img src="./image/video.png" alt="" class="icon-wrap" />

                            <span>Video Calls</span>
                        </div>
                    </div>
                    <div class="col-sm-2 col-md-2">
                        <div class="icon-wrapper">
                            <img src="./image/img.png" alt="" class="icon-wrap" />
                            <span>Picture Messages</span>
                        </div>
                    </div>
                    <div class="col-sm-2 col-md-2">
                        <div class="icon-wrapper">
                            <img src="./image/play.png" alt="" class="icon-wrap" />
                            <span>Video Messages</span>
                        </div>
                    </div>
                    <div class="col-sm-2 col-md-2">
                        <div class="icon-wrapper">
                            <img src="./image/mic.png" alt="" class="icon-wrap" />
                            <span>Voice Messages</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- OnlineNow-wrappers start -->

            <div class="OnlineNow-wrappers">
                <div class="online-hading-wrapp">
                    <h2>Online <span> Now</span></h2>
                    <a href="onlinepage.html">View all</a>
                </div>
                <div class="row">
                    @foreach ($online as $item)
                        <div class="col-sm-3">
                            <div class="first-col-wrapper">
                                <img class="model-img" src="{{ url('profile-image') . '/' . $item->profile_image }}"
                                    alt="Model-Image">

                                <div class="col-overlay"></div>
                                <label class="new-label">New</label>
                                <div class="colname-wrapper">
                                    <label class="colname">{{ $item->first_name }}</label>
                                    <span class="colcost">${{ $item->cost_msg }}per message</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- OnlineNow-wrappers close -->
            <!-- new model start -->
            <div class="OnlineNow-wrappers">
                <div class="online-hading-wrapp">
                    <h2>New <span>Models</span></h2>
                    <a href="#">View all</a>
                </div>
                <div class="row">

                    @foreach ($new as $item)
                        <div class="col-sm-3">
                            <div class="first-col-wrapper">
                                <img class="model-img" src="{{ url('profile-image') . '/' . $item->profile_image }}"
                                    alt="Model-Image">
                                <label class="new-label">New</label>
                                <div class="col-overlay">
                                    <div class="colname-wrapper">
                                        <label class="colname">{{ $item->first_name }}</label>
                                        <span class="colcost">${{ $item->cost_msg }}per message</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>

            <!-- new model close -->
        </div>
    </div>
    <!-- websbar-wraepper end -->

    <!-- Available For Phone start -->
    <div class="available-bg-wrapp">
        <div class="available-bgoverlay"></div>
        <div class="container">
            <div class="availebaleforphone-wrapep">
                <h2 class="availableForHading">
                    Available For <span>Phone Sex</span>
                </h2>
                <section class="sec sec-2 availablephoneslide-wrapper">
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="owl-carousel owl-theme" id="staff">

                                @foreach ($phone as $item)
                                    <div class="item">
                                        <div class="box-b staff-itemwrapper">
                                            <div class="box-img">
                                                <img class="model-img"
                                                    src="{{ url('profile-image') . '/' . $item->profile_image }}"
                                                    alt="Model-Image">
                                                <span class="callsing">
                                                    <i class="fa fa-phone" aria-hidden="true"></i></span>
                                            </div>
                                            <h3>{{ $item->first_name }}</h3>
                                            <p>Call for ${{ $item->cost_audiocall }} per minute</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <!-- Available For Phone end -->

    <!-- Available for Call wrapper start -->
    <div class="availebalforcall-wrapperbg">
        <div class="container">
            <!-- Availebal call wrpp  start-->
            <div class="OnlineNow-wrappers">
                <div class="online-hading-wrapp">
                    <h2>Available For <span>Video Calls</span></h2>
                    <a href="videocallpage.html">View all</a>
                </div>
                <div class="row">
                    @foreach ($video as $item)
                        <div class="col-sm-3">
                            <div class="first-col-wrapper">

                                <img class="model-img" src="{{ url('profile-image') . '/' . $item->profile_image }}"
                                    alt="Model-Image">
                                <label class="new-label">New</label>
                                <img src="./image/videobtn.png" alt="#" class="videobtnicon" />
                                <div class="col-overlay">
                                    <div class="colname-wrapper">
                                        <label class="colname">{{ $item->first_name }}</label>
                                        <span class="colcost">${{ $item->cost_audiocall }} per Video Call</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <!-- Availebal call wrpp end -->

            <!-- Featured model  start-->
            <div class="feayuredmodel-wrapper">
                <div class="row">
                    <div class="col-sm-3">
                        <div id="sync1" class="owl-carousel owl-theme">
                            <div class="item">
                                <div class="team-member">
                                    <div class="team-img teamimg-wrapp">
                                        <img src="./image/Featured1.png" alt="team member" class="img-responsive" />
                                        <span class="img-number">1</span>
                                    </div>
                                </div>
                                <div class="team-title">
                                    <h5>Naomi Nova</h5>
                                    <p>
                                        I’m Jenna, the single tattooedPAWG who’s always down to be
                                        the nastiest littlecumslut,I reply INSTANT;
                                    </p>
                                </div>
                            </div>
                            <div class="item">
                                <div class="team-member">
                                    <div class="team-img teamimg-wrapp">
                                        <img src="https://image.freepik.com/free-photo/confident-businesswoman-holding-pen_1098-2049.jpg"
                                            alt="team member" class="img-responsive" />
                                        <span class="img-number">1</span>
                                    </div>
                                </div>
                                <div class="team-title">
                                    <h5>Naomi Nova</h5>
                                    <p>
                                        I’m Jenna, the single tattooedPAWG who’s always down to be
                                        the nastiest littlecumslut,I reply INSTANT;
                                    </p>
                                </div>
                            </div>
                            <div class="item">
                                <div class="team-member">
                                    <div class="team-img teamimg-wrapp">
                                        <img src="https://image.freepik.com/free-photo/confident-businesswoman-holding-pen_1098-2049.jpg"
                                            alt="team member" class="img-responsive" />
                                        <span class="img-number">1</span>
                                    </div>
                                </div>
                                <div class="team-title">
                                    <h5>Naomi Nova</h5>
                                    <p>
                                        I’m Jenna, the single tattooedPAWG who’s always down to be
                                        the nastiest littlecumslut,I reply INSTANT;
                                    </p>
                                </div>
                            </div>
                            <div class="item">
                                <div class="team-member">
                                    <div class="team-img teamimg-wrapp">
                                        <img src="https://image.freepik.com/free-photo/confident-businesswoman-holding-pen_1098-2049.jpg"
                                            alt="team member" class="img-responsive" />
                                        <span class="img-number">1</span>
                                    </div>
                                </div>
                                <div class="team-title">
                                    <h5>Naomi Nova</h5>
                                    <p>
                                        I’m Jenna, the single tattooedPAWG who’s always down to be
                                        the nastiest littlecumslut,I reply INSTANT;
                                    </p>
                                </div>
                            </div>
                            <div class="item">
                                <div class="team-member">
                                    <div class="team-img teamimg-wrapp">
                                        <img src="https://image.freepik.com/free-photo/businesswoman-yawning-in-the-office_1098-2218.jpg"
                                            alt="team member" class="img-responsive" />
                                        <span class="img-number">1</span>
                                    </div>
                                </div>
                                <div class="team-title">
                                    <h5>Naomi Nova</h5>
                                    <p>
                                        I’m Jenna, the single tattooedPAWG who’s always down to be
                                        the nastiest littlecumslut,I reply INSTANT;
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-9">
                        <div class="slideitem-wrapper">
                            <div>
                                <h5 class="fetuedmodal-heading">
                                    Featured <span>Modal</span>
                                </h5>
                            </div>
                            <div id="sync2" class="owl-carousel owl-theme">
                                <div class="item slideimg-wrapp">
                                    <img src="./image/featued2.png" alt="team member" class="img-responsive" />
                                    <span class="smallimg-number">2</span>
                                    <h4 class="imgmodel-name">Naomi Nova</h4>
                                </div>
                                <div class="item slideimg-wrapp">
                                    <img src="./image/43.png" alt="team member" class="img-responsive" />
                                    <span class="smallimg-number">3</span>
                                    <h4 class="imgmodel-name">Naomi Nova</h4>
                                </div>
                                <div class="item slideimg-wrapp">
                                    <img src="./image/40.png" alt="team member" class="img-responsive" />
                                    <span class="smallimg-number">4</span>
                                    <h4 class="imgmodel-name">Naomi Nova</h4>
                                </div>
                                <div class="item slideimg-wrapp">
                                    <img src="https://image.freepik.com/free-photo/smiling-secretary-with-glasses-and-white-shirt_1098-3301.jpg"
                                        alt="team member" class="img-responsive" />
                                    <span class="smallimg-number">5</span>
                                    <h4 class="imgmodel-name">Naomi Nova</h4>
                                </div>
                                <div class="item slideimg-wrapp">
                                    <img src="https://image.freepik.com/free-photo/businesswoman-yawning-in-the-office_1098-2218.jpg"
                                        alt="team member" class="img-responsive" />
                                    <span class="smallimg-number">6</span>
                                    <h4 class="imgmodel-name">Naomi Nova</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Featured model  end-->
        </div>
    </div>
    <!-- Trending Models for Call wrapper  end-->

    <!-- Trending Models for Call wrapper  end-->
    <div class="trendingmodelbg">
        <div class="container">
            <div class="OnlineNow-wrappers">
                <div class="online-hading-wrapp">
                    <h2>Trending <span> Models </span></h2>
                    <a href="trendingpage.html">View all</a>
                </div>
                <div class="row">
                    @foreach ($trending as $item)
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="Tranding-model-wrapper">
                                <img src="{{ url('profile-image') . '/' . $item->profile_image }}" alt="#" />
                                <div class="Trandingcol-overlay">
                                    <div class="Trandingcolname-wrapper">
                                        <label class="colname">{{ $item->first_name }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
            <!-- Availebal call wrpp end -->
        </div>
    </div>

    <!-- Trending Models for Call wrapper  end-->
    <!-- Explore img for  wrapper  start-->
    <div class="explore-bg-wrapper">
        <div class="container">
            <div class="OnlineNow-wrappers">
                <div class="online-hading-wrapp">
                    <h2><span> Explore </span></h2>
                    <a href="#">View all</a>
                </div>

                <div class="main">
                    <div class="container">
                        <div class="card_wrapper">
                            @foreach ($explore as $item)
                                <div class="card">
                                    <img src="{{ url('profile-image') . '/' . $item->profile_image }}"
                                        alt="" />
                                </div>
                            @endforeach
                            {{-- <div class="card">
                                <img src="./image/explore2.png" alt="" />
                            </div>
                            <div class="card">
                                <img src="./image/explore3.png" alt="" />
                            </div>
                            <div class="card">
                                <img src="./image/explore4.png" alt="" />
                            </div>
                            <div class="card">
                                <img src="./image/explore4.png" alt="" />
                            </div>
                            <div class="card">
                                <img src="./image/explore5.png" alt="" />
                            </div>
                            <div class="card">
                                <img src="./image/explore6.png" alt="" />
                            </div>
                            <div class="card">
                                <img src="./image/explore8.png" alt="" />
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Availebal call wrpp end -->
    </div>
    <!-- Explore img for  wrapper  end-->

    <!-- Adult-works start -->
    <div class="Adult-works-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="adultx-wrapper">
                        <h2 class="howAdult-wraper">How AdultX Works</h2>
                        <div class="adultwomneimg">
                            <img src="./image/Rectangle81.png" alt="#" class="img-fluid" />
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="adultx-wrapper">
                        <h2 class="howAdult-wraper">Join AdultX for FREE</h2>
                        <div class="adult-form-wrpper">
                            <form method="POST" action="{{ route('storeuser') }}">
                                @csrf
                                <div class="username-wrapper">
                                    <label> Username </label>
                                    <input placeholder="Choose a Username" name="first_name"type="text" />
                                    <small class="text-danger">
                                        @error('first_name')
                                            {{ $message }}
                                        @enderror
                                    </small>
                                </div>
                                <div class="username-wrapper">
                                    <label> Email </label>
                                    <input placeholder="Enter your Email" name="email" type="text" />
                                    <small class="text-danger">
                                        @error('email')
                                            {{ $message }}
                                        @enderror
                                    </small>
                                </div>
                                <div class="username-wrapper">
                                    <label> Password </label>
                                    <input placeholder="Enter your Password"name="password" type="text" />
                                    <small class="text-danger">
                                        @error('password')
                                            {{ $message }}
                                        @enderror
                                    </small>
                                </div>
                                <div class="checkline">
                                    <input type="checkbox" class="checkbox-input" name="readbox" />
                                    <p>
                                        I have read and agreed to AdultX.com’s<b>
                                            Terms of Service</b>
                                    </p>

                                </div>
                                <small class="text-danger">
                                    @error('readbox')
                                        {{ $message }}
                                    @enderror
                                </small><br>
                                <button class="singin-btn">Sign up for free</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
@endsection
