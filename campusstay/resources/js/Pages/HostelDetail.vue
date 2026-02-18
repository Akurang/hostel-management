<template>
  <div class="min-h-screen bg-slate-50">
    <Navbar />

    <main class="mx-auto w-full max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
      <button
        type="button"
        class="mb-6 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100"
        @click="router.visit('/hostels')"
      >
        Back
      </button>

      <section v-if="!hostel" class="rounded-2xl border border-slate-200 bg-white p-10 text-center shadow-sm">
        <p class="text-xl font-bold text-slate-900">Hostel not found</p>
        <button
          type="button"
          class="mt-4 rounded-lg bg-blue-900 px-4 py-2 text-sm font-semibold text-white"
          @click="router.visit('/hostels')"
        >
          Browse Hostels
        </button>
      </section>

      <template v-else>
        <section class="mb-8 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
          <div class="aspect-[16/7] w-full bg-slate-100">
            <img :src="activeImage" :alt="hostel.name" class="h-full w-full object-cover">
          </div>
          <div class="grid gap-3 p-4 sm:grid-cols-3">
            <button
              v-for="(image, index) in thumbnailImages"
              :key="`${hostel.id}-${index}`"
              type="button"
              class="aspect-video overflow-hidden rounded-lg border-2"
              :class="activeImage === image ? 'border-blue-900' : 'border-transparent'"
              @click="activeImage = image"
            >
              <img :src="image" :alt="`${hostel.name} ${index + 1}`" class="h-full w-full object-cover">
            </button>
          </div>
        </section>

        <section class="mb-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
          <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
              <h1 class="text-3xl font-black text-slate-900">{{ hostel.name }}</h1>
            </div>

            <div class="flex flex-wrap items-center gap-2 text-sm">
              <span
                v-if="hostel.is_verified"
                class="rounded-full bg-emerald-600 px-3 py-1 font-semibold text-white"
              >
                Verified
              </span>
              <span class="rounded-full px-3 py-1 font-semibold text-white" :class="genderBadgeClass">
                {{ genderLabel }}
              </span>
              <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-3 py-1 font-semibold text-amber-700">
                * {{ Number(hostel.rating || 0).toFixed(1) }} ({{ hostel.total_reviews || 0 }})
              </span>
              <span class="rounded-full bg-slate-100 px-3 py-1 font-semibold text-slate-700">
                {{ hostel.distance_from_campus }}
              </span>
            </div>
          </div>
        </section>

        <section class="grid gap-8 lg:grid-cols-3">
          <div class="space-y-6 lg:col-span-2">
            <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
              <h2 class="mb-3 text-xl font-black text-slate-900">About</h2>
              <p class="leading-relaxed text-slate-700">{{ hostel.description }}</p>
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
              <h2 class="mb-4 text-xl font-black text-slate-900">Amenities</h2>
              <div class="grid gap-3 sm:grid-cols-2">
                <div
                  v-for="amenity in hostel.amenities"
                  :key="`${hostel.id}-${amenity}`"
                  class="flex items-center gap-2 rounded-lg bg-slate-100 px-3 py-2 text-sm font-medium text-slate-700"
                >
                  <span class="text-base">{{ amenityIconMap[amenity] || 'dot' }}</span>
                  <span>{{ amenity }}</span>
                </div>
              </div>
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
              <h2 class="mb-4 text-xl font-black text-slate-900">Room Types</h2>
              <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                  <thead>
                    <tr class="border-b border-slate-200 text-slate-500">
                      <th class="px-3 py-2 font-semibold">Room Type</th>
                      <th class="px-3 py-2 font-semibold">Total Beds</th>
                      <th class="px-3 py-2 font-semibold">Available Beds</th>
                      <th class="px-3 py-2 font-semibold">Price/Semester</th>
                      <th class="px-3 py-2 font-semibold">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="room in hostel.room_types" :key="room.type" class="border-b border-slate-100">
                      <td class="px-3 py-3 text-slate-700">{{ room.type }}</td>
                      <td class="px-3 py-3 text-slate-700">{{ room.total_beds }}</td>
                      <td class="px-3 py-3 text-slate-700">{{ room.available_beds }}</td>
                      <td class="px-3 py-3 font-semibold text-blue-900">GHS {{ Number(room.price_per_semester).toLocaleString() }}</td>
                      <td class="px-3 py-3">
                        <span
                          class="rounded-full px-2.5 py-1 text-xs font-semibold"
                          :class="room.available_beds > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700'"
                        >
                          {{ room.available_beds > 0 ? 'Available' : 'Full' }}
                        </span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
              <h2 class="mb-4 text-xl font-black text-slate-900">Reviews</h2>
              <div class="space-y-4">
                <div
                  v-for="review in reviews"
                  :key="review.id"
                  class="rounded-xl border border-slate-200 bg-slate-50 p-4"
                >
                  <div class="mb-2 flex flex-wrap items-center justify-between gap-2">
                    <p class="font-semibold text-slate-900">{{ review.student_name }}</p>
                    <p class="text-xs text-slate-500">{{ review.date }}</p>
                  </div>
                  <p class="mb-2 text-sm text-amber-600">{{ '*'.repeat(review.rating) }}</p>
                  <p class="text-sm text-slate-700">{{ review.comment }}</p>
                </div>
              </div>
              <p class="mt-4 text-xs font-semibold text-slate-500">Reviews are from verified students only.</p>
            </article>
          </div>

          <aside class="lg:col-span-1">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm lg:sticky lg:top-24">
              <p class="text-sm text-slate-600">Starting from</p>
              <p class="mb-4 text-2xl font-black text-blue-900">GHS {{ Number(startingPrice).toLocaleString() }}</p>

              <label class="mb-2 block text-sm font-semibold text-slate-700">Select Room Type</label>
              <select
                v-model="selectedRoomTypeKey"
                class="mb-3 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none ring-blue-300 transition focus:ring"
              >
                <option v-for="room in availableRoomTypeOptions" :key="room.type" :value="room.type">
                  {{ room.type }} - GHS {{ Number(room.price_per_semester).toLocaleString() }}
                </option>
              </select>

              <p class="mb-5 text-sm text-slate-600">
                Available beds: <span class="font-semibold text-slate-900">{{ selectedRoomType?.available_beds ?? 0 }}</span>
              </p>

              <button
                type="button"
                class="mb-2 w-full rounded-lg bg-blue-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-800"
                @click="showBookingNotice"
              >
                Book Now
              </button>

              <button
                type="button"
                class="mb-4 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100"
              >
                Add to Comparison
              </button>

              <div class="rounded-lg bg-slate-100 p-3 text-sm text-slate-600">
                Manager contact: <span class="font-semibold text-slate-800">+233 24 000 0000</span>
              </div>
            </div>
          </aside>
        </section>
      </template>
    </main>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import Navbar from '../Components/common/Navbar.vue';

