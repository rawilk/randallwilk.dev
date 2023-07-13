<x-front.section-list heading="Development tools" id="dev-tools">
    <x-front.section-list-item title="iTerm2">
        <p>
            My terminal of choice is
            <x-front.legal-link url="https://www.iterm2.com/" target="_blank" text="iTerm2" after="." />
            I'm also using the
            <a href="https://en.wikipedia.org/wiki/Z_shell" target="_blank" rel="noreferrer noopener">Z shell</a>
            and
            <x-front.legal-link url="https://ohmyz.sh/" target="_blank" text="Oh My Zsh" after="." />
        </p>

        <p>
            For theming, I use a slightly modified version of
            <x-front.legal-link url="https://github.com/rawilk/dotfiles/blob/main/misc/Solarized%20Dark%20Corrected.itermcolors" target="_blank" text="Solarized Dark" after="." />
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
            :src="asset('images/uses/archive/iTerm screenshot.png')"
            alt="iTerm Screenshot"
            class="-mb-[35px]"
        />
    </x-front.section-list-item>

    <x-front.section-list-item title="PhpStorm">
        <p>
            I absolutely love using
            <x-front.legal-link url="https://www.jetbrains.com/phpstorm" target="_blank" text="PhpStorm" />
            for development. Since my primary language is PHP, it makes sense to use this one. I've tried others like
            Visual Studio Code or Atom, but I like this one the best. Here's a screenshot of my editor setup:
        </p>

        <x-images.lightbox
            :src="asset('images/uses/archive/PhpStorm screenshot.png')"
            alt="PhpStorm Screenshot"
            class="my-4"
        />

        <p>
            I'm using the
            <x-front.legal-link url="https://plugins.jetbrains.com/plugin/8006-material-theme-ui" target="_blank" text="Material Theme UI" />
            theme with the Material Oceanic color scheme. I use Fira Code as my editor font.
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
    </x-front.section-list-item>

    <x-front.section-list-item title="Laravel Valet">
        <p>
            When using macOS, it's a no-brainer to use
            <x-front.legal-link url="https://laravel.com/docs/master/valet" target="_blank" text="Laravel Valet" after="." />
            Valet makes it extremely simple to run my sites locally through Nginx.
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

    <x-front.section-list-item title="BabelEdit">
        <p>
            <x-front.legal-link url="https://www.codeandweb.com/babeledit" target="_blank" text="BabelEdit" />
            makes managing language files for Laravel projects simple. Instead of jumping between files for language
            lines, I can do it all in one single window.
        </p>
    </x-front.section-list-item>

    <x-front.section-list-item title="Transmit">
        <p>
            Sometimes to connect to S3, FTP and SFTP servers, I like to use
            <x-front.legal-link url="https://panic.com/transmit/" target="_blank" text="Transmit" after="." />
        </p>
    </x-front.section-list-item>
</x-front.section-list>
