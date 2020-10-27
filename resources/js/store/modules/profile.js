const state = {
    user: null,
    userStatus: false,
    posts: null,
    postStatus: null
};

const getters = {
    user: state => state.user,
    posts: state => state.posts,
    status: state => {
      return {
        user: state.userStatus,
        posts: state.postStatus, 
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

    fetchUserPosts({ commit }, userId) {
        commit("setPostStatus", "Loading...");
        axios
            .get(`/api/users/${userId}/posts`)
            .then(res => {
                commit("setPosts", res.data);
                commit("setPostStatus", "success");
            })
            .catch(err => {
                console.log(
                    `Unable to fetch the user's posts from the server. Error:${err}`
                );
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
    setPosts(state, posts) {
        state.posts = posts;
    },
    setUserFriendship(state, friendship) {
        state.user.data.attributes.friendship = friendship;
    },
    setUserStatus(state, status) {
        state.userStatus = status;
    },
    setPostStatus(state, status) {
        state.postStatus = status;
    }
};

export default {
    state,
    getters,
    actions,
    mutations
};
