export default Vue => {
    Vue.filter('titlecase', string => titlecase(string));

    Vue.prototype.$titlecase = string => titlecase(string);
};

/**
 * Articles, conjunctions, and prepositions less than six letters long are
 * changed to lower case unless they are at the beginning of a sentence.
 *
 * @type {array}
 */
const MINOR_WORDS = [
    'A',
    'Abaft',
    'About',
    'Above',
    'Afore',
    'After',
    'Along',
    'Amid',
    'Among',
    'An',
    'And',
    'Am',
    'Apud',
    'Are',
    'As',
    'Aside',
    'At',
    'Atop',
    'Below',
    'But',
    'By',
    'Circa',
    'Down',
    'For',
    'From',
    'Given',
    'In',
    'Into',
    'Is',
    'Lest',
    'Like',
    'Mid',
    'Midst',
    'Minus',
    'Near',
    'Next',
    'Nor',
    'Of',
    'Off',
    'On',
    'Onto',
    'Or',
    'Out',
    'Over',
    'Pace',
    'Past',
    'Per',
    'Plus',
    'Pro',
    'Qua',
    'Round',
    'Sans',
    'Save',
    'Since',
    'So',
    'Than',
    'The',
    'Thru',
    'Till',
    'Times',
    'To',
    'Under',
    'Until',
    'Unto',
    'Up',
    'Upon',
    'Via',
    'Vice',
    'With',
    'Worth',
    'Yet'
];

/**
 * Certain words such as initialisms or acronyms should be left uppercase
 *
 * @type {array}
 */
const ACRONYMS = ['Id', 'Tv', 'Html', 'Xml', 'Url', 'Http', 'Vpn'];

/**
 * Transform the given string to title case.
 *
 * @param {string} string
 * @returns {string}
 */
const titlecase = string => {
    if (! string) {
        return '';
    }

    let str = string.toString();

    // Capitalize each word
    str = str.replace(/([^\W_]+[^\s-]*) */g, s => s.charAt(0).toUpperCase() + s.substr(1).toLowerCase());

    // Certain minor words should be left lowercase unless they are the first or last words
    // of a string
    let i, j, regexp;
    for (i = 0, j = MINOR_WORDS.length; i < j; i++) {
        regexp = new RegExp('\\s' + MINOR_WORDS[i] + '\\s', 'g');

        str = str.replace(regexp, s => s.toLowerCase());
    }

    //  Certain words such as initialisms or acronyms should be left uppercase.
    for (i = 0, j = ACRONYMS.length; i < j; i++) {
        regexp = new RegExp('\\b' + ACRONYMS[i] + '\\b', 'g');
        str = str.replace(regexp, ACRONYMS[i].toUpperCase());
    }

    return str;
};