export const updatePageTitle = newTitle => {
    document.querySelector('.page-title').innerText = newTitle;

    const currentTitle = window.document.title.split(' | ');
    currentTitle.shift();
    currentTitle.unshift(newTitle);

    window.document.title = currentTitle.join(' | ');
};
