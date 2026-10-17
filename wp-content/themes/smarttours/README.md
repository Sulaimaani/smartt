# Canada Smart Tours (smarttours.ca) — WordPress Theme

**Canada Smart Tours** is a clean, modern, Viator-inspired WordPress theme designed for tour operators and online resellers in the Okanagan Valley, British Columbia, Canada.

---

## 1. What Changed (Redesign Overview)

The theme has been redesigned from the ground up for a clean, modern, and professional aesthetic inspired by Viator's tour pages:

### Design System
- **Typography:** Modern clean sans-serif (**Plus Jakarta Sans** / Inter) with normal-case headings, refined font weights, and clear visual hierarchy (no heavy condensed uppercase).
- **Color Palette (CSS Variables):**
  - Primary Brand Green: `#0F7B5F` (buttons, active states, key accents)
  - Dark Body Text: `#1A1A1A`
  - Muted Text: `#6B6B6B`
  - Borders: `#E5E5E5`
  - Light Background: `#F7F7F7`
  - Surface White: `#FFFFFF`
  - Rating Yellow: `#F5B301`
- **Dark Mode Support:** Clean, high-contrast dark theme toggle with persistence in `localStorage` and system `prefers-color-scheme` support.
- **Card & Element Styling:** 12px rounded corners on cards, 8px on buttons/inputs, soft shadows only on hover, 8px spacing scale, 1200px max content width.
- **Mobile-First Responsiveness:** Fully tested across 375px (mobile), 768px (tablet), 1024px (small desktop), and 1440px (wide desktop). All touch targets $\ge$ 44px with zero horizontal scroll.

### Header & Navigation
- **Row 1:**
  - Sticky white bar with Canada Smart Tours logo mark.
  - Large rounded center search bar (`Search tours in Kelowna & Okanagan Valley...`) with **Live Search Suggestions** for both experiences and categories.
  - Right tools: CAD ($) currency badge, dark mode toggle button, Help link, and **My Trip** heart button with real-time count badge.
- **Row 2:**
  - **Explore All** dropdown with a desktop **Mega Menu** featuring all 12 main Okanagan categories and their sub-categories in columns.
  - Quick direct links: *Wine Touring*, *Active & Outdoors*, *Food & Drink*, *Family Friendly*, *Attractions*, and *Trip Planner*.
- **Mobile Header:**
  - Compact header with brand logo, mobile search drawer toggle, theme toggle, My Trip count badge, and hamburger button.
  - Slide-in mobile drawer with accordion-based category navigation and quick links.

### Homepage
- **Hero Section:** Full-width Okanagan landscape image with dark overlay, bold headline, interactive search box with category select and date picker, and popular search chips (*Wine Touring*, *Lake Charters*, *Myra Trestles*, *Craft Breweries*, *Family Friendly*).
- **Top Experiences Carousel:** Horizontal card scroll with next/prev arrow buttons on desktop and smooth touch swipe on mobile.
- **Explore by Category:** 12 image tiles with 12px rounded corners, category names, and experience counts (counts of 0 are automatically hidden).
- **Why Book with Smart Tours:** 3 trust points (*100% Okanagan Local*, *Live Availability*, *Free Cancellation*).
- **Local Guides & Blog:** 3 clean insider cards.
- **Recently Viewed Experiences:** Smart strip dynamically rendered from `localStorage`.

### Listing & Category Archives
- **Breadcrumbs & Intro:** Clean breadcrumb trail, H1, one-line category description.
- **Sub-Category Chips:** Scrollable chip pills under H1 on mobile and desktop instead of oversized tiles.
- **Left White Filter Sidebar (Desktop):**
  - Keyword search within category.
  - Free cancellation toggle switch.
  - Price range slider with live `$X CAD` label.
  - Duration filter radio buttons (Any, Up to 2 hours, 2 to 4 hours, 4+ hours).
  - Star rating filter (Any, 4.5+ stars, 4.0+ stars).
  - Amenities checkboxes with counts and `+ Show more` expansion toggle.
  - Reset filters button.
