import Vue from 'vue';
import Router from 'vue-router';
import Dashboard from '../components/Pages/Dashboard';


Vue.use(Router);
const router = new Router({
    routes:[
        {
            path:'/',
            name:'index',
            component:Dashboard,
            mode: 'history'

        }
    ]
});



export default router;
