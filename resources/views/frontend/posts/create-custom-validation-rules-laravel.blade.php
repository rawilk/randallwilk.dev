@extends('layouts.single-post')

@section('post-content')
    <div class="container">
        <p>
            One thing I really love about Laravel is its built-in <b-link href="https://laravel.com/docs/5.7/validation" target="_blank">validation</b-link> rules.
            Laravel makes it really easy to add validation rules and messages to your models. For most scenarios, using
            the built-in validation rules will be enough, but what if those rules aren't enough? That's where creating
            your own custom validation rules comes in to play.
        </p>

        <p>
            Any time I need some kind of custom validation rule on an attribute, I make a custom validation
            rule because I can then reuse the rule on any other attribute that might need the same kind of
            validation.
        </p>

        <p>
            Let's say you have a form where a user can update their password on some kind of profile page,
            but you don't want them to enter a password that contains their name. This could easily be accomplished
            with a custom validation rule.
        </p>

        <p>
            The custom rule would look like this:
        </p>

        <github-gist gist-id="rawilk/febe62182d9295f3dfe22017aaa66cf6"></github-gist>

        <p class="my-5b">
            You could then use the validation rule in a request like this:
        </p>

        <github-gist gist-id="rawilk/87afa11c12ee8a8fc483ca8f956528af"></github-gist>

        <p class="mt-5b">
            That's really all there is to creating a custom validation rule and using it. The rule
            shown above is an extremely simple example of what is possible with a custom validation rule.
        </p>

        <p>
            For a more in-depth explanation on this take a look at a
            <b-link href="https://medium.com/@taylorotwell/custom-validation-rules-in-laravel-5-5-c6cb250f65df" target="_blank">post by Taylor Otwell</b-link>
            which goes into more detail on the subject.
        </p>

    </div>
@endsection