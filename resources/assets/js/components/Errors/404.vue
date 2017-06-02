<template>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
        <path-content :titleContent="titleContent"></path-content>

        <!-- Main content -->
        <section class="content">
            <div class="error-page">
                <h2 class="headline text-yellow"> 404</h2>

                <div class="error-content">
                    <h3><i class="fa fa-warning text-yellow"></i> Oops! Page not found.</h3>

                    <p>
                        No pudimos encontrar la página que buscabas.
                        <a href="#">regresar a dashboard</a>
                    </p>
                </div>
                <!-- /.error-content -->
            </div>
            <!-- /.error-page -->
        </section>
        <!-- /.content -->
    </div>
</template>
<script>
import PathContent from './../Layouts/Path.vue';
export default {
    data(){
        return {
            titleContent: 'Page not found'
        }
    },
    components:{PathContent}
}
</script>
