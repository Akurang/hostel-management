# CampusStay — Backend Setup Cursor Prompts
> Prompts only. No git commands.
> Stack: Laravel 11 | Vue 3 (script setup) | Inertia.js | PostgreSQL | Tailwind CSS | Laravel Sanctum
> Follow prompts in STRICT ORDER. Complete and verify each one before moving to the next.

---

## HOW TO USE THIS DOCUMENT
1. At the start of every Cursor session, paste the **Project Context Block** first
2. Add `@MVP_PRD.md` and `@Implementation_Document.md` as file references in Cursor
3. Paste the specific prompt for that step
4. Run the app and verify it works before moving on

---

## 🧠 PROJECT CONTEXT BLOCK
> Paste this at the top of EVERY new Cursor session. Never skip this.

```
## CampusStay — Backend Project Context

I am building CampusStay, a centralized university hostel booking platform for
Ghanaian university students. I have completed the frontend in Vue 3 + Vite (standalone).
I am now integrating the Laravel 11 backend.

### Architecture (from Implementation Document)
Monolithic Laravel 11 application with Vue 3 SPA served via Inertia.js.
Client (Vue) communicates through Inertia to Laravel controllers.
Controllers interact with Services which interact with PostgreSQL.
Real-time updates via Laravel Reverb (WebSockets).
Background jobs via Laravel Queue Workers.

### Tech Stack
- Backend: Laravel 11
- Frontend: Vue 3 (Composition API, <script setup> ONLY — never Options API)
- Bridge: Inertia.js (replaces Vue Router and Axios for page navigation and data)
- Database: PostgreSQL
- Authentication: Laravel Sanctum (session-based, NOT token-based)
- Styling: Tailwind CSS (no inline styles, no external CSS files)
- Real-time: Laravel Reverb
- Queues: Laravel Queue Workers

### Three User Roles (from Implementation Document)
- STUDENT: browses, books, and reviews hostels
- MANAGER: manages their hostel(s), approves/rejects bookings, views dashboard
- ADMIN: oversees entire platform, approves managers, moderates reviews

### Booking Status Flow (from PRD & Implementation Document)
PENDING_APPROVAL → AWAITING_PAYMENT → CONFIRMED
Terminal states: REJECTED, CANCELLED

### Database Tables (from Implementation Document)
users, hostels, rooms, bookings, payments, waitlists, reviews, amenities, hostel_amenities

### Domain Rules
- Room types: '1-in-a-room' | '2-in-a-room' | '3-in-a-room'
- Gender policy: 'male' | 'female' | 'mixed'
- Prices are in GHS (Ghanaian Cedis) — always display with ₵ symbol
- Reviews only from verified students (students with a CONFIRMED booking)
- Payment methods: Mobile Money (MTN/Vodafone/AirtelTigo), Card, Bank Transfer, Crypto
- MVP: full payment only (no installments)

### Key Inertia.js Principle
Inertia replaces both the API layer AND Vue Router:
- No JSON API endpoints — Inertia passes data directly as page props
- No Vue Router — use Inertia's <Link> component and router.visit() for navigation
- Laravel routes map directly to Vue page components via Inertia::render()

### Frontend Files Location
Completed Vue components live in resources/js/ after migration from the Vite project.
All pages use <script setup> syntax. Pinia and components are already built.

### References
Always refer to @MVP_PRD.md and @Implementation_Document.md for full context.
```

---

## PROMPT 1 — Laravel Project Scaffolding

## 🧠 PROJECT CONTEXT BLOCK
> Paste this at the top of EVERY new Cursor session. Never skip this.

```
## CampusStay — Backend Project Context

I am building CampusStay, a centralized university hostel booking platform for
Ghanaian university students. I have completed the frontend in Vue 3 + Vite (standalone).
I am now integrating the Laravel 11 backend.

### Architecture (from Implementation Document)
Monolithic Laravel 11 application with Vue 3 SPA served via Inertia.js.
Client (Vue) communicates through Inertia to Laravel controllers.
Controllers interact with Services which interact with PostgreSQL.
Real-time updates via Laravel Reverb (WebSockets).
Background jobs via Laravel Queue Workers.

### Tech Stack
- Backend: Laravel 11
- Frontend: Vue 3 (Composition API, <script setup> ONLY — never Options API)
- Bridge: Inertia.js (replaces Vue Router and Axios for page navigation and data)
- Database: PostgreSQL
- Authentication: Laravel Sanctum (session-based, NOT token-based)
- Styling: Tailwind CSS (no inline styles, no external CSS files)
- Real-time: Laravel Reverb
- Queues: Laravel Queue Workers

### Three User Roles (from Implementation Document)
- STUDENT: browses, books, and reviews hostels
- MANAGER: manages their hostel(s), approves/rejects bookings, views dashboard
- ADMIN: oversees entire platform, approves managers, moderates reviews

### Booking Status Flow (from PRD & Implementation Document)
PENDING_APPROVAL → AWAITING_PAYMENT → CONFIRMED
Terminal states: REJECTED, CANCELLED

### Database Tables (from Implementation Document)
users, hostels, rooms, bookings, payments, waitlists, reviews, amenities, hostel_amenities

### Domain Rules
- Room types: '1-in-a-room' | '2-in-a-room' | '3-in-a-room'
- Gender policy: 'male' | 'female' | 'mixed'
- Prices are in GHS (Ghanaian Cedis) — always display with ₵ symbol
- Reviews only from verified students (students with a CONFIRMED booking)
- Payment methods: Mobile Money (MTN/Vodafone/AirtelTigo), Card, Bank Transfer, Crypto
- MVP: full payment only (no installments)

### Key Inertia.js Principle
Inertia replaces both the API layer AND Vue Router:
- No JSON API endpoints — Inertia passes data directly as page props
- No Vue Router — use Inertia's <Link> component and router.visit() for navigation
- Laravel routes map directly to Vue page components via Inertia::render()

### Frontend Files Location
Completed Vue components live in resources/js/ after migration from the Vite project.
All pages use <script setup> syntax. Pinia and components are already built.

### References
Always refer to @MVP_PRD.md and @Implementation_Document.md for full context.
```
---

## Task: Scaffold the Laravel 11 Project

I am setting up the CampusStay backend from scratch.
Do NOT create any migrations, models, or controllers yet — just project setup.

### Step 1: Create the Laravel Project
```bash
composer create-project laravel/laravel campusstay
cd campusstay
```

