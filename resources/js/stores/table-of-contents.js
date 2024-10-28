export default {
    current: '',
    headings: [],

    registerHeadings(tableOfContents) {
        this.headings = tableOfContents
            .flatMap(node => [
                node.id,
                ...node.children.map(child => child.id),
            ])
            .map(id => {
                let el = document.getElementById(id);
                if (! el) {
                    return;
                }

                return { id, el };
            })
            .filter(Boolean);

        this.updateCurrentSection();
    },

    updateCurrentSection() {
        const currentPositions = this.headings.map(heading => {
            const style = window.getComputedStyle(heading.el);
            const scrollMt = parseFloat(style.scrollMarginTop);

            const top =
                window.scrollY +
                heading.el.getBoundingClientRect().top -
                scrollMt;

            return { ...heading, top: Math.max(0, top) };
        });

        let top = window.scrollY + 5;
        let current = currentPositions[0]?.id;

        for (let heading of currentPositions) {
            if (heading.top <= 0) {
                break;
            }

            if (top >= heading.top) {
                current = heading.id;
            } else {
                break;
            }
        }

        this.current = current ?? null;
    },
};
