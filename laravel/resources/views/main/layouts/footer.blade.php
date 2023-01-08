<div class="bg-dark mt-5">
    <div class="container">
        <footer class="py-3">
            <ul class="nav justify-content-center border-bottom border-secondary pb-2 mb-2">
                <li class="nav-item">
                    <a href="{{ route('page', ['page' => 'about']) }}" class="nav-link px-2 footer-link">About</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('page', ['page' => 'privacy-policy']) }}" class="nav-link px-2 footer-link">Privacy Policy</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('page', ['page' => 'terms']) }}" class="nav-link px-2 footer-link">Terms and Conditions</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('page', ['page' => 'disclaimer']) }}" class="nav-link px-2 footer-link">Disclaimer</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('page', ['page' => 'dmca']) }}" class="nav-link px-2 footer-link">DMCA</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('contact') }}" class="nav-link px-2 footer-link">Contact Us</a>
                </li>
            </ul>
            <p class="text-center footer-copyright mb-0">&copy; {{ date('Y') }} <a href="{{ route('index') }}" class="text-white">{{ config('app.name') }}</a></p>
        </footer>
    </div>
</div>