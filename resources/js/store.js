import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

const store = new Vuex.Store({
  state: {
    building_id : 0
  },
  mutations: {
    building(state,value){
        state.building_id = value
    }
  },
  actions: {
    setBuildingId({ commit }, value) {
        commit('building', value);
    },
  },
  getters: {
    getBuildingId(state){
        return state.building_id;
    }
  },
});

export default store;
