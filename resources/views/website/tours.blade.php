@extends('website.layout.app')
@section('title', config('app.name') . ' – Our Tour Packages')
@section('content')
    <main>
        <!-- Header Start -->
        <div class="container-fluid bg-breadcrumb">
            <div class="container text-center py-5" style="max-width: 900px;">
                <h3 class="text-white display-3 mb-4 wow fadeInDown" data-wow-delay="0.1s">Our Tour Packages</h3>
                <ol class="breadcrumb justify-content-center text-white mb-0 wow fadeInDown" data-wow-delay="0.3s">
                    <li class="breadcrumb-item"><a href="{{ route('website.index') }}" class="text-white">Home</a></li>
                    <li class="breadcrumb-item active text-secondary">Tour Packages</li>
                </ol>
            </div>
        </div>
        <!-- Header End -->
        <!-- Tours We Offer Start -->
        <div class="container-fluid tours overflow-hidden py-5">
            <div class="container py-5">
                <div class="section-title text-center wow fadeInUp" data-wow-delay="0.1s" style="margin-bottom: 70px;">
                    <div class="sub-style">
                        <h5 class="sub-title text-primary px-3">TOUR PACKAGES WE OFFER</h5>
                    </div>
                    <h1 class="display-5 mb-4">Discover Amazing Journeys with Our Custom Tour Packages</h1>
                    <p class="mb-0">Embark on unforgettable adventures with {{ config('app.name') }}. From exotic beach
                        escapes to cultural explorations, our custom-designed tour packages ensure hassle-free travel and
                        lifelong
                        memories tailored just for you.</p>
                </div>

                <!-- Filters & Search Section -->
                <div class="row mb-5">
                    <div class="col-12">
                        <div class="card shadow-lg border-0 rounded-4 overflow-hidden"
                            style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                            <div class="card-header bg-primary text-white py-4">
                                <h4 class="mb-0 fw-bold text-center text-white">
                                    <i class="fas fa-filter-circle me-2"></i>Refine Your Tour Package Search
                                </h4>
                            </div>
                            <div class="card-body p-4">
                                <form method="GET" action="{{ route('website.tours') }}" id="tourFilters">
                                    <div class="row g-3 align-items-end">
                                        <!-- Search -->
                                        <div class="col-lg-4 col-md-6">
                                            <label for="search" class="form-label fw-semibold text-muted small">
                                                <i class="fas fa-search text-primary me-1"></i>Search Tour Packages
                                            </label>
                                            <div class="input-group input-group-lg rounded-3">
                                                <span class="input-group-text bg-white border-end-0 rounded-start-3">
                                                    <i class="fas fa-search text-primary"></i>
                                                </span>
                                                <input type="text"
                                                    class="form-control border-start-0 rounded-end-3 shadow-sm"
                                                    id="search" name="search" value="{{ request('search') }}"
                                                    placeholder="e.g., Bali Adventure or Beach Escape...">
                                            </div>
                                        </div>

                                        <!-- Duration Filter -->
                                        <div class="col-lg-2 col-md-6">
                                            <label for="duration" class="form-label fw-semibold text-muted small">
                                                <i class="fas fa-calendar-alt text-primary me-1"></i>Duration
                                            </label>
                                            <select class="form-select form-select-lg rounded-3 shadow-sm" id="duration"
                                                name="duration">
                                                <option value="all">Any Length</option>
                                                <option value="1-3" {{ request('duration') == '1-3' ? 'selected' : '' }}>
                                                    1-3 Days</option>
                                                <option value="4-7" {{ request('duration') == '4-7' ? 'selected' : '' }}>
                                                    4-7 Days</option>
                                                <option value="8-14"
                                                    {{ request('duration') == '8-14' ? 'selected' : '' }}>8-14 Days</option>
                                                <option value="15+" {{ request('duration') == '15+' ? 'selected' : '' }}>
                                                    15+ Days</option>
                                            </select>
                                        </div>

                                        <!-- Country Filter -->
                                        <div class="col-lg-3 col-md-6">
                                            <label for="country" class="form-label fw-semibold text-muted small">
                                                <i class="fas fa-globe text-primary me-1"></i>Destination Country
                                            </label>
                                            <select class="form-select form-select-lg rounded-3 shadow-sm" id="country"
                                                name="country">
                                                <option value="all">All Countries</option>
                                                @foreach ($countries as $country)
                                                    <option value="{{ $country->id }}"
                                                        {{ request('country') == $country->id ? 'selected' : '' }}>
                                                        {{ $country->country }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Sort By -->
                                        <div class="col-lg-3 col-md-6">
                                            <label for="sort_by" class="form-label fw-semibold text-muted small">
                                                <i class="fas fa-sort text-primary me-1"></i>Sort By
                                            </label>
                                            <select class="form-select form-select-lg rounded-3 shadow-sm" id="sort_by"
                                                name="sort_by">
                                                <option value="name"
                                                    {{ request('sort_by') == 'name' ? 'selected' : '' }}>
                                                    Name (A-Z)
                                                </option>
                                                <option value="duration"
                                                    {{ request('sort_by') == 'duration' ? 'selected' : '' }}>Duration
                                                </option>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="d-flex gap-2">
                                                <button type="submit"
                                                    class="btn btn-primary btn-lg px-4 rounded-pill shadow-sm transition-all"
                                                    style="transition: all 0.3s ease;">
                                                    <i class="fas fa-magic me-2"></i>Apply Filters
                                                </button>
                                                <a href="{{ route('website.tours') }}"
                                                    class="btn btn-outline-secondary btn-lg px-4 rounded-pill shadow-sm transition-all"
                                                    style="transition: all 0.3s ease;">
                                                    <i class="fas fa-undo me-2"></i>Clear All
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <div class="d-flex justify-content-end align-items-center">
                                                <div class="d-flex align-items-center gap-1">
                                                    <label for="sort_order"
                                                        class="mb-0 fw-semibold text-muted small me-2">Order:</label>
                                                    <select
                                                        class="form-select form-select-sm rounded-3 shadow-sm d-inline-block w-auto"
                                                        name="sort_order"
                                                        onchange="document.getElementById('tourFilters').submit();">
                                                        <option value="asc"
                                                            {{ request('sort_order') == 'asc' ? 'selected' : '' }}>
                                                            <i
                                                                class="fas fa-sort-alpha-down-alt text-muted me-1"></i>Ascending
                                                        </option>
                                                        <option value="desc"
                                                            {{ request('sort_order') == 'desc' ? 'selected' : '' }}>
                                                            <i class="fas fa-sort-alpha-down text-muted me-1"></i>Descending
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Results Info -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-0 text-muted">
                                    Showing {{ $tour_packages->firstItem() ?? 0 }} to
                                    {{ $tour_packages->lastItem() ?? 0 }}
                                    of {{ $tour_packages->total() }} tour packages
                                    @if (request()->filled('search'))
                                        for "<strong>{{ request('search') }}</strong>"
                                    @endif
                                </p>
                            </div>
                            @if ($tour_packages->hasPages())
                                <div>
                                    <small class="text-muted">Page {{ $tour_packages->currentPage() }} of
                                        {{ $tour_packages->lastPage() }}</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Tour Packages Section -->
                @if ($tour_packages->count() > 0)
                    <div class="row g-4">
                        @forelse($tour_packages as $index => $tour_package)
                            <div class="col-lg-6 col-xl-3 wow fadeInUp" data-wow-delay="{{ 0.1 + ($index % 4) * 0.1 }}s">
                                <div class="tour-item h-100">
                                    <div class="tour-inner h-100 d-flex flex-column">
                                        <div class="tour-img rounded position-relative">
                                            @if ($tour_package->image)
                                                <img src="{{ asset($tour_package->image) }}" class="img-fluid w-100"
                                                    alt="{{ $tour_package->name }} Image"
                                                    style="height: 250px; object-fit: cover;">
                                            @else
                                                <img src="{{ asset('website/assets/img/default-tour.jpg') }}"
                                                    class="img-fluid w-100" alt="{{ $tour_package->name }} Image"
                                                    style="height: 250px; object-fit: cover;">
                                            @endif
                                            <div class="position-absolute top-0 start-0 m-3">
                                                @if ($tour_package->country)
                                                    <span class="badge bg-primary px-3 py-2 rounded-pill me-1">
                                                        <i
                                                            class="fas fa-globe me-1"></i>{{ $tour_package->country->country }}
                                                    </span>
                                                @endif
                                            </div>
                                            @if ($tour_package->duration_days)
                                                <div class="position-absolute top-0 end-0 m-3">
                                                    <span class="badge bg-success px-3 py-2 rounded-pill">
                                                        <i
                                                            class="fas fa-calendar-alt me-1"></i>{{ $tour_package->duration_days }}
                                                        Days
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                        <div
                                            class="tour-content bg-secondary rounded-bottom p-4 flex-grow-1 d-flex flex-column">
                                            <a href="{{ route('website.tour-package-details', $tour_package->slug) }}"
                                                class="text-decoration-none">
                                                <h4 class="text-white mb-3">{{ $tour_package->name }}</h4>
                                            </a>
                                            <p class="mb-3 flex-grow-1 text-white">
                                                {{ Str::limit(html_entity_decode(strip_tags($tour_package->description)), 100) }}
                                            </p>

                                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                                <div class="tour-info text-white">
                                                    @if ($tour_package->duration_days)
                                                        <small><i
                                                                class="fas fa-clock me-1"></i>{{ $tour_package->duration_days }}
                                                            Days</small>
                                                    @endif

                                                </div>
                                                <a class="btn btn-primary rounded-pill text-white py-2 px-4"
                                                    href="{{ route('website.tour-package-details', $tour_package->slug) }}">
                                                    View Details <i class="fa fa-arrow-right ms-2"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                        @endforelse
                    </div>
                @endif

                <!-- No Results Message -->
                @if ($tour_packages->count() == 0)
                    <div class="row">
                        <div class="col-12 text-center py-5">
                            <div class="alert alert-info d-inline-block">
                                <i class="fas fa-info-circle me-2"></i>
                                @if (request()->filled(['search', 'duration']))
                                    No tour packages found matching your criteria. Try adjusting your filters.
                                @else
                                    No tour packages available at the moment. Please check back later!
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Pagination -->
                @if ($tour_packages->hasPages())
                    <div class="row mt-5">
                        <div class="col-12">
                            <nav aria-label="Tour packages pagination">
                                <div class="d-flex justify-content-center">
                                    {{ $tour_packages->links('pagination::bootstrap-4') }}
                                </div>
                            </nav>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <!-- Tours We Offer End -->
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-submit form when filters change (except search)
            const filterElements = document.querySelectorAll('#country_id, #duration, #sort_by');
            filterElements.forEach(element => {
                element.addEventListener('change', function() {
                    document.getElementById('tourFilters').submit();
                });
            });

            // Debounced search
            const searchInput = document.getElementById('search');
            let searchTimeout;

            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    if (this.value.length >= 3 || this.value.length === 0) {
                        document.getElementById('tourFilters').submit();
                    }
                }, 500);
            });

            // Price range validation
            const minPriceInput = document.querySelector('input[name="min_price"]');
            const maxPriceInput = document.querySelector('input[name="max_price"]');

            function validatePriceRange() {
                const minPrice = parseFloat(minPriceInput.value) || 0;
                const maxPrice = parseFloat(maxPriceInput.value) || Infinity;

                if (minPrice > maxPrice && maxPrice !== Infinity) {
                    maxPriceInput.setCustomValidity('Maximum price must be greater than minimum price');
                } else {
                    maxPriceInput.setCustomValidity('');
                }
            }

            minPriceInput.addEventListener('change', validatePriceRange);
            maxPriceInput.addEventListener('change', validatePriceRange);

            // Smooth scroll to results after filter
            if (window.location.search && !window.location.hash) {
                setTimeout(() => {
                    document.querySelector('.tour-item')?.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }, 100);
            }
        });
    </script>
@endsection
