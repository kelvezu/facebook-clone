const state = {
    user: null,
    userStatus: false
};

const getters = {
    user: state => state.user,
    userStatus: state => state.userStatus
};

const actions = {
    fetchUser({ commit }, userId) {
        commit("setUserStatus", "loading");
        axios
            .get(`/api/users/${userId}`)
            .then(res => {
                commit("setUser", res.data);
                commit("setUserStatus", "success");
            })
            .catch(err => {
                console.error(
                    `Unable to fetch the user from the server. Error:${err}`
                );
                commit("setUserStatus", "error");
            });
    }
};

const mutations = {
    setUser(state, user) {
        state.user = user;
    },
    setUserStatus(state, status) {
        state.userStatus = status;
    }
};

export default {
    state,
    getters,
    actions,
    mutations
};
