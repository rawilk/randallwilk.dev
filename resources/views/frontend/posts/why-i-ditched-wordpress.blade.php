@extends('layouts.single-post')

@section('post-content')
    <div class="container">
        <p>
            A website can be a very crucial factor for any company; it is like your public face or a
            house address. Your home page is the equivalent to visiting your office and seeing how aesthetic
            the reception area is to give that first impression. It's very important to keep it clean,
            informative, and also secure from any threats out there.
        </p>

        <p>
            When I first built my website, I built it in Wordpress mainly because I worked in it for the majority
            of my projects at work at the time so I was comfortable with it. At the time it was convenient for me
            to choose a theme that I could get the look and feel I wanted for the site with maybe a few small
            tweaks here and there. Hosting was also extremely easy and cost effective with shared hosting
            plans available all over the place for as little as $3 per month on your first year.
        </p>

        <p>
            For a while (well even until I switched platforms), things ran smoothly on my site. I was satisfied
            with how things were built and ran on the site. However as I started to work more and more
            on projects built with Laravel I started dislike Wordpress more and more. My switch from Wordpress
            to Laravel is long overdue as I've actually been thinking about switching for a while but I never actually
            did anything about it until about a month ago.
        </p>

        <p>
            Here are some of the main reasons why I ditched Wordpress for Laravel:
        </p>

        <ol>
            <li>
                Maintaining plugins and themes on a Wordpress site is time consuming and very frustrating
                when an update is made and the whole site breaks. I'm also really not a fan of how often
                some plugins and themes update.
            </li>
            <li>
                I felt limited with Wordpress. There's only so much you can do with Wordpress, even with
                all the plugins and themes out there. Certain tasks were much more difficult or just not
                possible to accomplish in Wordpress when I could have easily built these functionalities
                out in Laravel where I have the freedom to code things how I need to.
            </li>
            <li>
                Poor security. Because of its ubiquity, Wordpress has also become a very popular target
                for hackers and attackers to test their skills. I personally never have had a problem with
                being hacked but I have seen clients have their Wordpress sites hacked due to them not
                keeping their Wordpress installation up-to-date or from other poor security practices.
            </li>
            <li>
                The speed of Wordpress is also an issue for me. There are many features of Wordpress
                that are often unused and just causes bloated code and can take it longer for the server
                to process the site.
            </li>
            <li>
                More of a personal one here, I'm just not a fan of the interface. Wordpress version 5.0 was
                really the driving force for me to finally ditch the platform. I did not like the new
                gutenberg editor and I really did not like having it forced on me.
            </li>
            <li>
                One other personal reason is the coding style of Wordpress. I absolutely hate it. I love
                that Laravel uses the psr-2 code style guidelines as I feel it makes much easier to work
                with and much less taxing on the mind. I can't believe that Wordpress still uses out-dated
                syntax and styles.
            </li>
        </ol>

        <p>
            It was really a no-brainer for me to switch to Laravel. My expertise on Laravel and just programming
            in general has improved a lot since I first built my website. I have much more control over everything
            on the Laravel version of my website and if it performs poorly, well that's almost entirely on me.
            I also use webpack as a build tool to easily take my styles and scripts from development to production.
            Although it is possible to do this in Wordpress, I always felt it was kind of clunky to do, as are
            many things on the platform.
        </p>

        <p>
            My server knowledge has greatly improved as well, so no more shared hosting for me. Hosting on my own
            server, through linode in this instance, gives me further flexibility in managing my site. Now,
            I'm free to run artisan commands to optimise the site and also run my scripts through webpack and
            npm to keep them up-to-date and optimised. Of course this can be accomplished through shared hosting,
            but with my own server I'm not at the mercy of my hosting provider to install the php version I need
            or my build tools.
        </p>

        <p>
            In no way is this post meant to bash Wordpress or deter anyone else from using the platform. As a
            programmer, Laravel makes more sense for me as I'm comfortable dealing with HTML directly to build
            out pages, so I don't need an interface to handle that for me. For the more less technical users
            and simple websites, Wordpress is still a good platform to use, although in the future I do plan
            on building out a CMS built on the Laravel framework as an alternative to Wordpress or even
            other already built Laravel CMS solutions out there.
        </p>
    </div>
@endsection