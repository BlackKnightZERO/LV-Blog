import Vue from 'vue'
import Router from 'vue-router'

import firstPage from './components/pages/firstPage'
import secondPage from './components/pages/secondPage'
import hooks from './components/pages/basic/hooks'
import methods from './components/pages/basic/methods'

//admin imports
import dashboard from './admin/pages/dashboard'
import tags from './admin/pages/tags'
import category from './admin/pages/category'

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
	{
		path: '/category',
      	component: category,
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