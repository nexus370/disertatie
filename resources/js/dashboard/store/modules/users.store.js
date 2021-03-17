import { downloadUsers, downloadUser, storeUser, patchUser,  disableUser, deleteUser } from '../../api/users.api';
import _orderBy from 'lodash/orderBy';

const initialState = () => ({
    users: [],
    nextPage: 1,
});

const state = initialState();

const getters = {
    getUsers (state) {
        return state.users; 
    },

    getNextPage(state) {
        return state.nextPage;
    },
}

const actions = {
    reset({ commit }) {
        commit('RESET');
    },

    async fetchUsers({commit}, query) {
        try {
            const response = await downloadUsers(query);
            const users = response.data.data.users;
            const links = response.data.links;
            const meta = response.data.meta;
   
            commit('SET_USERS',users );

            if(links.next) {
                commit('SAVE_NEXT_PAGE', links.next.substr(links.next.length-1));
            }else {
                commit('SAVE_NEXT_PAGE', null);
            }

        } catch (error) {
            throw error; 
        }

    },

    async fetchFilteredUsers({commit}, query) {
        try {
            const response = await downloadUsers(query);
            const users = response.data.data.users;
            const links = response.data.links;

            commit('SET_FILTERED_USERS', users);

            if(links.next) {
                commit('SAVE_FILTERED_NEXT_PAGE', links.next.substr(links.next.length-1));
            }else {
                commit('SAVE_FILTERED_NEXT_PAGE', -1);
            }    

        } catch ( error ) {
            throw error; 
        }
    },

    async refreshUsers({commit}) {
        try {
            const response = await downloadUsers(1);
            commit('REFRESH_USERS', response.data.data.users);
        } catch (error) {
            throw error; 
        }
    },

    async addUser({commit}, user) {
        try {
            const response = await storeUser(user);
            // commit('ADD_USER', response.data.user)
        } catch (error) {
            throw error;
        }
    },

    sortUsersList({commit}, sortBy) {
       commit('SORT_USERS', sortBy);
    }
    
}

const mutations = {
    RESET(state) {
        const newState = initialState();
        Object.keys(newState).forEach(key => {
            state[key] = newState[key]
        })
    },

    SET_USERS(state, users) {
        state.users.push(...users);
    },

    SET_FILTERED_USERS(state, users) {
        state.users.slice(0, state.users.length);
        state.users = users;
    },

    REFRESH_USERS(state, users) {
        state.users = users;
        state.nextPage = 2;
    },

    ADD_USER(state, user) {
        state.users.unshift(user);
    },
    
    REMOVE_USER(state, index) {
        state.users.splice(index, 1);
    },

    SAVE_NEXT_PAGE(state, page) {
        state.nextPage = page;
    },

    SORT_USERS(state, sortBy) {
        switch(sortBy) {
            case 1:
                state.users = _orderBy(state.users, [user => user.name.toLowerCase()], ['asc']);
                break;
            case 2:
                state.users = _orderBy(state.users, [user => user.name.toLowerCase()], ['desc']);
                break;
            case 3:
                state.users = _orderBy(state.users, [user => user.firstName.toLowerCase()], ['asc']);
                break;
            case 4:
                state.users = _orderBy(state.users, [user => user.firstName.toLowerCase()], ['desc']);
                break;
            case 5:
                state.users = _orderBy(state.users, [user => user.email.toLowerCase()], ['asc']);
                break;
            case 6: 
                state.users = _orderBy(state.users, [user => user.email.toLowerCase()], ['desc']);
                break;
            case 7: 
                state.users = _orderBy(state.users, ['roleId'], ['desc']);
                break;
            case 8:
                state.users = _orderBy(state.users, ['roleId'], ['asc']);
                break;
            case 9: 
                state.users = _orderBy(state.users, ['orders'], ['desc']);
                break;
            case 10:
                state.users = _orderBy(state.users, ['orders'], ['asc']);
                break;
            case 11: 
                state.users = _orderBy(state.users, ['reservations'], ['desc']);
                break;
            case 12:
                state.users = _orderBy(state.users, ['reservations'], ['asc']);
                break;
            case 13:
                state.users = _orderBy(state.users, ['createdAt'], ['asc']);
                break;
            case 14:
                state.users = _orderBy(state.users, ['createdAt'], ['desc']);
                break;
        }
    }

}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations,
}