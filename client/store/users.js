import {routes} from '~/utils/routes'

export const state = () => ({
  users: {
    data:[],
    links:{},
    meta:{}
  },
  user: [],
});

export const getters = {
  users: (state) => state.users,
  user: (state) => state.user,
};
export const mutations = {
  set_users(state, data) {
    state.users = data
  },
  set_user(state, data) {
    state.user = data
  }
};


export const actions = {
  async get({ commit }, payload) {
    try {
      const { data } = await this.$axios.get(routes.users, payload);
      commit('set_users', data);
      return data
    } catch (error) {
      return Promise.reject(error)
    }
  },
  async show({ commit }, payload) {
    try {
      const { data } = await this.$axios.get(`${routes.users}/${payload}`);
      commit('set_user', data.data);
      return data
    } catch (error) {
      return Promise.reject(error)
    }
  },
  async create({ commit }, payload) {
    try {
      const { data } = await this.$axios.post(routes.users, payload);
      commit('set_user', data.data);
      return data.data
    } catch (error) {
      return Promise.reject(error)
    }
  },
  async update({ commit }, payload) {
    try {
      const { id, formData } = payload;
      const { data } = await this.$axios.put(`${routes.users}/${id}`, formData);
      commit('set_user', data.data);
      return data.data
    } catch (error) {
      return Promise.reject(error)
    }
  },
  async delete({ commit }, payload) {
    try {
      const response = await this.$axios.delete(`${routes.users}/${payload}`);
      return true;
    } catch (error) {
      return Promise.reject(error)
    }
  },
  async changepassword({ commit }, payload) {
    try {
      // const { id, formData } = payload;
      const { data } = await this.$axios.put(`${routes.change_password}`, payload);
      return true
    } catch (error) {
      return Promise.reject(error)
    }
  },
  async register({ commit }, payload) {
    try {
      const { data } = await this.$axios.post(routes.register, payload);
      commit('set_user', data.data);
      return data.data
    } catch (error) {
      return Promise.reject(error)
    }
  },
}