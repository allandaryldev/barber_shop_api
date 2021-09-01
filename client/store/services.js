import { routes } from '~/utils/routes'

export const state = () => ({
  services: {
    data:[],
    links:{},
    meta:{}
  },
  service: [],
});

export const getters = {
  services: (state) => state.services,
  service: (state) => state.service,
};
export const mutations = {
  set_services(state, data) {
    state.services = data
  },
  set_service(state, data) {
    state.service = data
  }
};


export const actions = {
  async get({ commit }, payload) {
    try {
      const { data } = await this.$axios.get(routes.services, payload);
      commit('set_services', data);
      return data.data
    } catch (error) {
      return Promise.reject(error)
    }
  },
  async show({ commit }, payload) {
    try {
      const { data } = await this.$axios.get(`${routes.services}/${payload}`);
      commit('set_service', data.data);
      return data.data
    } catch (error) {
      return Promise.reject(error)
    }
  },
  async create({ commit }, payload) {
    try {
      const { data } = await this.$axios.post(routes.services, payload);
      commit('set_service', data.data);
      return data.data
    } catch (error) {
      return Promise.reject(error)
    }
  },
  async update({ commit }, payload) {
    try {
      const { id, formData } = payload;
      const { data } = await this.$axios.put(`${routes.services}/${id}`, formData);
      commit('set_service', data.data);
      return data.data
    } catch (error) {
      return Promise.reject(error)
    }
  },
  async delete({ commit }, payload) {
    try {
      const response = await this.$axios.delete(`${routes.services}/${payload}`);
      return true;
    } catch (error) {
      return Promise.reject(error)
    }
  }
}