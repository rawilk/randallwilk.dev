<section class="page-header page-header-modern bg-color-light-scale-1 page-header-md">
    <div class="container">
        <div class="row">
            <div class="col-md-{{ isset($breadcrumbs) ? '8' : '12' }} order-2 order-md-1 align-self-center p-static">
                <h1 class="text-dark">{{ $title }}</h1>

                @if (isset($subtitle))
                    <span class="sub-title text-dark">{{ $subtitle }}</span>
                @endif
            </div>

            @if (isset($breadcrumbs))
                <div class="col-md-4 order-1 order-md-2 align-self-center">
                    <ul class="breadcrumb d-block text-md-right">
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
</section>
