const state = {
    newsPosts: null,
    newsPostStatus: null
};

const getters = {
    newsPosts: state => state.newsPosts,
    newsStatus: state => {
        return {
            newsPostStatus: state.newsPostStatus
        };
    }
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
    }
};

const mutations = {
    setPosts(state, posts) {
        state.newsPosts = posts;
    },
    setPostStatus(state, status) {
        state.newsPostStatus = status;
    }
};

export default {
    state,
    getters,
    actions,
    mutations
};
