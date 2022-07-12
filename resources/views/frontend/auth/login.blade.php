<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />

    <link rel="stylesheet" href="./css/style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

    <title>Adult X</title>
</head>

<body>
    <div class="login-bg">
        <div class="container">
            <div class="login-wrapper">
                <div class="login-bannerwrapper">
                    <img src="./image/loginbanner.png" alt="" class="login-bannerimg" />
                    <div class="login-bannerhading-wrapp">
                        <img src="./image/loginlogo.png" alt="#" class="hadingadult" />
                    </div>
                </div>
                <div class="loginfrom-wrapper">
                    <div class="login-form-wraper">
                        <img src="./image/singup.png" alt="" class="mb-3" />
                        <p class="sinup-linewrapp">
                            Sign up to view more posts Or log in if you already have an
                            account
                        </p>
                        <div class="logintab-wrapp">
                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-home" type="button" role="tab"
                                        aria-controls="pills-home" aria-selected="true">
                                        Sign Up
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-profile" type="button" role="tab"
                                        aria-controls="pills-profile" aria-selected="false">
                                        Log in
                                    </button>
                                </li>
                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                                    aria-labelledby="pills-profile-tab">
                                    <div class="tab-pane fade show active loginform-wrapper" id="pills-home"
                                        role="tabpanel" aria-labelledby="pills-home-tab">
                                        <form method="POST" action="{{ route('mainlogin') }}">
                                            @csrf
                                            <div class="singpform-wraper">
                                                <div class="inputfild-wrapp" style="border-bottom: 1px solid #193147">
                                                    <label class="input-label">Email Address</label>
                                                    <input type="email" placeholder="Email" name="email"
                                                        class="email-adrrs" />
                                                </div>
                                                <div class="inputfild-wrapp">
                                                    <label class="input-label">Password</label>
                                                    <input type="password" placeholder="Password" name="password"
                                                        class="lockpassword" />
                                                </div>
                                            </div>
                                            <div class="checkinput-wraperonlogin">
                                                <div class="checkinput-wraper">
                                                    <input type="checkbox" class="ckeckoutinpt" />
                                                    <p>Remember Me</p>
                                                </div>

                                                <a href="#"> Forgot Password? </a>
                                            </div>
                                            <div class="login-pagebtn-wrapp">
                                                <button class="singupbtn">Login</button>
                                            </div>
                                            <p class="byloginline">
                                                By logging in you are agreeing to our and
                                                <b>Terms of Service</b> and
                                                <b> Privacy Policy. </b>
                                            </p>
                                        </form>
                                    </div>
                                </div>
                                <div class="tab-pane fade show active loginform-wrapper" id="pills-home" role="tabpanel"
                                    aria-labelledby="pills-home-tab">
                                    <form>
                                        <div class="singpform-wraper">
                                            <div class="inputfild-wrapp" style="border-bottom: 1px solid #193147">
                                                <label class="input-label">Display Name</label>
                                                <input type="text" placeholder="name" required
                                                    class="Displayname-input" style="" />
                                            </div>
                                            <div class="inputfild-wrapp" style="border-bottom: 1px solid #193147">
                                                <label class="input-label">Email Address</label>
                                                <input type="gmail" placeholder="Email" name="email"
                                                    class="email-adrrs" />
                                            </div>
                                            <div class="inputfild-wrapp">
                                                <label class="input-label">Password</label>
                                                <input type="text" placeholder="Password"
                                                    name="password"class="lockpassword" />
                                            </div>
                                        </div>
                                        <div class="checkinput-wraper">
                                            <input type="checkbox" class="ckeckoutinpt" />
                                            <p>
                                                I have read and agreed to AdultX.com’s
                                                <b>Terms of Service</b>
                                            </p>
                                        </div>
                                        <div class="login-pagebtn-wrapp">
                                            <button class="singupbtn">Sign up</button>

                                            <button class="applyasmodel-btn">
                                                Apply as a modal
                                            </button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
</body>

</html>
