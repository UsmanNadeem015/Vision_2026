(function(){
      "use strict";

      // ---------- DUMMY JSON DATA (restaurant database) ----------
      const dummyRestaurants = [
        {
          id: 1,
          name: "Spice Symphony",
          rating: 4.8,
          address: "123 Curry Lane, Downtown",
          aiNote: "Legendary Hyderabadi biryani with aromatic saffron and tender meat — locals call it 'soul food'.",
          tags: ["biryani", "indian", "rice"],
          mapUrl: "#"
        },
        {
          id: 2,
          name: "Pizza Alchemist",
          rating: 4.6,
          address: "87 Stone Oven Ave, Midtown",
          aiNote: "Wood-fired Neapolitan pizza with 48h fermented dough. The Margherita is pure perfection.",
          tags: ["pizza", "italian", "margherita", "pepperoni"],
          mapUrl: "#"
        },
        {
          id: 3,
          name: "Burger & Beyond",
          rating: 4.5,
          address: "456 Patty Plaza, Uptown",
          aiNote: "Smash burgers with caramelized onions, secret sauce, and brioche — a messy masterpiece.",
          tags: ["burger", "american", "cheeseburger", "fries"],
          mapUrl: "#"
        },
        {
          id: 4,
          name: "Sushi Ryu",
          rating: 4.9,
          address: "22 Ocean Drive, Harbor",
          aiNote: "Omakase experience with fresh wasabi and bluefin tuna. Worth every penny.",
          tags: ["sushi", "japanese", "sashimi", "roll"],
          mapUrl: "#"
        },
        {
          id: 5,
          name: "Taco Fuego",
          rating: 4.7,
          address: "789 Calle Verde, Eastside",
          aiNote: "Al pastor tacos with pineapple & house-made salsa. Voted best street food 2025.",
          tags: ["tacos", "mexican", "burrito", "quesadilla"],
          mapUrl: "#"
        },
        {
          id: 6,
          name: "Peking Duck House",
          rating: 4.4,
          address: "33 Dynasty Blvd, Chinatown",
          aiNote: "Crispy duck pancakes & authentic dim sum. Great for groups.",
          tags: ["duck", "chinese", "dimsum", "noodles"],
          mapUrl: "#"
        }
      ];

      // DOM elements
      const searchInput = document.getElementById('searchInput');
      const searchBtn = document.getElementById('searchBtn');
      const dynamicContent = document.getElementById('dynamicContent');

      // Helper: render loading skeletons (grid)
      function renderLoading() {
        const skeletonHtml = `
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-7">
            ${Array.from({ length: 6 }).map(() => `
              <div class="card bg-[#1a1125] shadow-xl border border-purple-800/20 rounded-2xl overflow-hidden">
                <div class="card-body p-5 space-y-4">
                  <div class="skeleton-loader h-7 w-3/4 rounded-lg"></div>
                  <div class="flex items-center gap-2">
                    <div class="skeleton-loader h-4 w-24 rounded-full"></div>
                    <div class="skeleton-loader h-4 w-16 rounded-full"></div>
                  </div>
                  <div class="skeleton-loader h-4 w-full rounded"></div>
                  <div class="skeleton-loader h-12 w-full rounded-xl"></div>
                  <div class="skeleton-loader h-5 w-2/3 rounded"></div>
                  <div class="card-actions mt-2">
                    <div class="skeleton-loader h-10 w-32 rounded-xl"></div>
                  </div>
                </div>
              </div>
            `).join('')}
          </div>
        `;
        dynamicContent.innerHTML = skeletonHtml;
      }

      // Render empty state (friendly)
      function renderEmpty(message = "No matching restaurants found. Try 'pizza', 'biryani' or 'tacos' 🍽️") {
        const emptyHtml = `
          <div id="emptyState" class="flex flex-col items-center justify-center py-16 text-center fade-in">
            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-purple-900/40 to-pink-900/40 flex items-center justify-center mb-6">
              <i class="fa-solid fa-face-frown text-5xl text-pink-300/70"></i>
            </div>
            <h3 class="text-2xl font-semibold text-gray-200">Nothing here yet</h3>
            <p class="text-gray-400 max-w-md mt-2">${message}</p>
          </div>
        `;
        dynamicContent.innerHTML = emptyHtml;
      }

      // Generate star rating component (DaisyUI style)
      function renderStars(rating) {
        const fullStar = '<i class="fa-solid fa-star text-warning"></i>';
        const halfStar = '<i class="fa-solid fa-star-half-stroke text-warning"></i>';
        const emptyStar = '<i class="fa-regular fa-star text-warning/60"></i>';
        const rounded = Math.round(rating * 2) / 2; // nearest 0.5
        let starsHtml = '';
        for (let i = 1; i <= 5; i++) {
          if (i <= Math.floor(rounded)) starsHtml += fullStar;
          else if (i === Math.ceil(rounded) && rounded % 1 !== 0) starsHtml += halfStar;
          else starsHtml += emptyStar;
        }
        return `<div class="flex items-center gap-0.5 text-base">${starsHtml}<span class="ml-2 text-gray-300 font-medium">${rating.toFixed(1)}</span></div>`;
      }

      // Render results cards (daisyUI cards) with gradient accents, hover animations
      function renderResults(restaurants) {
        if (!restaurants.length) {
          renderEmpty("We couldn't find that dish. How about trying 'biryani', 'pizza' or 'burger'?");
          return;
        }
        
        const gridStart = `<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-7 fade-in">`;
        const cardsHtml = restaurants.map(r => {
          return `
            <div class="card bg-[#1a1125] border border-purple-700/30 shadow-xl shadow-purple-900/20 backdrop-blur-sm 
                        rounded-3xl card-hover transition-all duration-300 hover:border-pink-500/40">
              <div class="card-body p-5 md:p-6">
                <!-- subtle gradient accent on top -->
                <div class="h-1.5 w-20 rounded-full bg-gradient-to-r from-purple-500 via-pink-500 to-orange-400 mb-1"></div>
                <h2 class="card-title text-2xl font-bold tracking-tight text-white">
                  ${r.name}
                  <div class="badge badge-sm badge-outline badge-secondary ml-2">AI pick</div>
                </h2>
                <!-- rating stars -->
                <div class="my-1">${renderStars(r.rating)}</div>
                <!-- address -->
                <div class="flex items-start gap-2 text-gray-300 text-sm mt-1">
                  <i class="fa-solid fa-location-dot text-pink-400 mt-1"></i>
                  <span>${r.address}</span>
                </div>
                <!-- AI explanation -->
                <div class="bg-purple-950/30 rounded-xl p-3 mt-2 border-l-4 border-orange-400">
                  <p class="text-gray-200 text-sm italic flex gap-2">
                    <i class="fa-solid fa-robot text-xs text-purple-300"></i>
                    “${r.aiNote}”
                  </p>
                </div>
                <!-- button -->
                <div class="card-actions justify-end mt-4">
                  <a href="#" class="btn btn-sm md:btn-md bg-gradient-to-r from-purple-600 to-pink-500 border-0 text-white 
                                     hover:from-pink-500 hover:to-orange-400 shadow-md shadow-pink-800/30 gap-2">
                    <i class="fa-regular fa-map"></i> View on Map
                  </a>
                </div>
              </div>
            </div>
          `;
        }).join('');
        
        dynamicContent.innerHTML = gridStart + cardsHtml + `</div>`;
      }

      // Simulate fetch (filter based on query)
      function performSearch(query) {
        renderLoading();
        
        // Simulate network delay (500ms)
        setTimeout(() => {
          const searchTerm = query.trim().toLowerCase();
          let filtered = [];
          
          if (searchTerm === '') {
            // if empty, show all popular (like 'trending')
            filtered = dummyRestaurants.filter(r => 
              r.tags.some(t => ['pizza','biryani','burger','sushi','tacos'].includes(t))
            );
          } else {
            filtered = dummyRestaurants.filter(restaurant => 
              restaurant.name.toLowerCase().includes(searchTerm) ||
              restaurant.tags.some(tag => tag.includes(searchTerm)) ||
              restaurant.aiNote.toLowerCase().includes(searchTerm)
            );
          }
          
          // extra: if still empty, show no results
          if (filtered.length === 0) {
            renderEmpty(`Nothing found for “${query}”. Try Biryani, Pizza or Sushi ✨`);
          } else {
            renderResults(filtered);
          }
        }, 600);
      }

      // Initial load (show default suggestions: popular dishes)
      function showDefaultPopular() {
        // show a mix (popular ones)
        const popular = dummyRestaurants.filter(r => 
          r.tags.includes('pizza') || r.tags.includes('biryani') || r.tags.includes('burger') || r.tags.includes('sushi')
        );
        renderResults(popular.slice(0,6));
      }

      // Event listeners
      searchBtn.addEventListener('click', () => {
        const query = searchInput.value;
        performSearch(query);
      });

      searchInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
          e.preventDefault();
          performSearch(searchInput.value);
        }
      });

      // Quick badge suggestions (click to fill & search)
      document.querySelectorAll('.badge-outline').forEach(badge => {
        badge.addEventListener('click', (e) => {
          const text = badge.innerText.replace(/[^a-zA-Z]/g, '').toLowerCase(); // extract word
          let dish = 'pizza';
          if (text.includes('pizza')) dish = 'pizza';
          else if (text.includes('sushi')) dish = 'sushi';
          else if (text.includes('tacos')) dish = 'tacos';
          else if (text.includes('biryani')) dish = 'biryani';
          else dish = text;
          searchInput.value = dish;
          performSearch(dish);
        });
      });

      // Initialize with default popular restaurants (friendly first view)
      showDefaultPopular();

      // Extra: add shimmer/glow to input when focused (already in css)
    })();
