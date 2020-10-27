const state = {
    user: null,
    userStatus: false
};

const getters = {
    user: state => state.user,
    userStatus: state => state.userStatus,
    friendship: state => state.user.data.attributes.friendship,
    friendButtonText: (state, getters, rootState) => {
        if (getters.friendship === null) {
            return "Add friend";
        } else if (getters.friendship.data.attributes.confirmed_at === null) {
            return "Pending Friend Request";
        }

        state.friendButtonText;
    }
};

const actions = {
    fetchUser({ commit, dispatch }, userId) {
        commit("setUserStatus", "Loading...");
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
    },

    sendFriendRequest({ commit, state }, friendId) {
        commit("setButtonText", "Loading...");

        axios
            .post("/api/friend-request", { friend_id: friendId })
            .then(res => {
                commit("setUserFriendship", res.data);
            })
            .catch(err => {
                commit("setButtonText", "Add a friend");
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
    setUserFriendship(state, friendship) {
        state.user.data.attributes.friendship = friendship;
    },
    setUserStatus(state, status) {
        state.userStatus = status;
    },
    setButtonText(state, text) {
        state.friendButtonText = text;
    }
};

export default {
    state,
    getters,
    actions,
    mutations
};
