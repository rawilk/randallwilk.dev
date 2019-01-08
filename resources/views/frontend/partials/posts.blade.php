<div class="blog-posts">
    <div class="row" v-cloak>
        @foreach ($posts as $post)
            @php
                $postLink = route('frontend.posts.view', ['post' => $post['slug']]);
            @endphp

            <b-col md="6">
                <b-card class="mb-3" no-body>
                    <div class="post-image">
                        <b-link href="{{ $postLink }}">
                            <b-img src="{{ getPostImage($post) }}" alt="{{ $post['title'] }}"></b-img>
                        </b-link>
                    </div>

                    <div class="post-content truncate">
                        <p class="m-0 text-muted">
                            {{ \Carbon\Carbon::parse($post['date'])->format('F d, Y') }}
                        </p>

                        <b-link href="{{ $postLink }}">
                            <p class="card-title post-title">{{ $post['title'] }}</p>
                        </b-link>

                        <p>
                            {{ $post['excerpt'] ?? '' }}
                        </p>

                        <b-link href="{{ $postLink }}" class="truncate-link text-muted text-semibold">
                            Read more...
                        </b-link>
                    </div>
                </b-card>
            </b-col>
        @endforeach
    </div>
</div>