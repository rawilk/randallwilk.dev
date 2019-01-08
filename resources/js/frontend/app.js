import Vue from 'vue';
import CorePlugin from '~/core/core-plugin';

Vue.use(CorePlugin);

import FeaturedBoxes from './components/featured-boxes';
import FeaturedBox from './components/featured-box';
import GithubGist from './components/github-gist';

new Vue({
    components: {
        FeaturedBoxes,
        FeaturedBox,
        GithubGist
    },
}).$mount('#app');