import {createRouter, createWebHistory} from 'vue-router';
import Login from "./views/auth/Login.vue";
import AuthLayout from "./layouts/full-page/AuthLayout.vue";
import DashboardLayout from "./layouts/dashboard/DashboardLayout.vue";
import store from "./store";
import middlewarePipeline from "./middleware/pipeline.js";
import auth from "./middleware/auth.js";
import packer from "./middleware/packer.js";
import packerGuest from "./middleware/packer-guest.js";
import guest from "./middleware/guest.js";
import ProductsList from "./views/product/List.vue";
import CategoriesList from "./views/category/List.vue";
import PackerLogin from "./views/auth/PackerLogin.vue";
import List from "./views/packers/List.vue";
import Packaging from "./views/packer/Packaging.vue";
import Package from "./views/packer/Package.vue";
import PackagesList from "./views/Package/List.vue";

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            component: DashboardLayout,
            children: [
                {
                    path: '/categories',
                    name: 'categories',
                    component: CategoriesList,
                    meta: {
                        middleware: [auth]
                    }
                },
                {
                    path: '/products',
                    name: 'products',
                    component: ProductsList,
                    meta: {
                        middleware: [auth]
                    }
                },
                {
                    path: '/packages',
                    name: 'packages',
                    component: PackagesList,
                    meta: {
                        middleware: [auth]
                    }
                },
                {
                    path: '/packers',
                    name: 'packers',
                    component: List,
                    meta: {
                        middleware: [auth]
                    }
                },
                {
                    path: '/packaging',
                    name: 'packaging',
                    component: Packaging,
                    meta: {
                        middleware: [packer]
                    }
                },
                {
                    path: '/package',
                    name: 'package',
                    component: Package,
                    meta: {
                        middleware: [packer]
                    }
                },
            ]
        },
        {
            path: '/',
            component: AuthLayout,
            children: [
                {
                    path: '/login',
                    name: 'login',
                    component: Login,
                    meta: {
                        middleware: [guest]
                    }
                },
                {
                    path: '/packer-login',
                    name: 'packer-login',
                    component: PackerLogin,
                    meta: {
                        middleware: [packerGuest]
                    }
                }
            ]
        },
    ],
});

router.beforeEach(async (to, from, next) => {

    /** Navigate to next if middleware is not applied */
    if (!to.meta.middleware && !to.meta.config && !to.meta.permissions) {
        return next()
    }
    const middleware = to.meta.middleware;
    const context = {
        to, from, next, store
    }

    return middleware[0]({
        ...context, next: middlewarePipeline(context, middleware, 1)
    })
})
export default router;
