import { createStore } from 'vuex';

const store = createStore({
  state: {
    locale: localStorage.getItem('locale') || 'en',
  },
  mutations: {
    setLocale(state, locale) {
      state.locale = locale;
      localStorage.setItem('locale', locale);
    },
  },
});

export default store;
