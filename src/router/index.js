import { defineAsyncComponent } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';

const HomePage = defineAsyncComponent(() => import('../pages/HomePage.vue'));
const ListingsPage = defineAsyncComponent(() => import('../pages/ListingsPage.vue'));
const HostelDetailPage = defineAsyncComponent(() => import('../pages/HostelDetailPage.vue'));

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomePage,
    },
    {
      path: '/hostels',
      name: 'hostels',
      component: ListingsPage,
    },
    {
      path: '/hostels/:id',
      name: 'hostel-detail',
      component: HostelDetailPage,
    },
  ],
  scrollBehavior() {
    return { top: 0, left: 0 };
  },
});

export default router;
