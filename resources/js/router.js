import Vue from 'vue'
import Router from 'vue-router'

import firstPage from './components/pages/firstPage'
import secondPage from './components/pages/secondPage'
import hooks from './components/pages/basic/hooks'
import methods from './components/pages/basic/methods'

import dashboard from './components/pages/dashboard'
import tags from './components/tags'

Vue.use(Router)

const routes = [
	//project routes
	{
		path: '/dashboard',
      	component: dashboard,
	},
	{
		path: '/tags',
      	component: tags,
	},































	//test routes
	{
		path:'/first-page',
		component:firstPage,
		// name: 'home',
		// meta:{
		// }
	},
	{
		path:'/second-page',
		component:secondPage,
	},
	//vue-hooks
	{
		path:'/hooks',
		component:hooks,
	},
	//vue-methods
	{
		path:'/methods',
		component:methods,
	},

]

export default new Router({
	mode: 'history',
	routes
})