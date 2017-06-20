<template>
    <section class="content-header">
        <h1 v-if="titlePath">
            {{titlePath}}
        </h1>
        <h1 v-else>
            Home
        </h1>
        <ol class="breadcrumb">
            <li><a href="#">Home</a></li>
            <li v-for="path in listPath" href="#"><a>{{ path }}</a></li>
        </ol>
    </section>
</template>
<script>
export default {
    props:{
        titlePath: {
            required: false
        },
        listPath: {
            required: false
        }
    }
}   
</script>