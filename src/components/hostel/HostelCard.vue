<template>
  <article
    class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg"
  >
    <div class="relative aspect-video overflow-hidden">
      <img
        :src="hostel.images[0]"
        :alt="hostel.name"
        class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
      />

      <span
        v-if="hostel.is_verified"
        class="absolute left-3 top-3 rounded-full bg-emerald-600 px-3 py-1 text-xs font-semibold text-white"
      >
        Verified
      </span>

      <span
        class="absolute right-3 top-3 rounded-full px-3 py-1 text-xs font-semibold text-white"
        :class="genderBadgeClass"
      >
        {{ genderPolicyLabel }}
      </span>
    </div>

    <div class="space-y-4 p-4">
      <h3 class="text-lg font-bold text-slate-900">{{ hostel.name }}</h3>

      <div class="flex flex-wrap items-center gap-3 text-sm text-slate-600">
        <span class="inline-flex items-center gap-1">
          <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M12 21s7-6.2 7-12a7 7 0 1 0-14 0c0 5.8 7 12 7 12Z" />
            <circle cx="12" cy="9" r="2.5" />
          </svg>
          {{ hostel.distance_from_campus }}
        </span>

        <span class="inline-flex items-center gap-1">
          <svg class="h-4 w-4 fill-amber-400 text-amber-400" viewBox="0 0 24 24">
            <path
              d="M12 2.6 14.9 8l5.9.9-4.3 4.2 1 5.9L12 16.9 6.5 19l1-5.9L3.2 8.9 9.1 8 12 2.6Z"
            />
          </svg>
          <span class="font-medium text-slate-700">{{ hostel.rating.toFixed(1) }}</span>
          <span>({{ hostel.total_reviews }} reviews)</span>
        </span>
      </div>

      <p class="text-sm text-slate-700">
        Starting from
        <span class="font-bold text-blue-900">GHS {{ startingPrice.toLocaleString() }}</span>
        <span class="text-slate-500">/semester</span>
      </p>

      <div class="flex flex-wrap gap-2">
        <span
          v-for="amenity in amenityPreview"
          :key="`${hostel.id}-${amenity}`"
          class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-700"
        >
          {{ amenity }}
        </span>
      </div>

      <button
        type="button"
        class="w-full rounded-lg bg-blue-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-800"
        @click="goToHostel"
      >
        View Details
      </button>
    </div>
  </article>
</template>

<script setup>
import { computed } from 'vue';
import { useRouter } from 'vue-router';

const props = defineProps({
  hostel: {
    type: Object,
    required: true,
  },
});

const router = useRouter();

const startingPrice = computed(() =>
  Math.min(...props.hostel.room_types.map((roomType) => roomType.price_per_semester)),
);

const amenityPreview = computed(() => props.hostel.amenities.slice(0, 4));

const genderBadgeClass = computed(() => {
  if (props.hostel.gender_policy === 'male') {
    return 'bg-blue-600';
  }

  if (props.hostel.gender_policy === 'female') {
    return 'bg-pink-600';
  }

  return 'bg-emerald-600';
});

const genderPolicyLabel = computed(() => {
  if (props.hostel.gender_policy === 'male') {
    return 'Male';
  }

  if (props.hostel.gender_policy === 'female') {
    return 'Female';
  }

  return 'Mixed';
});

const goToHostel = () => {
  router.push(`/hostels/${props.hostel.id}`);
};
</script>
