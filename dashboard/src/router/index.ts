import { createRouter, createWebHistory } from 'vue-router'
import type { RouteRecordRaw } from 'vue-router'
import { authRoutes } from './auth'
import { notAuthenticatedMiddleware } from '@/middlewares/auth.middleware'

const routes: RouteRecordRaw[] = [
    ...authRoutes,
    {
        path: '/dashboard',
        name: 'dashboard.index',
        component: () => import('@/views/dashboard/Dashboard.vue'),
        beforeEnter: notAuthenticatedMiddleware
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router