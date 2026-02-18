<template>
  <div class="min-h-screen bg-slate-50">
    <Navbar />

    <main class="mx-auto w-full max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
      <div class="grid gap-8 lg:grid-cols-4">
        <aside class="lg:col-span-1">
          <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <h2 class="mb-5 text-lg font-black text-slate-900">Filter Hostels</h2>

            <div class="space-y-5">
              <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Search</label>
                <input
                  v-model="currentFilters.search"
                  type="text"
                  class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none ring-blue-300 transition focus:ring"
                  placeholder="Search hostels"
                  @input="submitFilters"
                >
              </div>

              <div>
                <p class="mb-2 text-sm font-semibold text-slate-700">Gender Policy</p>
                <div class="space-y-2">
                  <label v-for="option in genderOptions" :key="option.value" class="flex items-center gap-2 text-sm text-slate-700">
                    <input
                      v-model="currentFilters.gender_policy"
                      :value="option.value"
                      type="radio"
                      name="gender-policy"
                      class="h-4 w-4 border-slate-300 text-blue-900 focus:ring-blue-600"
                      @change="submitFilters"
                    >
                    {{ option.label }}
                  </label>
                </div>
              </div>

              <div>
                <p class="mb-2 text-sm font-semibold text-slate-700">Room Type</p>
                <div class="space-y-2">
                  <label v-for="roomType in roomTypeOptions" :key="roomType" class="flex items-center gap-2 text-sm text-slate-700">
                    <input
                      :checked="currentFilters.room_type.includes(roomType)"
                      type="checkbox"
                      class="h-4 w-4 rounded border-slate-300 text-blue-900 focus:ring-blue-600"
                      @change="toggleRoomType(roomType)"
                    >
                    {{ roomType }}
                  </label>
                </div>
              </div>

              <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Max Price</label>
                <input
                  v-model.number="currentFilters.max_price"
                  type="range"
                  min="0"
                  max="5000"
                  step="100"
                  class="w-full accent-blue-900"
                  @input="submitFilters"
                >
                <p class="mt-2 text-sm text-slate-600">Up to GHS {{ Number(currentFilters.max_price || 5000).toLocaleString() }}</p>
              </div>

              <div>
                <p class="mb-2 text-sm font-semibold text-slate-700">Amenities</p>
                <div class="max-h-56 space-y-2 overflow-y-auto pr-1">
                  <label
                    v-for="amenity in allAmenities"
                    :key="amenity"
                    class="flex items-center gap-2 text-sm text-slate-700"
                  >
                    <input
                      :checked="currentFilters.amenities.includes(amenity)"
                      type="checkbox"
                      class="h-4 w-4 rounded border-slate-300 text-blue-900 focus:ring-blue-600"
                      @change="toggleAmenity(amenity)"
                    >
                    {{ amenity }}
                  </label>
                </div>
              </div>

              <button
                type="button"
                class="w-full rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100"
                @click="resetFilters"
              >
                Reset Filters
              </button>
            </div>
          </div>
        </aside>

        <section class="lg:col-span-3">
          <p class="mb-5 text-sm font-semibold text-slate-700">
            Showing {{ hostels.length }} {{ hostels.length === 1 ? 'hostel' : 'hostels' }}
          </p>

          <div v-if="hostels.length > 0" class="grid gap-6 sm:grid-cols-1 xl:grid-cols-2">
            <HostelCard v-for="hostel in hostels" :key="hostel.id" :hostel="hostel" />
          </div>

          <div
            v-else
            class="flex min-h-[300px] flex-col items-center justify-center rounded-2xl border border-dashed border-slate-300 bg-white text-center"
          >
            <svg class="mb-3 h-10 w-10 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M3 10h18M6 6h12M8 14h8M10 18h4" />
            </svg>
            <p class="text-lg font-semibold text-slate-700">No hostels match your filters</p>
          </div>
        </section>
      </div>
    </main>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import { reactive } from 'vue';
import Navbar from '../Components/common/Navbar.vue';
import HostelCard from '../Components/hostel/HostelCard.vue';

const props = defineProps({
  hostels: {
    type: Array,
    default: () => [],
  },
  filters: {
    type: Object,
    default: () => ({}),
  },
  allAmenities: {
    type: Array,
    default: () => [],
  },
});

const genderOptions = [
  { label: 'All', value: 'all' },
  { label: 'Male', value: 'male' },
  { label: 'Female', value: 'female' },
  { label: 'Mixed', value: 'mixed' },
];

const roomTypeOptions = ['1-in-a-room', '2-in-a-room', '3-in-a-room'];

const currentFilters = reactive({
  search: props.filters.search ?? '',
  gender_policy: props.filters.gender_policy ?? 'all',
  room_type: Array.isArray(props.filters.room_type)
    ? props.filters.room_type
    : props.filters.room_type && props.filters.room_type !== 'all'
      ? [props.filters.room_type]
      : [],
  max_price: props.filters.max_price ? Number(props.filters.max_price) : 5000,
  amenities: Array.isArray(props.filters.amenities) ? props.filters.amenities : [],
});

const submitFilters = () => {
  const payload = {
    search: currentFilters.search,
    gender_policy: currentFilters.gender_policy,
    room_type: currentFilters.room_type.length ? currentFilters.room_type : 'all',
    max_price: Number(currentFilters.max_price) >= 5000 ? null : Number(currentFilters.max_price),
    amenities: currentFilters.amenities,
  };

  router.get('/hostels', payload, { preserveState: true, replace: true });
};

const toggleRoomType = (roomType) => {
  if (currentFilters.room_type.includes(roomType)) {
    currentFilters.room_type = currentFilters.room_type.filter((item) => item !== roomType);
  } else {
    currentFilters.room_type = [...currentFilters.room_type, roomType];
  }

  submitFilters();
};

const toggleAmenity = (amenity) => {
  if (currentFilters.amenities.includes(amenity)) {
    currentFilters.amenities = currentFilters.amenities.filter((item) => item !== amenity);
  } else {
    currentFilters.amenities = [...currentFilters.amenities, amenity];
  }

  submitFilters();
};

const resetFilters = () => {
  currentFilters.search = '';
  currentFilters.gender_policy = 'all';
  currentFilters.room_type = [];
  currentFilters.max_price = 5000;
  currentFilters.amenities = [];

  submitFilters();
};
</script>
