import { createApp } from "vue";
import { createVuetify } from "vuetify";
import * as components from "vuetify/components";
import * as directives from "vuetify/directives";

import "@mdi/font/css/materialdesignicons.css";
import "vuetify/styles";
import App from "./vue/App.vue";

const vuetify = createVuetify({
    components,
    directives,
    icons: {
        iconfont: 'mdi'
    }
});

const app = createApp(App);
app.use(vuetify);
app.mount("#app");
