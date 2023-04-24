import { createI18n } from "vue-i18n";
import locales from './locales';

const supportLanguages = [];

function loadLocaleMessages() {
    const messages = {};

    Object.keys(locales).forEach(locale => {
        messages[locale] = locales[locale];
    });

    return messages;
}

export default createI18n({
    legacy: false,
    locale: localStorage.getItem('locale') || 'en',
    fallbackLocale: null,
    messages: loadLocaleMessages(),
    globalInjection: true,
});