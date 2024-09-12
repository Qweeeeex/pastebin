import { createRouter, createWebHistory } from 'vue-router'
import PasteView from '@/views/PasteView.vue'
import CreatePaste from '@/views/CreatePaste.vue'
import UserPastes from '@/views/UserPastes.vue'
import RecentAndLoggedPastes from '@/views/RecentAndLoggedPastes.vue'
import LoginView from '@/views/LoginView.vue'
import RegisterView from '@/views/RegisterView.vue'

const routes = [
    {
        path: '/',
        component: RecentAndLoggedPastes,
    },
    {
        path: '/pastes/:id',
        component: PasteView,
        props: true,
    },
    {
        path: '/create',
        component: CreatePaste,
    },
    {
        path: '/user/pastes',
        component: UserPastes,
    },
    {
        path: '/login',
        component: LoginView,
    },
    {
        path: '/register',
        component: RegisterView,
    },
]

// Создание роутера
const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router;