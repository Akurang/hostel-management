import { defineStore } from 'pinia';

export const useHostelStore = defineStore('hostelStore', {
  state: () => ({
    comparisonList: [],
  }),

  getters: {
    comparisonCount: (state) => state.comparisonList.length,
  },

  actions: {
    addToComparison(hostel) {
      if (!this.comparisonList.find((item) => item.id === hostel.id)) {
        this.comparisonList.push(hostel);
      }
    },

    removeFromComparison(hostelId) {
      this.comparisonList = this.comparisonList.filter((item) => item.id !== hostelId);
    },

    clearComparison() {
      this.comparisonList = [];
    },
  },
});
