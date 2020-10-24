<template>
<div>
    <div class="w-100 h-64 overflow-hidden">
        <img class="object-cover w-full" src="https://c4.wallpaperflare.com/wallpaper/31/105/276/retrowave-synthwave-neon-ultrawide-wallpaper-preview.jpg" alt="Cover photo">
    </div>
    <h2>{{ users.data.attributes.name }}</h2>
</div>
</template>

<script>
export default {
    name: 'Show',
    data() {
        return {
            users: null,
            loading: true,
        }
    },
    mounted(){
        axios.get(`/api/users/${this.$route.params.userId}`)
            .then(res => {
                this.users = res.data;
            })
            .catch(err => {
                console.log(`Unable to fetch the user from the server. Error:${err}`);
            })
            .finally(() => {
                this.loading = false;
            });

        axios.get(`/api/posts/${this.$route.params.userId}`)
            .then(res => {
                this.users = res.data;
            })
            .catch(err => {
                console.log(`Unable to fetch the user from the server. Error:${err}`);
            })
            .finally(() => {
                this.loading = false;
            });            
    }
}
</script>