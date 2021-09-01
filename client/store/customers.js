import { routes } from '~/utils/routes'

export const state = () => ({
  customers: {
    data:[],
    links:{},
    meta:{}
  },
  customer: {},
});

export const getters = {
  customers: (state) => state.customers,
  customer: (state) => state.customer,
};
export const mutations = {
  set_customers(state, data) {
    state.customers = data
  },
  set_customer(state, data) {
    state.customer = data
  }
};


export const actions = {
  async get({ commit }, payload) {
    try {
      const { data } = await this.$axios.get(routes.customers, payload);
      commit('set_customers', data);
      return data
    } catch (error) {
      return Promise.reject(error)
    }
  },
  async show({ commit }, payload) {
    try {
      const { data } = await this.$axios.get(`${routes.customers}/${payload}`);
      commit('set_customer', data.data);
      return data
    } catch (error) {
      return Promise.reject(error)
    }
  },
  async create({ commit }, payload) {
    try {
      const { data } = await this.$axios.post(routes.customers, payload);
      commit('set_customer', data.data);
      return data.data
    } catch (error) {
      // error.response.data.errors
      return Promise.reject(error)
    }
  },
  async update({ commit }, payload) {
    try {
      const { id, formData } = payload;
      const { data } = await this.$axios.put(`${routes.customers}/${id}`, formData);
      commit('set_customer', {});
      return data.data
    } catch (error) {
      return Promise.reject(error)
    }
  },
  async delete({ commit }, payload) {
    try {
      const response = await this.$axios.delete(`${routes.customers}/${payload}`);
      return true;
    } catch (error) {
      return Promise.reject(error)
    }
  }
}