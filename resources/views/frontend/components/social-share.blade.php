@php
    $shareUrl = request()->url();
    $shareTitle = isset($shareTitle) ? urlencode($shareTitle) : '&amp;'
@endphp

<ul class="social-icons">
    <li class="social-icons-facebook">
        <b-link href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" title="Share On Facebook">
            <i class="mdi mdi-facebook"></i>
        </b-link>
    </li>
    <li class="social-icons-twitter">
        <b-link href="https://twitter.com/intent/tweet?text={{ $shareTitle }}&amp;url={{ $shareUrl }}" target="_blank" title="Share On Twitter">
            <i class="mdi mdi-twitter"></i>
        </b-link>
    </li>
    <li class="social-icons-linkedin">
        <b-link href="https://www.linkedin.com/shareArticle?mini=true&amp;url={{ $shareUrl }};title={{ $shareTitle }}&amp;summary=&amp;source={{ urlencode(config('app.name')) }}"
                target="_blank" title="Share On LinkedIn">
            <i class="mdi mdi-linkedin"></i>
        </b-link>
    </li>
</ul>