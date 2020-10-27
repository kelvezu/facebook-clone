const state = {
    user: null,
    userStatus: false,
    friendButtonText: null
};

const getters = {
    user: state => state.user,
    userStatus: state => state.userStatus,
    friendship: state => state.user.data.attributes.friendship,
    friendButtonText: state => state.friendButtonText
};

const actions = {
    fetchUser({ commit, dispatch }, userId) {
        commit("setUserStatus", "Loading...");
        axios
            .get(`/api/users/${userId}`)
            .then(res => {
                commit("setUser", res.data);
                commit("setUserStatus", "success");
                dispatch("setFriendButton");
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
                commit("setButtonText", "Pending Friend Request");
            })
            .catch(err => {
                commit("setButtonText", "Add a friend");
                console.error(
                    `Unable to fetch the user from the server. Error:${err}`
                );
                commit("setUserStatus", "error");
            });
    },

    setFriendButton({ commit, getters }) {
        if (getters.friendship === null) {
            commit("setButtonText", "Add friend");
        } else if (getters.friendship.data.attributes.confirmed_at === null) {
            commit("setButtonText", "Pending Friend Request");
        }
    }
};

const mutations = {
    setUser(state, user) {
        state.user = user;
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
