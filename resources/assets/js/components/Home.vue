<template>
    <div>
        <path-content :titleContent="titleContent"></path-content>
        <section class="content">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">Example Component</div>

                        <div class="panel-body">
                            I'm an example Home!
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
import PathContent from './Layouts/Path.vue'
    export default {
        data(){
            return {
                titleContent: 'Dashboard'
            }
        },
        components: {PathContent}
    }
</script>