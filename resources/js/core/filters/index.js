import calendar from './other/date/calendar';
import camelcase from './string/camelcase';
import date from './other/date/date';
import lowercase from './string/lowercase';
import placeholder from './string/placeholder';
import pluralize from './string/pluralize';
import prettyBytes from './other/file/prettyBytes';
import reverse from './string/reverse';
import titlecase from './string/titlecase';
import trans from './string/trans';
import truncate from './string/truncate';
import uppercase from './string/uppercase';

export default Vue => {
    calendar(Vue);
    camelcase(Vue);
    date(Vue);
    lowercase(Vue);
    placeholder(Vue);
    pluralize(Vue);
    prettyBytes(Vue);
    reverse(Vue);
    titlecase(Vue);
    trans(Vue);
    truncate(Vue);
    uppercase(Vue);
};