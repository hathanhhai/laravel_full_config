<template>

    <!-- Navigation Bar-->
    <header id="topnav">
        <div class="topbar-main">
            <div class="container-fluid">

                <!-- Logo container-->
                <div class="logo">
                    <!-- Text Logo -->
                    <!--<a href="index.html" class="logo">-->
                    <!--<span class="logo-small"><i class="mdi mdi-radar"></i></span>-->
                    <!--<span class="logo-large"><i class="mdi mdi-radar"></i> Adminto</span>-->
                    <!--</a>-->
                    <!-- Image Logo -->
                    <router-link to="/" class="logo">
                        <img src="/adminto/assets/images/logo-sm.png" alt="" height="26" class="logo-small">
                        <img src="/adminto/assets/images/logo.png" alt="" height="24" class="logo-large">
                    </router-link>

                </div>
                <!-- End Logo container-->


                <div class="menu-extras topbar-custom">

                    <ul class="list-unstyled topbar-right-menu float-right mb-0">

                        <li class="menu-item">
                        <!-- Mobile menu toggle-->
                        <a class="navbar-toggle nav-link">
                            <div class="lines">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </a>
                        <!-- End mobile menu toggle-->
                    </li>




                        <li class="notification-list btn-logout">
                            <a   href="/authentication/logout" class="">
                                <i style="color:white" class="ti-power-off m-r-5"></i> <i style="color:white">Logout</i>
                            </a>
                        </li>

                    </ul>
                </div>
                <!-- end menu-extras -->

                <div class="clearfix"></div>

            </div> <!-- end container -->
        </div>
        <!-- end topbar-main -->

        <Navigation></Navigation>

        <!-- end navbar-custom -->
    </header>
    <!-- End Navigation Bar-->
</template>

<script>
    import Navigation from './Navigation'
    export default {
        name: "Header",
        components: {Navigation},
        methods:{

        }
    }
</script>

<style scoped>

</style>
