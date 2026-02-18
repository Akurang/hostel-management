import { defineStore } from 'pinia';
import mockHostels from '../data/mockHostels';

const defaultFilters = () => ({
  gender_policy: 'all',
  room_type: 'all',
  max_price: null,
  amenities: [],
});

export const useHostelStore = defineStore('hostelStore', {
  state: () => ({
    hostels: [...mockHostels],
    selectedHostel: null,
    filters: defaultFilters(),
    searchQuery: '',
  }),

  getters: {
    filteredHostels: (state) => {
      const normalizedQuery = state.searchQuery.trim().toLowerCase();

      return state.hostels.filter((hostel) => {
        const matchesSearch =
          normalizedQuery.length === 0 ||
          hostel.name.toLowerCase().includes(normalizedQuery) ||
          hostel.description.toLowerCase().includes(normalizedQuery);

        const matchesGender =
          state.filters.gender_policy === 'all' ||
          hostel.gender_policy === state.filters.gender_policy;

        const roomTypeFilter = state.filters.room_type;
        const selectedRoomTypes = Array.isArray(roomTypeFilter)
          ? roomTypeFilter
          : roomTypeFilter === 'all'
            ? []
            : [roomTypeFilter];

        const matchesRoomType =
          selectedRoomTypes.length === 0 ||
          hostel.room_types.some((roomType) => selectedRoomTypes.includes(roomType.type));

        const matchesPrice =
          state.filters.max_price === null ||
          hostel.room_types.some((roomType) => roomType.price_per_semester <= state.filters.max_price);

        const matchesAmenities =
          state.filters.amenities.length === 0 ||
          state.filters.amenities.every((amenity) => hostel.amenities.includes(amenity));

        return matchesSearch && matchesGender && matchesRoomType && matchesPrice && matchesAmenities;
      });
    },

    getHostelById: (state) => (id) => state.hostels.find((hostel) => hostel.id === id),

    allAmenities: (state) => {
      const amenities = new Set();

      state.hostels.forEach((hostel) => {
        hostel.amenities.forEach((amenity) => amenities.add(amenity));
      });

      return [...amenities].sort((a, b) => a.localeCompare(b));
    },
  },

  actions: {
    setFilters(newFilters) {
      this.filters = {
        ...this.filters,
        ...newFilters,
      };
    },

    resetFilters() {
      this.filters = defaultFilters();
      this.searchQuery = '';
    },

    setSearchQuery(query) {
      this.searchQuery = query;
    },

    setSelectedHostel(id) {
      this.selectedHostel = this.getHostelById(id) || null;
    },
  },
});
