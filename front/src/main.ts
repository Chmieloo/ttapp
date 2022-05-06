import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import VueGamepad from "vue-gamepad";
import scrollto from "vue-scrollto";
import VuejsDialog from "vuejs-dialog";
//import VuejsDialogMixin from "vuejs-dialog/dist/vuejs-dialog-mixin.min.js"; // only needed in custom components

// include the default style
import "vuejs-dialog/dist/vuejs-dialog.min.css";

const app = createApp(App);

app.use(router);
app.use(scrollto);

app.mount("#app");
