<template>
  <header
    class="sticky top-0 z-50 border-b border-slate-100 bg-white/95 backdrop-blur transition-shadow duration-300"
    :class="{ 'shadow-md': hasScrolled }"
  >
    <nav class="mx-auto flex h-16 w-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
      <RouterLink to="/" class="text-xl font-extrabold tracking-tight text-blue-900">
        <span class="text-emerald-600">Campus</span>Stay
      </RouterLink>

      <ul class="hidden items-center gap-8 md:flex">
        <li v-for="link in navLinks" :key="link.label">
          <RouterLink
            :to="link.to"
            class="text-sm font-semibold transition-colors duration-200"
            :class="isActiveRoute(link) ? 'text-blue-900' : 'text-slate-600 hover:text-blue-900'"
          >
            {{ link.label }}
          </RouterLink>
        </li>
      </ul>

      <div class="hidden items-center gap-3 md:flex">
        <button class="rounded-lg border border-blue-100 px-4 py-2 text-sm font-semibold text-blue-900 transition hover:bg-blue-50">
          Sign In
        </button>
        <button class="rounded-lg bg-blue-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-800">
          Get Started
        </button>
      </div>

      <button
        type="button"
        class="inline-flex items-center justify-center rounded-lg border border-slate-200 p-2 text-slate-700 md:hidden"
        @click="isMenuOpen = !isMenuOpen"
      >
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M4 7h16M4 12h16M4 17h16" />
        </svg>
      </button>
    </nav>

    <div v-if="isMenuOpen" class="border-t border-slate-100 bg-white px-4 py-4 shadow-sm md:hidden">
      <ul class="space-y-3">
        <li v-for="link in navLinks" :key="`mobile-${link.label}`">
          <RouterLink
            :to="link.to"
            class="block rounded-lg px-3 py-2 text-sm font-semibold"
            :class="isActiveRoute(link) ? 'bg-blue-50 text-blue-900' : 'text-slate-700 hover:bg-slate-50'"
            @click="isMenuOpen = false"
          >
            {{ link.label }}
          </RouterLink>
        </li>
      </ul>

      <div class="mt-4 grid grid-cols-2 gap-2">
        <button class="rounded-lg border border-blue-100 px-3 py-2 text-sm font-semibold text-blue-900">
          Sign In
        </button>
        <button class="rounded-lg bg-blue-900 px-3 py-2 text-sm font-semibold text-white">Get Started</button>
      </div>
    </div>
  </header>
</template>

<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();

const navLinks = [
  { label: 'Home', to: '/' },
  { label: 'Find Hostels', to: '/hostels' },
  { label: 'How It Works', to: { path: '/', hash: '#how-it-works' } },
];

const isMenuOpen = ref(false);
const hasScrolled = ref(false);

const handleScroll = () => {
  hasScrolled.value = window.scrollY > 8;
};

const isActiveRoute = (link) => {
  if (typeof link.to === 'string') {
    return route.path === link.to;
  }

  return route.path === link.to.path && route.hash === link.to.hash;
};

onMounted(() => {
  handleScroll();
  window.addEventListener('scroll', handleScroll, { passive: true });
});

onBeforeUnmount(() => {
  window.removeEventListener('scroll', handleScroll);
});
</script>
