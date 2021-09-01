import { routes } from '~/utils/routes'

export const state = () => ({
  transactions: {
    data:[],
    links:{},
    meta:{}
  },
  transaction: [],
});

export const getters = {
  transactions: (state) => state.transactions,
  transaction: (state) => state.transaction,
};
export const mutations = {
  set_transactions(state, data) {
    state.transactions = data
  },
  set_transaction(state, data) {
    state.transaction = data
  }
};


export const actions = {
  async get({ commit }, payload) {
    try {
      const { data } = await this.$axios.get(routes.transactions, payload);
      commit('set_transactions', data);
      return data
    } catch (error) {
      return Promise.reject(error)
    }
  },
  async create({ commit }, payload) {
    try {
      const { data } = await this.$axios.post(routes.transactions, payload);
      commit('set_transaction', data.data);
      return data.data
    } catch (error) {
      return Promise.reject(error)
    }
  },
  async update({ commit }, payload) {
    try {
      const { id, formData } = payload;
      const { data } = await this.$axios.put(`${routes.transactions}/${id}`, formData);
      commit('set_transaction', data.data);
      return data.data
    } catch (error) {
      return Promise.reject(error)
    }
  },
  async delete({ commit }, payload) {
    try {
      const response = await this.$axios.delete(`${routes.transactions}/${payload}`);
      return true;
    } catch (error) {
      return Promise.reject(error)
    }
  }
}