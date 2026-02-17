<template>
  <div class="min-h-screen bg-slate-50">
    <Navbar />

    <section class="relative isolate overflow-hidden">
      <img
        src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?auto=format&fit=crop&w=1800&q=80"
        alt="Campus residence"
        class="h-[560px] w-full object-cover"
      />
      <div class="absolute inset-0 bg-slate-950/60" />
      <div class="absolute -bottom-20 right-8 h-64 w-64 rounded-full bg-emerald-400/20 blur-3xl" />

      <div class="absolute inset-0 mx-auto flex w-full max-w-7xl items-center px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-3xl space-y-8 text-white">
          <div class="space-y-4">
            <div class="inline-flex items-center rounded-full border border-white/30 bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-emerald-200">
              Trusted by students across Ghana campuses
            </div>
            <h1 class="text-4xl font-black leading-tight sm:text-5xl">Find Your Perfect Campus Home</h1>
            <p class="max-w-2xl text-base text-slate-100 sm:text-lg">
              Browse, compare and book verified university hostels - all in one place.
            </p>
          </div>

          <form
            class="grid gap-3 rounded-2xl bg-white/95 p-4 text-slate-900 shadow-lg sm:grid-cols-[1fr_auto_auto]"
            @submit.prevent="handleSearch"
          >
            <input
              v-model="searchInput"
              type="text"
              placeholder="Search by hostel name..."
              class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm outline-none ring-blue-300 transition focus:ring"
            />

            <select
              v-model="genderFilter"
              class="rounded-lg border border-slate-200 px-4 py-3 text-sm outline-none ring-blue-300 transition focus:ring"
            >
              <option value="all">All</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
              <option value="mixed">Mixed</option>
            </select>

            <button
              type="submit"
              class="rounded-lg bg-blue-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-blue-800"
            >
              Search
            </button>
          </form>

          <div class="flex flex-wrap items-center gap-3">
            <RouterLink
              to="/hostels"
              class="rounded-lg bg-emerald-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-400"
            >
              Browse Verified Hostels
            </RouterLink>
            <RouterLink
              :to="{ path: '/', hash: '#how-it-works' }"
              class="rounded-lg border border-white/40 px-4 py-2 text-sm font-semibold text-white transition hover:bg-white/10"
            >
              See How It Works
            </RouterLink>
          </div>
        </div>
      </div>
    </section>

    <section class="border-y border-slate-200 bg-white py-7">
      <div class="mx-auto grid w-full max-w-7xl gap-4 px-4 sm:grid-cols-3 sm:px-6 lg:px-8">
        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-center">
          <p class="text-2xl font-black text-blue-900">500+</p>
          <p class="text-sm text-slate-600">Students Housed</p>
        </div>
        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-center">
          <p class="text-2xl font-black text-blue-900">20+</p>
          <p class="text-sm text-slate-600">Verified Hostels</p>
        </div>
        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-center">
          <p class="text-2xl font-black text-blue-900">3</p>
          <p class="text-sm text-slate-600">Room Types Available</p>
        </div>
      </div>
    </section>

    <section class="mx-auto w-full max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
      <div class="mb-8 flex items-center justify-between">
        <div>
          <h2 class="text-2xl font-black text-slate-900">Featured Hostels</h2>
          <p class="mt-1 text-sm text-slate-600">Top-rated options students are booking this semester.</p>
        </div>
        <RouterLink
          to="/hostels"
          class="rounded-lg border border-blue-200 px-4 py-2 text-sm font-semibold text-blue-900 transition hover:bg-blue-50"
        >
          View All Hostels
        </RouterLink>
      </div>

      <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <HostelCard v-for="hostel in featuredHostels" :key="hostel.id" :hostel="hostel" />
      </div>
    </section>

    <section id="how-it-works" class="border-y border-slate-200 bg-white py-16 scroll-mt-24">
      <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
        <h2 class="mb-2 text-center text-2xl font-black text-slate-900">How It Works</h2>
        <p class="mb-8 text-center text-sm text-slate-600">Three simple steps from search to move-in.</p>

        <div class="grid gap-6 md:grid-cols-3">
          <article
            v-for="(step, index) in steps"
            :key="step.title"
            class="rounded-2xl border border-slate-200 bg-slate-50 p-6 text-center transition hover:-translate-y-1 hover:shadow-md"
          >
            <div class="mb-4 text-xs font-bold uppercase tracking-wide text-blue-700">
              Step {{ index + 1 }}
            </div>
            <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 text-blue-900">
              <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path :d="step.icon" />
              </svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900">{{ step.title }}</h3>
            <p class="mt-2 text-sm text-slate-600">{{ step.description }}</p>
          </article>
        </div>
      </div>
    </section>

    <footer class="bg-slate-900 py-10 text-slate-200">
      <div class="mx-auto flex w-full max-w-7xl flex-col gap-6 px-4 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8">
        <div>
          <p class="text-xl font-extrabold"><span class="text-emerald-400">Campus</span>Stay</p>
          <p class="text-sm text-slate-400">University hostel discovery and booking platform.</p>
        </div>

        <div class="flex flex-wrap gap-4 text-sm font-semibold">
          <RouterLink to="/" class="hover:text-white">Home</RouterLink>
          <RouterLink to="/hostels" class="hover:text-white">Find Hostels</RouterLink>
          <RouterLink :to="{ path: '/', hash: '#how-it-works' }" class="hover:text-white">
            How It Works
          </RouterLink>
        </div>
      </div>

      <p class="mt-8 text-center text-xs text-slate-400">
        (c) {{ currentYear }} CampusStay. All rights reserved.
      </p>
    </footer>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue';
import { useRouter } from 'vue-router';
import Navbar from '../components/common/Navbar.vue';
import HostelCard from '../components/hostel/HostelCard.vue';
import { useHostelStore } from '../stores/hostelStore';

const router = useRouter();
const hostelStore = useHostelStore();

const searchInput = ref('');
const genderFilter = ref('all');

const featuredHostels = computed(() => hostelStore.hostels.slice(0, 3));

const currentYear = new Date().getFullYear();

const steps = [
  {
    title: 'Search & Filter',
    description: 'Use smart filters to find hostels that fit your budget and preferences.',
    icon: 'M3 6h18M7 12h10M10 18h4',
  },
  {
    title: 'Compare & Choose',
    description: 'Review room options, amenities, distance, and ratings before deciding.',
    icon: 'M4 6h16v12H4zM9 10h6',
  },
  {
    title: 'Book & Move In',
    description: 'Reserve your preferred room and get ready for a smooth campus stay.',
    icon: 'M6 3h12l3 4v14H3V7l3-4Zm2 8 2 2 4-4',
  },
];

const handleSearch = async () => {
  hostelStore.setSearchQuery(searchInput.value.trim());
  hostelStore.setFilters({ gender_policy: genderFilter.value });
  await router.push('/hostels');
};
</script>
