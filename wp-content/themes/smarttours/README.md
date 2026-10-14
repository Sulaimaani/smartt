# Smart Tours (smarttours.ca) — WordPress Theme

**Canada Smart Tours** is a lightweight, high-performance, mobile-first WordPress theme designed specifically for tour operators and resellers in the Okanagan Valley, British Columbia, Canada.

It recreates the UX and layout structure of [Tourism Kelowna Experiences](https://www.tourismkelowna.com/experiences/) with a 3-level deep hierarchical experience taxonomy, full-bleed category tiles, faceted sidebar filters, interactive Leaflet/OpenStreetMap mapping, a trip builder, click/affiliate tracking, and native meta boxes (no ACF or paid plugins required).

---

## 1. Quick Installation & Setup

1. **Upload and Activate Theme:**
   - In your WordPress Admin, go to **Appearance → Themes → Add New → Upload Theme**.
   - Choose `smarttours.zip` (located in `wp-content/themes/smarttours.zip` or `htdocs/smarttours.zip`) and click **Install Now**.
   - Click **Activate**.

2. **Configure Permalinks (Crucial):**
   - Go to **Settings → Permalinks**.
   - Select **Post name** (`/%postname%/`).
   - Click **Save Changes** twice. This ensures that the custom rewrite rules for nested category URLs (`/experiences/active-outdoors/lake-activities/scuba-diving/`) and tracking links (`/go/{id}/`) are active.

3. **Automatic Demo Content & Taxonomy Tree:**
   - On theme activation, the 12-category Okanagan taxonomy tree, filter amenities, guide pages, blog posts, and demo experiences are automatically seeded.
   - To re-seed or delete demo data at any time, navigate in WP Admin to **Experiences → Demo Content**.

---

## 2. Managing Experiences & Bookings

### How to Add a New Experience
1. Go to **Experiences → Add New Experience**.
2. Enter the title (e.g. *Mission Hill Family Estate Tour & Tasting*).
3. Add a description in the main editor and an excerpt for cards.
4. Set the **Featured Image** (16:10 aspect ratio recommended).
5. In the right sidebar:
   - Check the relevant **Experience Categories** (an experience can belong to multiple categories).
   - Check the applicable **Amenities** (e.g., *Family Friendly*, *Product Tasting*, *Outdoor Patio*).
6. Fill in the **Experience Details & Booking** meta box:
   - **Location:** Street address, City (e.g. *West Kelowna*), Postal Code.
   - **Coordinates:** Latitude and Longitude (e.g. `49.8354`, `-119.5841`) for interactive Leaflet maps and "Near Me" distance sorting.
   - **Pricing & Duration:** e.g. Price from `$65` CAD, Duration `90 mins`.
   - **Booking Configuration:**
     - `none` — Displays a direct **VISIT WEBSITE** button linking to the official site.
     - `affiliate_link` / `external_link` — Displays a **BOOK NOW** button routed through `/go/{experience_id}/` (tracked in database, logged in Click Reports, and 302 redirected with `rel="nofollow sponsored"`).
     - `booking_widget` — Displays a **BOOK NOW** button that smoothly scrolls down to an embedded booking widget section.
   - **Booking Widget Code:** Paste your embed snippet or iframe from **Bókun, Rezdy, Viator, or GetYourGuide**.
   - **Gallery Images:** Comma-separated image URLs or media IDs for the hero lightbox carousel.

---

## 3. Key Theme Features

- **Header & Navigation:**
  - Top white bar: Bold text logo `CANADA SMART TOURS`, Kelowna live weather placeholder (`Kelowna 1°C`), `My Trip` counter badge, and search modal trigger.
  - Light blue navigation bar (`#ADD8E6`): `EXPERIENCES`, `EXPLORE`, `PLAN`, `STAY`, `EVENTS`.
  - **Desktop Mega Menu:** Hovering over `EXPERIENCES` displays all 12 main categories and their sub-categories in columns.
  - **Mobile Accordion Navigation:** Mobile hamburger button opens a full-screen slide-in drawer with collapsible accordion subcategories and quick access to saved trips.

- **Experiences Landing Page (`/experiences/`):**
  - Full-width hero image with breadcrumbs and introductory copy.
  - 12 category tiles grid (4 columns desktop, 2 tablet, 1 mobile): full-bleed photos with dark overlay, yellow ring icon, and bold condensed uppercase typography.
  - `#EXPLORETHEOKANAGAN BLOG` section featuring the 3 latest outdoor and wine stories.

- **Category Archives (`taxonomy-experience_category.php`):**
  - Nested category paths: `/experiences/{category}/{sub-category}/{sub-sub-category}/`.
  - Child category tiles and linked guide pages / blog posts.
  - **Sidebar:** Keyword search input with yellow search button, amenities checkboxes with live counts, and "CLEAR FILTERS".
  - **Toolbar:** Grid / List view toggle, sorting (Recommended, A–Z, Near Me via browser geolocation), and "SHOW MAP" Leaflet interactive map toggle.
  - **AJAX Faceted Filtering:** Instant filtering without page reload; shareable URL query parameters (`?keyword=...&amenities=...&sort=...`).
  - **Quickview Modal:** Click "QUICKVIEW" on any card to preview experience highlights without leaving the page.

- **Single Experience Page (`single-experience.php`):**
  - Beige top hero (`#E8E1D3`) with a decorative circle background accent.
  - Lightbox photo gallery with `1/5` counter, "Add to Trip" heart button, and native Web Share API trigger.
  - Dynamic booking button logic based on `booking_type`.
  - Address with pin, phone link, price from, duration, social icon circles, rich description, amenities badge pills, interactive Leaflet map, and "YOU MAY ALSO LIKE" related cards.

- **Trip Builder ("My Trip"):**
  - Heart buttons store experience IDs in `localStorage`.
  - Dedicated `/my-trip/` page displays saved experiences with remove buttons and an interactive Leaflet route map showing all saved stops.

- **Click Tracking & Affiliate Redirects:**
  - Tracked redirects via `/go/{experience_id}/`.
  - Records timestamp, experience ID, referrer, user agent, IP address, and destination in `wp_st_clicks`.
  - **Click Reports Admin:** View clicks per experience, filter by date range (Today, 7 days, 30 days, All time), and export to CSV.

---

## 4. Verification & Testing Checklist

| Test Item | Status | Verified Details |
|---|:---:|---|
| **Category Permalinks** | Passed | Tested 1-level, 2-level, and 3-level URLs (e.g. `/experiences/active-outdoors/lake-activities/scuba-diving/`) — HTTP 200 |
| **Single Experience Permalinks** | Passed | Nested permalink `/experiences/food-drink/restaurants/winery-restaurants/mission-hill-.../` — HTTP 200 |
| **Desktop Mega Menu (1440px)** | Passed | 4-column mega menu displaying all 12 categories and subcategories |
| **Mobile Drawer (375px)** | Passed | Slide-in drawer with accordion navigation and tap targets >= 44px |
| **AJAX Filters & Faceted Search** | Passed | Keyword search and amenities checkboxes filter cards dynamically without page reload |
| **Leaflet Map Integration** | Passed | OpenStreetMap tiles, custom marker popups, and route map on `/my-trip/` |
| **Quickview Modal** | Passed | Opens modal with experience summary, amenities, and booking button |
| **Trip Builder (localStorage)** | Passed | Heart buttons toggle items, header counter increments, `/my-trip/` plots saved markers |
| **Click Tracking `/go/` Route** | Passed | Returns 302 redirect with `rel="nofollow sponsored"` and logs to `wp_st_clicks` |
| **Click Reports Admin** | Passed | Viewable under `Experiences → Click Reports` with CSV export |
| **PHP & Console Cleanliness** | Passed | Zero PHP errors/warnings with `WP_DEBUG = true`; no console errors |
| **Responsiveness** | Passed | Verified at 375px, 768px, 1024px, and 1440px |

---

## 5. File Structure

```text
smarttours/
├── style.css                      # Theme header & metadata
├── functions.php                  # Theme setup, assets enqueue & hooks
├── header.php                     # Header, top bar, mega menu & mobile drawer
├── footer.php                     # Footer, newsletter, copyright & quickview modal
├── front-page.php                 # Homepage template
├── archive-experience.php         # Experiences landing page (/experiences/)
├── taxonomy-experience_category.php # Hierarchical category archive with AJAX filters
├── single-experience.php          # Single experience details & booking logic
├── page-my-trip.php               # Trip planner page (/my-trip/)
├── page.php                       # Standard guide page template
├── single.php                     # Blog post story template
├── index.php                      # Fallback index template
├── 404.php                        # Custom 404 page
├── README.md                      # Documentation & setup guide
├── screenshot.png                 # Theme preview screenshot
├── inc/
│   ├── post-types.php             # Experience CPT & custom image sizes
│   ├── taxonomies.php             # experience_category & amenity taxonomies + term meta
│   ├── permalinks.php             # Pretty nested rewrites & request parser
│   ├── meta-boxes.php             # Native custom meta boxes for experiences
│   ├── ajax-filters.php           # AJAX filter handler, Haversine sorting & Quickview
│   ├── click-tracking.php         # Custom DB table wp_st_clicks & /go/ redirect handler
│   ├── demo-seeder.php            # 12-category tree seeder, guides, blogs, leaf experiences
│   ├── schema-seo.php             # JSON-LD BreadcrumbList & TouristAttraction schema
│   └── admin-pages.php            # Click Reports page & Demo content reset tool
└── assets/
    ├── css/
    │   └── main.css               # Mobile-first CSS design system
    └── js/
        └── main.js                # Filters, Leaflet map, trip builder & UI controller
```
