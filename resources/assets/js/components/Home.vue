<template>
    <div class="default-content">
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
    export default {
        data(){
            return {
                titlePath: 'Home',
                listaPath: [{route: '/', name: 'Home'}]
            }
        },
        mounted() {
            this.$events.fire('changePath', this.listaPath, this.titlePath);
        }
    }
</script>
<style>
    .default-content {
        min-height: 570px;
    }
</style>