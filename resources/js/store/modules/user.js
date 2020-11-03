const state = {
    user: null,
    userStatus: true
};

const getters = {
    authUser: state => state.user,
    authUserStatus: state => state.userStatus
};

const actions = {
    fetchAuthUser({ commit }) {
        axios
            .get("/api/auth-user")
            .then(res => {
                commit("setAuthUser", res.data);
            })
            .catch(err =>
                console.error(
                    `Could not fetched the authenticated user. Error:${err}`
                )
            )
            .finally(() => commit("setUserStatus", false));
    }
};

const mutations = {
    setAuthUser(state, user) {
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
