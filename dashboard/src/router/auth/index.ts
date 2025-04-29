import type { RouteRecordRaw } from 'vue-router'
import {authenticatedMiddleware} from "@/middlewares/auth.middleware.ts";

export const authRoutes: RouteRecordRaw[] = [
    {
        path: '/admin',
        name: 'admin.signin',
        component: () => import('@/views/auth/Login.vue'),
        beforeEnter: authenticatedMiddleware
    }
]