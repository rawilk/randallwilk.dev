import { copyableText } from './components/copyable-text';

Alpine.data('copyableText', copyableText);

Alpine.magic('clipboard', () => {
    return subject => navigator.clipboard.writeText(subject);
});
