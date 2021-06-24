import {
  addItemTocart,
  patchItem,
  removeItemFromCart
} from "../../api/cart.api.js"

import _findIndex from 'lodash/findIndex'

const initialState = () => ({
  showCart: false,
  cart: {
    id: 0,
    itemsCount: 0,
    items: [],
  }
})

const state = initialState();

const getters = {
  getCartItems: (state) => state.cart.items,
  getCartId: (state) => state.cart.id,
  getCartItemsCount: (state) => state.cart.itemsCount,
  getShowCart: (state) => state.showCart
}

const actions = {
  async addItemToCart({ commit }, payload) {
    const response = await addItemTocart(payload.item);

    payload.item.id = response.data.productId;

    commit('ADD_ITEM', payload);
  },
  async pathItem({ commit }, payload) {
    await patchItem(payload.item);

    commit('PATCH_ITEM', payload.item.id)
  },

  async removeItem({commit}, payload) {
    await removeItemFromCart(payload.item);

    commit('REMOVE_ITEM', payload)
  },

  setCartItems({commit}, items) {
    commit('SET_ITEMS', items);
  }

}

const mutations = {
  ADD_ITEM(state, item) {
    state.cart.items.push(item);
  },

  PATCH_ITEM(state, payload) {
    const itemIndex = _findIndex(state.cart.items, ['id', payload.item.id]);

    payload.vm.$set(state.cart.items[itemIndex], 'quantity', payload.item.quantity);
  },

  REMOVE_ITEM(state, itemId) {
    const itemIndex = __findIndex(state.cart.items, ['id', itemId]);

    state.cart.items.splice(itemIndex, 1);
  },

  SET_ITEMS(state, items) {
    state.cart.items = items;
  }
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
}