<template>
    <div v-if="authUser" class="flex flex-col flex-1 h-screen overflow-y-hidden">
        <Nav />
        <div class="flex overflow-y-hidden flex-1">
            <Sidebar />
            <div class="w-2/3 overflow-x-hidden">
                <router-view :key="$route.fullPath"></router-view>
            </div>
        </div>
    </div>
</template>

<script>
import Nav from "./Nav";
import Sidebar from "./Sidebar";
import { mapActions, mapGetters } from "vuex";

export default {
    name: "App",
    components: {
        Nav,
        Sidebar
    },
    methods: {
        ...mapActions([
            'fetchAuthUser',
            'setPageTitle'
        ])
    },
    computed: {
        ...mapGetters({
            authUser: 'authUser'
        }),
    },
    mounted() {
        this.fetchAuthUser();
    },
    created() {
        this.setPageTitle(this.$route.meta.title);
    },
    watch: {
        $route(to,from){
            this.setPageTitle(to.meta.title);
        }   
    }
};
</script>

<style></style>
