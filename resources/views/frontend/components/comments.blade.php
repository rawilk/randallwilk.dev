<div class="post-block mt-5b mb-4 post-comments">
    <h4 class="mb-3">Comments</h4>
    <div id="disqus_thread"></div>
</div>

@push('js')
    <script>
        var disqus_config = function () {
            this.page.url = '{{ $canonical ?? request()->url() }}';
            this.page.identifier = '{{ $disqusIdentifier ?? null }}';
        };

        (function() { // DON'T EDIT BELOW THIS LINE
            var d = document, s = d.createElement('script');
            s.src = 'https://randallwilk.disqus.com/embed.js';
            s.setAttribute('data-timestamp', +new Date());
            (d.head || d.body).appendChild(s);
        })();
    </script>
    <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
@endpush