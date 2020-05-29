import Vue from 'vue'
import Router from 'vue-router'

import firstPage from './components/pages/firstPage'
import secondPage from './components/pages/secondPage'
import hooks from './components/pages/basic/hooks'
import methods from './components/pages/basic/methods'

import useComponents from './vuex/useComponents'

//login
import login from './components/login'

//admin imports
import dashboard from './admin/pages/dashboard'
import tags from './admin/pages/tags'
import category from './admin/pages/category'
import users from './admin/pages/users'
import role from './admin/pages/role'
import permission from './admin/pages/permission'

Vue.use(Router)

const routes = [
	//project routes
	{
		path: '/login',
		  component: login,
		  name:'login'
	},
	{
		path: '/dashboard',
		  component: dashboard,
		  name:'dashboard'
	},
	{
		path: '/tags',
		  component: tags,
		  name:'tags'
	},
	{
		path: '/category',
		  component: category,
		  name:'category'
	},
	{
		path: '/users',
		  component: users,
		  name:'users'
	},
	{
		path: '/role',
		  component: role,
		  name:'role'
	},
	{
		path: '/permission',
		  component: permission,
		  name:'permission'
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
	//vuex-test
	{
		path:'/vuex-test',
		component:useComponents,
	},

]

export default new Router({
	mode: 'history',
	routes
})