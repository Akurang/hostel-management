## Project Context
I'm building CampusStay — a university hostel booking platform.
- Stack: Vue 3 (Composition API + <script setup>), Vite, Vue Router 4, Pinia, Tailwind CSS
- Target users: Students, Hostel Managers, Platform Admins
- We are in the FRONTEND-ONLY phase using mock/static data. No backend yet.
- All components must use <script setup> syntax only — no Options API.
- Use Tailwind CSS only for styling — no inline styles, no external CSS files.
- Keep components small and reusable. Extract repeated UI into components.
- Refer to @MVP_PRD.md and @Implementation_Document.md for full context.
- Also refer too @cursor-skills for rules and best practices


##Prompt 1
Create `src/data/mockHostels.js` with 5 realistic campus hostel objects.

Each hostel must have these exact fields based on the CampusStay PRD:
- id (string, e.g. 'hostel-1')
- name (string)
- description (string, 2-3 sentences)
- gender_policy (one of: 'male', 'female', 'mixed')
- distance_from_campus (string e.g. '5 mins walk')
- price_per_semester (number, in GHS — Ghanaian cedis)
- room_types: array of objects, each with:
    { type: '1-in-a-room' | '2-in-a-room' | '3-in-a-room', price_per_semester: number, total_beds: number, available_beds: number }
- amenities: array of strings from this list:
    ['WiFi', 'Laundry', 'Kitchen', 'Study Room', 'Generator', 'Security', 'CCTV', 'Water 24/7', 'Gym', 'Parking']
- images: array of 4 Unsplash placeholder URLs (use real unsplash image URLs for student accommodation)
- rating (number between 3.5 and 5.0)
- total_reviews (number)
- manager_id (string, e.g. 'manager-1')
- is_verified (boolean)

Make the hostels feel like real Ghanaian university campus hostels.
Export the array as default.


## Prompt 2

Set up Vue Router 4 in `src/router/index.js`.

Routes needed for Phase 1 (frontend):
- `/` → HomePage (src/pages/HomePage.vue)
- `/hostels` → ListingsPage (src/pages/ListingsPage.vue)
- `/hostels/:id` → HostelDetailPage (src/pages/HostelDetailPage.vue)

All routes should use lazy loading with defineAsyncComponent pattern.
Add a scrollBehavior that scrolls to top on every route change.
Export the router as default.


## Prompt 3 — Pinia Store

Create a Pinia store at `src/stores/hostelStore.js`.

State:
- hostels: [] (populated from mockHostels.js on init)
- selectedHostel: null
- filters: { gender_policy: 'all', room_type: 'all', max_price: null, amenities: [] }
- searchQuery: ''

Getters:
- filteredHostels: applies all active filters and searchQuery to the hostels list
- getHostelById: (id) => finds a single hostel by id
- allAmenities: returns a unique sorted list of all amenities across all hostels

Actions:
- setFilters(newFilters): merges new filter values into state
- resetFilters(): resets all filters to defaults
- setSearchQuery(query): updates searchQuery
- setSelectedHostel(id): sets selectedHostel using getHostelById


## Prompt 4 — Navbar Component

Create `src/components/common/Navbar.vue`.

Requirements:
- CampusStay logo/brand name on the left (use a simple text logo with a distinct color)
- Nav links: Home, Find Hostels, How It Works
- Right side: "Sign In" (ghost button) and "Get Started" (solid primary button)
- On mobile: hamburger menu that toggles a dropdown with all links
- Highlight active route using Vue Router's useRoute()
- Sticky at top with a subtle shadow on scroll (use a scroll event listener)
- Colors: use a clean white navbar with a primary color accent (suggest a deep blue or green that feels academic)
- Use <script setup> and Tailwind only


## Prompt 5 — HostelCard Component

Create `src/components/hostel/HostelCard.vue`.

Props: hostel (Object, required) — matches the shape in mockHostels.js

The card must display:
- Hostel image (first image from images array) with an aspect ratio of 16:9
- Verified badge (if is_verified is true) overlaid on image top-left
- Gender policy badge (color-coded: blue=male, pink=female, green=mixed) top-right
- Hostel name (bold)
- Distance from campus with a location pin icon
- Star rating + total reviews count
- Starting price (lowest price_per_semester from room_types) with "/semester" label
- A row of up to 4 amenity chips/pills
- "View Details" button that navigates to /hostels/:id using Vue Router

