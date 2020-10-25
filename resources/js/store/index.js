import Vue from "vue";
import Vuex from "vuex";
import User from "./modules/user.js";
import Title from "./modules/title.js";

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        User,
        Title
    }
});
