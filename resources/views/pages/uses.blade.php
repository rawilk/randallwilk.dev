@extends('layouts.page')
@section('title', 'My current setup')

@section('metaDescription', 'Randall Wilk is a full-stack web developer based in Wausau, WI.')

@section('content')
    <h1>My current setup</h1>

    <p>
        To be honest, I wasn't going to post this originally, but I've seen other developers do similar
        things, so I thought why not? Here are the settings and apps that I'm using.
    </p>

    <h2>IDE</h2>
    <p>
        I mainly program in PHP and my editor of choice is
        <a href="https://www.jetbrains.com/phpstorm/" target="_blank" rel="noopener">PhpStorm</a>.
        Here's a screenshot of it.
    </p>
    <img src="{{ asset('images/screenshots/phpstorm-screenshot.png') }}" alt="phpstorm screenshot">
    <p>
        I'm using the
        <a href="https://plugins.jetbrains.com/plugin/8006-material-theme-ui" target="_blank" rel="noopener">
            Material Theme UI
        </a>
        with the Oceanic color scheme. The font used is Fira Code medium.
    </p>
    <p>
        Like seen in the screenshot I've hidden a lot of things in the UI of PhpStorm. I like to keep it minimal, although I don't go overboard on it.
        I do like to have a few things showing in the toolbars for easy access.
    </p>

    <h2>Terminal</h2>
    <p>Here's a screenshot from my terminal.</p>
    <img src="{{ asset('images/screenshots/iterm-screenshot.png') }}" alt="iterm screenshot">
    <p>
        All my terminal settings are saved in <a href="https://github.com/rawilk/dotfiles">my dotfiles repository</a>.
        If you want the same environment, follow the instructions of the repo.
    </p>
    <p>
        My terminal of choice is <a href="https://www.iterm2.com/">iTerm2</a>. I'm also using the
        <a href="https://en.wikipedia.org/wiki/Z_shell">Z shell</a> and <a href="https://ohmyz.sh/">Oh My Zsh</a>.
    </p>
    <p>
        The color scheme used is <a href="https://github.com/rawilk/dotfiles/blob/master/misc/Solarized%20Dark%20Corrected.itermcolors">a slightly modified version of Solarized Dark</a>.
        I actually am using the exact same file I found from Freek Van der Herten in <a href="https://github.com/freekmurze/dotfiles">his dotfiles repository</a>.
        The font I'm using differs from his though, as I'm using a patched version of Source Code Pro. I also use several
        <a href="https://github.com/rawilk/dotfiles/blob/master/shell/.aliases">aliases</a> and
        <a href="https://github.com/rawilk/dotfiles/blob/master/shell/.functions">functions</a>, much of which are similar to Freek's.
    </p>

    <h2>MacOS</h2>
    <p>
        I actually haven't been using MacOS for very long, but I really like it so far. I'm a long time Windows user, so switching has been
        a big learning experience for me, but I can't say I'd ever want to go back to it.
    </p>
    <p>
        As with my IDE, I like to keep things minimal. I hide the dock by default as well as removing all apps from it. I also like
        to keep my desktop clean; not even hard disks are allowed to be displayed here. Generally, only apps that are running are shown
        in the dock. Here's a screenshot where I've deliberately moved my pointer down so the dock is shown.
    </p>
    <img src="{{ asset('images/screenshots/mac-screenshot.png') }}" alt="mac screenshot">
    <p>
        I've also hidden the indicator for running apps (that dot underneath each app), because if the app
        is in my dock, it's running.
    </p>
    <p>
        These are some of the apps I'm using:
    </p>

    <ul>
        <li>To run projects locally I use <a href="https://laravel.com/docs/master/valet">Laravel Valet</a>.</li>
        <li>
            I've come to really love <a href="https://www.alfredapp.com/">Alfred</a>. I'm also using several workflows.
            First up is <a href="https://github.com/sebastiandedeyne/naming-things-alfred-workflow">syn and assoc</a> by <a href="https://twitter.com/sebdedeyne">Sebastian De Deyne</a>, to help with naming things.
            Another one is <a href="https://github.com/deanishe/alfred-fakeum">fakeum</a> by
            <a href="https://github.com/deanishe">deanishe</a> for generating quick fake test data.
            Last but not least I use the <a href="https://github.com/tillkruss/alfred-laravel-docs">Laravel docs workflow</a>
            by <a href="https://twitter.com/tillkruss">Till Krüss</a> to easily search the Laravel docs.
        </li>
        <li>
            To connect to sftp servers I use <a href="https://panic.com/transmit/">Transmit</a>.
        </li>
        <li>
            Whenever I need to run an arbitrary piece of code or test something quick, I use
            <a href="https://tinkerwell.app">Tinkerwell</a>.
        </li>
        <li>
            Local mail testing is done with <a href="https://usehelo.com/">HELO</a>.
        </li>
        <li>
            Databases are managed with <a href="https://www.jetbrains.com/datagrip">DataGrip</a>.
        </li>
        <li>
            If you're not using a password manager, you really should be. I use
            <a href="https://www.lastpass.com/">LastPass</a>.
        </li>
        <li>
            All settings of my apps are backed up to iCloud through <a href="https://github.com/lra/mackup">Mackup</a>.
            This is a fantastic piece of software that moves all your preferences to a cloud storage of your choice
            and symlinks them.
        </li>
        <li>
            For email, I use <a href="https://sparkmailapp.com/">Spark</a>.
        </li>
        <li>
            My browser of choice is <a href="https://www.google.com/chrome/">Google Chrome</a>.
        </li>
        <li>
            Coming from a Windows background, I still like to use
            <a href="https://www.microsoft.com/en-us/microsoft-365/word">Microsoft Word</a> for most word processing
            tasks and <a href="https://www.microsoft.com/en-us/microsoft-365/excel">Microsoft Excel</a> for
            viewing and editing spreadsheets.
        </li>
        <li>
            For listening to music, I prefer to use <a href="https://spotify.com">Spotify</a>.
        </li>
    </ul>

    <h2>Hardware</h2>
    <div>
        <img src="{{ asset('images/screenshots/screenshot-macos.png') }}" alt="MacOS screenshot">
    </div>
    <p>
        I'm using a 27" iMac with a 3.7 GHz 6-Core Intel Core i5 processor, 8GB of RAM
        and 1TB SSD.
    </p>
    <p>
        My peripherals include:
    </p>
    <ul>
        <li>a silver wireless Apple Magic Keyboard with numeric keys</li>
        <li>a Basilisk X HyperSpeed mouse</li>
    </ul>

    <p>My current phone is an iPhone 11 with 64 GB of storage.</p>
@endsection