### Step 2: Install All Required Packages
```bash
composer require inertiajs/inertia-laravel
composer require laravel/sanctum
composer require laravel/reverb
composer require spatie/laravel-permission

npm install @inertiajs/vue3 vue@3 @vitejs/plugin-vue
npm install -D tailwindcss @tailwindcss/vite
npm install @vueuse/core pinia axios
npm install laravel-echo pusher-js
```

### Step 3: Configure vite.config.js
Replace the default file with a configuration that:
- Uses @vitejs/plugin-vue
- Uses the Laravel Vite plugin (laravel-vite-plugin)
- Sets up Tailwind CSS via @tailwindcss/vite
- Entry point: resources/js/app.js and resources/css/app.css

### Step 4: Configure Inertia Middleware
- Run: `php artisan inertia:middleware`
- Register the generated HandleInertiaRequests middleware
  in bootstrap/app.php inside the web middleware group

### Step 5: Create the Inertia Root Blade Template
Create `resources/views/app.blade.php`:
- Standard HTML5 boilerplate
- @vite(['resources/css/app.css', 'resources/js/app.js']) in <head>
- @inertiaHead directive in <head>
- @inertia directive in <body>
- Google Fonts link: Playfair Display (display font) + DM Sans (body font)
- Apply font families via Tailwind config

### Step 6: Set Up resources/js/app.js
```js
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { createPinia } from 'pinia'
import '../css/app.css'

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    return pages[`./Pages/${name}.vue`]
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(createPinia())
      .mount(el)
  },
})
```

