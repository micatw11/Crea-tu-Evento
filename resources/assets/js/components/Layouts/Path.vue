<template>
    <section class="content-header">
        <h1 v-if="titleContent">
            {{titleContent}}
        </h1>
        <h1 v-else>
            Home
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i>Home</a></li>
            <li v-if="titleContent" class="active">{{ titleContent }}</li>

            <div v-if="listContent" v-for="path in listContent">
                <li class="active">{{ path }}</li>
            </div>
        </ol>
    </section>
</template>
<script>
export default {
    props:{
        titleContent: {
            required: false
        },
        listContent: {
            required: false
        }
    }
}   
</script>