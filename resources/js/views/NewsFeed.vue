<template>
  <div class="flex flex-col items-center py-4">
    <NewPost />
    <p v-if="newsStatus.postsStatus === 'loading'">Loading . . .</p>
    <Post
      v-else-if="newsStatus.postsStatus === 'success'"
      v-for="(post, postKey) in posts.data"
      :key="postKey"
      :post="post"
    />

    <div v-else class="mt-4">
        No post found. Get started.
    </div>
  </div>
</template>

<script>
import NewPost from "../components/NewPost";
import Post from "../components/Post";
import { mapGetters } from "vuex";

export default {
  name: "NewsFeed",
  components: {
    NewPost,
    Post,
  },

  computed: {
    ...mapGetters({
      posts: "posts",
      newsStatus: "newsStatus",
    }),
  },

  mounted() {
    this.$store.dispatch("fetchNewsPosts");
  },
};
</script>

<style>
</style>