const state = {
    user: null,
    userStatus: false,
   
};

const getters = {
    user: state => state.user,
   
    status: state => {
      return {
        user: state.userStatus,
        posts: state.postsStatus
      
      }
    },
    userStatus: state => state.userStatus,
    postStatus: state => state.postStatus,
    friendship: state => state.user.data.attributes.friendship,
    friendButtonText: (state, getters, rootState) => {
        if (rootState.User.user.data.user_id === state.user.data.user_id) {
            return "";
        } else if (getters.friendship === null) {
            return "Add Friend";
        } else if (
            getters.friendship.data.attributes.confirmed_at === null &&
            getters.friendship.data.attributes.friend_id !==
                rootState.User.user.data.user_id
        ) {
            return "Pending Friend Request";
        } else if (getters.friendship.data.attributes.confirmed_at !== null) {
            return "";
        }

        return "Accept";
    }
};

const actions = {
    fetchUser({ commit }, userId) {
        commit("setUserStatus", "Loading...");
        axios
            .get(`/api/users/${userId}`)
            .then(res => {
                commit("setUser", res.data);
                commit("setUserStatus", "success");
            })
            .catch(err => {
                console.log(
                    `Unable to fetch the user from the server. Error:${err}`
                );
                commit("setUserStatus", "error");
            });
    },

    sendFriendRequest({ commit, getters }, friendId) {
        if (getters.friendButtonText !== "Add Friend") {
            return;
        }

        axios
            .post("/api/friend-request", { friend_id: friendId })
            .then(res => {
                commit("setUserFriendship", res.data);
            })
            .catch(err => {
                console.error(
                    `Unable to fetch the user from the server. Error:${err}`
                );
                commit("setUserStatus", "error");
            });
    },

    acceptFriendRequest({ commit, state }, userId) {
        axios
            .post("/api/friend-request-response", {
                user_id: userId,
                status: 1
            })
            .then(res => commit("setUserFriendship", res.data))
            .catch(err => {
                console.error(
                    `Unable to fetch the user from the server. Error:${err}`
                );
                commit("setUserStatus", "error");
            });
    },

    ignoreFriendRequest({ commit, state }, userId) {
        axios
            .delete("/api/friend-request-response/delete", {
                data: { user_id: userId }
            })
            .then(res => {
                commit("setUserFriendship", null);
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
    setUserFriendship(state, friendship) {
        state.user.data.attributes.friendship = friendship;
    },
    setUserStatus(state, status) {
        state.userStatus = status;
    },
};

export default {
    state,
    getters,
    actions,
    mutations
};
