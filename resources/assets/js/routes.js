import VueRouter from 'vue-router';
import auth from './auth.js';


var Header = require('./components/Header');
var Login = require('./components/Auth/Login');
/*
import Barra from './components/Layouts/Barra.vue';
import BarraLateral from './components/Layouts/BarraLateral.vue';
import Footer from'./components/Layouts/Footer.vue';

*/

let routes = [ 
		{
			path: '/',
			name: 'home',
			component: require('./components/Home'),
			beforeEnter: guardRoute
		},
		{
			path: '/login',
			name: 'login',
			component: Login,
			beforeEnter: guardLogin
		},
		{
			path: '/about',
			component: require('./components/About'),
			beforeEnter: guardRoute
		},
		{
			path: '/calendario',
			component: require('./components/Calendario'),
			beforeEnter: guardRoute

		},
		{
			path: '/header',
			component: Header,
			beforeEnter: guardRoute
		}
 ];

function guardRoute (to, from, next) {

  if (!auth.user.authenticated) {
    next({
      name: 'login'
    })
  } else {
    next()
  }
}

function guardLogin(to, from, next) {
	if (!auth.user.authenticated) //control de logueo
	{
		//se controla que no se venga esde logout
		if(from.name == null || from.name !== 'home') 
		{		
			Vue.http.get('api/user').then(response => {
					auth.user.authenticated = true
					auth.user.profile = response.data.data
					next({ name: 'home'})
			}, response => {
					next();
			})
		} else {//si se viene desde logout
			next();
		}
	}
	else
	{
    next({ name: 'home'})
	}
}


export default new VueRouter({
	routes,
	linkActiveClass: 'is-active'
});