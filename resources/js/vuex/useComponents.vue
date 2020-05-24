<template>
	<div>
		<div class="content">
			<div class="container-fluid">
                <h2>Show Changes-></h2>
                <p> State of counter : {{ counter }}</p>
                <!-- <p> State of counter : {{ getCounter }}</p> -->
                 <!-- <p> State of counter : {{ $store.state.counter }}</p> -->
            </div>

            <div style="margin-top:25px;">
                <componentA></componentA>
            </div>
            <div style="margin-top:25px;">
                <componentB></componentB>
            </div>
            <div style="margin-top:25px;">
                <componentC></componentC>
            </div>
            <Button type="info" @click="stateIncrement"> + </Button>
            <Button type="warning" @click="stateDecrement"> - </Button>
            <Button type="success" @click="doubleO"> 00 </Button>
        </div>
    </div>        
</template>

<script>
import componentA from './componentA';
import componentB from './componentB';
import componentC from './componentC';

import { mapGetters } from 'vuex';
    
export default {
    components:{
        componentA,componentB,componentC,
    },
    watch:{
        counter(val){ //vuex-state-access
            console.log(val);
             this.runAmethodWhenCounterChange(); //run method from watch
             console.log('local var->',this.localvar); //access local variable from watch
        },
    },
    data(){
        return{
            localvar : 99,
        }
    },
    created(){     ///this is not getter
        // console.log(this.$store.state.counter);
    },
    computed: {
        // ...mapGetters(['getCounter'])
        ...mapGetters({
            'counter' : 'getCounter'  //custom name assigned
        })
    },
    methods:{
        stateIncrement(){
            // this.$store.state.counter++;
            this.$store.commit('incrementCounter',1);  //name, payload
        },
        stateDecrement(){
            //this.$store.commit('decrementCounter',1);  //name, payload
            this.$store.dispatch('decrementCounter',1);  // dispatching an action*
        },
        doubleO(){
            this.$store.commit('multiplyHundred');     //name, no-payload
        },
        runAmethodWhenCounterChange(){
            console.log('running onchnage method');
        }
    }

}
</script>