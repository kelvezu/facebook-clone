const state = {
    newsPosts: null,
    newsPostStatus: null,
    postMessage: null
};

const getters = {
    newsPosts: state => state.newsPosts,
    newsStatus: state => {
        return {
            newsPostStatus: state.newsPostStatus
        };
    },
    postMessage: state => state.postMessage
};

const actions = {
    fetchNewsPosts({ commit }) {
        commit("setPostStatus", "loading");
        axios
            .get("/api/posts")
            .then(res => {
                commit("setPosts", res.data);
                commit("setPostStatus", "success");
            })
            .catch(err => console.log(err));
    },
    submitPostMessage({ commit,state }) {
        commit("setPostStatus", "loading");
        axios
            .post("/api/posts" , {body: state.postMessage})
            .then(res => {
                commit("pushPost", res.data);
                commit("updateMessage", "");
                commit("setPostStatus", "success");
            })
            .catch(err => console.error(`Error in submitting post message. Error: ${err}`));
    },
    likePost({commit}, data) {
        axios.post(`/api/posts/${data.postId}/like`)
            .then( res => {
                commit('pushLikes', {likes: res.data, postKey: data.postKey});
            })
            .catch(err => console.error(`Failed to liked the post. Error:${err}`))
    }
};

const mutations = {
    setPosts(state, posts) {
        state.newsPosts = posts;
    },
    setPostStatus(state, status) {
        state.newsPostStatus = status;
    },
    updateMessage(state, message) {
        state.postMessage = message;
    },
    pushPost(state, post) {
        state.newsPosts.data.unshift(post);
    },
    pushLikes(state, data) {
        state.newsPosts.data[data.postKey].data.attributes.likes = data.likes;
    }
};

export default {
    state,
    getters,
    actions,
    mutations
};
