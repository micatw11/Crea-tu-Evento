<template>
    <section class="content-header">
        <h1 v-if="titlePath">
            {{titlePath}}
        </h1>
        <ol class="breadcrumb">
            <template v-if="listPath.length > 0" v-for="path in listPath">
                <router-link :to="path.route" tag="li"><a>{{ path.name }}</a></router-link>

            </template>
        </ol>
    </section>
</template>
<script>
export default {
    data() {
        return {
            titlePath: '',
            listPath: []
        }
    },
    mounted() {

        this.$events.listen('changePath', this.changePath);
        this.$events.listen('pushToPath', this.putPath);
    },
    methods: {
        putPath: function(path, titlePath){
            this.titlePath = titlePath;
            this.listPath.push(path);
        },
        changePath: function(pathList, titlePath){
            this.titlePath = titlePath;
            this.listPath = pathList;
        }

    }
}   
</script>