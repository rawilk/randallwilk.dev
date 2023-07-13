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
            :src="asset('images/uses/macos-ventura-screenshot.png')"
            alt="macOS desktop"
            class="mb-4"
        />

        <p>
            In
            <x-front.legal-link url="https://github.com/rawilk/dotfiles" target="_blank" text="my dotfiles repo" />
            you'll find my
            <x-front.legal-link url="https://github.com/rawilk/dotfiles/blob/main/macos/set-defaults.sh" target="_blank" text="custom macOS settings" after="." />
        </p>
    </x-front.section-list-item>

    <x-front.section-list-item title="Raycast">
        <p>
            I've never liked Spotlight on macOS, and previously I used Alfred as a replacement. However, now I've discovered
            <x-front.legal-link url="https://www.raycast.com/" target="_blank" text="Raycast" after="." />
            Raycast is my primary mode of navigating through macOS, as I've bound the keyboard shortcut to
            <kbd class="italic text-slate-500 inline-flex gap-x-1">
               <kbd class="font-sans">cmd</kbd>
               <span class="font-sans">+</span>
               <kbd class="font-sans">space</kbd>
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

    <x-front.section-list-item title="Mackup">
        <p>
            All settings from my apps are backed up to iCloud using
            <x-front.legal-link url="https://github.com/lra/mackup" target="_blank" text="Mackup" after="." />
            This is a fantastic piece of software that moves all your preferences to your cloud storage provider of
            choice and symlinks them.
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

    <x-front.section-list-item title="VPN">
        <p>
            I usually only turn it on when I need to, but on both my MacBook and iPhone, I like to use
            <x-front.legal-link url="https://nordvpn.com" target="_blank" text="NordVPN" after="." />
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

    <x-front.section-list-item title="Microsoft Office">
        <p>
            Although the subscription is kind of pricy, I still really like the using Microsoft Word & Excel from the
            <x-front.legal-link url="https://www.office.com/" target="_blank" text="Microsoft Office 365" />
            subscription. For me, they are just nicer to use than most alternatives.
        </p>
    </x-front.section-list-item>

    <x-front.section-list-item title="Bartender">
        <p>
            <x-front.legal-link url="https://www.macbartender.com/" target="_blank" text="Bartender" />
            is a nice little utility that helps manage how the menu bar is laid out for macOS.
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
