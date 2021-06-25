const initialState = () => ({
  showBackdrop: false,
});

const state = initialState();

const getters = {
  getBackdropState: (state) => state.showBackdrop
}

const actions = {
  toggleBackdrop({commit}) {
    commit('TOGGLE')
  }
}

const mutations = {
  TOGGLE(state) {
    state.showBackdrop = !state.showBackdrop;
  }
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
}