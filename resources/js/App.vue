<template>
    <div>
        <Header v-if="this.$route.path != '/login'">
        </Header>

        <div class="wrapper">
            <div class="container-fluid mt-mobile">
            <Breadcrumb ></Breadcrumb>
               <router-view/>
            </div>
        </div>
    </div>
</template>

<script>
    import Navigation from "./components/Layouts/Navigation";
    import Header from "./components/Layouts/Header";
    import Breadcrumb from './components/Layouts/Breadcrumb'

    export default {
        name: 'App',
        components: {Navigation, Header, Breadcrumb},
        created(){

        },
        mounted(){

        }
    }
</script>

<style scoped>

</style>