const props = defineProps({
  hostel: {
    type: Object,
    default: null,
  },
  reviews: {
    type: Array,
    default: () => [],
  },
});

const amenityIconMap = {
  WiFi: 'wifi',
  Laundry: 'shirt',
  Kitchen: 'utensils',
  'Study Room': 'book',
  Generator: 'zap',
  Security: 'shield',
  CCTV: 'camera',
  'Water 24/7': 'droplets',
  Gym: 'dumbbell',
  Parking: 'car',
};

const activeImage = ref(props.hostel?.images?.[0] || '');
const selectedRoomTypeKey = ref('');

const hostel = computed(() => props.hostel);
const thumbnailImages = computed(() => (hostel.value?.images || []).slice(1, 4));

const genderBadgeClass = computed(() => {
  if (!hostel.value) return '';
  if (hostel.value.gender_policy === 'male') return 'bg-blue-600';
  if (hostel.value.gender_policy === 'female') return 'bg-pink-600';
  return 'bg-emerald-600';
});

const genderLabel = computed(() => {
  if (!hostel.value) return '';
  if (hostel.value.gender_policy === 'male') return 'Male';
  if (hostel.value.gender_policy === 'female') return 'Female';
  return 'Mixed';
});

const startingPrice = computed(() => {
  if (!hostel.value || !hostel.value.room_types?.length) return 0;
  return Math.min(...hostel.value.room_types.map((room) => Number(room.price_per_semester)));
});

const availableRoomTypeOptions = computed(() => {
  if (!hostel.value) return [];
  return hostel.value.room_types.filter((room) => room.available_beds > 0);
});

if (availableRoomTypeOptions.value.length) {
  selectedRoomTypeKey.value = availableRoomTypeOptions.value[0].type;
}

const selectedRoomType = computed(() =>
  availableRoomTypeOptions.value.find((room) => room.type === selectedRoomTypeKey.value),
);

const showBookingNotice = () => {
  window.alert('Booking coming soon!');
};
</script>
