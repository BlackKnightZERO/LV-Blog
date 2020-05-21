import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({

    state:{
        counter: 1000
    },

    getters:{
        getCounter(state){
            return state.counter;
        },
        getCounterRatio(state){
            return state.counter / 100;
        }
    },

    /// mutations are *** not asynchronous
    mutations:{
        incrementCounter(state,payload){               //state == this.state == state:{counter:1000}
            // console.log(state,payload);             //payload is sent from action's(method's) commit             
            state.counter += payload;
        },
        decrementCounter(state,payload){
            state.counter -= payload;
        },
        multiplyHundred(state){
            state.counter = state.counter * 100;
        }
    },
    /// actions asynchronous *** can be used for api calling
    actions:{
        decrementCounter({ commit }, payload){
            // console.log(commit);
            // console.log(payload);
            commit('decrementCounter',payload);

        }
    }

})