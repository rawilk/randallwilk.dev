<section class="page-header page-header-modern bg-color-light-scale-1 page-header-md">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col">
                @if (isset($previousProject) && $previousProject !== null)
                    <a href="{{ route('frontend.projects.view', ['project' => $previousProject['slug']]) }}" class="portfolio-prev text-decoration-none d-block">
                        <div class="d-flex align-items-center line-height-1">
                            <i class="mdi mdi-arrow-left text-dark mr-3"></i>
                            <div class="d-none d-sm-block line-height-1">
                                <span class="text-dark opacity-4 text-1">PREVIOUS PROJECT</span>
                                <h4 class="font-weight-bold text-3 mb-0">{{ $previousProject['title'] }}</h4>
                            </div>
                        </div>
                    </a>
                @endif
            </div>

            <div class="col">
                <div class="row">
                    <div class="col-md-12 align-self-center p-static order-2 text-center">
                        <div class="overflow-hidden pb-2">
                            <h1 class="text-dark font-weight-bold text-9">
                                {{ $slot }}
                            </h1>
                        </div>
                    </div>

                    @if (isset($breadcrumbs))
                        <div class="col-md-12 align-self-center order-1">
                            <ul class="breadcrumb d-block text-center">
                                <li>
                                    <a href="{!! route('frontend.home') !!}">Home</a>
                                </li>

                                @foreach ($breadcrumbs as $index => $breadcrumb)
                                    <li class="{{ $loop->last ? 'active' : null }}">
                                        @if ($loop->last)
                                            {{ $breadcrumb['display'] }}
                                        @else
                                            <a href="{{ $breadcrumb['url'] }}">
                                                {{ $breadcrumb['display'] }}
                                            </a>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                </div>
            </div>

            <div class="col">
                @if (isset($nextProject) && $nextProject !== null)
                    <a href="{{ route('frontend.projects.view', ['project' => $nextProject['slug']]) }}" class="portfolio-next text-decoration-none d-block float-right">
                        <div class="d-flex align-items-center text-right line-height-1">
                            <div class="d-none d-sm-block line-height-1">
                                <span class="text-dark opacity-4 text-1">NEXT PROJECT</span>
                                <h4 class="font-weight-bold text-3 mb-0">{{ $nextProject['title'] }}</h4>
                            </div>
                            <i class="mdi mdi-arrow-right text-dark text-4 ml-3"></i>
                        </div>
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>
