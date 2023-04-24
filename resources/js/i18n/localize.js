import locales from './locales';

export default {
  methods: {
    $t(key) {
      const translations = locales[this.$store.state.locale];
      return translations[key] || key;
    },
  },
};
