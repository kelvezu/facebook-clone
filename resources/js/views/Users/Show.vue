<template>
<div class="flex flex-col items-center" v-if="status.user === 'success' && user">
    <div class="relative mb-8">
        <div class="w-full h-64 overflow-hidden z-10">
            <img src="https://www.nba.com/lakers/sites/lakers/files/1920_lal_mktg_finals_final_wallpaper_jd.jpg" alt="Cover photo" class="object-cover w-full">
        </div>

        <div class="absolute flex items-center bottom-0 left-0 -mb-8 ml-12 z-20">
            <div class="w-32">
                 <img class="w-32 h-32 border-4 border-gray-200 rounded-full shadow-lg object-cover rounded-full" src="https://world-celebs.com/public/media/celebrity/2019/07/13/0hoybydh3lax-joji-filthy-frank.jpg" alt="profile pic">
            </div>  

            <p class="text-2xl ml-4 text-gray-100 ml-4">{{ user.data.attributes.name }}</p>
        </div>

        <div class="absolute flex items-center bottom-0 right-0 mb-4 mr-12 z-20">
            <button
                v-if="friendButtonText && friendButtonText !== 'Accept'" 
                class="bg-blue-500 text-white font-semibold rounded p-1 text-xs focus:outline-none hover:bg-white hover:text-blue-500 border hover:border-blue-500"
                @click="$store.dispatch('sendFriendRequest', $route.params.userId)" 
            >
                {{ friendButtonText }}
            </button>

            <button
                v-if="friendButtonText && friendButtonText === 'Accept'" 
                class="mr-2 bg-blue-500 text-white font-semibold rounded p-1 text-xs focus:outline-none hover:bg-white hover:text-blue-500 border hover:border-blue-500"
                @click="$store.dispatch('acceptFriendRequest', $route.params.userId)" 
            >
                 Accept
            </button>

            <button
                v-if="friendButtonText && friendButtonText === 'Accept'" 
                class="mr-2 bg-blue-500 text-white font-semibold rounded p-1 text-xs focus:outline-none hover:bg-white hover:text-blue-500 border hover:border-blue-500"
                @click="$store.dispatch('ignoreFriendRequest', $route.params.userId)" 
            >
                 Ignore
            </button>
        </div>
    </div>

    <div v-if="status.posts === 'Loading...' ">Loading post...</div>

    <div v-else-if="posts.length < 1">
        No posts found! Get started.
    </div>

     <Post v-else v-for="post in posts.data" :key="post.data.post_id" :post="post" />

</div>
</template>

<script>
import Post from '../../components/Post';
import {mapGetters} from 'vuex';

export default {
    name: 'Show',
    components: {
        Post
    },
    mounted(){
        this.$store.dispatch('fetchUser', this.$route.params.userId);
        this.$store.dispatch('fetchUserPosts', this.$route.params.userId);
    },
    computed: {
        ...mapGetters({
            user: 'user',
            posts: 'posts',
            status: 'status',
            friendButtonText: 'friendButtonText'
        })
    }
}
</script>