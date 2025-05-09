<x-front.section-list heading="Software" id="software">
    <x-front.section-list-item title="macOS">
        <p>
            For the longest time I was a Windows user, until roughly late 2019 or so when I decided to give macOS
            Catalina a try. Now that I've been using it for a few years now, I can positively say I wouldn't ever switch
            back. For me, I love the development experience I get on a mac and the UI simple to work with. It's also
            great that it just works seamlessly with my other Apple devices.
        </p>

        <p>
            I like to keep my desktop pretty clean, so I hide the dock by default and never save anything to the desktop
            itself; not even drives are allowed on my desktop. I also only keep the apps are currently running in the
            dock and hide the indicator for running apps (that dot underneath each app), because if it's in the dock
            it's running. Here's a screenshot where I've deliberately moved my pointer down so the dock is shown.
        </p>

        <x-images.lightbox
            :src="asset('images/uses/desktop-screenshot.jpeg')"
            alt="macOS desktop"
            class="my-6"
        />

        <p>
            In
            <x-front.legal-link url="https://github.com/rawilk/dotfiles" target="_blank" text="my dotfiles repo" />
            you'll find some of my
            <x-front.legal-link url="https://github.com/rawilk/dotfiles/blob/main/macos/set-defaults.sh" target="_blank" text="custom macOS settings" after="." />
        </p>
    </x-front.section-list-item>

    <x-front.section-list-item title="Raycast">
        <p>
            I've never liked Spotlight on macOS, and previously I used Alfred as a replacement. However, now I've discovered
            <x-front.legal-link url="https://www.raycast.com/" target="_blank" text="Raycast" after="." />
            Raycast is my primary mode of navigating through macOS, as I've bound the keyboard shortcut to
            <kbd class="italic text-gray-500 inline-flex gap-x-1 shadow-none">
               <kbd class="font-mono">cmd</kbd>
               <span class="font-mono">+</span>
               <kbd class="font-mono">space</kbd>
            </kbd>
            (overriding Spotlight's keybinding).
        </p>

        <p>
            Some of the extensions I've installed for Raycast include:
        </p>

        <ul>
            <li>
                <x-front.legal-link url="https://www.raycast.com/gdsmith/jetbrains" target="_blank" text="JetBrains Toolbox Recent Projects" after=":" /> Allows me to open up a PhpStorm project from anywhere.
            </li>
            <li>
                <x-front.legal-link url="https://www.raycast.com/indykoning/laravel-docs" target="_blank" text="Laravel Docs" after=":" /> Allows me to search the Laravel Docs from anywhere.
            </li>
            <li>
                <x-front.legal-link url="https://www.raycast.com/vimtor/tailwindcss" target="_blank" text="Tailwind CSS" after=":" /> Allows me to search Tailwind Docs and classes from anywhere.
            </li>
        </ul>
    </x-front.section-list-item>

    <x-front.section-list-item title="1Password">
        <p>
            If you're not using a password manager, you're doing it wrong. I prefer to use
            <x-front.legal-link url="https://1password.com/" target="_blank" text="1Password" after="." />
            I honestly don't think I could live without 1Password. Having access to all my vaults across all my devices
            is also a plus.
        </p>
    </x-front.section-list-item>

    <x-front.section-list-item title="Backblaze">
        <p>
            In addition to time machine backups, I also use
            <x-front.legal-link url="https://www.backblaze.com/" target="_blank" text="Backblaze" />
            as an offsite backup.
        </p>
    </x-front.section-list-item>

    <x-front.section-list-item title="Browser">
        <p>
            My browser of choice is Google Chrome, although I occasionally will use Firefox or Safari too. To block ads
            on certain sites, I use the
            <x-front.legal-link url="https://adguard.com/" target="_blank" text="AdGuard extension" after="." />
        </p>
    </x-front.section-list-item>

    <x-front.section-list-item title="Spark">
        <p>
            On both my MacBook and iPhone, I prefer to use the
            <x-front.legal-link url="https://sparkmailapp.com/" target="_blank" text="Spark Mail App" />
            for handling my emails.
        </p>
    </x-front.section-list-item>

    <x-front.section-list-item title="Spotify">
        <p>
            I'm almost always listening to music either at work or when I'm driving, so
            <x-front.legal-link url="https://spotify.com" target="_blank" text="Spotify" />
            is very nice to have for streaming music.
        </p>

        <p>
            When I'm just trying to focus, lately I've been listening to either the
            <x-front.legal-link url="https://open.spotify.com/playlist/37i9dQZF1DX5trt9i14X7j?si=bd88755d6dd1409b" target="_blank" text="Coding Mode" />
            playlist or the
            <x-front.legal-link url="https://open.spotify.com/playlist/37i9dQZF1DXa2SPUyWl8Y5?si=057a9fd337474ade" target="_blank" text="Beats to think to" />
            playlist.
        </p>
    </x-front.section-list-item>

    <x-front.section-list-item title="Bartender">
        <p>
            <x-front.legal-link url="https://www.macbartender.com/" target="_blank" text="Bartender" />
            is a nice little utility that helps manage how the menu bar is laid out for macOS.
        </p>
    </x-front.section-list-item>

    <x-front.section-list-item title="Showcode">
        <p>
            <x-front.legal-link url="https://showcode.app/" target="_blank" text="Showcode" />
            makes it easy to share beautiful screenshots of code snippets.
        </p>
    </x-front.section-list-item>

    <x-front.section-list-item title="Alcove">
        <p>
            <x-front.legal-link url="https://tryalcove.com/" target="_blank" text="Alcove" />
            is a nice little app that turns the notch on the MacBook into a dynamic island, kind of like
            how it is on the iPhone. It's not quite as versatile as it is on iOS, however it is nice for a quick glance
            for things like what music is currently playing. In my desktop screenshot above it is shown with the current
            song from Spotify playing in it.
        </p>
    </x-front.section-list-item>

    <x-front.section-list-item title="Shottr">
        <p>
            <x-front.legal-link url="https://shottr.cc/" target="_blank" text="Shottr" />
            makes manipulating screenshots a lot easier and nicer than the default screenshot functionality provided
            by macOS. My favorite feature from it is the ability to easily blur out text in my screenshots.
        </p>
    </x-front.section-list-item>

    <x-front.section-list-item title="Blip">
        <p>
            While Airdrop works very well between Apple devices, it can be kind of annoying to transfer files between either my Mac or iPhone
            and my Windows PC when I need to. The <x-front.legal-link url="https://blip.net/" target="_blank" text="Blip" />
            app helps bridge that gap and is free and easy to use. I've actually started using it more often between my Apple devices as well
            than I use Airdrop now.
        </p>
    </x-front.section-list-item>

    <x-front.section-list-item title="ChatGPT App">
        <p>
            Having a desktop app to use for my <x-front.legal-link url="https://chatgpt.com" target="_blank" text="ChatGPT" />
            needs is just more convenient than having to always navigate to the website when I need AI for something.
        </p>
    </x-front.section-list-item>

    <x-front.section-list-item title="DaisyDisk">
        <p>
            <x-front.legal-link url="https://daisydiskapp.com/" target="_blank" text="DaisyDisk" />
            is a nice utility that helps you analyze how your disk space is being used. It's honestly a utility that I
            should probably use more often.
        </p>
    </x-front.section-list-item>
</x-front.section-list>