On hover: subtle card lift (shadow + translateY) using Tailwind transitions.
Make it fully responsive. Use <script setup> and Tailwind only.


## Prompt 6 — HomePage

Create `src/pages/HomePage.vue`. Import and use the hostelStore.

Sections to build (in order):

1. HERO SECTION
   - Full-width with a background image (use an Unsplash campus/accommodation image)
   - Dark overlay for text readability
   - Headline: "Find Your Perfect Campus Home"
   - Subtext: "Browse, compare and book verified university hostels — all in one place."
   - A search bar with: text input ("Search by hostel name..."), a gender filter dropdown (All/Male/Female/Mixed), and a "Search" button
   - Search should update the hostelStore's searchQuery and gender_policy filter, then navigate to /hostels

2. STATS BAR
   - 3 stats: "500+ Students Housed", "20+ Verified Hostels", "3 Room Types Available"
   - Clean horizontal bar below the hero

3. FEATURED HOSTELS
   - Section title: "Featured Hostels"
   - Responsive grid (1 col mobile, 2 col tablet, 3 col desktop)
   - Show the first 3 hostels from the store using <HostelCard />
   - "View All Hostels" button linking to /hostels

4. HOW IT WORKS
   - 3 steps: "Search & Filter", "Compare & Choose", "Book & Move In"
   - Each with an icon, title, and 1-line description

5. FOOTER
   - Simple footer with CampusStay brand, nav links, and copyright

Use <script setup>, Tailwind only, and keep sections visually separated.


## Prompt 7 — ListingsPage

Create `src/pages/ListingsPage.vue`. Use the hostelStore for data and filters.

Layout: Sidebar (left, 1/4 width on desktop) + Hostel Grid (right, 3/4 width). Stack vertically on mobile.

SIDEBAR — Filter Panel:
- Section title: "Filter Hostels"
- Search input (synced to hostelStore.searchQuery)
- Gender Policy: radio buttons (All / Male / Female / Mixed)
- Room Type: checkboxes (1-in-a-room / 2-in-a-room / 3-in-a-room)
- Max Price: a range slider (min: 0, max: 5000 GHS, step: 100) with live value display
- Amenities: checkboxes dynamically generated from hostelStore.allAmenities getter
- "Reset Filters" button that calls hostelStore.resetFilters()
- All filters should be reactive and immediately update the hostelStore

HOSTEL GRID:
- Show result count: "Showing X hostels"
- Responsive grid: 1 col mobile, 2 col desktop
- Render filteredHostels using <HostelCard />
- If no results: show an empty state with an icon and "No hostels match your filters" message

Use <script setup> and Tailwind only.


## Prompt 8 — HostelDetailPage

Create `src/pages/HostelDetailPage.vue`.

On mount, use the route param `:id` to call hostelStore.setSelectedHostel(id).
If hostel not found, show a "Hostel not found" message with a back button.

Sections to build:

1. IMAGE GALLERY
   - Large primary image on top
   - Row of 3 thumbnail images below (clicking changes the main image)

2. HEADER ROW
   - Hostel name (large, bold)
   - Verified badge (if applicable)
   - Gender policy badge (color-coded)
   - Star rating + review count
   - Distance from campus

3. TWO-COLUMN LAYOUT (on desktop):
   LEFT COLUMN (2/3 width):
   
   a. ABOUT — hostel description
   
   b. AMENITIES — grid of amenity chips with icons
      Map these amenities to relevant icons:
      WiFi→wifi, Laundry→shirt, Kitchen→utensils, Study Room→book,
      Generator→zap, Security→shield, CCTV→camera, Water 24/7→droplets,
      Gym→dumbbell, Parking→car
   
   c. ROOM TYPES TABLE
      Columns: Room Type | Total Beds | Available Beds | Price/Semester | Status
      Status badge: green "Available" if available_beds > 0, red "Full" if 0
   
   d. REVIEWS SECTION
      Show 3 mock review cards (name, star rating, date, comment text)
      "Reviews are from verified students only" disclaimer

   RIGHT COLUMN (1/3 width — sticky on scroll):
   
   BOOKING CTA CARD:
   - Starting from price (lowest room type)
   - Room type selector (dropdown of available room types)
   - Selected room's available beds count
   - "Book Now" button (primary, full width) — for now just shows a toast/alert "Booking coming soon!"
   - "Add to Comparison" button (secondary) — placeholder for now
   - Manager contact placeholder

Use <script setup> and Tailwind only.