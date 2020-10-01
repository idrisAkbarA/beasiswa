
// plugins/moment.js

import moment from 'moment';

moment.locale('id');

export default function install (Vue) {
  Object.defineProperties(Vue.prototype, {
    $moment: {
      get () {
        return moment;
      }
    }
  })
}

// main.js



// use this.$moment in your components