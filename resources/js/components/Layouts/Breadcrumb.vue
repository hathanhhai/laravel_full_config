<template>

    <div class="row bm-mobile">
        <div class="col-sm-12 button_class">
            <div class="btn-group pull-left m-t-20 m-b-10">
                <router-link to="/dashboard" class="btn btn-custom waves-effect waves-light w-md m-b-5">Tổng Quan</router-link>
                <router-link to="/controller" class="btn btn-purple waves-effect w-md waves-light m-b-5 button-class-element">Điều Khiển</router-link>
                <router-link to="/camera" class="btn btn-info waves-effect w-md waves-light m-b-5 button-class-element">Camera</router-link>
            </div>
        </div>
    </div>
    <!-- end page title end breadcrumb -->
</template>

<script>
    export default {
        name: "Breadcrumb"
    }
</script>

<style scoped>

</style>