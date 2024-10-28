<x-page
    title="Scheduled Maintenance"
    :show-header="false"
    :show-footer="false"
>
    <div class="wrap pt-10 mb-10">
        <x-logo
            type="dual"
            class="h-12 w-auto"
        />
    </div>

    <x-front.page-banner>
        Be right back!

        <x-slot:content>
            <p class="banner-intro">
                I'm currently working on some improvements to the site...<br>
                Please check back in a couple of minutes!
            </p>
        </x-slot:content>
    </x-front.page-banner>

    <section class="section">
        <div class="wrap markup">
            <x-errors.contact>
                <x-slot:title>
                    Even though my site is undergoing some maintenance,<br>
                    you can still reach me.
                </x-slot:title>
            </x-errors.contact>
        </div>
    </section>
</x-page>
