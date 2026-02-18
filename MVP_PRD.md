**CampusStay - Product Requirements Document** **\(MVP\)**

**1. Product Overview**

CampusStay is a centralized digital platform designed for a university to allow students to discover, compare, and book hostels. It enables hostel managers to efficiently manage rooms, occupancy, payments, and communication in real-time. 

**2. Target Users**

Students – University students seeking accommodation. 

Hostel Managers – Owners/operators managing specific hostels. 

Platform Admin – Oversees the entire system and approves managers. 

**3. Core Student Features**

- Browse all available hostels

- Filter by price, distance, room type, amenities

- View detailed hostel pages with availability

- Online booking \(pending admin approval\)

- Waitlist system

- Room comparison tool

- Reviews & ratings \(verified students only\)

- Virtual tours

- AI chatbot support

**4. Booking Workflow \(MVP\)** 1. Student selects hostel and room type \(1, 2, or 3 in a room\). 

2. Booking created with status: PENDING\_APPROVAL. 

3. Manager reviews and approves/rejects. 

4. If approved, payment deadline is set \(AWAITING\_PAYMENT\). 

5. After payment confirmation, booking becomes CONFIRMED. 

**5. Payment \(MVP Focus: Full Payment Only\)**

Supported methods:

- Mobile Money \(MTN, Vodafone, AirtelTigo\)

- Card \(Visa/Mastercard\)

- Bank Transfer

- Crypto

For MVP, only full payment is required. 

**6. Hostel Manager Features**

- Dashboard \(occupancy, revenue, pending approvals\)

- Room management \(create rooms, set prices, bed capacity\)

- Booking approvals

- Reports \(occupancy, revenue, trends\)

- Post announcements

- View current residents

**7. Platform Admin Features**

- Approve/suspend hostel managers

- Moderate reviews

- View system-wide analytics

- Manage payment configurations **8. Real-Time Features**

- Real-time bed availability updates

- Live occupancy dashboard

- Email/SMS notifications

**9. Technical Architecture** Backend: Laravel 11, PostgreSQL

Frontend: Vue 3 \(Composition API \+ script setup\), Inertia.js Authentication: Laravel Sanctum Real-Time: WebSockets \(Laravel Reverb or Pusher\) Styling: Tailwind CSS

**10. Database Overview**

Core Tables:

- users \(roles: student, manager, admin\)

- hostels

- rooms

- bookings

- payments

- reviews

- amenities

- hostel\_amenities \(pivot\)

**11. Success Metrics**

- Booking conversion rate

- Average occupancy rate

- Payment completion rate

- Review ratings

- Waitlist conversion rate

**12. MVP Milestones**

Phase 1: Authentication, Hostel Listing, Room Creation, Booking System Phase 2: Reviews, Dashboards, Real-time updates, Waitlist Phase 3: AI Chatbot, Smart Pricing, Map View, WhatsApp Integration



