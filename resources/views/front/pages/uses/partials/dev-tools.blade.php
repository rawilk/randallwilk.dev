<x-front.section-list heading="Development tools" id="dev-tools">
    <x-front.section-list-item title="Warp Terminal">
        <p>
            My terminal of choice is
            <x-front.legal-link url="https://www.warp.dev/" target="_blank" text="warp" after="." />
            I'm also using the
            <a href="https://en.wikipedia.org/wiki/Z_shell" target="_blank" rel="noreferrer noopener">Z shell</a>
            and
            <x-front.legal-link url="https://ohmyz.sh/" target="_blank" text="Oh My Zsh" after="." />
        </p>

        <p>
            For theming, I use the Base16 Material theme, which is a
            <x-front.legal-link url="https://docs.warp.dev/appearance/custom-themes" target="_blank" text="custom theme" />
            from Warp.

            The font I use is
            <x-front.legal-link url="https://github.com/powerline/fonts/blob/master/SourceCodePro/Source%20Code%20Pro%20for%20Powerline.otf" target="_blank" text="Source Code Pro for Powerline" after="." />
            I also use several custom
            <x-front.legal-link url="https://github.com/rawilk/dotfiles/blob/main/shell/.aliases" target="_blank" text="aliases" />
            and
            <x-front.legal-link url="https://github.com/rawilk/dotfiles/blob/main/shell/.functions" target="_blank" text="functions" after="." />
        </p>

        <p>
            All my terminal settings are saved in
            <x-front.legal-link url="https://github.com/rawilk/dotfiles" target="_blank" text="my dotfiles repository" after="." />
            If you want the same environment, you can follow the installation instructions of the repo. I do recommend
            forking and customizing the installation script to fit your needs, however.
        </p>

        <x-images.lightbox
            :src="asset('images/uses/warp-screenshot.png')"
            alt="Warp Terminal"
            class="mx-auto w-[40rem] -mb-[35px]"
        />
    </x-front.section-list-item>

    <x-front.section-list-item title="PhpStorm">
        <p>
            <x-front.legal-link url="https://www.jetbrains.com/phpstorm" target="_blank" text="PhpStorm" />
            is still my IDE of choice. I've tried many others in the past including Visual Studio Code, Sublime, or Atom, but I keep
            coming back to PhpStorm. Here's a screenshot of my editor setup:
        </p>

        <x-images.lightbox
            :src="asset('images/uses/phpstorm-screenshot.png?v=01JARTKKG5QEGZDMRNP1W5BCXS')"
            alt="PhpStorm IDE"
            class="mx-auto mb-4"
        />

        <p>
            I'm using the
            <x-front.legal-link url="https://plugins.jetbrains.com/plugin/11938-one-dark-theme" target="_blank" text="One Dark Theme" />
            with the Vivid color scheme, and I use Fira Code as my editor font.
        </p>

        <p>
            Since I mostly work on Laravel projects or packages, I usually enable the
            <x-front.legal-link url="https://laravel-idea.com/" target="_blank" text="Laravel Idea" />
            plugin. It's a paid plugin, but it's definitely worth the money since it can provide stuff like
            auto-completions for route names, request fields and more.
        </p>

        <p>
            To make testing more convenient, I also like to use the
            <x-front.legal-link url="https://plugins.jetbrains.com/plugin/14636-pest" target="_blank" text="Pest Plugin" after="." />
            It makes
            <x-front.legal-link url="https://pestphp.com/" target="_blank" text="Pest" />
            a first class citizen in the IDE, and as another perk, it's completely free.
        </p>

        <p>
            I've also found it very helpful to configure tasks to run both <code>npm run dev</code> and <code>php artisan horizon</code>
            automatically when I open projects, so I don't need to keep manually running them.
        </p>
    </x-front.section-list-item>

    <x-front.section-list-item title="Laravel Herd">
        <p>
            Using <x-front.legal-link url="https://herd.laravel.com/" target="_blank" text="Herd" />
            for local development is a no-brainer. In my experience it's made it much easier to manage my sites and
            local php and node versions, plus it runs each site much faster.
        </p>

        <p>
            The free version is enough for my needs, as it's easy enough to install and run my own MySQL/PostgreSQL instances without
            needing to pay for Herd to do it for me.
        </p>
    </x-front.section-list-item>

    <x-front.section-list-item title="DBngin">
        <p>
            Speaking of databases, <x-front.legal-link url="https://dbngin.com/" target="_blank" text="DBngin" /> makes it trivial to install
            and run instances of MySQL and PostgreSQL locally. I used to just install them through Homebrew, however I've found this
            to be much better solution for my requirements.
        </p>
    </x-front.section-list-item>

    <x-front.section-list-item title="Ray">
        <p>
            <x-front.legal-link url="https://myray.app/" target="_blank" text="Ray" />
            is an excellent debugging tool I like to use from the amazing
            <x-front.legal-link url="https://spatie.be" target="_blank" text="Spatie" after="." />
        </p>
    </x-front.section-list-item>

    <x-front.section-list-item title="TablePlus">
        <p>
            In the past, I've used DataGrip from JetBrains to manage my databases. Now, I prefer to use
            <x-front.legal-link url="https://tableplus.com/" target="_blank" text="TablePlus" after="," />
            as it is much simpler to use.
        </p>
    </x-front.section-list-item>

    <x-front.section-list-item title="Helo">
        <p>
            For email testing, I love using
            <x-front.legal-link url="https://usehelo.com/" target="_blank" text="Helo" after="." />
        </p>
    </x-front.section-list-item>

    <x-front.section-list-item title="Tinkerwell">
        <p>
            Another amazing product from
            <x-front.legal-link url="https://beyondco.de/" target="_blank" text="BeyondCode" after="," />
            <x-front.legal-link url="https://tinkerwell.app/" target="_blank" text="Tinkerwell" />
            makes running arbitrary code either locally or even remotely via ssh simple to do.
        </p>
    </x-front.section-list-item>

    <x-front.section-list-item title="Transmit">
        <p>
            Sometimes to connect to S3, FTP and SFTP servers, I like to use
            <x-front.legal-link url="https://panic.com/transmit/" target="_blank" text="Transmit" after="." />
        </p>
    </x-front.section-list-item>
</x-front.section-list>
