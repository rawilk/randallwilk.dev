{{--
    This component is mostly useful to help show flashed session errors from socialite logins, since
    they are performed from a window we open with JS.
--}}

<div
    x-data="{
        show: false,
        socialiteMessage: undefined,

        init() {
            let message = localStorage.getItem('socialite.message');
            if (! message) {
                this.$root.remove();

                return;
            }

            message = JSON.parse(message);

            this.socialiteMessage = message.message;

            this.show = true;

            localStorage.removeItem('socialite.message');
        }
    }"
    wire:ignore
    x-show="show"
    style="display: none;"
    x-cloak
>
    <x-feedback.alert
        :color="App\Enums\SessionAlert::Error->color()"
        remove-parent-on-dismiss
        dismiss
    >
        <p x-text="socialiteMessage"></p>
    </x-feedback.alert>
</div>
