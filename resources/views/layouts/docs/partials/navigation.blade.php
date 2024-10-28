<div class="hidden lg:relative lg:block lg:flex-none">
    <div
        class="sticky top-10 -ml-0.5 h-[calc(100vh-101px)] overflow-y-auto overflow-x-hidden pt-10 pb-16 pl-0.5 navigation-custom-scrollbar"
        x-data="{
            position: 0,
            storageKey: @js('docs:scroll:' . $repository->slug . ':' . $page->alias),
            init() {
                this.position = localStorage.getItem(this.storageKey) ?? 0;

                this.$nextTick(() => {
                    if (this.position > 0) {
                        this.$el.scrollTop = this.position;
                    }
                });

                this.$el.addEventListener('scroll', () => {
                    this.position = this.$el.scrollTop;
                    localStorage.setItem(this.storageKey, this.position);
                });
            },
        }"
    >
        <x-docs.navigation
            :navigation="$navigation"
            :page="$page"
            :repository="$repository"
            type="desktop"
            class="w-60 pr-5"
        />
    </div>
</div>
