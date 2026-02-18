<template>
  <div class="min-h-screen bg-slate-50">
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
      <div class="mb-8 flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-black text-slate-900">Admin Dashboard</h1>
          <p class="mt-1 text-sm text-slate-600">Manage manager approvals and monitor platform activity.</p>
        </div>
      </div>

      <p v-if="$page.props.flash.success" class="mb-5 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm text-emerald-700">
        {{ $page.props.flash.success }}
      </p>

      <section class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <article class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm" v-for="card in cards" :key="card.label">
          <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ card.label }}</p>
          <p class="mt-2 text-2xl font-black text-slate-900">{{ card.value }}</p>
        </article>
      </section>

      <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <h2 class="text-xl font-bold text-slate-900">Pending Manager Approvals</h2>

        <div v-if="pendingManagers.length === 0" class="mt-4 rounded-lg bg-slate-50 p-4 text-sm text-slate-600">
          No pending manager approvals.
        </div>

        <div v-else class="mt-4 space-y-4">
          <article
            v-for="manager in pendingManagers"
            :key="manager.id"
            class="flex flex-col gap-4 rounded-xl border border-slate-200 p-4 md:flex-row md:items-center md:justify-between"
          >
            <div>
              <p class="font-semibold text-slate-900">{{ manager.name }}</p>
              <p class="text-sm text-slate-600">{{ manager.email }}</p>
              <p class="text-sm text-slate-500">{{ manager.university || 'University not set' }} • {{ manager.requested_at }}</p>
            </div>

            <div class="flex items-center gap-2">
              <button
                type="button"
                class="rounded-lg bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-500"
                @click="approveManager(manager.id)"
              >
                Approve
              </button>
              <button
                type="button"
                class="rounded-lg border border-rose-200 px-3 py-2 text-xs font-semibold text-rose-700 hover:bg-rose-50"
                @click="suspendManager(manager.id)"
              >
                Suspend
              </button>
            </div>
          </article>
        </div>
      </section>

      <section class="mt-8 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <h2 class="text-xl font-bold text-slate-900">All Manager Accounts</h2>

        <div v-if="managers.length === 0" class="mt-4 rounded-lg bg-slate-50 p-4 text-sm text-slate-600">
          No manager accounts yet.
        </div>

        <div v-else class="mt-4 overflow-x-auto">
          <table class="min-w-full text-left text-sm">
            <thead>
              <tr class="border-b border-slate-200 text-slate-500">
                <th class="px-3 py-2 font-semibold">Manager</th>
                <th class="px-3 py-2 font-semibold">University</th>
                <th class="px-3 py-2 font-semibold">Requested</th>
                <th class="px-3 py-2 font-semibold">Status</th>
                <th class="px-3 py-2 font-semibold">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="manager in managers" :key="`all-${manager.id}`" class="border-b border-slate-100">
                <td class="px-3 py-3">
                  <p class="font-semibold text-slate-900">{{ manager.name }}</p>
                  <p class="text-xs text-slate-600">{{ manager.email }}</p>
                </td>
                <td class="px-3 py-3 text-slate-700">{{ manager.university || '-' }}</td>
                <td class="px-3 py-3 text-slate-600">{{ manager.requested_at }}</td>
                <td class="px-3 py-3">
                  <span
                    class="rounded-full px-2.5 py-1 text-xs font-semibold"
                    :class="manager.is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700'"
                  >
                    {{ manager.is_active ? 'Active' : 'Pending' }}
                  </span>
                </td>
                <td class="px-3 py-3">
                  <button
                    v-if="!manager.is_active"
                    type="button"
                    class="rounded-lg bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-500"
                    @click="approveManager(manager.id)"
                  >
                    Approve
                  </button>
                  <button
                    v-else
                    type="button"
                    class="rounded-lg border border-rose-200 px-3 py-2 text-xs font-semibold text-rose-700 hover:bg-rose-50"
                    @click="suspendManager(manager.id)"
                  >
                    Suspend
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>
    </div>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
  stats: {
    type: Object,
    required: true,
  },
  pendingManagers: {
    type: Array,
    default: () => [],
  },
  managers: {
    type: Array,
    default: () => [],
  },
});

const cards = computed(() => [
  { label: 'Total Users', value: props.stats.totalUsers },
  { label: 'Students', value: props.stats.students },
  { label: 'Managers', value: props.stats.managers },
  { label: 'Pending Manager Approvals', value: props.stats.pendingManagerApprovals },
  { label: 'Active Hostels', value: props.stats.activeHostels },
  { label: 'Pending Bookings', value: props.stats.pendingBookings },
]);

const approveManager = (id) => {
  router.post(`/admin/managers/${id}/approve`);
};

const suspendManager = (id) => {
  router.post(`/admin/managers/${id}/suspend`);
};
</script>
