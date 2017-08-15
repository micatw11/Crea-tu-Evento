// componentes requeridos para rutas
import VueRouter from 'vue-router';
import auth from './auth.js';
import role from './config.js';

//templates
var Login = require('./components/Auth/Login');
var Home = require('./components/Home');


var Calendar = require('./components/Calendario');
var Registrar = require('./components/Auth/Register');

var FormPasswordReset = require('./components/Auth/Password/Email');
var PasswordReset = require('./components/Auth/Password/Reset');

var Perfil = require('./components/Layouts/Perfil');

var IndexUsuarios = require('./components/Usuarios/Index');
var IndexProveedor = require('./components/Proveedores/Index');
var NewProveedor = require('./components/Proveedores/New');
var EditRubro = require('./components/Proveedores/RubrosDetalle/EditRubro');
var Form = require('./components/Proveedores/Form');

var NewPublicacion = require('./components/Proveedores/Publicaciones/NewPublicacion');
var EditPublicacion = require('./components/Proveedores/Publicaciones/EditPublicacion');
var PerfilPublicacion = require('./components/Proveedores/Publicaciones/Perfil');

var IndexCategoria = require('./components/Proveedores/Categorias/Index');

var InternalServerError = require('./components/Errors/500');
var PageNotFound = require('./components/Errors/404');

let routes = [
		{
			path: "*", 
			component: PageNotFound
		},
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
			path: '/password-reset',
			name: 'passwordResetForm',
			component: FormPasswordReset,
			beforeEnter: guardLogin
		},
		{
			path: '/password/reset/:token',
			name: 'passwordReset',
			component: PasswordReset,
			beforeEnter: guardLogin
		},
		{
			path: '/usuario',
			component: IndexUsuarios,
			beforeEnter: guardRoute,
			meta: { Role: [role.ADMINISTRADOR, role.SUPERVISOR] }		
		},
		{
			path: '/proveedores',
			component: IndexProveedor,
			beforeEnter: guardRoute,
			meta: { Role: [role.ADMINISTRADOR, role.SUPERVISOR, role.OPERADOR] }	
		},
		{
			path: '/proveedor/new',
			component: NewProveedor,
			beforeEnter: guardRoute,
			meta: { Role: [role.ADMINISTRADOR, role.SUPERVISOR, role.OPERADOR]}	
		},
		{
			path: '/proveedor/rubro/:idRubro',
			name: 'EditRubro',
			component: EditRubro,
			beforeEnter: guardRoute,
			meta: { Role: [role.ADMINISTRADOR, role.SUPERVISOR, role.OPERADOR, role.PROVEEDOR]}	
		},
		{
			path: '/form',
			component: Form,
			beforeEnter: guardRoute	
		},
		{
			path: '/categorias',
			component: IndexCategoria,
			beforeEnter: guardRoute,
			meta: { Role: [role.ADMINISTRADOR, role.SUPERVISOR] }	
		},
		{
			path: '/calendario',
			component: Calendar,
			beforeEnter: guardRoute

		},
		{
			path: '/usuario/:userId/perfil',
			name: 'user',
			component: Perfil,
			beforeEnter: guardRoute

		},
		//publicaciones
		{
			path: '/publicacion/new',
			name: 'new-publicacion',
			component: NewPublicacion,
			beforeEnter: guardRoute,
			meta: { Role: [role.PROVEEDOR]}	
		},
		{
			path: '/publicacion/:publicacionId',
			name: 'perfil-publicacion',
			component: PerfilPublicacion,
			beforeEnter: guardRoute
		},
		{
			path: '/publicacion/:publicacionId/edit',
			name: 'edit-publicacion',
			component: EditPublicacion,
			beforeEnter: guardRoute,
			meta: { Role: [role.PROVEEDOR]}	
		},
		//errors
		{
			path: '/404',
			name: 'pageNotFound',
			component: PageNotFound,
			beforeEnter: guardRoute

		},
		{
			path: '/500',
			name: 'internalServerError',
			component: InternalServerError,
			beforeEnter: guardRoute

		}
 ];

/** guardRoute 
 *
 * Esta función chequea si el usuario esta 
 * en condiciones de acceder a las rutas
 *
 * Parametros
 * to: objeto de ruta a la que se desea acceder.
 * from: objeto de ruta de la que se proviene.
 * next: funcion utilizada para direccionar.
 *
 * Retorna 
 *	void
 */
function guardRoute (to, from, next) 
{
 	if (!auth.user.authenticated) {
 		if(!checkAuth()){
 			//se redirecciona al usuario al login si no esta autenticado
			next('/login');
		} else{
			//se chequea si el usuario posee acceso a la ruta
			checkRole(to, from, next);
		}
  	}
  	else 
  	{
  		//se chequea si el usuario posee acceso a la ruta
		checkRole(to, from, next);

	}
}

/** guardLogin 
 *
 * Esta función chequea si el usuario esta 
 * en condiciones de acceder al login
 *
 * Parametros
 * to: objeto de ruta a la que se desea acceder.
 * from: objeto de ruta de la que se proviene.
 * next: funcion utilizada para direccionar.
 *
 * Retorna 
 *	void
 */
function guardLogin(to, from, next) 
{
	if(from.name == null)//si se intenta acceder por url recargando
	{
		Vue.http.get('api/user').then(response => {
			auth.user.authenticated = true;
			auth.user.profile = response.data.data;
			next(from.path);//se redirecciona a home
		}, response => {
			next();//se autoriza a acceder a login
		})

	} else if(auth.user.authenticated){//si se intenta aceder desde alguna url
		next(from.path);//se redirecciona a la ruta de donde se proviene 
	} else {
		next();// se autoriza a acceder a login
	}

}

/** checkRole 
 *
 * Esta función chequea los roles necesarios que debe de tener 
 * un usuario para acceder a una ruta determinada.
 *
 * Parametros
 * to: objeto de ruta a la que se desea acceder.
 * from: objeto de ruta de la que se proviene.
 * next: funcion utilizada para direccionar.
 *
 * Retorna 
 *	void
 */
function checkRole(to, from, next){
	//Se chequea si la ruta a la que se desea acceder posee restricciones por roles
	if (to.matched.some(record => record.meta.Role)) {
		let match = false;
		for (let rol of to.meta.Role){
			//Se compara el rol del usuario con el los reoles requeridos para acceder
			if(rol == auth.user.profile.roles_id){
				match= true;
				break;
			}
		}

		if(match) next();
		else next(from.path);
	} else {
		next();
	}
}

/** checkAuth 
 *
 * Esta función chequea si el usuario posee una sesión activa 
 *
 * Retorna (Boolean)
 *	true: indicando que el usuario tiene una sesión iniciada
 *  false: indicando que el usuario no tiene una sesión iniciada
 */
function checkAuth(){
	Vue.http.get('api/user').then(response => {
		auth.user.authenticated = true;
		auth.user.profile = response.data.data;
		return true;
	}, response => {
		return false;
	})
}

export default new VueRouter({
	mode: 'hash',
	routes,
	linkActiveClass: 'is-active'
});