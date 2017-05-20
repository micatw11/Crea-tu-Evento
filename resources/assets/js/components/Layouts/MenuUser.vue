<template>
    <!-- User Account: style can be found in dropdown.less -->
    <li class="dropdown user user-menu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <img src="/dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
            <span v-if="auth.user.authenticated" class="hidden-xs">{{ auth.user.profile.name }}</span>
        </a>
        <ul class="dropdown-menu">
            <!-- User image -->
            <li class="user-header" v-if="auth.user.authenticated">
                <img src="/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                <p>
                    {{ auth.user.profile.name }} - Web Developer
                    <small>Member since Nov. 2012</small>
                </p>
            </li>
            <!-- Menu Body -->
            <li class="user-body">
                <div class="row">
                    <div class="col-xs-4 text-center">
                        <a href="#">Followers</a>
                    </div>
                    <div class="col-xs-4 text-center">
                        <a href="#">Sales</a>
                    </div>
                    <div class="col-xs-4 text-center">
                        <a href="#">Friends</a>
                    </div>
                </div>
                <!-- /.row -->
            </li>
            <!-- Menu Footer-->
            <li class="user-footer">
                <div class="pull-left">
                    <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                    <a  @click="logout" class="btn btn-default btn-flat">
                        Logout
                    </a>
                </div>
            </li>
        </ul>
    </li>
</template>
<script>
import auth from '../../auth.js';
import router from '../../routes.js';
export default {
    data() {
        return {
            auth: auth
        }
    },
    methods: {
        logout: function() {
            
            this.$http.post(
                'api/logout'
            ).then(response => {
                auth.user.authenticated = false
                auth.user.profile = null
                router.push('/login')
            })

        }
    }
}
</script>