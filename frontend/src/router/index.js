import { createRouter, createWebHistory } from 'vue-router'
import PasteView from '@/views/PasteView.vue'
import CreatePaste from '@/views/CreatePaste.vue'
import UserPastes from '@/views/UserPastes.vue'
import RecentPastes from '@/views/RecentAndLoggedPastes.vue'

const routes = [
    {
        path: '/',
        component: RecentPastes,
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
]

// Создание роутера
const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router;