<template>
    <div class="bg-white rounded shadow w-2/3 mt-3 overflow-hidden">
        <div class="flex flex-col p-4">
            <div class="flex items-center">
                <div>
                    <img
                        class="w-8 h-8 object-cover rounded-full"
                        :src="post.data.attributes.posted_by.data.attributes.profile_image.data.attributes.path"
                        :alt="'profile pic'"
                    />
                </div>
                <div class="ml-2">
                    <div>
                        <router-link
                            :to="{
                                name: 'user.show',
                                params: {
                                    userId:
                                        post.data.attributes.posted_by.data
                                            .user_id
                                }
                            }"
                            class="text-sm font-semibold"
                            >
                            {{
                                post.data.attributes.posted_by.data.attributes
                                    .name
                            }}
                            </router-link
                        >
                    </div>
                    <div class="mt-0">
                        <p class="text-xs text-gray-600">
                            {{ post.data.attributes.posted_at }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="mt-3">
                <p>{{ post.data.attributes.body }}</p>
            </div>
        </div>

        <div v-if="post.data.attributes.image.length" class="w-full">
            <img class="w-full" :src="post.data.attributes.image" alt="post image" />
        </div>

        <div class="px-4 pt-2 flex justify-between">
            <button
                v-if="post.data.attributes.likes.like_count !== 0"
                class="flex justify-center items-center p-1"
            >
                <div>
                    <svg
                        :class="
                            post.data.attributes.likes.user_likes_post
                                ? 'text-blue-500'
                                : ''
                        "
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        class="fill-current w-5 h-5"
                    >
                        <path
                            d="M20.8 15.6c.4-.5.6-1.1.6-1.7 0-.6-.3-1.1-.5-1.4.3-.7.4-1.7-.5-2.6-.7-.6-1.8-.9-3.4-.8-1.1.1-2 .3-2.1.3-.2 0-.4.1-.7.1 0-.3 0-.9.5-2.4.6-1.8.6-3.1-.1-4.1-.7-1-1.8-1-2.1-1-.3 0-.6.1-.8.4-.5.5-.4 1.5-.4 2-.4 1.5-2 5.1-3.3 6.1l-.1.1c-.4.4-.6.8-.8 1.2-.2-.1-.5-.2-.8-.2H3.7c-1 0-1.7.8-1.7 1.7v6.8c0 1 .8 1.7 1.7 1.7h2.5c.4 0 .7-.1 1-.3l1 .1c.2 0 2.8.4 5.6.3.5 0 1 .1 1.4.1.7 0 1.4-.1 1.9-.2 1.3-.3 2.2-.8 2.6-1.6.3-.6.3-1.2.3-1.6.8-.8 1-1.6.9-2.2.1-.3 0-.6-.1-.8zM3.7 20.7c-.3 0-.6-.3-.6-.6v-6.8c0-.3.3-.6.6-.6h2.5c.3 0 .6.3.6.6v6.8c0 .3-.3.6-.6.6H3.7zm16.1-5.6c-.2.2-.2.5-.1.7 0 0 .2.3.2.7 0 .5-.2 1-.8 1.4-.2.2-.3.4-.2.6 0 0 .2.6-.1 1.1-.3.5-.9.9-1.8 1.1-.8.2-1.8.2-3 .1h-.1c-2.7.1-5.4-.3-5.4-.3H8v-7.2c0-.2 0-.4-.1-.5.1-.3.3-.9.8-1.4 1.9-1.5 3.7-6.5 3.8-6.7v-.3c-.1-.5 0-1 .1-1.2.2 0 .8.1 1.2.6.4.6.4 1.6-.1 3-.7 2.1-.7 3.2-.2 3.7.3.2.6.3.9.2.3-.1.5-.1.7-.1h.1c1.3-.3 3.6-.5 4.4.3.7.6.2 1.4.1 1.5-.2.2-.1.5.1.7 0 0 .4.4.5 1 0 .3-.2.6-.5 1z"
                        />
                    </svg>
                </div>
                <div class="ml-1">
                    <p>{{ post.data.attributes.likes.like_count }} likes</p>
                </div>
            </button>
            <div v-if="post.data.attributes.comments.comment_count">
                {{ post.data.attributes.comments.comment_count }} comments
            </div>
        </div>

        <div class="flex justify-between border-1 border-gray-400 m-4">
            <button
                @click="
                    $store.dispatch('likePost', {
                        postId: post.data.post_id,
                        postKey: $vnode.key
                    })
                "
                class="flex justify-center py-2 rounded-lg text-sm w-full focus:outline-none cursor-pointer"
            >
                <svg
                    :class="
                        post.data.attributes.likes.user_likes_post
                            ? 'text-blue-500'
                            : ''
                    "
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    class="fill-current w-5 h-5"
                >
                    <path
                        d="M20.8 15.6c.4-.5.6-1.1.6-1.7 0-.6-.3-1.1-.5-1.4.3-.7.4-1.7-.5-2.6-.7-.6-1.8-.9-3.4-.8-1.1.1-2 .3-2.1.3-.2 0-.4.1-.7.1 0-.3 0-.9.5-2.4.6-1.8.6-3.1-.1-4.1-.7-1-1.8-1-2.1-1-.3 0-.6.1-.8.4-.5.5-.4 1.5-.4 2-.4 1.5-2 5.1-3.3 6.1l-.1.1c-.4.4-.6.8-.8 1.2-.2-.1-.5-.2-.8-.2H3.7c-1 0-1.7.8-1.7 1.7v6.8c0 1 .8 1.7 1.7 1.7h2.5c.4 0 .7-.1 1-.3l1 .1c.2 0 2.8.4 5.6.3.5 0 1 .1 1.4.1.7 0 1.4-.1 1.9-.2 1.3-.3 2.2-.8 2.6-1.6.3-.6.3-1.2.3-1.6.8-.8 1-1.6.9-2.2.1-.3 0-.6-.1-.8zM3.7 20.7c-.3 0-.6-.3-.6-.6v-6.8c0-.3.3-.6.6-.6h2.5c.3 0 .6.3.6.6v6.8c0 .3-.3.6-.6.6H3.7zm16.1-5.6c-.2.2-.2.5-.1.7 0 0 .2.3.2.7 0 .5-.2 1-.8 1.4-.2.2-.3.4-.2.6 0 0 .2.6-.1 1.1-.3.5-.9.9-1.8 1.1-.8.2-1.8.2-3 .1h-.1c-2.7.1-5.4-.3-5.4-.3H8v-7.2c0-.2 0-.4-.1-.5.1-.3.3-.9.8-1.4 1.9-1.5 3.7-6.5 3.8-6.7v-.3c-.1-.5 0-1 .1-1.2.2 0 .8.1 1.2.6.4.6.4 1.6-.1 3-.7 2.1-.7 3.2-.2 3.7.3.2.6.3.9.2.3-.1.5-.1.7-.1h.1c1.3-.3 3.6-.5 4.4.3.7.6.2 1.4.1 1.5-.2.2-.1.5.1.7 0 0 .4.4.5 1 0 .3-.2.6-.5 1z"
                    />
                </svg>
                <p class="ml-2">
                    {{
                        post.data.attributes.likes.user_likes_post
                            ? "You liked this post"
                            : "Like"
                    }}
                </p>
            </button>
            <button
                @click="showComment = !showComment"
                class="flex justify-center py-2 rounded-lg text-sm text-gray-700 w-full hover:bg-gray-200"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    class="fill-current w-5 h-5"
                >
                    <path
                        d="M20.3 2H3.7C2 2 .6 3.4.6 5.2v10.1c0 1.7 1.4 3.1 3.1 3.1V23l6.6-4.6h9.9c1.7 0 3.1-1.4 3.1-3.1V5.2c.1-1.8-1.3-3.2-3-3.2zm1.8 13.3c0 1-.8 1.8-1.8 1.8H9.9L5 20.4V17H3.7c-1 0-1.8-.8-1.8-1.8v-10c0-1 .8-1.8 1.8-1.8h16.5c1 0 1.8.8 1.8 1.8v10.1zM6.7 6.7h10.6V8H6.7V6.7zm0 2.9h10.6v1.3H6.7V9.6zm0 2.8h10.6v1.3H6.7v-1.3z"
                    />
                </svg>
                <p class="ml-2">Comment</p>
            </button>
        </div>

        <div v-if="showComment" class="p-4 pt-4">
            <div class="flex">
                <input
                    v-model="commentBody"
                    type="text"
                    name="comment"
                    class="w-full h-8 bg-gray-200 rounded-full pl-4 focus:outline-none"
                    placeholder="Write a comment."
                />
                <button
                    v-if="commentBody"
                    @click="$store.dispatch('commentPost', { body: commentBody, postId: post.data.post_id, postKey: $vnode.key }); commentBody = ''"
                    class="bg-gray-200 rounded p-2 ml-2 text-sm"
                >
                    Post
                </button>
            </div>

            <div
                class="flex my-2 items-start bg-gray-100 p-2 rounded"
                v-for="(comment, commentKey) in post.data.attributes.comments
                    .data"
                :key="commentKey"
            >
                <div>
                    <img
                        class="w-8 h-8 object-cover rounded-full"
                        :src="comment.data.attributes.commented_by.data.attributes.profile_image.data.attributes.path"
                        alt="profile pic"
                    />
                </div>
                <div class="ml-2 flex flex-col">
                    <router-link
                        class="text-blue-600 font-semibold"
                        :to="{
                            name: 'user.show',
                            params: {
                                userId:
                                    comment.data.attributes.commented_by.data
                                        .user_id
                            }
                        }"
                    >
                        {{ comment.data.attributes.commented_by.data.attributes.name }}
                    </router-link>
                    <p class="text-sm">{{ comment.data.attributes.body }}</p>
                    <small class="text-xs text-gray-700">{{ comment.data.attributes.commented_at }}</small>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Post",
    props: ["post"],
    data: () => {
        return {
            showComment: false,
            commentBody: null
        };
    },
   
};
</script>

<style></style>
