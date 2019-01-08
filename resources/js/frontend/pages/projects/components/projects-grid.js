export default {
    props: {
        categories: {
            type: Array,
            default: () => ([])
        },
        projects: {
            type: Array,
            default: () => ([])
        }
    },

    computed: {
        /**
         * Filter the projects out by the currently selected category.
         *
         * @returns {array}
         */
        categoryProjects () {
            if (this.selectedCategory === null) {
                return this.projects;
            }

            return this.projects.filter(project => project.categories.includes(this.selectedCategory));
        }
    },

    data () {
        return {
            selectedCategory: null
        };
    },

    methods: {
        getProjectImage (project) {
            return project.image === null ? '/images/project-placeholder.png' : project.image;
        },

        getProjectLink (project) {
            return this.route(
                'frontend.projects.view',
                { project: project.slug }
            );
        },

        projectCategories (project) {
            return project.categories.join(', ');
        }
    }
};