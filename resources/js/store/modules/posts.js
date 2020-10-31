const state = {
    posts: null,
    postsStatus: null,
    postMessage: null
};

const getters = {
    posts: state => state.posts,
    newsStatus: state => {
        return {
            postsStatus: state.postsStatus
        };
    },
    postMessage: state => state.postMessage
};

const actions = {
    fetchNewsPosts({ commit }) {
        commit("setPostsStatus", "loading");
        axios
            .get("/api/posts")
            .then(res => {
                commit("setPosts", res.data);
                commit("setPostsStatus", "success");
            })
            .catch(err => console.log(err));
    },
    fetchUserPosts({ commit }, userId) {
        commit("setPostsStatus", "loading");
        axios
            .get(`/api/users/${userId}/posts`)
            .then(res => {
                commit("setPosts", res.data);
                commit("setPostsStatus", "success");
            })
            .catch(err => {
                console.log(
                    `Unable to fetch the user's posts from the server. Error:${err}`
                );
            });
    },
    submitPostMessage({ commit, state }) {
        commit("setPostsStatus", "loading");
        axios
            .post("/api/posts", { body: state.postMessage })
            .then(res => {
                commit("pushPost", res.data);
                commit("updateMessage", "");
                commit("setPostsStatus", "success");
            })
            .catch(err =>
                console.error(`Error in submitting post message. Error: ${err}`)
            );
    },
    likePost({ commit }, data) {
        axios
            .post(`/api/posts/${data.postId}/like`)
            .then(res => {
                commit("pushLikes", { likes: res.data, postKey: data.postKey });
            })
            .catch(err =>
                console.error(`Failed to liked the post. Error:${err}`)
            );
    },
    commentPost({ commit }, data) {
        axios
            .post(`/api/posts/${data.postId}/comment`, { body: data.body })
            .then(res => {
                commit("pushComments", {
                    comments: res.data,
                    postKey: data.postKey
                });
            })
            .catch(err =>
                console.error(`Failed to liked the post. Error:${err}`)
            );
    }
};

const mutations = {
    setPosts(state, posts) {
        state.posts = posts;
    },
    setPostsStatus(state, status) {
        state.postsStatus = status;
    },
    updateMessage(state, message) {
        state.postMessage = message;
    },
    pushPost(state, post) {
        state.posts.data.unshift(post);
    },
    pushLikes(state, data) {
        state.posts.data[data.postKey].data.attributes.likes = data.likes;
    },
    pushComments(state, data) {
        state.posts.data[data.postKey].data.attributes.comments = data.comments;
    }
};

export default {
    state,
    getters,
    actions,
    mutations
};
