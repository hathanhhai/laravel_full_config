<template>
    <div class="navbar-custom">
        <div class="container-fluid">
            <div id="navigation">
                <!-- Navigation Menu-->
                <ul class="navigation-menu">
                    <li class="has-submenu">
                        <router-link to="/dashboard"><i class="mdi mdi-view-dashboard"></i> <span> Tổng Quan </span> </router-link>
                    </li>
                    <li class="has-submenu">
                        <router-link to="/controller"><i class="mdi mdi-invert-colors"></i> <span> Điều Khiển </span> </router-link>
                    </li>
                    <li class="has-submenu">
                        <router-link to="/camera"><i class="mdi mdi-chart-donut-variant"></i><span> Camera </span> </router-link>
                    </li>
                    <li class="has-submenu">
                        <router-link to="/setting"><i class="mdi mdi-view-list"></i> <span> Thiết Lập </span> </router-link>

                    </li>
                </ul>
                <!-- End navigation menu -->
            </div> <!-- end #navigation -->
        </div> <!-- end container -->
    </div>
</template>

<script>
    export default {
        name: "Navigation"
    }
</script>

<style scoped>

</style>