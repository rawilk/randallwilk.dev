<template>
    <div v-html="content">
    </div>
</template>

<script>
    import jsonp from 'jsonp';

    const gistUrl = 'https://gist.github.com';

    export default {

        props: {
            gistId: {
                type: String,
                required: true
            }
        },

        data () {
            return {
                content: 'Loading...'
            };
        },

        created () {
            this.loadGist();
        },

        methods: {
            loadGist () {
                const url = `${gistUrl}/${this.gistId}.json`;

                jsonp(url, null, (error, data) => {
                    if (data.div) {
                        this.content = data.div;
                    }
                });
            }
        }
    };
</script>