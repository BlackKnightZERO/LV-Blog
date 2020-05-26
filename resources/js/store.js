import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({

    state:{
        counter: 1000,

        deleteModalObj : {
            showDeleteModal:false,
            deleteUrl:'',
            deleteIndex:-1,
            isDeleted:false,
            deleteData:{

            },
        }
    },

    getters:{
        getCounter(state){
            return state.counter;
        },
        getCounterRatio(state){
            return state.counter / 100;
        },

        getDeleteModalObj(state){
            return state.deleteModalObj;
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
        },
        // setDeleteModal(state, data){
        //     state.deleteModalObj.showDeleteModal = false;
        //     state.deleteModalObj.isDeleted = data;
        // },
        setDeleteModal(state, data){
            const deleteModalObj = {
                showDeleteModal:false,
                deleteUrl:'',
                deleteIndex:state.deleteModalObj.deleteIndex,
                isDeleted:data,
                deleteData:{

                },
            }
            state.deleteModalObj = deleteModalObj;
        },
        // resetDeleteModal(state,data){
        //     state.deleteModalObj.deleteIndex = data;
        // },
        
        setDeleteModalObj(state, data){
            state.deleteModalObj = data;
        }
    },
    /// actions asynchronous *** can be used for api calling
    actions:{
        decrementCounter({ commit }, payload){
            // console.log(commit);
            // console.log(payload);
            commit('decrementCounter',payload);

        },
        // reset({ commit }, data){
        //     commit('resetDeleteModal',data)
        // }
    }

})