- **Mobile Bottom Sheet:** Sticky floating "Filters" button on mobile that opens an animated bottom drawer with all filter controls and an "Apply Filters" CTA.
- **Toolbar:** Found count, sort dropdown (*Recommended*, *Price: Low to High*, *Price: High to Low*, *Highest Rated*, *Duration*, *Near Me via GPS*), Grid/List view switcher, and Leaflet interactive map toggle.
- **Skeleton Loaders:** Shimmering skeleton cards display during AJAX filter updates.

### Viator-Style Single Experience Page
- **Header:** Breadcrumbs, H1, rating score with review count link, location pin, Share button, and Save to Trip heart button.
- **Viator Photo Gallery:**
  - Vertical thumbnails column on left + large main image on right with previous/next arrows and a photo counter pill (`1 / 5 Photos`).
  - Fullscreen lightbox modal on click with keyboard Esc / arrow navigation.
  - Full-width swipeable gallery on mobile.
- **Quick Facts Row:** Duration, Mobile Ticket Accepted, Language, Pickup / Meeting Point.
- **Sticky Desktop Booking Box:**
  - Price from `$X CAD` per person with Best Price Guarantee badge.
  - Date picker input.
  - Travelers selector with Adults (+/-) counters and live total price calculation.
  - Big "Book Now" CTA respecting existing booking types (`booking_widget`, `affiliate_link`, `external_link`, `none`).
  - Free cancellation trust note and "Reserve now & pay later" hint.
- **Mobile Sticky Bottom Bar:** Docked price summary and Book Now CTA fixed at bottom of viewport.
- **Sticky In-Page Navigation:** `Overview` | `What's Included` | `Meeting & Pickup` | `What to Expect` | `Additional Info` | `Cancellation` | `Reviews` with scroll-spy highlighting.
- **Rich Content Sections:**
  - Overview with amenities tags.
  - What's Included / Excluded with green checkmark and cross icons.
  - Meeting & Pickup details with Leaflet map and "Open in Google Maps" directions link.
  - Operating schedule (hidden if empty).
  - What to Expect details.
  - Additional Info (two-column bullets with `+ Show more details` toggle).
  - Standard 24-hour cancellation policy card.
  - Local visitor questions & support card.
  - Traveller photos grid.
  - Customer reviews with score pill, star breakdown bars, and verified review cards.
- **You Might Also Like Carousel:** 4 related tours from the same category.
- **Recently Viewed Strip:** Automatically tracks current tour and displays previously viewed experiences.

---

## 2. How to Share a Trip (My Trip Planner)

The theme features a server-persisted, shareable trip planner that requires no visitor login.

### Saving Experiences
1. Click the **Heart icon** on any experience card, listing page, or single experience hero.
2. A toast notification confirms: `Added to My Trip • View`.
3. The header **My Trip** pill updates its counter badge instantly.

### Viewing & Managing Your Trip
1. Navigate to `/my-trip/` or click the **My Trip** badge in the header.
2. The page displays:
   - Total number of saved tours.
   - Estimated total price in **CAD ($)** calculated live.
   - An interactive **Leaflet Route Map** with numbered stop pins (`Stop #1`, `Stop #2`, ...).
   - Saved experience cards with individual date inputs (e.g. `July 10, 2026`) and custom notes (e.g. `Book patio table`).
   - A **Remove** button on each card and a **Clear All** button.

### Generating a Shareable Trip Link
1. On the `/my-trip/` page, click the **Share Trip** button.
2. Smart Tours securely saves the itinerary server-side into the custom database table `wp_st_trips`:
   - `id`: Unique record ID.
   - `share_code`: 6-character random alphanumeric code (e.g. `ab12cd`).
   - `experience_ids`: JSON array of saved experience post IDs.
   - `notes`: JSON object containing dates and notes for each stop.
   - `created_at`: MySQL timestamp.
3. The Share Modal pops up with the permanent link:
   `https://smarttours.ca/trip/ab12cd/` (or `http://localhost/wordpress/trip/ab12cd/`).
