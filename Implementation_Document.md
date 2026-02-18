**CampusStay - MVP Implementation Document** **1. System Architecture Overview** Architecture Style: Monolithic Laravel 11 application with Vue 3 SPA via Inertia.js. 

Client \(Vue\) communicates through Inertia to Laravel controllers, which interact with services and PostgreSQL. Real-time updates handled via Laravel broadcasting \(Reverb/Pusher\). 

**2. Technology Stack**

Backend: Laravel 11

Frontend: Vue 3 \(Composition API with script setup\) Database: PostgreSQL

Authentication: Laravel Sanctum

Styling: Tailwind CSS

Real-time: Laravel Reverb or Pusher Queues: Laravel Queue Workers

**3. Roles and Authorization**

Roles: ADMIN, MANAGER, STUDENT. 

Implementation includes role column in users table, Laravel Policies, and middleware protection for route groups. 

**4. Database Schema Overview**

Core Tables:

- users \(role-based\)

- hostels \(linked to managers\)

- rooms \(linked to hostels, includes bed counts\)

- bookings \(approval workflow states\)

- payments \(transaction tracking\)

- waitlists \(queue for unavailable rooms\)

- reviews \(verified student reviews\)

- amenities \+ hostel\_amenities pivot **5. Booking Workflow Implementation**

1. Student submits booking \(status: pending\_approval\). 

2. Manager reviews booking. 

3. If approved, status becomes awaiting\_payment and deadline is set. 

4. Upon successful payment, status becomes confirmed and available beds decrement. 

5. Real-time event dispatched for availability updates. 

**6. Real-Time Updates**

Events broadcast when bookings are confirmed or cancelled. 

Frontend listens via Laravel Echo to update bed availability and dashboards instantly. 

**7. Waitlist Logic**

When no beds are available, students join waitlist. 

When a bed opens, first student is notified and given limited time to complete booking. 

Implemented using Laravel Jobs and Queues. 

**8. Dashboard Metrics**

Manager dashboard includes:

- Total beds

- Occupied beds

- Occupancy rate

- Pending approvals

- Revenue summary

Charts implemented in Vue for analytics visualization. 

**9. AI Features \(MVP Level\)**

AI Chatbot integrated via API for hostel queries. 

AI FAQ auto-response using hostel data context. 

Smart pricing suggestions based on occupancy thresholds. 

**10. Security Considerations**

- Role-based access control

- Form Request validation

- Prevent double bookings

- Payment verification handling

- Rate limiting for chatbot API **11. Development Phases**

Phase 1: Auth, Roles, Hostel & Room CRUD

Phase 2: Booking \+ Approval Workflow Phase 3: Payment Integration

Phase 4: Dashboard \+ Reviews

Phase 5: Real-time \+ AI Enhancements **12. Deployment Plan**

Deploy to VPS \(e.g., DigitalOcean\). 

Use Nginx, PostgreSQL, Supervisor for queues, SSL via Let's Encrypt. 

Ensure WebSocket server is running for real-time features.



