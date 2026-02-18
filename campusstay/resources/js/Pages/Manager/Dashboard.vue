<template>
  <div class="min-h-screen bg-slate-50">
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
      <div class="mb-8">
        <h1 class="text-3xl font-black text-slate-900">Manager Dashboard</h1>
        <p class="mt-1 text-sm text-slate-600">Track your hostels, occupancy, approvals, and revenue.</p>
      </div>

      <section class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <article class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
          <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Hostels</p>
          <p class="mt-2 text-2xl font-black text-slate-900">{{ stats.hostels }}</p>
        </article>
        <article class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
          <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total Beds</p>
          <p class="mt-2 text-2xl font-black text-slate-900">{{ stats.total_beds }}</p>
        </article>
        <article class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
          <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Occupied Beds</p>
          <p class="mt-2 text-2xl font-black text-slate-900">{{ stats.occupied_beds }}</p>
        </article>
        <article class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
          <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Occupancy Rate</p>
          <p class="mt-2 text-2xl font-black text-slate-900">{{ stats.occupancy_rate }}%</p>
        </article>
      </section>

      <section class="mb-8 grid gap-4 sm:grid-cols-3">
        <article class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
          <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Pending Approvals</p>
          <p class="mt-2 text-2xl font-black text-amber-600">{{ stats.pending_approvals }}</p>
        </article>
        <article class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
          <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Confirmed Bookings</p>
          <p class="mt-2 text-2xl font-black text-emerald-700">{{ stats.confirmed_bookings }}</p>
        </article>
        <article class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
          <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Successful Revenue</p>
          <p class="mt-2 text-2xl font-black text-blue-900">GHS {{ Number(stats.successful_revenue).toLocaleString() }}</p>
        </article>
      </section>

      <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <h2 class="text-xl font-bold text-slate-900">Recent Bookings</h2>

        <div v-if="recentBookings.length === 0" class="mt-4 rounded-lg bg-slate-50 p-4 text-sm text-slate-600">
          No bookings yet.
        </div>

        <div v-else class="mt-4 overflow-x-auto">
          <table class="min-w-full text-left text-sm">
            <thead>
              <tr class="border-b border-slate-200 text-slate-500">
                <th class="px-3 py-2 font-semibold">Reference</th>
                <th class="px-3 py-2 font-semibold">Student</th>
                <th class="px-3 py-2 font-semibold">Hostel</th>
                <th class="px-3 py-2 font-semibold">Status</th>
                <th class="px-3 py-2 font-semibold">Date</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="booking in recentBookings" :key="booking.id" class="border-b border-slate-100">
                <td class="px-3 py-3 font-medium text-slate-900">{{ booking.reference }}</td>
                <td class="px-3 py-3 text-slate-700">{{ booking.student_name }}</td>
                <td class="px-3 py-3 text-slate-700">{{ booking.hostel_name }}</td>
                <td class="px-3 py-3">
                  <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">
                    {{ booking.status }}
                  </span>
                </td>
                <td class="px-3 py-3 text-slate-600">{{ booking.created_at }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>
    </div>
  </div>
</template>

<script setup>
defineProps({
  stats: {
    type: Object,
    required: true,
  },
  recentBookings: {
    type: Array,
    default: () => [],
  },
});
</script>