4. Sharing Channels:
   - **Copy Link:** Click "Copy link" to copy the URL to the clipboard with an instant confirmation toast (`Link copied to clipboard!`).
   - **WhatsApp:** Direct WhatsApp share link with pre-filled message.
   - **Email:** Direct mailto link with subject and itinerary URL.
   - **Native Mobile Share:** Triggers `navigator.share` on iOS and Android devices.

### Opening a Shared Trip
1. When a friend or client opens `smarttours.ca/trip/{share_code}/`, the custom rewrite rule loads `page-my-trip.php` in read-only shared mode.
2. The page displays:
   - Banner: `Shared Okanagan Itinerary • Trip {CODE}`.
   - A prominent **Save to My Trip** button: clicking imports the entire trip (all experience IDs and notes) into the visitor's local trip planner and redirects them to their personal `/my-trip/` page.
   - Complete route map and cards.

---

## 3. Experience Meta Fields Reference

All new Viator-style layout sections are supported by native WordPress meta boxes under **Experiences → Edit Experience**:

| Meta Field Key | Label | Description |
|---|---|---|
| `_st_badge` | Card Badge | e.g. `Likely to Sell Out`, `Free Cancellation`, `Bestseller` |
| `_st_rating` | Star Rating | e.g. `4.9` (0 to 5) |
| `_st_review_count` | Review Count | e.g. `128` |
| `_st_language` | Offered Languages | e.g. `English, French` |
| `_st_mobile_ticket` | Mobile Ticket | `yes` (Mobile Ticket Accepted) or `no` (Printed Voucher) |
| `_st_pickup` | Pickup / Transportation | e.g. `Pickup offered from Kelowna hotels` |
| `_st_included` | What's Included | Textarea: one item per line (rendered with green checkmarks) |
| `_st_excluded` | What's Excluded | Textarea: one item per line (rendered with cross icons) |
| `_st_meeting_point` | Meeting Point Instructions | Detailed address and meeting instructions |
| `_st_end_point` | End Point | Activity end location |
| `_st_operating_hours` | Operating Schedule | e.g. `Daily: 10:00 AM – 6:00 PM (May - Oct)` (hidden if empty) |
| `_st_what_to_expect` | What to Expect | Detailed tour description / paragraphs |
| `_st_additional_info` | Additional Information | Textarea: one bullet per line (supports Show More toggle) |
| `_st_cancellation_policy` | Cancellation Policy | Custom cancellation terms (defaults to 24h standard) |
| `_st_price_from` | Price From (CAD) | e.g. `65` |
| `_st_duration` | Duration | e.g. `3 Hours` |
| `_st_booking_type` | Booking Type | `none`, `affiliate_link`, `booking_widget`, `external_link` |
| `_st_booking_url` | Booking / Affiliate URL | Direct link or tracked redirect via `/go/{id}/` |
| `_st_booking_widget_code` | Booking Widget Code | Embed script / iframe from Bókun, Rezdy, Viator, etc. |
| `_st_gallery_ids` | Gallery Images | Comma-separated image URLs or media IDs |

*Note: If any optional meta field is left empty, its corresponding frontend section is gracefully hidden.*

---

## 4. Verification & Testing

The theme has been tested across 375px, 768px, 1024px, and 1440px viewports:
- [x] Header search, live suggestions dropdown, and Explore mega menu work seamlessly.
- [x] Mobile drawer slides in with accordions and accessible tap targets $\ge$ 44px.
- [x] Category filters (price slider, duration, rating, free cancellation, amenities) and sorting update via AJAX with skeleton loading states.
- [x] Single experience gallery thumbnails, lightbox, sticky tabs, and sticky desktop booking box / mobile bottom bar function smoothly.
- [x] My Trip planner adds/removes items, saves notes/dates, generates shareable `/trip/{code}/` links, copies to clipboard, and imports trips.
- [x] Zero PHP notices or warnings in `debug.log` and zero JavaScript console errors.
