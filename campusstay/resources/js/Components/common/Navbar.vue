<template>
  <header
    class="sticky top-0 z-50 border-b border-slate-100 bg-white/95 backdrop-blur transition-shadow duration-300"
    :class="{ 'shadow-md': hasScrolled }"
  >
    <nav class="mx-auto flex h-16 w-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
      <Link href="/" class="text-xl font-extrabold tracking-tight text-blue-900">
        <span class="text-emerald-600">Campus</span>Stay
      </Link>

      <ul class="hidden items-center gap-8 md:flex">
        <li v-for="link in navLinks" :key="link.label">
          <Link
            :href="link.href"
            class="text-sm font-semibold transition-colors duration-200"
            :class="isActiveRoute(link.href) ? 'text-blue-900' : 'text-slate-600 hover:text-blue-900'"
          >
            {{ link.label }}
          </Link>
        </li>
      </ul>

      <div class="hidden items-center gap-3 md:flex">
        <template v-if="user">
          <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">{{ user.name }}</span>
          <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">{{ roleLabel }}</span>
          <Link
            href="/logout"
            method="post"
            as="button"
            type="button"
            class="rounded-lg border border-blue-100 px-4 py-2 text-sm font-semibold text-blue-900 transition hover:bg-blue-50"
          >
            Logout
          </Link>
        </template>
        <template v-else>
          <Link href="/login" class="rounded-lg border border-blue-100 px-4 py-2 text-sm font-semibold text-blue-900 transition hover:bg-blue-50">
            Sign In
          </Link>
          <Link href="/register" class="rounded-lg bg-blue-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-800">
            Get Started
          </Link>
        </template>
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
          <Link
            :href="link.href"
            class="block rounded-lg px-3 py-2 text-sm font-semibold"
            :class="isActiveRoute(link.href) ? 'bg-blue-50 text-blue-900' : 'text-slate-700 hover:bg-slate-50'"
            @click="isMenuOpen = false"
          >
            {{ link.label }}
          </Link>
        </li>
      </ul>

      <div class="mt-4 grid grid-cols-2 gap-2">
        <template v-if="user">
          <Link
            href="/logout"
            method="post"
            as="button"
            type="button"
            class="col-span-2 rounded-lg border border-blue-100 px-3 py-2 text-sm font-semibold text-blue-900"
          >
            Logout
          </Link>
        </template>
        <template v-else>
          <Link href="/login" class="rounded-lg border border-blue-100 px-3 py-2 text-center text-sm font-semibold text-blue-900">
            Sign In
          </Link>
          <Link href="/register" class="rounded-lg bg-blue-900 px-3 py-2 text-center text-sm font-semibold text-white">Get Started</Link>
        </template>
      </div>
    </div>
  </header>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';

const page = usePage();

const user = computed(() => page.props.auth?.user ?? null);
const navLinks = computed(() => {
  const links = [
    { label: 'Home', href: '/' },
    { label: 'Find Hostels', href: '/hostels' },
    { label: 'How It Works', href: '/#how-it-works' },
  ];

  if (user.value?.role === 'admin') {
    links.push({ label: 'Admin Dashboard', href: '/admin/dashboard' });
  }

  if (user.value?.role === 'manager') {
    links.push({ label: 'Manager Dashboard', href: '/manager/dashboard' });
  }

  return links;
});
const roleLabel = computed(() => {
  if (!user.value?.role) return '';
  return user.value.role.charAt(0).toUpperCase() + user.value.role.slice(1);
});
const isMenuOpen = ref(false);
const hasScrolled = ref(false);

const handleScroll = () => {
  hasScrolled.value = window.scrollY > 8;
};

const normalizePath = (url) => {
  const [path] = url.split('?');
  return path.replace(/\/$/, '') || '/';
};

const isActiveRoute = (href) => {
  const currentPath = normalizePath(page.url);
  const targetPath = normalizePath(href.split('#')[0]);

  if (targetPath === '/') {
    return currentPath === '/';
  }

  return currentPath.startsWith(targetPath);
};

onMounted(() => {
  handleScroll();
  window.addEventListener('scroll', handleScroll, { passive: true });
});

onBeforeUnmount(() => {
  window.removeEventListener('scroll', handleScroll);
});
</script>
