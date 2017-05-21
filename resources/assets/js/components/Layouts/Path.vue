<template>
    <section class="content-header">
        <h1>
            {{titleContent}}
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i>Home</a></li>
            <li class="active">{{ titleContent }}</li>
        </ol>
    </section>
</template>
<script>
export default {
    props:['titleContent']
}   
</script>