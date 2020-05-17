<template>
	<div>
		<h3 ref="myref">hooks page</h3>
		<p>{{ name }}</p>
	</div>
</template>

<script>
export default {
	data(){
		return{
			name:'arif',
		}
	},
	beforeCreate(){
		console.log(this.name);
		console.log('beforeCreate can be used for redirect if not logged in');
	},
	created(){
		console.log(this.name,this.$refs.myref);
		 console.log('created can be used to retrieve data with axios api')
	},
	mounted(){
		 console.log(this.$refs.myref);
		 console.log('mounted can be used to access html data with ref');
	}
}
</script>