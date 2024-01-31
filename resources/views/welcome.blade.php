<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}" />
    <noscript>
        <link rel="stylesheet" href="{{ asset('assets/css/noscript.css') }}" />
    </noscript>

    <!-- Styles -->
</head>

<body class="is-preload">
    <div id="wrapper">

        <!-- Header -->
        <header id="header" class="alt">
            <a href="index.php" class="logo"><strong>RJ</strong> <span>RENT</span></a>
            <nav>
                <div>
                    @if (Route::has('login'))
                    <div class="alt">
                        @auth
                        <a href="{{ url('/dashboard') }}">Dashboard</a>
                        @else
                        <a href="{{ route('register') }}">Register</a>
                        @endauth
                    </div>
                    @endif
                </div>
            </nav>
        </header>

        <!-- Menu -->
        <section id="banner" class="major">
						<div class="inner">
							<header class="major">
								<h1>Rentalnya Segala Rental</h1>
							</header>

							<div class="content">
								<p>Klik untuk Login</p>
								<ul class="actions">
									<li><a href="{{ route('login') }}"  class="button next scrolly">Sign In</a></li>
								</ul>
							</div>
						</div>
					</section>

                    <div id="main">
							<section>
								<div class="inner">
									<header class="major">
										<h2>Tentang Kami</h2>
									</header>
									<p>Kami menyediakan solusi transportasi yang menyenangkan dan mudah. Dari mobil yang nyaman hingga motor yang seru dan sepeda yang ramah lingkungan, temukan kendaraan yang sesuai dengan petualangan Anda.
										Mengapa memilih kami?
										transportasi berkualitas tinggi,
										Harga kompetitif,
										Pelayanan ramah serta cepat dan
										Proses pemesanan yang sederhana.
										Mari jadikan setiap perjalanan Anda tak terlupakan bersama RJ RENT. Bergabunglah dan nikmati kebebasan menjelajahi dunia dengan gaya Anda! Ada Scooter dan ATV juga Loh!!</p>
								</div>
							</section>
				<!-- Footer -->
				<footer id="footer">
					<div class="inner">
						<ul class="icons">
							<li><a href="#" class="icon alt fa-twitter"><span class="label">Twitter</span></a></li>
							<li><a href="#" class="icon alt fa-facebook"><span class="label">Facebook</span></a></li>
							<li><a href="#" class="icon alt fa-instagram"><span class="label">Instagram</span></a></li>
						</ul>
						<ul class="copyright">
							<li>Copyright © 2023 RJ RENT</li>
						</ul>
					</div>
				</footer>

			</div>

</body>
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.scrolly.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.scrollex.min.js') }}"></script>
<script src="{{ asset('assets/js/browser.min.js') }}"></script>
<script src="{{ asset('assets/js/breakpoints.min.js') }}"></script>
<script src="{{ asset('assets/js/util.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>

</html>