### Step 7: Migrate Frontend Vue Files
Copy all completed frontend files into the Laravel structure:
- src/pages/*.vue → resources/js/Pages/*.vue
- src/components/ → resources/js/Components/
- src/stores/ → resources/js/stores/
- src/data/ → resources/js/data/

After copying, update all files:
- Fix all import paths to reflect the new directory structure
- Replace every <RouterLink> with Inertia's <Link> component
  (import { Link } from '@inertiajs/vue3')
- Replace every useRouter().push('/path') with
  router.visit('/path') (import { router } from '@inertiajs/vue3')
- Replace every useRoute().params with usePage().props where needed

### Step 8: Configure .env
```
APP_NAME=CampusStay
APP_URL=http://localhost:8000
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=campusstay
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

### Step 9: Create the PostgreSQL Database
In your PostgreSQL client or psql:
```sql
CREATE DATABASE campusstay;
```

### Step 10: Verify the Setup
```bash
php artisan key:generate
php artisan serve
# In a separate terminal:
npm run dev
```
Visit http://localhost:8000.
You should see the CampusStay homepage rendered via Inertia with the full Vue frontend intact.
If you see the page correctly, the scaffold is complete.
```

---

## PROMPT 2 — Database Migrations

## 🧠 PROJECT CONTEXT BLOCK
> Paste this at the top of EVERY new Cursor session. Never skip this.

```
## CampusStay — Backend Project Context

I am building CampusStay, a centralized university hostel booking platform for
Ghanaian university students. I have completed the frontend in Vue 3 + Vite (standalone).
I am now integrating the Laravel 11 backend.

### Architecture (from Implementation Document)
Monolithic Laravel 11 application with Vue 3 SPA served via Inertia.js.
Client (Vue) communicates through Inertia to Laravel controllers.
Controllers interact with Services which interact with PostgreSQL.
Real-time updates via Laravel Reverb (WebSockets).
Background jobs via Laravel Queue Workers.

### Tech Stack
- Backend: Laravel 11
- Frontend: Vue 3 (Composition API, <script setup> ONLY — never Options API)
- Bridge: Inertia.js (replaces Vue Router and Axios for page navigation and data)
- Database: PostgreSQL
- Authentication: Laravel Sanctum (session-based, NOT token-based)
- Styling: Tailwind CSS (no inline styles, no external CSS files)
- Real-time: Laravel Reverb
- Queues: Laravel Queue Workers

### Three User Roles (from Implementation Document)
- STUDENT: browses, books, and reviews hostels
- MANAGER: manages their hostel(s), approves/rejects bookings, views dashboard
- ADMIN: oversees entire platform, approves managers, moderates reviews

### Booking Status Flow (from PRD & Implementation Document)
PENDING_APPROVAL → AWAITING_PAYMENT → CONFIRMED
Terminal states: REJECTED, CANCELLED

### Database Tables (from Implementation Document)
users, hostels, rooms, bookings, payments, waitlists, reviews, amenities, hostel_amenities

### Domain Rules
- Room types: '1-in-a-room' | '2-in-a-room' | '3-in-a-room'
- Gender policy: 'male' | 'female' | 'mixed'
- Prices are in GHS (Ghanaian Cedis) — always display with ₵ symbol
- Reviews only from verified students (students with a CONFIRMED booking)
- Payment methods: Mobile Money (MTN/Vodafone/AirtelTigo), Card, Bank Transfer, Crypto
- MVP: full payment only (no installments)

### Key Inertia.js Principle
Inertia replaces both the API layer AND Vue Router:
- No JSON API endpoints — Inertia passes data directly as page props
- No Vue Router — use Inertia's <Link> component and router.visit() for navigation
- Laravel routes map directly to Vue page components via Inertia::render()

### Frontend Files Location
Completed Vue components live in resources/js/ after migration from the Vite project.
All pages use <script setup> syntax. Pinia and components are already built.

### References
Always refer to @MVP_PRD.md and @Implementation_Document.md for full context.
```
---

## Task: Create All Database Migrations

Re-read the project context before starting.
Create ALL nine migrations now so the full schema exists from the start.
Write them in the exact order listed below — this order respects foreign key dependencies.
Do NOT modify the default Laravel users migration. Extend it with a separate migration.

---

### Migration 1: Extend Users Table
File: `add_campusstay_fields_to_users_table`

Add these columns to the existing users table:
```php
$table->enum('role', ['student', 'manager', 'admin'])->default('student')->after('email');
$table->string('student_id')->nullable()->unique()->after('role');
$table->string('phone')->nullable()->after('student_id');
$table->string('university')->nullable()->after('phone'); // 'KNUST', 'UG', 'UCC', etc.
$table->string('academic_year')->nullable()->after('university'); // e.g. '2024/2025'
$table->boolean('is_active')->default(true)->after('academic_year');
$table->timestamp('approved_at')->nullable()->after('is_active');
```

---

### Migration 2: Amenities Table
```php
Schema::create('amenities', function (Blueprint $table) {
    $table->id();
    $table->string('name')->unique();
    $table->string('icon')->nullable();
    $table->timestamps();
});
```

---

### Migration 3: Hostels Table
```php
Schema::create('hostels', function (Blueprint $table) {
    $table->id();
    $table->foreignId('manager_id')->constrained('users')->onDelete('cascade');
    $table->string('name');
    $table->string('slug')->unique();
    $table->text('description');
    $table->enum('gender_policy', ['male', 'female', 'mixed']);
    $table->string('address');
    $table->string('distance_from_campus');
    $table->string('university');
    $table->decimal('latitude', 10, 8)->nullable();
    $table->decimal('longitude', 11, 8)->nullable();
    $table->boolean('is_verified')->default(false);
    $table->boolean('is_active')->default(true);
    $table->json('images')->nullable();
    $table->timestamps();
    $table->softDeletes();
});
```

---

### Migration 4: Hostel-Amenities Pivot Table
```php
Schema::create('hostel_amenities', function (Blueprint $table) {
    $table->foreignId('hostel_id')->constrained()->onDelete('cascade');
    $table->foreignId('amenity_id')->constrained()->onDelete('cascade');
    $table->primary(['hostel_id', 'amenity_id']);
});
```

---

### Migration 5: Rooms Table
```php
Schema::create('rooms', function (Blueprint $table) {
    $table->id();
    $table->foreignId('hostel_id')->constrained()->onDelete('cascade');
    $table->enum('type', ['1-in-a-room', '2-in-a-room', '3-in-a-room']);
    $table->decimal('price_per_semester', 10, 2);
    $table->integer('total_beds');
    $table->integer('available_beds');
    $table->json('room_amenities')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

---

### Migration 6: Bookings Table
```php
Schema::create('bookings', function (Blueprint $table) {
    $table->id();
    $table->string('reference')->unique();
    $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
    $table->foreignId('hostel_id')->constrained()->onDelete('cascade');
    $table->foreignId('room_id')->constrained()->onDelete('cascade');
    $table->enum('status', [
        'pending_approval',
        'awaiting_payment',
        'confirmed',
        'rejected',
        'cancelled'
    ])->default('pending_approval');
    $table->string('academic_year');
    $table->enum('semester', ['first', 'second']);
    $table->text('rejection_reason')->nullable();
    $table->timestamp('payment_deadline')->nullable();
    $table->timestamp('approved_at')->nullable();
    $table->timestamp('confirmed_at')->nullable();
    $table->timestamps();
    $table->softDeletes();

    // Prevent double bookings: one booking per student per semester per year
    $table->unique(['student_id', 'academic_year', 'semester']);
});
```

---

### Migration 7: Payments Table
```php
Schema::create('payments', function (Blueprint $table) {
    $table->id();
    $table->string('transaction_id')->unique();
    $table->foreignId('booking_id')->constrained()->onDelete('cascade');
    $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
    $table->decimal('amount', 10, 2);
    $table->enum('method', [
        'mtn_momo',
        'vodafone_cash',
        'airteltigo_money',
        'card',
        'bank_transfer',
        'crypto'
    ]);
    $table->enum('status', ['pending', 'success', 'failed', 'refunded'])
          ->default('pending');
    $table->string('provider_reference')->nullable();
    $table->json('metadata')->nullable();
    $table->timestamp('paid_at')->nullable();
    $table->timestamps();
});
```

---

### Migration 8: Waitlists Table
```php
Schema::create('waitlists', function (Blueprint $table) {
    $table->id();
    $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
    $table->foreignId('room_id')->constrained()->onDelete('cascade');
    $table->integer('position');
    $table->timestamp('notified_at')->nullable();
    $table->timestamp('expires_at')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
    $table->unique(['student_id', 'room_id']);
});
```

---

### Migration 9: Reviews Table
```php
Schema::create('reviews', function (Blueprint $table) {
    $table->id();
    $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
    $table->foreignId('hostel_id')->constrained()->onDelete('cascade');
    $table->foreignId('booking_id')->constrained()->onDelete('cascade');
    $table->tinyInteger('rating');
    $table->text('comment');
    $table->boolean('is_visible')->default(true);
    $table->timestamps();

    // One review per student per hostel
    $table->unique(['student_id', 'hostel_id']);
});
```

---

After writing all migrations, run:
```bash
php artisan migrate
```

Verify all 9 tables exist in PostgreSQL with correct columns before moving on.
Fix any errors before proceeding to the next prompt.
```

---

## PROMPT 3 — Models & Relationships

## 🧠 PROJECT CONTEXT BLOCK
> Paste this at the top of EVERY new Cursor session. Never skip this.

```
## CampusStay — Backend Project Context

I am building CampusStay, a centralized university hostel booking platform for
Ghanaian university students. I have completed the frontend in Vue 3 + Vite (standalone).
I am now integrating the Laravel 11 backend.

### Architecture (from Implementation Document)
Monolithic Laravel 11 application with Vue 3 SPA served via Inertia.js.
Client (Vue) communicates through Inertia to Laravel controllers.
Controllers interact with Services which interact with PostgreSQL.
Real-time updates via Laravel Reverb (WebSockets).
Background jobs via Laravel Queue Workers.

### Tech Stack
- Backend: Laravel 11
- Frontend: Vue 3 (Composition API, <script setup> ONLY — never Options API)
- Bridge: Inertia.js (replaces Vue Router and Axios for page navigation and data)
- Database: PostgreSQL
- Authentication: Laravel Sanctum (session-based, NOT token-based)
- Styling: Tailwind CSS (no inline styles, no external CSS files)
- Real-time: Laravel Reverb
- Queues: Laravel Queue Workers

### Three User Roles (from Implementation Document)
- STUDENT: browses, books, and reviews hostels
- MANAGER: manages their hostel(s), approves/rejects bookings, views dashboard
- ADMIN: oversees entire platform, approves managers, moderates reviews

### Booking Status Flow (from PRD & Implementation Document)
PENDING_APPROVAL → AWAITING_PAYMENT → CONFIRMED
Terminal states: REJECTED, CANCELLED

### Database Tables (from Implementation Document)
users, hostels, rooms, bookings, payments, waitlists, reviews, amenities, hostel_amenities

### Domain Rules
- Room types: '1-in-a-room' | '2-in-a-room' | '3-in-a-room'
- Gender policy: 'male' | 'female' | 'mixed'
- Prices are in GHS (Ghanaian Cedis) — always display with ₵ symbol
- Reviews only from verified students (students with a CONFIRMED booking)
- Payment methods: Mobile Money (MTN/Vodafone/AirtelTigo), Card, Bank Transfer, Crypto
- MVP: full payment only (no installments)

### Key Inertia.js Principle
Inertia replaces both the API layer AND Vue Router:
- No JSON API endpoints — Inertia passes data directly as page props
- No Vue Router — use Inertia's <Link> component and router.visit() for navigation
- Laravel routes map directly to Vue page components via Inertia::render()

### Frontend Files Location
Completed Vue components live in resources/js/ after migration from the Vite project.
All pages use <script setup> syntax. Pinia and components are already built.

### References
Always refer to @MVP_PRD.md and @Implementation_Document.md for full context.
```
---

## Task: Create All Eloquent Models

Re-read the project context before starting.
Create all models with correct relationships, fillable fields, and casts
that match the CampusStay schema exactly.

---

### Update Existing User Model (app/Models/User.php)
Add to the existing file — do not replace it entirely:

fillable: add role, student_id, phone, university, academic_year, is_active, approved_at

casts: add is_active as boolean, approved_at as datetime

Relationships:
```php
public function hostels(): HasMany
{
    return $this->hasMany(Hostel::class, 'manager_id');
}

public function bookings(): HasMany
{
    return $this->hasMany(Booking::class, 'student_id');
}

public function reviews(): HasMany
{
    return $this->hasMany(Review::class, 'student_id');
}

public function waitlists(): HasMany
{
    return $this->hasMany(Waitlist::class, 'student_id');
}
```

Role helper methods:
```php
public function isStudent(): bool { return $this->role === 'student'; }
public function isManager(): bool { return $this->role === 'manager'; }
public function isAdmin(): bool   { return $this->role === 'admin'; }
```

---

### Create app/Models/Amenity.php
```php
protected $fillable = ['name', 'icon'];

public function hostels(): BelongsToMany
{
    return $this->belongsToMany(Hostel::class, 'hostel_amenities');
}
```

---

### Create app/Models/Hostel.php
```php
use SoftDeletes;

protected $fillable = [
    'manager_id', 'name', 'slug', 'description', 'gender_policy',
    'address', 'distance_from_campus', 'university',
    'latitude', 'longitude', 'is_verified', 'is_active', 'images'
];

protected $casts = [
    'is_verified' => 'boolean',
    'is_active'   => 'boolean',
    'images'      => 'array',
];

protected $appends = ['average_rating', 'total_reviews', 'available_room_types_count'];

// Relationships
public function manager(): BelongsTo         // → User
public function rooms(): HasMany             // → Room
public function bookings(): HasMany          // → Booking
public function reviews(): HasMany           // → Review (where is_visible = true)
public function amenities(): BelongsToMany   // → Amenity via hostel_amenities

// Appended attributes
public function getAverageRatingAttribute(): float
{
    return round($this->reviews->avg('rating') ?? 0, 1);
}

public function getTotalReviewsAttribute(): int
{
    return $this->reviews->count();
}

public function getAvailableRoomTypesCountAttribute(): int
{
    return $this->rooms->where('available_beds', '>', 0)->count();
}
```

---

### Create app/Models/Room.php
```php
protected $fillable = [
    'hostel_id', 'type', 'price_per_semester',
    'total_beds', 'available_beds', 'room_amenities', 'is_active'
];

protected $casts = [
    'price_per_semester' => 'decimal:2',
    'room_amenities'     => 'array',
    'is_active'          => 'boolean',
];

public function hostel(): BelongsTo   // → Hostel
public function bookings(): HasMany   // → Booking
public function waitlists(): HasMany  // → Waitlist
```

---

### Create app/Models/Booking.php
```php
use SoftDeletes;

protected $fillable = [
    'reference', 'student_id', 'hostel_id', 'room_id', 'status',
    'academic_year', 'semester', 'rejection_reason',
    'payment_deadline', 'approved_at', 'confirmed_at'
];

protected $casts = [
    'payment_deadline' => 'datetime',
    'approved_at'      => 'datetime',
    'confirmed_at'     => 'datetime',
];

// Auto-generate booking reference on create
protected static function boot()
{
    parent::boot();
    static::creating(function ($booking) {
        $count = static::withTrashed()->count();
        $booking->reference = 'CS-' . date('Y') . '-' . str_pad($count + 1, 5, '0', STR_PAD_LEFT);
    });
}

public function student(): BelongsTo  // → User
public function hostel(): BelongsTo   // → Hostel
public function room(): BelongsTo     // → Room
public function payment(): HasOne     // → Payment
```

---

### Create app/Models/Payment.php
```php
protected $fillable = [
    'transaction_id', 'booking_id', 'student_id', 'amount',
    'method', 'status', 'provider_reference', 'metadata', 'paid_at'
];

protected $casts = [
    'amount'   => 'decimal:2',
    'metadata' => 'array',
    'paid_at'  => 'datetime',
];

public function booking(): BelongsTo  // → Booking
public function student(): BelongsTo  // → User (student_id)
```

---

### Create app/Models/Waitlist.php
```php
protected $fillable = [
    'student_id', 'room_id', 'position',
    'notified_at', 'expires_at', 'is_active'
];

protected $casts = [
    'notified_at' => 'datetime',
    'expires_at'  => 'datetime',
    'is_active'   => 'boolean',
];

public function student(): BelongsTo  // → User
public function room(): BelongsTo     // → Room
```

---

### Create app/Models/Review.php
```php
protected $fillable = [
    'student_id', 'hostel_id', 'booking_id',
    'rating', 'comment', 'is_visible'
];

protected $casts = [
    'rating'     => 'integer',
    'is_visible' => 'boolean',
];

public function student(): BelongsTo  // → User
public function hostel(): BelongsTo   // → Hostel
public function booking(): BelongsTo  // → Booking
```

---

After creating all models, verify relationships work:
```bash
php artisan tinker
App\Models\Hostel::with(['rooms', 'amenities', 'manager'])->first()
```
```

---

## PROMPT 4 — Database Seeders

## 🧠 PROJECT CONTEXT BLOCK
> Paste this at the top of EVERY new Cursor session. Never skip this.

```
## CampusStay — Backend Project Context

I am building CampusStay, a centralized university hostel booking platform for
Ghanaian university students. I have completed the frontend in Vue 3 + Vite (standalone).
I am now integrating the Laravel 11 backend.

### Architecture (from Implementation Document)
Monolithic Laravel 11 application with Vue 3 SPA served via Inertia.js.
Client (Vue) communicates through Inertia to Laravel controllers.
Controllers interact with Services which interact with PostgreSQL.
Real-time updates via Laravel Reverb (WebSockets).
Background jobs via Laravel Queue Workers.

### Tech Stack
- Backend: Laravel 11
- Frontend: Vue 3 (Composition API, <script setup> ONLY — never Options API)
- Bridge: Inertia.js (replaces Vue Router and Axios for page navigation and data)
- Database: PostgreSQL
- Authentication: Laravel Sanctum (session-based, NOT token-based)
- Styling: Tailwind CSS (no inline styles, no external CSS files)
- Real-time: Laravel Reverb
- Queues: Laravel Queue Workers

### Three User Roles (from Implementation Document)
- STUDENT: browses, books, and reviews hostels
- MANAGER: manages their hostel(s), approves/rejects bookings, views dashboard
- ADMIN: oversees entire platform, approves managers, moderates reviews

### Booking Status Flow (from PRD & Implementation Document)
PENDING_APPROVAL → AWAITING_PAYMENT → CONFIRMED
Terminal states: REJECTED, CANCELLED

### Database Tables (from Implementation Document)
users, hostels, rooms, bookings, payments, waitlists, reviews, amenities, hostel_amenities

### Domain Rules
- Room types: '1-in-a-room' | '2-in-a-room' | '3-in-a-room'
- Gender policy: 'male' | 'female' | 'mixed'
- Prices are in GHS (Ghanaian Cedis) — always display with ₵ symbol
- Reviews only from verified students (students with a CONFIRMED booking)
- Payment methods: Mobile Money (MTN/Vodafone/AirtelTigo), Card, Bank Transfer, Crypto
- MVP: full payment only (no installments)

### Key Inertia.js Principle
Inertia replaces both the API layer AND Vue Router:
- No JSON API endpoints — Inertia passes data directly as page props
- No Vue Router — use Inertia's <Link> component and router.visit() for navigation
- Laravel routes map directly to Vue page components via Inertia::render()

### Frontend Files Location
Completed Vue components live in resources/js/ after migration from the Vite project.
All pages use <script setup> syntax. Pinia and components are already built.

### References
Always refer to @MVP_PRD.md and @Implementation_Document.md for full context.
```
---

## Task: Create Database Seeders

Re-read the project context before starting.
Create seeders that populate the database with realistic data
matching the shape of the frontend mock data (mockHostels.js).
Data must feel like real Ghanaian university campus hostels.

---

### Seeder 1: UserSeeder
Create these accounts with password 'password' for all:

Admin:
- name: 'Platform Admin', email: 'admin@campusstay.com', role: 'admin'

Managers (is_active: true, approved_at: now()):
- name: 'Kwame Asante',  email: 'manager1@campusstay.com', role: 'manager'
- name: 'Abena Mensah',  email: 'manager2@campusstay.com', role: 'manager'
- name: 'Kofi Boateng',  email: 'manager3@campusstay.com', role: 'manager'

Students:
- name: 'Akosua Darko',    email: 'student1@campusstay.com',
  role: 'student', student_id: 'CS/2021/001', university: 'KNUST'
- name: 'Yaw Frimpong',    email: 'student2@campusstay.com',
  role: 'student', student_id: 'CS/2022/002', university: 'UG'
- name: 'Ama Owusu',       email: 'student3@campusstay.com',
  role: 'student', student_id: 'CS/2022/003', university: 'UCC'
- name: 'Kwesi Acheampong', email: 'student4@campusstay.com',
  role: 'student', student_id: 'CS/2023/004', university: 'KNUST'
- name: 'Efua Asiedu',     email: 'student5@campusstay.com',
  role: 'student', student_id: 'CS/2023/005', university: 'UG'

---

### Seeder 2: AmenitySeeder
Seed all amenities with icon names:
```php
$amenities = [
    ['name' => 'WiFi',        'icon' => 'wifi'],
    ['name' => 'Laundry',     'icon' => 'shirt'],
    ['name' => 'Kitchen',     'icon' => 'flame'],
    ['name' => 'Study Room',  'icon' => 'book-open'],
    ['name' => 'Generator',   'icon' => 'zap'],
    ['name' => 'Security',    'icon' => 'shield'],
    ['name' => 'CCTV',        'icon' => 'camera'],
    ['name' => 'Water 24/7',  'icon' => 'droplets'],
    ['name' => 'Gym',         'icon' => 'dumbbell'],
    ['name' => 'Parking',     'icon' => 'car'],
    ['name' => 'Common Room', 'icon' => 'sofa'],
    ['name' => 'Cafeteria',   'icon' => 'utensils'],
];
```

---

### Seeder 3: HostelSeeder
Create 6 hostels. Assign managers in rotation (manager1, manager2, manager3, manager1...).
Each hostel gets 2–3 rooms and 4–8 amenities attached.

Use these realistic hostel names and details:

Hostel 1 — "Nkrumah Executive Suites"
- manager: manager1, gender: mixed, university: KNUST
- address: 'Ayigya, Kumasi, Ashanti Region'
- distance: '5 mins walk', is_verified: true
- rooms: 1-in-a-room (₵3,800, 20 beds, 8 available),
         2-in-a-room (₵2,400, 40 beds, 15 available)
- amenities: WiFi, Generator, Security, CCTV, Water 24/7, Study Room, Parking

Hostel 2 — "Legon Heights Residence"
- manager: manager2, gender: female, university: UG
- address: 'Accra, Greater Accra Region, near University of Ghana'
- distance: '3 mins walk', is_verified: true
- rooms: 1-in-a-room (₵4,200, 15 beds, 3 available),
         2-in-a-room (₵2,800, 30 beds, 0 available),
         3-in-a-room (₵1,800, 45 beds, 12 available)
- amenities: WiFi, Laundry, Kitchen, Security, CCTV, Common Room, Cafeteria

Hostel 3 — "Cape Coast Student Lodge"
- manager: manager3, gender: male, university: UCC
- address: 'University Road, Cape Coast, Central Region'
- distance: '8 mins walk', is_verified: false
- rooms: 2-in-a-room (₵2,000, 50 beds, 20 available),
         3-in-a-room (₵1,500, 60 beds, 35 available)
- amenities: WiFi, Generator, Water 24/7, Parking, Security

Hostel 4 — "Kotoka Premium Flats"
- manager: manager1, gender: mixed, university: KNUST
- address: 'Bomso, Kumasi, Ashanti Region'
- distance: '10 mins walk', is_verified: true
- rooms: 1-in-a-room (₵4,500, 10 beds, 2 available),
         2-in-a-room (₵3,000, 20 beds, 8 available)
- amenities: WiFi, Gym, Laundry, Generator, Security, CCTV, Water 24/7, Parking

Hostel 5 — "Adenta Student Inn"
- manager: manager2, gender: female, university: UG
- address: 'Adenta, Accra, Greater Accra Region'
- distance: '15 mins drive', is_verified: false
- rooms: 2-in-a-room (₵2,200, 35 beds, 18 available),
         3-in-a-room (₵1,600, 45 beds, 0 available)
- amenities: WiFi, Kitchen, Laundry, Common Room, Water 24/7

Hostel 6 — "Mensah Sarbah Annex"
- manager: manager3, gender: mixed, university: UG
- address: 'East Legon, Accra, Greater Accra Region'
- distance: '7 mins walk', is_verified: true
- rooms: 1-in-a-room (₵3,500, 12 beds, 5 available),
         2-in-a-room (₵2,500, 24 beds, 10 available),
         3-in-a-room (₵1,700, 36 beds, 20 available)
- amenities: WiFi, Security, Generator, Study Room, Cafeteria, CCTV

For images, use real Unsplash URLs for student accommodation:
https://images.unsplash.com/photo-1555854877-bab0e564b8d5?w=800
https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800
https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800
https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=800
(rotate through these 4 for all hostels)

---

### Seeder 4: DatabaseSeeder
Wire all seeders in dependency order:
```php
$this->call([
    UserSeeder::class,
    AmenitySeeder::class,
    HostelSeeder::class,
]);
```

---

Run and verify:
```bash
php artisan db:seed
php artisan tinker
App\Models\Hostel::with(['rooms', 'amenities', 'manager'])->get()->count()
// Should return 6
```
```

---

## PROMPT 5 — Authentication

## 🧠 PROJECT CONTEXT BLOCK
> Paste this at the top of EVERY new Cursor session. Never skip this.

```
## CampusStay — Backend Project Context

I am building CampusStay, a centralized university hostel booking platform for
Ghanaian university students. I have completed the frontend in Vue 3 + Vite (standalone).
I am now integrating the Laravel 11 backend.

### Architecture (from Implementation Document)
Monolithic Laravel 11 application with Vue 3 SPA served via Inertia.js.
Client (Vue) communicates through Inertia to Laravel controllers.
Controllers interact with Services which interact with PostgreSQL.
Real-time updates via Laravel Reverb (WebSockets).
Background jobs via Laravel Queue Workers.

### Tech Stack
- Backend: Laravel 11
- Frontend: Vue 3 (Composition API, <script setup> ONLY — never Options API)
- Bridge: Inertia.js (replaces Vue Router and Axios for page navigation and data)
- Database: PostgreSQL
- Authentication: Laravel Sanctum (session-based, NOT token-based)
- Styling: Tailwind CSS (no inline styles, no external CSS files)
- Real-time: Laravel Reverb
- Queues: Laravel Queue Workers

### Three User Roles (from Implementation Document)
- STUDENT: browses, books, and reviews hostels
- MANAGER: manages their hostel(s), approves/rejects bookings, views dashboard
- ADMIN: oversees entire platform, approves managers, moderates reviews

### Booking Status Flow (from PRD & Implementation Document)
PENDING_APPROVAL → AWAITING_PAYMENT → CONFIRMED
Terminal states: REJECTED, CANCELLED

### Database Tables (from Implementation Document)
users, hostels, rooms, bookings, payments, waitlists, reviews, amenities, hostel_amenities

### Domain Rules
- Room types: '1-in-a-room' | '2-in-a-room' | '3-in-a-room'
- Gender policy: 'male' | 'female' | 'mixed'
- Prices are in GHS (Ghanaian Cedis) — always display with ₵ symbol
- Reviews only from verified students (students with a CONFIRMED booking)
- Payment methods: Mobile Money (MTN/Vodafone/AirtelTigo), Card, Bank Transfer, Crypto
- MVP: full payment only (no installments)

### Key Inertia.js Principle
Inertia replaces both the API layer AND Vue Router:
- No JSON API endpoints — Inertia passes data directly as page props
- No Vue Router — use Inertia's <Link> component and router.visit() for navigation
- Laravel routes map directly to Vue page components via Inertia::render()

### Frontend Files Location
Completed Vue components live in resources/js/ after migration from the Vite project.
All pages use <script setup> syntax. Pinia and components are already built.

### References
Always refer to @MVP_PRD.md and @Implementation_Document.md for full context.
```

---

## Task: Build Full Authentication with Laravel Sanctum + Inertia

Re-read the project context before starting.

CampusStay has three roles: STUDENT, MANAGER, ADMIN.
After login, each role redirects to a different destination.
Authentication uses Laravel Sanctum with SESSION-based auth
(not token/Bearer auth — Inertia uses session cookies).

---

### Step 1: Configure Sanctum for Session Auth
In config/sanctum.php confirm stateful domains includes:
'localhost', '127.0.0.1', 'localhost:8000'

In bootstrap/app.php ensure the web middleware group includes:
- \Illuminate\Session\Middleware\StartSession::class
- \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class

---

### Step 2: Create Form Request Validators

Create `app/Http/Requests/Auth/LoginRequest.php`:
```php
rules: [
    'email'    => ['required', 'email'],
    'password' => ['required', 'string'],
]
```

Create `app/Http/Requests/Auth/RegisterRequest.php`:
```php
rules: [
    'name'                  => ['required', 'string', 'max:255'],
    'email'                 => ['required', 'email', 'unique:users'],
    'password'              => ['required', 'string', 'min:8', 'confirmed'],
    'role'                  => ['required', 'in:student,manager'],
    'student_id'            => ['required_if:role,student', 'nullable', 'string'],
    'university'            => ['required', 'string'],
    'phone'                 => ['required', 'string'],
]
```

---

### Step 3: Create AuthController
Create `app/Http/Controllers/Auth/AuthController.php`:

```php
// Show login page
public function showLogin()
{
    return Inertia::render('Auth/Login');
}

// Handle login
public function login(LoginRequest $request)
{
    if (!Auth::attempt($request->only('email', 'password'))) {
        return back()->withErrors(['email' => 'Invalid credentials. Please try again.']);
    }

    $request->session()->regenerate();

    return match(Auth::user()->role) {
        'admin'   => redirect()->route('admin.dashboard'),
        'manager' => redirect()->route('manager.dashboard'),
        default   => redirect()->route('hostels.index'),
    };
}

// Show register page
public function showRegister()
{
    return Inertia::render('Auth/Register');
}

// Handle registration
public function register(RegisterRequest $request)
{
    $user = User::create([
        'name'          => $request->name,
        'email'         => $request->email,
        'password'      => bcrypt($request->password),
        'role'          => $request->role,
        'student_id'    => $request->student_id,
        'university'    => $request->university,
        'phone'         => $request->phone,
        'is_active'     => $request->role !== 'manager', // managers need admin approval
    ]);

    if ($request->role === 'manager') {
        return redirect()->route('login')
            ->with('success', 'Account created. Awaiting admin approval before you can log in.');
    }

    Auth::login($user);
    return redirect()->route('hostels.index');
}

// Handle logout
public function logout(Request $request)
{
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('home');
}
```

---

### Step 4: Share Auth User via Inertia Middleware
Update `app/Http/Middleware/HandleInertiaRequests.php` share() method:
```php
public function share(Request $request): array
{
    return [
        ...parent::share($request),
        'auth' => [
            'user' => $request->user() ? [
                'id'         => $request->user()->id,
                'name'       => $request->user()->name,
                'email'      => $request->user()->email,
                'role'       => $request->user()->role,
                'university' => $request->user()->university,
            ] : null,
        ],
        'flash' => [
            'success' => fn() => session('success'),
            'error'   => fn() => session('error'),
        ],
    ];
}
```

---

### Step 5: Add Auth Routes in routes/web.php
```php
use App\Http\Controllers\Auth\AuthController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
```

---

### Step 6: Build Auth Vue Pages

Create `resources/js/Pages/Auth/Login.vue` using <script setup> and Tailwind only.
Use useForm from @inertiajs/vue3 for form handling (it manages submission + errors natively).

Layout: Two-panel side-by-side on desktop, single column on mobile.
Left panel: deep green background (#1B4332), CampusStay logo, tagline, decorative element.
Right panel: white, login form.

Form fields:
- Email address (type="email")
- Password (type="password", with show/hide toggle)
- "Remember me" checkbox
- "Forgot password?" link (placeholder, no functionality yet)

On submit: form.post(route('login'))
Show inline validation errors from form.errors below each field.
Show flash error message at top if login fails.
Link to /register at the bottom: "Don't have an account? Sign up"

---

Create `resources/js/Pages/Auth/Register.vue` using <script setup> and Tailwind only.
Same two-panel layout as Login.

Form fields:
- Full Name
- Email Address
- Phone Number
- University (select dropdown: KNUST, UG, UCC, Ashesi, Other)
- Account Type toggle (two large pill buttons: "I'm a Student" / "I'm a Manager")
- Student ID (text input — only visible when Student is selected, use v-show)
- Password + Confirm Password
- Manager notice (shown only when Manager selected):
  "Manager accounts require admin approval before activation.
   You will be notified by email once approved."

On submit: form.post(route('register'))
Show inline errors. Link to /login: "Already have an account? Sign in"

---

### Step 7: Update Navbar.vue for Real Auth State
Replace all mock auth logic with real Inertia shared props:

```js
import { usePage } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'

const page = usePage()
const user = computed(() => page.props.auth.user)

function logout() {
    router.post('/logout')
}
```

In the template:
- If user is null: show "Sign In" (→ /login) and "Get Started" (→ /register) buttons
- If user is logged in: show user's name, a role badge
  (green pill: 'Student' | 'Manager' | 'Admin'), and a "Logout" button
```

---

## PROMPT 6 — Hostel Listings (Connect to Real Data)

## 🧠 PROJECT CONTEXT BLOCK
> Paste this at the top of EVERY new Cursor session. Never skip this.

```
## CampusStay — Backend Project Context

I am building CampusStay, a centralized university hostel booking platform for
Ghanaian university students. I have completed the frontend in Vue 3 + Vite (standalone).
I am now integrating the Laravel 11 backend.

### Architecture (from Implementation Document)
Monolithic Laravel 11 application with Vue 3 SPA served via Inertia.js.
Client (Vue) communicates through Inertia to Laravel controllers.
Controllers interact with Services which interact with PostgreSQL.
Real-time updates via Laravel Reverb (WebSockets).
Background jobs via Laravel Queue Workers.

### Tech Stack
- Backend: Laravel 11
- Frontend: Vue 3 (Composition API, <script setup> ONLY — never Options API)
- Bridge: Inertia.js (replaces Vue Router and Axios for page navigation and data)
- Database: PostgreSQL
- Authentication: Laravel Sanctum (session-based, NOT token-based)
- Styling: Tailwind CSS (no inline styles, no external CSS files)
- Real-time: Laravel Reverb
- Queues: Laravel Queue Workers

### Three User Roles (from Implementation Document)
- STUDENT: browses, books, and reviews hostels
- MANAGER: manages their hostel(s), approves/rejects bookings, views dashboard
- ADMIN: oversees entire platform, approves managers, moderates reviews

### Booking Status Flow (from PRD & Implementation Document)
PENDING_APPROVAL → AWAITING_PAYMENT → CONFIRMED
Terminal states: REJECTED, CANCELLED

### Database Tables (from Implementation Document)
users, hostels, rooms, bookings, payments, waitlists, reviews, amenities, hostel_amenities

### Domain Rules
- Room types: '1-in-a-room' | '2-in-a-room' | '3-in-a-room'
- Gender policy: 'male' | 'female' | 'mixed'
- Prices are in GHS (Ghanaian Cedis) — always display with ₵ symbol
- Reviews only from verified students (students with a CONFIRMED booking)
- Payment methods: Mobile Money (MTN/Vodafone/AirtelTigo), Card, Bank Transfer, Crypto
- MVP: full payment only (no installments)

### Key Inertia.js Principle
Inertia replaces both the API layer AND Vue Router:
- No JSON API endpoints — Inertia passes data directly as page props
- No Vue Router — use Inertia's <Link> component and router.visit() for navigation
- Laravel routes map directly to Vue page components via Inertia::render()

### Frontend Files Location
Completed Vue components live in resources/js/ after migration from the Vite project.
All pages use <script setup> syntax. Pinia and components are already built.

### References
Always refer to @MVP_PRD.md and @Implementation_Document.md for full context.
```

---

## Task: Replace Mock Data with Real PostgreSQL Data via Inertia

Re-read the project context before starting.
This is the most important transition in the project.
The Vue frontend pages already exist. I am replacing mockHostels.js
with real data from PostgreSQL passed via Inertia page props.

Key principle: the HostelResource must output data in the EXACT same shape
as mockHostels.js so Vue components require zero changes.

---

### Step 1: Create HostelResource
Create `app/Http/Resources/HostelResource.php`:

```php
public function toArray(Request $request): array
{
    return [
        'id'                    => $this->id,
        'name'                  => $this->name,
        'slug'                  => $this->slug,
        'description'           => $this->description,
        'gender_policy'         => $this->gender_policy,
        'distance_from_campus'  => $this->distance_from_campus,
        'address'               => $this->address,
        'university'            => $this->university,
        'is_verified'           => $this->is_verified,
        'rating'                => $this->average_rating,
        'total_reviews'         => $this->total_reviews,
        'room_types'            => $this->rooms->map(fn($room) => [
            'id'                  => $room->id,
            'type'                => $room->type,
            'price_per_semester'  => $room->price_per_semester,
            'total_beds'          => $room->total_beds,
            'available_beds'      => $room->available_beds,
            'room_amenities'      => $room->room_amenities ?? [],
        ]),
        'amenities'             => $this->amenities->pluck('name'),
        'images'                => $this->images ?? [],
        'manager'               => [
            'name' => $this->manager->name,
        ],
    ];
}
```

---

### Step 2: Create HomeController
Create `app/Http/Controllers/HomeController.php`:

```php
public function index()
{
    $featuredHostels = Hostel::query()
        ->where('is_active', true)
        ->where('is_verified', true)
        ->with(['rooms', 'amenities', 'reviews', 'manager'])
        ->withCount('reviews')
        ->limit(3)
        ->get();

    return Inertia::render('Home', [
        'featuredHostels' => HostelResource::collection($featuredHostels),
    ]);
}
```

---

### Step 3: Create HostelController
Create `app/Http/Controllers/HostelController.php`:

```php
public function index(Request $request)
{
    $query = Hostel::query()
        ->where('is_active', true)
        ->with(['rooms', 'amenities', 'reviews', 'manager'])
        ->withCount('reviews');

    // Search by name or address
    if ($search = $request->search) {
        $query->where(fn($q) =>
            $q->where('name', 'ilike', "%$search%")
              ->orWhere('address', 'ilike', "%$search%")
        );
    }

    // Filter by gender policy
    if ($gender = $request->gender_policy and $gender !== 'all') {
        $query->where('gender_policy', $gender);
    }

    // Filter by room type
    if ($roomType = $request->room_type and $roomType !== 'all') {
        $query->whereHas('rooms', fn($q) =>
            $q->where('type', $roomType)->where('is_active', true)
        );
    }

    // Filter by max price
    if ($maxPrice = $request->max_price) {
        $query->whereHas('rooms', fn($q) =>
            $q->where('price_per_semester', '<=', $maxPrice)
        );
    }

    // Filter by amenities (hostel must have ALL selected amenities)
    if ($amenities = $request->amenities) {
        foreach ($amenities as $amenity) {
            $query->whereHas('amenities', fn($q) =>
                $q->where('name', $amenity)
            );
        }
    }

    $hostels = $query->get();

    return Inertia::render('Listings', [
        'hostels'  => HostelResource::collection($hostels),
        'filters'  => $request->only(['search', 'gender_policy', 'room_type', 'max_price', 'amenities']),
        'allAmenities' => Amenity::orderBy('name')->pluck('name'),
    ]);
}

public function show(Hostel $hostel)
{
    $hostel->load(['rooms', 'amenities', 'manager', 'reviews.student']);

    return Inertia::render('HostelDetail', [
        'hostel' => new HostelResource($hostel),
        'reviews' => $hostel->reviews->where('is_visible', true)->map(fn($review) => [
            'id'                  => $review->id,
            'student_name'        => $review->student->name,
            'avatar_initial'      => strtoupper(substr($review->student->name, 0, 1)),
            'rating'              => $review->rating,
            'comment'             => $review->comment,
            'date'                => $review->created_at->format('F Y'),
            'is_verified_student' => true,
        ]),
    ]);
}
```

---

### Step 4: Register Routes in routes/web.php
```php
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HostelController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/hostels', [HostelController::class, 'index'])->name('hostels.index');
Route::get('/hostels/{hostel:slug}', [HostelController::class, 'show'])->name('hostels.show');
```

---

### Step 5: Update Vue Pages to Use Inertia Props

Update `resources/js/Pages/Home.vue` (formerly HomePage.vue):
- Remove mockHostels import and hostelStore dependency for featured hostels
- Add: `const props = defineProps({ featuredHostels: Array })`
- Replace the hostelStore.hostels slice with props.featuredHostels in the template
- Search bar: on submit, call router.visit('/hostels', { data: { search, gender_policy } })

Update `resources/js/Pages/Listings.vue` (formerly ListingsPage.vue):
- Remove Pinia store filtering entirely
- Add: `const props = defineProps({ hostels: Array, filters: Object, allAmenities: Array })`
- Populate filter controls from props.filters on mount (so URL params restore filter state)
- Each filter change calls:
  ```js
  router.get('/hostels', { ...currentFilters }, { preserveState: true, replace: true })
  ```
  This triggers server-side filtering and re-renders with new results
- Display props.hostels in the grid (no client-side filtering needed)
- Use props.allAmenities for the amenities checkbox list

Update `resources/js/Pages/HostelDetail.vue` (formerly HostelDetailPage.vue):
- Remove hostelStore.selectHostel() call and all store dependencies
- Add: `const props = defineProps({ hostel: Object, reviews: Array })`
- Use props.hostel and props.reviews directly throughout the template
- The hostel is already loaded by Laravel — no onMounted fetch needed

---

### Step 6: Clean Up
- Remove the mockHostels.js import from any Vue page that now uses Inertia props
- Keep src/data/mockHostels.js file in place temporarily as reference
- Update the Pinia hostelStore to only manage UI state (comparison list, etc.)
  — remove all hostel data state since data now comes from Inertia props

Verify the full flow works:
1. Visit http://localhost:8000 → HomePage shows 3 real hostels from DB
2. Click "View All" → ListingsPage shows all 6 seeded hostels
3. Use the filters → page re-renders with filtered results from PostgreSQL
4. Click a hostel card → HostelDetailPage shows real hostel data
```
