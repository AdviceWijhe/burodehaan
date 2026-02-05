/**
 * Kennisbank Search Functionality
 * Real-time search with category filtering
 */
console.log('Kennisbank Search JavaScript is loaded');

// Global function for form submission handling
function handleSearchSubmit(event) {
    event.preventDefault();
    event.stopPropagation();
    
    const searchInput = document.getElementById('search-field-kennisbank');
    if (searchInput) {
        const query = searchInput.value.trim();
        
        if (query.length >= 2) {
            // Trigger AJAX search
            if (window.kennisbankSearchHandler) {
                window.kennisbankSearchHandler.performSearch(query);
            }
        }
    }
    
    return false;
}

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-field-kennisbank');
    const searchForm = document.querySelector('.search-form');
    const resultsContainer = document.querySelector('.w-full.lg\\:w-8\\/12');
    
    if (!searchInput || !searchForm) {
        return;
    }
    
    // Check if AJAX variables are available
    if (typeof kennisbankSearch === 'undefined') {
        console.error('KennisbankSearch AJAX variables not loaded');
        return;
    }
    
    console.log('KennisbankSearch AJAX variables loaded:', kennisbankSearch);
    
    // Check if we're on a kennisbank page
    if (!kennisbankSearch.isKennisbankPage) {
        console.log('Not on a kennisbank page, skipping initialization');
        return;
    }
    
    console.log('Initializing kennisbank search on kennisbank page');
    
    let searchTimeout;
    let currentSearchQuery = '';
    let originalContent = null; // Declare here to avoid reference error
    
    // Store the original PHP-generated content
    storeOriginalContent();
    
    // Make search handler globally available
    window.kennisbankSearchHandler = {
        performSearch: performSearch,
        loadAllPosts: loadAllPosts
    };
    
    // Debounced search function
    function performSearch(query) {
        if (query.length < 2) {
            // If query is too short, restore original content
            if (currentSearchQuery !== '') {
                currentSearchQuery = '';
                restoreOriginalContent();
            }
            return;
        }
        
        if (currentSearchQuery === query) return;
        currentSearchQuery = query;
        
        // Show loading state
        showLoadingState();
        
        // Perform AJAX search
        const formData = new FormData();
        formData.append('action', 'kennisbank_search');
        formData.append('query', query);
        formData.append('nonce', kennisbankSearch.nonce);
        
        fetch(kennisbankSearch.ajaxUrl, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log('Search response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Search response data:', data);
            if (data.success) {
                displaySearchResults(data.data, query);
            } else {
                console.error('Search failed:', data.data);
                showErrorState();
            }
        })
        .catch(error => {
            console.error('Search error:', error);
            showErrorState();
        });
    }
    
    // Load all posts (for when search is cleared)
    function loadAllPosts() {
        const formData = new FormData();
        formData.append('action', 'kennisbank_load_all');
        formData.append('nonce', kennisbankSearch.nonce);
        
        fetch(kennisbankSearch.ajaxUrl, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log('Load all response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Load all response data:', data);
            if (data.success) {
                displaySearchResults(data.data, '');
            } else {
                console.error('Load all failed:', data.data);
                showErrorState();
            }
        })
        .catch(error => {
            console.error('Load error:', error);
            showErrorState();
        });
    }
    
    // Display search results
    function displaySearchResults(posts, query) {
        if (!resultsContainer) return;
        
        let html = '';
        
        if (posts.length === 0) {
            html = `
                <section class="no-results not-found">
                    <header class="page-header mb-8">
                        <h1 class="page-title text-3xl font-bold text-gray-900">Geen resultaten gevonden</h1>
                        <p class="text-gray-600 mt-4">Er zijn geen resultaten gevonden voor: "<strong>${query}</strong>"</p>
                    </header>
                    <div class="page-content prose prose-lg">
                        <p>Probeer een andere zoekterm of bekijk alle beschikbare categorieën.</p>
                    </div>
                </section>
            `;
        } else {
            // Group posts by category
            const postsByCategory = {};
            posts.forEach(post => {
                if (post.categories && post.categories.length > 0) {
                    const categoryName = post.categories[0].name;
                    if (!postsByCategory[categoryName]) {
                        postsByCategory[categoryName] = [];
                    }
                    postsByCategory[categoryName].push(post);
                }
            });
            
            // Show search results header if searching
            if (query) {
                html += `
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-[#092354] mb-4">Zoekresultaten voor: "${query}"</h2>
                        <p class="text-gray-600">${posts.length} resultaten gevonden</p>
                    </div>
                `;
            } else {
                // Show "Alle downloads en informatie" header when showing all posts
                html += `
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-[#092354] mb-4">Alle downloads en informatie</h2>
                        <p class="text-gray-600">${posts.length} items beschikbaar</p>
                    </div>
                `;
            }
            
            // Display posts by category
            Object.keys(postsByCategory).forEach(categoryName => {
                const categoryPosts = postsByCategory[categoryName];
                
                html += `
                    <div class="mb-8">
                        <h2 class="">${categoryName}</h2>
                    </div>
                    <div class="mb-16">
                `;
                
                categoryPosts.forEach((post, index) => {
                    const isEven = (index % 2 == 1);
                    const bgClass = isEven ? 'bg-white' : 'bg-gray-100';
                    
                    html += `
                        <div class="${bgClass} w-full flex items-center justify-between relative p-3 pe-10">
                            <div class="flex gap-4 items-center">
                                <div class="w-26 h-26 bg-white border border-gray-200 flex items-center justify-center p-2">
                                    ${post.thumbnail ? 
                                        `<img src="${post.thumbnail}" class="h-full w-auto object-contain" alt="${post.title}">` :
                                        `<svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                        </svg>`
                                    }
                                </div>
                                <div class="">
                                    <h3 class="">${post.title}</h3>
                                </div>
                            </div>
                            <div class="">
                                <a href="${post.download_url}" 
                                   class="btn btn-red flex items-center gap-2 transition-colors"
                                   ${post.has_attachment ? 'download' : ''}>
                                   <svg xmlns="http://www.w3.org/2000/svg" width="11" height="15" viewBox="0 0 11 15" fill="none">
                                        <path d="M5.39746 12.1455L0.0761716 6.82519L1.14062 5.76074L4.69922 9.32031L4.69922 -2.63079e-07L6.2041 -1.97299e-07L6.2041 9.21191L9.6543 5.76074L10.7178 6.8252L5.39746 12.1455Z" fill="white"/>
                                        <rect y="13.4952" width="10.6409" height="1.50488" fill="white"/>
                                    </svg>
                                    <span class="">Download document</span>
                                </a>
                            </div>
                        </div>
                    `;
                });
                
                html += `</div>`;
            });
        }
        
        resultsContainer.innerHTML = html;
    }
    
    // Show loading state
    function showLoadingState() {
        if (!resultsContainer) return;
        
        resultsContainer.innerHTML = `
            <div class="flex items-center justify-center py-12">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#092354]"></div>
                <span class="ml-3 text-[#092354]">Zoeken...</span>
            </div>
        `;
    }
    
    // Show error state
    function showErrorState() {
        if (!resultsContainer) return;
        
        resultsContainer.innerHTML = `
            <section class="no-results not-found">
                <header class="page-header mb-8">
                    <h1 class="page-title text-3xl font-bold text-gray-900">Er is een fout opgetreden</h1>
                </header>
                <div class="page-content prose prose-lg">
                    <p>Er is een fout opgetreden tijdens het zoeken. Probeer het opnieuw.</p>
                </div>
            </section>
        `;
    }
    
    // Show empty state (initial state)
    function showEmptyState() {
        if (!resultsContainer) return;
        
        resultsContainer.innerHTML = `
            <section class="no-results not-found">
                <header class="page-header mb-8">
                    <h1 class="page-title text-3xl font-bold text-gray-900">Zoek in de kennisbank</h1>
                    <p class="text-gray-600 mt-4">Typ een zoekterm in het zoekveld om te beginnen met zoeken.</p>
                </header>
                <div class="page-content prose prose-lg">
                    <p>Zoek op product, titel of probleem om relevante documenten te vinden.</p>
                </div>
            </section>
        `;
    }
    
    // Store original content and restore it
    function storeOriginalContent() {
        if (resultsContainer && !originalContent) {
            originalContent = resultsContainer.innerHTML;
        }
    }
    
    function restoreOriginalContent() {
        if (resultsContainer && originalContent) {
            resultsContainer.innerHTML = originalContent;
        }
    }
    
    // Event listeners
    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            performSearch(query);
        }, 300); // 300ms delay
    });
    
    // Clear search when input is empty
    searchInput.addEventListener('input', function() {
        if (this.value.trim() === '') {
            clearTimeout(searchTimeout);
            currentSearchQuery = '';
            restoreOriginalContent();
        }
    });
    
    // Prevent form submission for real-time search
    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const query = searchInput.value.trim();
        
        if (query.length >= 2) {
            performSearch(query);
        } else {
            // If query is too short, restore original content
            restoreOriginalContent();
        }
        
        return false;
    });
    
    // Original PHP content is preserved and restored when search is cleared
});
