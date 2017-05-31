import VueRouter from 'vue-router';
import auth from './auth.js';



var Login = require('./components/Auth/Login');
var Home = require('./components/Home');
var About = require('./components/About');
var Calendar = require('./components/Calendario');
var Perfil = require('./components/Layouts/Perfil');
var Registrar = require('./components/Auth/Register');

let routes = [ 
		{
			path: '/',
			name: 'home',
			component: Home,
			beforeEnter: guardRoute
		},
		{
			path: '/login',
			name: 'login',
			component: Login,
			beforeEnter: guardLogin
		},
		{
			path: '/registrar',
			name: 'registrar',
			component: Registrar,
			beforeEnter: guardLogin
		},
		{
			path: '/about',
			component: About,
			beforeEnter: guardRoute
		},
		{
			path: '/calendario',
			component: Calendar,
			beforeEnter: guardRoute

		},
		{
			path: '/user/:userId/perfil',
			name: 'user',
			component: Perfil,
			beforeEnter: guardRoute

		}
 ];


function guardRoute (to, from, next) 
{
 	if (!auth.user.authenticated) {
		Vue.http.get('api/user').then(response => {
			auth.user.authenticated = true
			auth.user.profile = response.data.data
			next()
		}, response => {
			next('/login')
		})
  	} else {
		next()
	}
}

function guardLogin(to, from, next) 
{
	if(from.name == null)//si se recarga
	{
		Vue.http.get('api/user').then(response => {
			auth.user.authenticated = true
			auth.user.profile = response.data.data
			next({ path:'/' })//sigue a home
		}, response => {
			next()//sigue a login
		})
	} else if(auth.user.authenticated){//si se intenta aceder desde la url
		next(from.path)//sigue a login
	} else {
		next()//sigue a login
	}

}




export default new VueRouter({
	routes,
	linkActiveClass: 'is-active'
});