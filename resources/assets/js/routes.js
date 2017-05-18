import VueRouter from 'vue-router';



var Header = require('./components/Header');

                


let routes = [ 
		{
			path: '/',
			component: require('./components/Home')
		},
		{
			path: '/about',
			component: require('./components/About')
		},
		{
			path: '/calendario',
			component: require('./components/Calendario')
		},
		{
			path: '/header',
			component: Header
		}
 ];



export default new VueRouter({
	routes,
	linkActiveClass: 'is-active'
});