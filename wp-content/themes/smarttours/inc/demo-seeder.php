<?php
/**
 * Demo Content Seeder & Category Tree Initializer
 *
 * @package SmartTours
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Seed all terms, guides, blog posts, and experiences
 */
function smarttours_seed_demo_content() {
    global $wpdb;

    // 1. Seed Amenities
    $amenities = array(
        'Family Friendly',
        'On-Site Shop',
        'Product Tasting',
        'Outdoor Patio',
        'WiFi',
        'Wheelchair Accessible',
        'Pet Friendly',
        'Parking',
        'Free Admission'
    );

    $amenity_ids = array();
    foreach ($amenities as $amenity_name) {
        $term = term_exists($amenity_name, 'amenity');
        if (!$term) {
            $inserted = wp_insert_term($amenity_name, 'amenity');
            if (!is_wp_error($inserted)) {
                $amenity_ids[$amenity_name] = $inserted['term_id'];
            }
        } else {
            $amenity_ids[$amenity_name] = is_array($term) ? $term['term_id'] : $term;
        }
    }

    // High quality Unsplash Okanagan curated landscape, wine, lake, outdoor photos
    $photos = array(
        'hero_main'   => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1920&q=80',
        'active'      => 'https://images.unsplash.com/photo-1502680390469-be75c86b636f?auto=format&fit=crop&w=800&q=80',
        'attractions' => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&w=800&q=80',
        'arts'        => 'https://images.unsplash.com/photo-1460661419201-fd4cecdf8a8b?auto=format&fit=crop&w=800&q=80',
        'wine'        => 'https://images.unsplash.com/photo-1510812431401-41d2bd2722f3?auto=format&fit=crop&w=800&q=80',
        'farms'       => 'https://images.unsplash.com/photo-1618160702438-9b02ab6515c9?auto=format&fit=crop&w=800&q=80',
        'food'        => 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=800&q=80',
        'wellness'    => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=800&q=80',
        'golf'        => 'https://images.unsplash.com/photo-1535131749006-b7f58c99034b?auto=format&fit=crop&w=800&q=80',
        'shopping'    => 'https://images.unsplash.com/photo-1472851294608-062f824d29cc?auto=format&fit=crop&w=800&q=80',
        'guided'      => 'https://images.unsplash.com/photo-1539635278303-d4002c07eae3?auto=format&fit=crop&w=800&q=80',
        'lgbtq'       => 'https://images.unsplash.com/photo-1561055657-b9e0bf0fa360?auto=format&fit=crop&w=800&q=80',
        'family'      => 'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?auto=format&fit=crop&w=800&q=80',
        'lake'        => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80',
        'biking'      => 'https://images.unsplash.com/photo-1485965120184-e220f721d03e?auto=format&fit=crop&w=800&q=80',
        'hiking'      => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=800&q=80',
        'winter'      => 'https://images.unsplash.com/photo-1482867996988-29ec3a0f1aac?auto=format&fit=crop&w=800&q=80',
        'winery'      => 'https://images.unsplash.com/photo-1506377247377-2a5b3b417ebb?auto=format&fit=crop&w=800&q=80',
        'brewery'     => 'https://images.unsplash.com/photo-1514933651103-005eec06c04b?auto=format&fit=crop&w=800&q=80',
    );

    // 2. Define Category Tree
    $tree = array(
        'Active & Outdoors' => array(
            'photo' => $photos['active'],
            'order' => 1,
            'desc'  => 'Immerse yourself in Okanagan mountain landscapes, pristine lakes, pine forests, and sunny valley trails.',
            'children' => array(
                'Beaches & Parks' => array('desc' => 'Sandy Okanagan lakeshore beaches, waterfront boardwalks, and shaded green municipal parks.'),
                'Biking' => array(
                    'desc' => 'World-class downhill bike parks, scenic shoreline trails, and paved cycling routes.',
                    'children' => array(
                        'Bike & Rental Services' => array('desc' => 'High-end e-bike rentals, mountain bikes, cruisers, and guided cycle tours.')
                    ),
                    'blogs' => array('Crawford Trails', 'Gillard Trails', 'Knox Mountain Park', 'Smith Creek Trails')
                ),
                'Birding' => array('desc' => 'Observe valley quail, blue herons, osprey, and migratory mountain birds.'),
                'Dog Friendly Activities' => array(
                    'desc' => 'Explore the sunny valley with your four-legged best friend.',
                    'blogs' => array('Dog Friendly Patios')
                ),
                'Hiking' => array('desc' => 'Pinnacle views, canyon climbs, and shaded pine forest paths overlooking Okanagan Lake.'),
                'Lake Activities' => array(
                    'desc' => 'Make a splash with boating, paddleboarding, water sports, and diving on Okanagan Lake.',
                    'children' => array(
                        'Boating & Marinas'           => array('desc' => 'Charter pontoon boats, speedboat rentals, and full-service valley marinas.'),
                        'Boat Launches'              => array('desc' => 'Public boat launch ramps with parking and vehicle trailer access.'),
                        'Kelowna Paddle Trail'        => array('desc' => 'A 27-kilometer marked shoreline paddle trail for kayaks and canoes.'),
                        'Non-Motorized Lake Rentals' => array('desc' => 'Stand-up paddleboards, pedal boats, single and tandem kayaks.'),
                        'Scuba Diving'               => array('desc' => 'Freshwater dive sites, sunken artifacts, and underwater lake drop-offs.')
                    )
                ),
                'Motorbiking' => array('desc' => 'Scenic highway tours and backcountry dirt trail networks across the mountains.'),
                'Myra Canyon Trestles' => array('desc' => 'Walk or cycle across 18 historic wooden trestle bridges suspended high above Myra Canyon.'),
                'Okanagan Rail Trail' => array('desc' => 'A flat 50-kilometer multi-use trail tracing the shores of Wood Lake and Kalamalka Lake.'),
                'Rock Climbing & Bouldering' => array('desc' => 'Granite crags and volcanic rock towers at the Boulderfields and Skaha Bluffs.'),
                'Winter Activities' => array(
                    'desc' => 'Champagne powder snow, alpine skiing, and serene mountain snowshoeing.',
                    'children' => array(
                        'Winter Hiking & Snowshoeing' => array('desc' => 'Snowshoe trails weaving through peaceful snowy pine forests.'),
                        'Nordic Skiing'               => array('desc' => 'Groomed cross-country ski trails at Telemark and Sovereign Lake.'),
                        'Ski Resorts'                 => array('desc' => 'World-renowned ski resorts including Big White, SilverStar, and Apex.')
                    )
                )
            )
        ),
        'Attractions' => array(
            'photo' => $photos['attractions'],
            'order' => 2,
            'desc'  => 'Iconic Okanagan landmarks, adventure parks, historic trestles, and scenic lookouts.',
        ),
        'Arts & Entertainment' => array(
            'photo' => $photos['arts'],
            'order' => 3,
            'desc'  => 'Celebrate vibrant Okanagan culture, theatre, live music, galleries, and sports.',
            'children' => array(
                'Cultural District'        => array('desc' => 'Kelowna’s vibrant cultural district filled with theaters and public art.'),
                'Casinos'                  => array('desc' => 'Exciting gaming, live tables, and entertainment lounges.'),
                'Festivals & Associations' => array('desc' => 'Annual community festivals, outdoor concerts, and seasonal celebrations.'),
                'Galleries & Studios'      => array('desc' => 'Local visual art, pottery, sculptures, and Indigenous artisan exhibitions.'),
                'Movie Theatres'           => array('desc' => 'IMAX, indie film houses, and luxury cinema seating.'),
                'Museums'                  => array('desc' => 'Okanagan heritage, orchard history, and military museums.'),
                'Performing Arts'          => array('desc' => 'Ballet, symphony orchestra, and live theatrical performances.'),
                'Sports Teams'             => array('desc' => 'Cheer on the Kelowna Rockets WHL hockey and valley baseball.')
            )
        ),
        'Wine Touring' => array(
            'photo' => $photos['wine'],
            'order' => 4,
            'desc'  => 'Sip and savour award-winning wines across world-famous vineyard benches and estates.',
            'children' => array(
                'All Wineries'            => array('desc' => 'Explore the complete directory of Okanagan wineries and estate tastings.'),
                'Guided Wine Tours'       => array('desc' => 'Luxury van, bus, and chauffeured sommelier-guided tasting excursions.'),
                'Winter in Wine Country'  => array('desc' => 'Cosy vineyard firesides, holiday light-ups, and cool-climate icewine tastings.'),
                'Wine Events'             => array('desc' => 'Vineyard dinners, wine release parties, and Okanagan Wine Festivals.')
            ),
            'blogs'  => array('Dog Friendly Wineries'),
            'guides' => array('Wine Touring 101')
        ),
        'Farms, Markets, Orchards' => array(
            'photo' => $photos['farms'],
            'order' => 5,
            'desc'  => 'Fresh Okanagan fruit stands, cherry orchards, honey farms, and bustling farmers markets.',
            'guides' => array('U-Pick 101', 'Blossom & Harvest Dates')
        ),
        'Food & Drink' => array(
            'photo' => $photos['food'],
            'order' => 6,
            'desc'  => 'Farm-to-table culinary brilliance, craft breweries, cideries, and panoramic patio dining.',
            'children' => array(
                'Restaurants' => array(
                    'desc' => 'Fine dining, lakeside bistros, and authentic casual valley eateries.',
                    'children' => array(
                        'Coffee Shops & Bakeries' => array('desc' => 'Artisanal roasters, sourdough bakeries, and cosy morning cafes.'),
                        'Winery Restaurants'      => array('desc' => 'Fine dining situated right among lush vineyard vines.')
                    )
                ),
                'Craft Beverages' => array(
                    'desc' => 'Locally brewed beer, crisp fruit cider, and distilled spirits.',
                    'children' => array(
                        'Breweries'   => array('desc' => 'Craft brewpubs in Kelowna’s North End brewery district.'),
                        'Cideries'    => array('desc' => 'Crisp craft cider made from authentic Okanagan heritage apples.'),
                        'Distilleries'=> array('desc' => 'Small-batch artisanal gin, single-malt whisky, and botanical vodka.')
                    )
                )
            ),
            'blogs' => array('Dog Friendly Patios')
        ),
        'Health & Wellness' => array(
            'photo' => $photos['wellness'],
            'order' => 7,
            'desc'  => 'Recharge your spirit at luxury vineyard spas, lakeside yoga studios, and natural thermal springs.'
        ),
        'Golf' => array(
            'photo' => $photos['golf'],
            'order' => 8,
            'desc'  => 'Pristine championship greens flanked by ponderosa pines and shimmering lake vistas.',
            'children' => array(
                'Golf Courses' => array('desc' => 'Championship 18-hole courses designed by world legends.'),
                'Golf Packages' => array(
                    'desc' => 'Stay and play packages pairing luxury lodging with green fees.',
                    'children' => array(
                        'Build Your Own Golf Package' => array('desc' => 'Custom golf itineraries customized to your group handicap and taste.')
                    )
                )
            )
        ),
        'Shopping' => array(
            'photo' => $photos['shopping'],
            'order' => 9,
            'desc'  => 'Chic boutique shopping on Bernard Avenue, artisan markets, and regional fashion malls.'
        ),
        'Guided Tours' => array(
            'photo' => $photos['guided'],
            'order' => 10,
            'desc'  => 'Expert local guides leading helicopter tours, culinary walks, wildlife safaris, and lake charters.'
        ),
        '2SLGBTQIA+' => array(
            'photo' => $photos['lgbtq'],
            'order' => 11,
            'desc'  => 'Inclusive, welcoming Okanagan experiences, pride celebrations, and queer-friendly spaces.',
            'children' => array(
                'Activities'     => array('desc' => 'Inclusive outdoor group outings, boat rides, and social gatherings.'),
                'Accommodations' => array('desc' => 'Certified rainbow-registered hotels, resorts, and charming B&Bs.'),
                'Wine & Food'    => array('desc' => 'Queer-owned and proudly allied wineries, bistros, and cocktail bars.'),
                'Events'         => array('desc' => 'Kelowna Pride festival, drag brunches, and valley social mixers.')
            )
        ),
        'Family Friendly' => array(
            'photo' => $photos['family'],
            'order' => 12,
            'desc'  => 'Unforgettable kid-approved memories: kangaroo farms, waterparks, fruit picking, and easy nature walks.'
        )
    );

    // Track created term IDs: 'path/to/slug' => term_id
    $term_map = array();
    $leaf_terms = array();

    // Helper to insert or get a term
    $insert_category = function($name, $parent_id, $data, $full_path) use (&$term_map, &$leaf_terms, &$insert_category, $photos) {
        $slug = sanitize_title($name);
        $existing = term_exists($name, 'experience_category', $parent_id);

        if (!$existing) {
            $inserted = wp_insert_term($name, 'experience_category', array(
                'parent'      => $parent_id,
                'slug'        => $slug,
                'description' => isset($data['desc']) ? $data['desc'] : ''
            ));
            $term_id = !is_wp_error($inserted) ? $inserted['term_id'] : 0;
        } else {
            $term_id = is_array($existing) ? $existing['term_id'] : $existing;
            if (isset($data['desc'])) {
                wp_update_term($term_id, 'experience_category', array('description' => $data['desc']));
            }
        }

        if ($term_id) {
            $term_map[$full_path] = $term_id;
            // Set image meta
            $photo = isset($data['photo']) ? $data['photo'] : $photos['hero_main'];
            update_term_meta($term_id, 'st_hero_image', $photo);
            update_term_meta($term_id, 'st_tile_image', $photo);
            if (isset($data['order'])) {
                update_term_meta($term_id, 'st_display_order', intval($data['order']));
            }

            // Recurse children
            if (isset($data['children']) && is_array($data['children'])) {
                foreach ($data['children'] as $child_name => $child_data) {
                    $insert_category($child_name, $term_id, $child_data, $full_path . '/' . sanitize_title($child_name));
                }
            } else {
                $leaf_terms[$full_path] = array(
                    'term_id' => $term_id,
                    'name'    => $name,
                    'desc'    => isset($data['desc']) ? $data['desc'] : ''
                );
            }
        }
        return $term_id;
    };

    // Execute tree insertion
    foreach ($tree as $main_name => $main_data) {
        $insert_category($main_name, 0, $main_data, sanitize_title($main_name));
    }

    // 3. Create (guide) Pages
    $guides = array(
        'Wine Touring 101' => array(
            'parent_slug' => 'wine-touring',
            'content'     => '<p>Welcome to Wine Touring 101 in the Okanagan Valley. The valley features over 200 licensed wineries spanning from Vernon through Kelowna, West Kelowna, Peachland, Summerland, Naramata, and Oliver-Osoyoos.</p><h3>When to Visit</h3><p>The peak season runs from May through October when vineyard patios are basked in summer sunshine. Spring brings bud break and barrel tastings, while harvest in September and October fills the cellar air with the heady aroma of fermenting grapes.</p><h3>Tasting Etiquette</h3><ul><li>Book tastings ahead during peak weekends.</li><li>Stay hydrated with valley spring water.</li><li>Designate a driver or book a certified guided luxury tour.</li></ul>'
        ),
        'U-Pick 101' => array(
            'parent_slug' => 'farms-markets-orchards',
            'content'     => '<p>Nothing matches the taste of sun-ripened Okanagan cherries, peaches, and berries straight from the branch. Here is your definitive guide to picking fruit like a local.</p><h3>What to Bring</h3><p>Wear closed-toe shoes, sun hats, and bring sturdy reusable containers. Many family orchards provide picking buckets and weigh stations.</p>'
        ),
        'Blossom & Harvest Dates' => array(
            'parent_slug' => 'farms-markets-orchards',
            'content'     => '<p>Timing your trip to the Okanagan bloom and harvest cycle makes for an unforgettable scenic getaway.</p><h3>Spring Blossoms (Late April - May)</h3><p>Apple, pear, apricot, and cherry trees blanket the rolling valley hills in pastel white and pink blossoms.</p><h3>Summer & Fall Harvests</h3><ul><li>Cherries: Late June to Early August</li><li>Peaches & Apricots: July to August</li><li>Apples & Pears: August to October</li><li>Wine Grapes: September to November</li></ul>'
        )
    );

    foreach ($guides as $guide_title => $guide_data) {
        $existing_page = get_page_by_title($guide_title);
        $parent_term_id = isset($term_map[$guide_data['parent_slug']]) ? $term_map[$guide_data['parent_slug']] : 0;

        if (!$existing_page) {
            $page_id = wp_insert_post(array(
                'post_title'   => $guide_title,
                'post_content' => $guide_data['content'],
                'post_status'  => 'publish',
                'post_type'    => 'page',
            ));
            if ($page_id && !is_wp_error($page_id)) {
                update_post_meta($page_id, '_st_demo', 1);
                update_post_meta($page_id, '_st_parent_term_id', $parent_term_id);
            }
        }
    }

    // 4. Create (blog) Posts
    $blogs = array(
        'Crawford Trails' => array(
            'parent_slug' => 'active-outdoors/biking',
            'excerpt'     => 'A premier mountain biking and trail running network in South Kelowna with flow lines and technical rock drops.',
            'content'     => '<p>The Crawford Trails (Myra-Bellevue Provincial Park) provide more than 100 kilometers of singletrack trails winding through ponderosa pines, rocky bluffs, and scenic canyon edges.</p><p>Trails range from smooth beginner cross-country routes to heart-pumping expert descents like Hilltown and Vapour.</p>'
        ),
        'Gillard Trails' => array(
            'parent_slug' => 'active-outdoors/biking',
            'excerpt'     => 'Famous for steep, technical downhill mountain biking lines, wooden features, and adrenaline-pumping gap jumps.',
            'content'     => '<p>Gillard is the Okanagan’s freeride heartland. Maintained by passionate local trailbuilders, it caters to intermediate and advanced riders seeking steep rock faces, ladders, and technical descents.</p>'
        ),
        'Knox Mountain Park' => array(
            'parent_slug' => 'active-outdoors/biking',
            'excerpt'     => 'Kelowna’s premier natural landmark offering panoramic vistas of Okanagan Lake and diverse multi-use trails.',
            'content'     => '<p>Soaring right above downtown Kelowna, Knox Mountain Park is a favourite for walkers, runners, and cyclists. The Apex Trail climbs to the summit pavilion, delivering 360-degree views across the entire lake basin.</p>'
        ),
        'Smith Creek Trails' => array(
            'parent_slug' => 'active-outdoors/biking',
            'excerpt'     => 'West Kelowna’s mountain biking jewel featuring fast, flowy descents and panoramic valley viewpoints.',
            'content'     => '<p>Located on the slopes of Mount Boucherie in West Kelowna, Smith Creek offers a purpose-built climbing trail that takes you to thrilling berm-filled descents like Holy Pail and Feel the Love.</p>'
        ),
        'Dog Friendly Patios' => array(
            'parent_slug' => 'active-outdoors/dog-friendly-activities',
            'excerpt'     => 'Discover the sunniest dog-welcoming brewery, winery, and cafe patios across Kelowna and West Kelowna.',
            'content'     => '<p>There is nothing better than relaxing with a refreshing craft beverage while your pup lounges beside your patio table. The Okanagan boasts numerous pet-friendly venues that supply fresh water bowls and homemade dog treats.</p>'
        ),
        'Dog Friendly Wineries' => array(
            'parent_slug' => 'wine-touring',
            'excerpt'     => 'Wineries where your pup can stroll through lush picnic grounds while you enjoy award-winning Pinot Noir.',
            'content'     => '<p>From Lake Country to the Westside Wine Trail, several renowned estates roll out the welcome mat for furry companions. Take a relaxing self-guided tour of scenic grounds with your dog on leash.</p>'
        )
    );

    foreach ($blogs as $blog_title => $blog_data) {
        $existing_post = get_page_by_title($blog_title, OBJECT, 'post');
        $parent_term_id = isset($term_map[$blog_data['parent_slug']]) ? $term_map[$blog_data['parent_slug']] : 0;

        if (!$existing_post) {
            $post_id = wp_insert_post(array(
                'post_title'   => $blog_title,
                'post_excerpt' => $blog_data['excerpt'],
                'post_content' => $blog_data['content'],
                'post_status'  => 'publish',
                'post_type'    => 'post',
            ));
            if ($post_id && !is_wp_error($post_id)) {
                update_post_meta($post_id, '_st_demo', 1);
                update_post_meta($post_id, '_st_parent_term_id', $parent_term_id);
            }
        }
    }

    // 5. Create "My Trip" page
    $my_trip_page = get_page_by_path('my-trip');
    if (!$my_trip_page) {
        $trip_page_id = wp_insert_post(array(
            'post_title'   => 'My Trip',
            'post_name'    => 'my-trip',
            'post_content' => '<!-- Saved Experiences loaded via JS -->',
            'post_status'  => 'publish',
            'post_type'    => 'page',
        ));
        update_post_meta($trip_page_id, '_st_demo', 1);
    }

    // 6. Realistic leaf experiences
    $demo_experiences_templates = array(
        array(
            'title'        => 'Mission Hill Family Estate Tour & Sommelier Tasting',
            'cat_paths'    => array('wine-touring/all-wineries', 'food-drink/restaurants/winery-restaurants'),
            'address'      => '1730 Mission Hill Rd',
            'city'         => 'West Kelowna',
            'postal_code'  => 'V4T 2E4',
            'lat'          => 49.8354,
            'lng'          => -119.5841,
            'phone'        => '(250) 768-7604',
            'website'      => 'https://www.missionhillwinery.com',
            'price_from'   => '65',
            'duration'     => '90 mins',
            'booking_type' => 'booking_widget',
            'booking_widget_code' => '<div style="background:#f4f4f4; padding:20px; border:2px dashed #0073aa; border-radius:8px; text-align:center;"><p><strong>[Live Bókun Booking Widget Simulation]</strong></p><p>Select Date & Time for Sommelier Tasting:</p><input type="date" value="2026-06-15" style="padding:8px; margin-right:10px;"/><button type="button" style="padding:10px 20px; background:#0073aa; color:#fff; border:none; border-radius:4px; font-weight:bold; cursor:pointer;" onclick="alert(\'Booking reservation simulation completed!\')">Reserve Seats</button></div>',
            'amenities'    => array('Product Tasting', 'Outdoor Patio', 'On-Site Shop', 'Parking', 'WiFi'),
            'photo'        => 'https://images.unsplash.com/photo-1506377247377-2a5b3b417ebb?auto=format&fit=crop&w=800&q=80',
            'gallery'      => 'https://images.unsplash.com/photo-1510812431401-41d2bd2722f3?auto=format&fit=crop&w=800&q=80, https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=800&q=80, https://images.unsplash.com/photo-1502680390469-be75c86b636f?auto=format&fit=crop&w=800&q=80',
            'excerpt'      => 'Discover the architectural wonder of Mission Hill, subterranean barrel cellars, and exquisite estate reserve vintages.',
            'content'      => '<p>Perched high atop Mount Boucherie with commanding vistas over Okanagan Lake, Mission Hill Family Estate is an international monument to viticulture and design.</p><p>This immersive sommelier-guided tasting includes an architectural stroll through the bell tower courtyard, access to the underground barrel cellar carved into volcanic rock, and a curated pairing of four premium reserve wines.</p>'
        ),
        array(
            'title'        => 'CedarCreek Estate Organic Wine & Vineyard Patio Tasting',
            'cat_paths'    => array('wine-touring/all-wineries', 'food-drink/restaurants/winery-restaurants'),
            'address'      => '5445 Lakeshore Rd',
            'city'         => 'Kelowna',
            'postal_code'  => 'V1W 4S5',
            'lat'          => 49.8052,
            'lng'          => -119.5023,
            'phone'        => '(778) 738-1020',
            'website'      => 'https://www.cedarcreek.bc.ca',
            'price_from'   => '45',
            'duration'     => '60 mins',
            'booking_type' => 'affiliate_link',
            'booking_url'  => 'https://www.viator.com/tours/Kelowna/CedarCreek-Wine-Tour/d50156',
            'amenities'    => array('Product Tasting', 'Outdoor Patio', 'Wheelchair Accessible', 'Parking'),
            'photo'        => 'https://images.unsplash.com/photo-1510812431401-41d2bd2722f3?auto=format&fit=crop&w=800&q=80',
            'gallery'      => 'https://images.unsplash.com/photo-1506377247377-2a5b3b417ebb?auto=format&fit=crop&w=800&q=80, https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=800&q=80',
            'excerpt'      => 'Certified organic farming, contemporary lakeside dining, and benchmark North Okanagan Pinot Noir and Chardonnay.',
            'content'      => '<p>CedarCreek is dedicated to 100% organic viticulture. Sit along the open glass pavilion overlooking tranquil waters while sampling small-lot single vineyard releases paired with artisanal valley bites.</p>'
        ),
        array(
            'title'        => 'Myra Canyon Trestles Historic Bike & E-Bike Rental',
            'cat_paths'    => array('active-outdoors/myra-canyon-trestles', 'active-outdoors/biking/bike-rental-services'),
            'address'      => '4429 June Springs Rd',
            'city'         => 'Kelowna',
            'postal_code'  => 'V1W 4E8',
            'lat'          => 49.8001,
            'lng'          => -119.3105,
            'phone'        => '(250) 878-8767',
            'website'      => 'https://www.myracanyonrentals.com',
            'price_from'   => '49',
            'duration'     => 'Half Day',
            'booking_type' => 'booking_widget',
            'booking_widget_code' => '<div style="background:#f9f9f9; padding:20px; border:2px solid #F5EE3A; border-radius:8px; text-align:center;"><p><strong>[Live Rezdy Booking Widget]</strong></p><label>Select Bike Type:</label><select style="margin:10px; padding:6px;"><option>Comfort Cruiser E-Bike</option><option>Standard Mountain Bike</option><option>Kids Bike / Trailer</option></select><button type="button" style="padding:10px 20px; background:#000; color:#fff; border:none; font-weight:bold;" onclick="alert(\'E-Bike reservation confirmed!\')">Book Now</button></div>',
            'amenities'    => array('Family Friendly', 'Parking', 'Pet Friendly', 'Free Admission'),
            'photo'        => 'https://images.unsplash.com/photo-1485965120184-e220f721d03e?auto=format&fit=crop&w=800&q=80',
            'gallery'      => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=800&q=80, https://images.unsplash.com/photo-1502680390469-be75c86b636f?auto=format&fit=crop&w=800&q=80',
            'excerpt'      => 'Ride high over 18 historic Kettle Valley Railway trestles with breathtaking panoramic views across Myra Canyon.',
            'content'      => '<p>Experience one of British Columbia’s premier natural engineering marvels. Rent an electric cruiser bike right at the canyon trailhead and pedal effortlessly across two stone tunnels and 18 restored wooden trestles suspended hundreds of feet above the canyon floor.</p>'
        ),
        array(
            'title'        => 'Okanagan Lake Luxury Pontoon Boat & Wakeboard Charter',
            'cat_paths'    => array('active-outdoors/lake-activities/boating-marinas', 'active-outdoors/lake-activities/non-motorized-lake-rentals'),
            'address'      => '1310 Water St (Downtown Marina)',
            'city'         => 'Kelowna',
            'postal_code'  => 'V1Y 9P3',
            'lat'          => 49.8865,
            'lng'          => -119.4975,
            'phone'        => '(250) 763-5599',
            'website'      => 'https://www.kelownadowntownmarina.com',
            'price_from'   => '180',
            'duration'     => '2 Hours',
            'booking_type' => 'external_link',
            'booking_url'  => 'https://www.kelownadowntownmarina.com/boat-rentals',
            'amenities'    => array('Family Friendly', 'Parking', 'WiFi', 'Wheelchair Accessible'),
            'photo'        => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80',
            'gallery'      => 'https://images.unsplash.com/photo-1502680390469-be75c86b636f?auto=format&fit=crop&w=800&q=80',
            'excerpt'      => 'Cruise crystal-clear Okanagan Lake in luxury. Captained pontoon charters, tubing, and private sunset cruises.',
            'content'      => '<p>Take in stunning valley panoramas from the water. Whether relaxing on cushioned lounger seating with a glass of valley wine or carving waves on wakeboards, our brand-new Avalon luxury pontoons make every lake day unforgettable.</p>'
        ),
        array(
            'title'        => 'Kelowna Paddle Trail Kayak & SUP Shoreline Excursion',
            'cat_paths'    => array('active-outdoors/lake-activities/kelowna-paddle-trail', 'active-outdoors/lake-activities/non-motorized-lake-rentals'),
            'address'      => '1600 Abbott St (City Park)',
            'city'         => 'Kelowna',
            'postal_code'  => 'V1Y 1B7',
            'lat'          => 49.8831,
            'lng'          => -119.4990,
            'phone'        => '(250) 868-9273',
            'website'      => 'https://www.okanaganpaddle.com',
            'price_from'   => '35',
            'duration'     => '2 Hours',
            'booking_type' => 'affiliate_link',
            'booking_url'  => 'https://www.getyourguide.com/kelowna-l501/paddle-trail-kayak-t123',
            'amenities'    => array('Family Friendly', 'Outdoor Patio', 'Pet Friendly', 'Free Admission'),
            'photo'        => 'https://images.unsplash.com/photo-1502680390469-be75c86b636f?auto=format&fit=crop&w=800&q=80',
            'gallery'      => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80',
            'excerpt'      => 'Glide along 27 km of calm designated shoreline paddle trail with views of floating bridges, beaches, and bird sanctuaries.',
            'content'      => '<p>The Kelowna Paddle Trail stretches along the eastern shoreline of Okanagan Lake. Launch directly from sandy City Park Beach and paddle through calm bays, past pristine waterfront properties and quiet lakeside bird sanctuaries.</p>'
        ),
        array(
            'title'        => 'Big White Ski Resort Champagne Powder Guided Mountain Tour',
            'cat_paths'    => array('active-outdoors/winter-activities/ski-resorts', 'guided-tours'),
            'address'      => '5315 Big White Rd',
            'city'         => 'Big White Mountain',
            'postal_code'  => 'V1X 4K8',
            'lat'          => 49.7212,
            'lng'          => -118.9287,
            'phone'        => '(250) 769-3344',
            'website'      => 'https://www.bigwhite.com',
            'price_from'   => '125',
            'duration'     => 'Full Day',
            'booking_type' => 'booking_widget',
            'booking_widget_code' => '<div style="background:#eef6fc; padding:20px; border:2px solid #0073aa; border-radius:8px; text-align:center;"><p><strong>[Big White Direct Lift Ticket & Tour Widget]</strong></p><button type="button" style="padding:10px 25px; background:#0073aa; color:#fff; border:none; font-weight:bold; cursor:pointer;" onclick="alert(\'Lift Pass Reserved!\')">Select Ski Tour Date</button></div>',
            'amenities'    => array('Family Friendly', 'Parking', 'WiFi', 'On-Site Shop'),
            'photo'        => 'https://images.unsplash.com/photo-1482867996988-29ec3a0f1aac?auto=format&fit=crop&w=800&q=80',
            'gallery'      => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=800&q=80',
            'excerpt'      => 'Experience legendary Okanagan champagne powder snow and iconic snow ghosts on Western Canada’s premier ski mountain.',
            'content'      => '<p>Canada’s favourite family ski resort boasts 16 lifts, 119 designated trails, and more than 750cm of light, dry powder annually. Join a mountain ambassador for an insider tour of the best alpine bowls and tree runs.</p>'
        ),
        array(
            'title'        => 'North End Brewery District Craft Tasting & Brewery Crawl',
            'cat_paths'    => array('food-drink/craft-beverages/breweries', 'food-drink/restaurants'),
            'address'      => '890 Clement Ave',
            'city'         => 'Kelowna',
            'postal_code'  => 'V1Y 7E4',
            'lat'          => 49.8970,
            'lng'          => -119.4880,
            'phone'        => '(250) 860-2739',
            'website'      => 'https://www.kelownabeertours.com',
            'price_from'   => '79',
            'duration'     => '3 Hours',
            'booking_type' => 'affiliate_link',
            'booking_url'  => 'https://www.viator.com/tours/Kelowna/Brewery-Crawl/d50156',
            'amenities'    => array('Product Tasting', 'Outdoor Patio', 'Pet Friendly', 'WiFi', 'On-Site Shop'),
            'photo'        => 'https://images.unsplash.com/photo-1514933651103-005eec06c04b?auto=format&fit=crop&w=800&q=80',
            'gallery'      => 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=800&q=80',
            'excerpt'      => 'Sample hazy IPAs, fruited sours, and wood-fired pizzas across Kelowna’s lively, walkable North End brewery row.',
            'content'      => '<p>Kelowna’s North End is packed with more than a dozen artisanal breweries within three walkable blocks. Enjoy behind-the-scenes brewhouse tours, meet brewmasters, and taste fresh pours paired with savory culinary bites.</p>'
        ),
        array(
            'title'        => 'Harvest Golf Club Championship 18-Hole Round & Dining',
            'cat_paths'    => array('golf/golf-courses', 'golf/golf-packages/build-your-own-golf-package'),
            'address'      => '2725 K. L. O. Rd',
            'city'         => 'Kelowna',
            'postal_code'  => 'V1W 4S1',
            'lat'          => 49.8520,
            'lng'          => -119.4350,
            'phone'        => '(250) 862-3101',
            'website'      => 'https://www.harvestgolf.com',
            'price_from'   => '160',
            'duration'     => '4.5 Hours',
            'booking_type' => 'none',
            'amenities'    => array('Outdoor Patio', 'Parking', 'WiFi', 'Wheelchair Accessible'),
            'photo'        => 'https://images.unsplash.com/photo-1535131749006-b7f58c99034b?auto=format&fit=crop&w=800&q=80',
            'gallery'      => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80',
            'excerpt'      => 'A championship layout carved through working fruit orchards, with scenic lake horizons and bentgrass greens.',
            'content'      => '<p>The Harvest Golf Club offers wide, manicured fairways set amidst 1,000 mature apple and fruit trees. Spectacular Okanagan Lake views accompany every hole, followed by patio dining at the Harvest Grille.</p>'
        ),
        array(
            'title'        => 'Kangaroo Creek Farm Interactive Family Experience',
            'cat_paths'    => array('family-friendly', 'attractions'),
            'address'      => '5932 Old Vernon Rd',
            'city'         => 'Kelowna',
            'postal_code'  => 'V1X 7V2',
            'lat'          => 49.9320,
            'lng'          => -119.3820,
            'phone'        => '(250) 766-5590',
            'website'      => 'https://www.kangaroocreekfarm.com',
            'price_from'   => '16',
            'duration'     => '2 Hours',
            'booking_type' => 'none',
            'amenities'    => array('Family Friendly', 'Parking', 'Wheelchair Accessible', 'On-Site Shop'),
            'photo'        => 'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?auto=format&fit=crop&w=800&q=80',
            'gallery'      => 'https://images.unsplash.com/photo-1618160702438-9b02ab6515c9?auto=format&fit=crop&w=800&q=80',
            'excerpt'      => 'Pet friendly red kangaroos, hold baby joeys, and meet capybaras, wallabies, and sugar gliders.',
            'content'      => '<p>A beloved Okanagan family tradition. Walk right among free-roaming adult red and grey kangaroos, gently pet baby joeys in their pouches, and interact with exotic animals in spacious outdoor paddocks.</p>'
        ),
        array(
            'title'        => 'Okanagan Lavender & Herb Farm Botanical Tour',
            'cat_paths'    => array('farms-markets-orchards', 'attractions'),
            'address'      => '4380 Takimy Rd',
            'city'         => 'Kelowna',
            'postal_code'  => 'V1W 4C2',
            'lat'          => 49.8240,
            'lng'          => -119.4670,
            'phone'        => '(250) 764-7759',
            'website'      => 'https://www.okanaganlavender.com',
            'price_from'   => '20',
            'duration'     => '75 mins',
            'booking_type' => 'external_link',
            'booking_url'  => 'https://www.okanaganlavender.com/workshops',
            'amenities'    => array('Family Friendly', 'On-Site Shop', 'Outdoor Patio', 'Parking'),
            'photo'        => 'https://images.unsplash.com/photo-1618160702438-9b02ab6515c9?auto=format&fit=crop&w=800&q=80',
            'gallery'      => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=800&q=80',
            'excerpt'      => 'Walk among fragrant purple lavender blooms, copper distillation workshops, and panoramic valley views.',
            'content'      => '<p>Stroll through certified pesticide-free aromatic lavender and botanical herb plantings. Learn the ancient art of essential oil distillation using traditional alembic copper stills and pick up handcrafted valley skincare products.</p>'
        ),
        array(
            'title'        => 'Kelowna Art Gallery & Cultural District Walking Tour',
            'cat_paths'    => array('arts-entertainment/cultural-district', 'arts-entertainment/galleries-studios'),
            'address'      => '1315 Water St',
            'city'         => 'Kelowna',
            'postal_code'  => 'V1Y 9R3',
            'lat'          => 49.8890,
            'lng'          => -119.4950,
            'phone'        => '(250) 762-2226',
            'website'      => 'https://www.kelownaartgallery.com',
            'price_from'   => '10',
            'duration'     => '90 mins',
            'booking_type' => 'none',
            'amenities'    => array('Wheelchair Accessible', 'Free Admission', 'WiFi', 'Family Friendly'),
            'photo'        => 'https://images.unsplash.com/photo-1460661419201-fd4cecdf8a8b?auto=format&fit=crop&w=800&q=80',
            'gallery'      => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&w=800&q=80',
            'excerpt'      => 'Explore contemporary visual art, rotating Canadian masterpieces, and public sculpture gardens in the heart of Kelowna.',
            'content'      => '<p>Situated in Kelowna’s downtown lakeside Cultural District, the Kelowna Art Gallery features four innovative exhibition spaces with works by regional, national, and international artists.</p>'
        ),
        array(
            'title'        => 'Summerland Waterfront Sparkling Wine & Cider Tour',
            'cat_paths'    => array('food-drink/craft-beverages/cideries', 'wine-touring/all-wineries'),
            'address'      => '13216 Bottleneck Dr',
            'city'         => 'Summerland',
            'postal_code'  => 'V0H 1Z0',
            'lat'          => 49.6010,
            'lng'          => -119.6780,
            'phone'        => '(250) 494-0455',
            'website'      => 'https://www.bottleneckdrive.com',
            'price_from'   => '55',
            'duration'     => '2 Hours',
            'booking_type' => 'affiliate_link',
            'booking_url'  => 'https://www.viator.com/tours/Summerland/Sparkling-Tour/d50156',
            'amenities'    => array('Product Tasting', 'Outdoor Patio', 'Parking', 'Pet Friendly'),
            'photo'        => 'https://images.unsplash.com/photo-1510812431401-41d2bd2722f3?auto=format&fit=crop&w=800&q=80',
            'gallery'      => 'https://images.unsplash.com/photo-1506377247377-2a5b3b417ebb?auto=format&fit=crop&w=800&q=80',
            'excerpt'      => 'Sample crisp sparkling traditional method wines and artisanal heritage ciders overlooking scenic Okanagan bluffs.',
            'content'      => '<p>Travel along the charming curves of Bottleneck Drive in Summerland. Experience heritage fruit cider pressed from century-old orchards and crisp effervescent sparkling wines made with traditional champagne techniques.</p>'
        )
    );

    // Insert experiences and link to terms
    foreach ($demo_experiences_templates as $exp) {
        $existing = get_page_by_title($exp['title'], OBJECT, 'experience');
        if (!$existing) {
            $post_id = wp_insert_post(array(
                'post_title'   => $exp['title'],
                'post_excerpt' => $exp['excerpt'],
                'post_content' => $exp['content'],
                'post_status'  => 'publish',
                'post_type'    => 'experience',
            ));

            if ($post_id && !is_wp_error($post_id)) {
                update_post_meta($post_id, '_st_demo', 1);
                update_post_meta($post_id, '_st_address', $exp['address']);
                update_post_meta($post_id, '_st_city', $exp['city']);
                update_post_meta($post_id, '_st_postal_code', $exp['postal_code']);
                update_post_meta($post_id, '_st_latitude', $exp['lat']);
                update_post_meta($post_id, '_st_longitude', $exp['lng']);
                update_post_meta($post_id, '_st_phone', $exp['phone']);
                update_post_meta($post_id, '_st_website_url', $exp['website']);
                update_post_meta($post_id, '_st_price_from', $exp['price_from']);
                update_post_meta($post_id, '_st_duration', $exp['duration']);
                update_post_meta($post_id, '_st_booking_type', $exp['booking_type']);
                if (isset($exp['booking_url'])) {
                    update_post_meta($post_id, '_st_booking_url', $exp['booking_url']);
                }
                if (isset($exp['booking_widget_code'])) {
                    update_post_meta($post_id, '_st_booking_widget_code', $exp['booking_widget_code']);
                }
                update_post_meta($post_id, '_st_gallery_ids', $exp['gallery']);
                update_post_meta($post_id, '_st_cover_image', $exp['photo']);

                // Assign categories
                $cat_ids = array();
                foreach ($exp['cat_paths'] as $cat_path) {
                    if (isset($term_map[$cat_path])) {
                        $cat_ids[] = $term_map[$cat_path];
                    }
                }
                if (!empty($cat_ids)) {
                    wp_set_object_terms($post_id, $cat_ids, 'experience_category');
                }

                // Assign amenities
                if (!empty($exp['amenities'])) {
                    $amenity_term_ids = array();
                    foreach ($exp['amenities'] as $a_name) {
                        if (isset($amenity_ids[$a_name])) {
                            $amenity_term_ids[] = $amenity_ids[$a_name];
                        }
                    }
                    if (!empty($amenity_term_ids)) {
                        wp_set_object_terms($post_id, $amenity_term_ids, 'amenity');
                    }
                }
            }
        }
    }

    // Also populate other leaf categories with 2-3 additional experiences so EVERY leaf category has experiences!
    foreach ($leaf_terms as $leaf_path => $leaf_info) {
        $term_id = $leaf_info['term_id'];
        $count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->term_relationships} WHERE term_taxonomy_id = %d", $term_id));
        if ($count < 2) {
            $needed = 2 - $count;
            for ($k = 1; $k <= $needed; $k++) {
                $unique_title = $leaf_info['name'] . ' Experience #' . $k . ' (' . $leaf_info['name'] . ')';
                $existing = get_page_by_title($unique_title, OBJECT, 'experience');
                if (!$existing) {
                    $random_lat = 49.85 + (mt_rand(-50, 150) / 1000);
                    $random_lng = -119.50 + (mt_rand(-100, 100) / 1000);
                    $post_id = wp_insert_post(array(
                        'post_title'   => $unique_title,
                        'post_excerpt' => 'Enjoy an unforgettable outing in ' . $leaf_info['name'] . ' nestled in the scenic Okanagan Valley.',
                        'post_content' => '<p>Discover ' . esc_html($leaf_info['name']) . '. This local Okanagan adventure offers exceptional valley scenery, professional hosts, and convenient access from Kelowna.</p>',
                        'post_status'  => 'publish',
                        'post_type'    => 'experience',
                    ));
                    if ($post_id && !is_wp_error($post_id)) {
                        update_post_meta($post_id, '_st_demo', 1);
                        update_post_meta($post_id, '_st_address', '100 Okanagan Trail');
                        update_post_meta($post_id, '_st_city', 'Kelowna');
                        update_post_meta($post_id, '_st_postal_code', 'V1Y 1A1');
                        update_post_meta($post_id, '_st_latitude', $random_lat);
                        update_post_meta($post_id, '_st_longitude', $random_lng);
                        update_post_meta($post_id, '_st_phone', '(250) 555-0199');
                        update_post_meta($post_id, '_st_price_from', (40 + $k * 15));
                        update_post_meta($post_id, '_st_duration', '2 Hours');
                        update_post_meta($post_id, '_st_booking_type', ($k % 2 === 0 ? 'affiliate_link' : 'none'));
                        update_post_meta($post_id, '_st_booking_url', 'https://www.viator.com/tours/Kelowna');
                        update_post_meta($post_id, '_st_cover_image', $photos['active']);
                        wp_set_object_terms($post_id, array($term_id), 'experience_category');
                        // Assign random 2-3 amenities
                        $random_amenities = array_rand($amenity_ids, 3);
                        $assigned_amenities = array();
                        foreach ($random_amenities as $am_name) {
                            $assigned_amenities[] = $amenity_ids[$am_name];
                        }
                        wp_set_object_terms($post_id, $assigned_amenities, 'amenity');
                    }
                }
            }
        }
    }

    update_option('smarttours_demo_seeded', '1');
}

/**
 * Delete all demo content
 */
function smarttours_delete_demo_content() {
    global $wpdb;
    // Find all posts and pages with _st_demo = 1
    $post_ids = $wpdb->get_col("SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_st_demo' AND meta_value = '1'");
    if (!empty($post_ids)) {
        foreach ($post_ids as $pid) {
            wp_delete_post($pid, true);
        }
    }

    delete_option('smarttours_demo_seeded');
}
