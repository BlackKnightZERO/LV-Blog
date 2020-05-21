<template>
    <div>
        <h2>I am component C</h2>
        <p> State of counter : {{ counter }}</p>
        <!-- <p> State of counter : {{ $store.state.counter }}</p> -->
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    computed: {
        // ...mapGetters(['getCounter'])
        ...mapGetters({
            'counter' : 'getCounter'  //custom name assigned
        })
    },
}
</script>