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
                  v-model="searchQuery"
                  type="text"
                  class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none ring-blue-300 transition focus:ring"
                  placeholder="Search hostels"
                />
              </div>

              <div>
                <p class="mb-2 text-sm font-semibold text-slate-700">Gender Policy</p>
                <div class="space-y-2">
                  <label v-for="option in genderOptions" :key="option.value" class="flex items-center gap-2 text-sm text-slate-700">
                    <input
                      v-model="genderPolicy"
                      :value="option.value"
                      type="radio"
                      name="gender-policy"
                      class="h-4 w-4 border-slate-300 text-blue-900 focus:ring-blue-600"
                    />
                    {{ option.label }}
                  </label>
                </div>
              </div>

              <div>
                <p class="mb-2 text-sm font-semibold text-slate-700">Room Type</p>
                <div class="space-y-2">
                  <label v-for="roomType in roomTypeOptions" :key="roomType" class="flex items-center gap-2 text-sm text-slate-700">
                    <input
                      :checked="selectedRoomTypes.includes(roomType)"
                      type="checkbox"
                      class="h-4 w-4 rounded border-slate-300 text-blue-900 focus:ring-blue-600"
                      @change="toggleRoomType(roomType)"
                    />
                    {{ roomType }}
                  </label>
                </div>
              </div>

              <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Max Price</label>
                <input
                  v-model.number="maxPrice"
                  type="range"
                  min="0"
                  max="5000"
                  step="100"
                  class="w-full accent-blue-900"
                />
                <p class="mt-2 text-sm text-slate-600">Up to GHS {{ maxPriceLabel }}</p>
              </div>

              <div>
                <p class="mb-2 text-sm font-semibold text-slate-700">Amenities</p>
                <div class="max-h-56 space-y-2 overflow-y-auto pr-1">
                  <label
                    v-for="amenity in hostelStore.allAmenities"
                    :key="amenity"
                    class="flex items-center gap-2 text-sm text-slate-700"
                  >
                    <input
                      :checked="selectedAmenities.includes(amenity)"
                      type="checkbox"
                      class="h-4 w-4 rounded border-slate-300 text-blue-900 focus:ring-blue-600"
                      @change="toggleAmenity(amenity)"
                    />
                    {{ amenity }}
                  </label>
                </div>
              </div>

              <button
                type="button"
                class="w-full rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100"
                @click="hostelStore.resetFilters()"
              >
                Reset Filters
              </button>
            </div>
          </div>
        </aside>

        <section class="lg:col-span-3">
          <p class="mb-5 text-sm font-semibold text-slate-700">
            Showing {{ filteredHostels.length }} {{ filteredHostels.length === 1 ? 'hostel' : 'hostels' }}
          </p>

          <div v-if="filteredHostels.length > 0" class="grid gap-6 sm:grid-cols-1 xl:grid-cols-2">
            <HostelCard v-for="hostel in filteredHostels" :key="hostel.id" :hostel="hostel" />
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
import { computed } from 'vue';
import Navbar from '../components/common/Navbar.vue';
import HostelCard from '../components/hostel/HostelCard.vue';
import { useHostelStore } from '../stores/hostelStore';

const hostelStore = useHostelStore();

const genderOptions = [
  { label: 'All', value: 'all' },
  { label: 'Male', value: 'male' },
  { label: 'Female', value: 'female' },
  { label: 'Mixed', value: 'mixed' },
];

const roomTypeOptions = ['1-in-a-room', '2-in-a-room', '3-in-a-room'];

const filteredHostels = computed(() => hostelStore.filteredHostels);

const searchQuery = computed({
  get: () => hostelStore.searchQuery,
  set: (value) => hostelStore.setSearchQuery(value),
});

const genderPolicy = computed({
  get: () => hostelStore.filters.gender_policy,
  set: (value) => hostelStore.setFilters({ gender_policy: value }),
});

const selectedRoomTypes = computed(() => {
  const roomType = hostelStore.filters.room_type;
  return Array.isArray(roomType) ? roomType : roomType === 'all' ? [] : [roomType];
});

const selectedAmenities = computed(() => hostelStore.filters.amenities);

const maxPrice = computed({
  get: () => hostelStore.filters.max_price ?? 5000,
  set: (value) => {
    hostelStore.setFilters({ max_price: value >= 5000 ? null : value });
  },
});

const maxPriceLabel = computed(() => (hostelStore.filters.max_price ?? 5000).toLocaleString());

const toggleRoomType = (roomType) => {
  const nextValues = selectedRoomTypes.value.includes(roomType)
    ? selectedRoomTypes.value.filter((item) => item !== roomType)
    : [...selectedRoomTypes.value, roomType];

  hostelStore.setFilters({ room_type: nextValues.length ? nextValues : 'all' });
};

const toggleAmenity = (amenity) => {
  const nextValues = selectedAmenities.value.includes(amenity)
    ? selectedAmenities.value.filter((item) => item !== amenity)
    : [...selectedAmenities.value, amenity];

  hostelStore.setFilters({ amenities: nextValues });
};
</script>
