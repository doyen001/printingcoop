{{-- CI: application/views/elements/HomeSections/our_printed_products.php --}}
{{-- Our Printed Products Section --}}

<style>
    :root {
        --primary-color: #ff6b35;
        --primary-dark: #e85a2c;
        --secondary-color: #2d3436;
        --text-color: #333333;
        --light-text: #ffffff;
        --muted-text: #666666;
        --background-light: #f8f9fa;
        --card-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        --transition: all 0.3s ease;
    }

    .printed-products {
        padding: 50px 0 0 0;
        background: linear-gradient(135deg, #ffffff 0%, var(--background-light) 100%);
        position: relative;
        overflow: hidden;
    }

    .printed-products::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100%;
        height: 100%;
        background-color: #fff;
        z-index: 1;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 30px;
        position: relative;
        z-index: 2;
    }

    .printed-products-content {
        position: relative;
        padding: 0 100px;
    }

    .printed-products-header {
        text-align: center;
        /* margin-bottom: 80px; */
        position: relative;
    }

    .printed-products-title {
        font-size: 28px;
        font-weight: 600;
        color: #484848;
        margin-bottom: 0;
        position: relative;
        display: inline-block;
        letter-spacing: -0.5px;
        /* opacity: 0;
        transform: translateY(30px);
        animation: fadeInUp 0.8s ease forwards; */
    }

    .printed-products-subtitle {
        color: #484848;
        font-weight: 100;
        font-size: 18px;
        margin-bottom: 30px;
    }

    /* .printed-products-title::after {
        content: '';
        position: absolute;
        bottom: -15px;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 4px;
        background: linear-gradient(to right, var(--primary-color), var(--primary-dark));
        border-radius: 2px;
    } */

    .categories-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        grid-template-rows: repeat(3, auto);
        gap: 20px;
        margin-bottom: 60px;
    }

    .product-card {
        background: transparent;
        text-align: center;
        /* transform: translateY(0) !important;
        transition: transform 0.3s ease;
        opacity: 0; */
        /* height: 340px; */
    }

    /* .product-card.animate {
        opacity: 1;
        transition: all 0.3s ease;
    } */

    .product-card:hover {
        transform: translateY(-3px) !important;
    }

    .product-image {
        width: 100%;
        background: #ffffff;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: .5rem;
        overflow: hidden;
    }

    .product-image img {
        position: static;
        width: 100%;
        height: 100%;
        aspect-ratio: 1 / 1;
        /* transform: scale(1.06); */
    }

    .product-info {
        position: static;
        padding: 10px 4px 0;
        background: transparent;
        text-align: center;
    }

    .product-category {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: #333333;
        margin-bottom: 2px;
        line-height: 1.3;
    }

    .printed-product-title {
        font-size: 18px;
        font-weight: 600;
        color: #333333;
        margin-bottom: 2px;
        line-height: 1.3;
    }

    .printed-product-title a {
        color: inherit;
        text-decoration: none;
    }

    .printed-product-title a:hover {
        text-decoration: underline;
    }

    .show-more-container {
        text-align: center;
        margin-top: 60px;
        margin-bottom: 20px;
        margin-left: 0px;
        margin-right: 0px;
		margin-left: 0px;
		margin-right: 0px;
        position: relative;
    }

    .show-more-container::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 100%;
        max-width: 300px;
        height: 1px;
        background: linear-gradient(to right, transparent, rgba(45, 52, 54, 0.1), transparent);
    }

    .show-more-button {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        padding: 18px 40px;
        border: none;
        background: #f28738;
        color: #fff;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 50px;
        cursor: pointer;
        transition: var(--transition);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        position: relative;
        overflow: hidden;
    }

    .show-more-button::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transform: translateX(-100%);
        transition: transform 0.6s ease;
    }

    .show-more-button:hover {
        background: #f28738;
        color: #ffffff;
        box-shadow: 0 12px 30px rgba(255, 107, 53, 0.3);
        transform: translateY(-3px);
    }

    .show-more-button:hover::before {
        transform: translateX(100%);
    }

    .show-more-text {
        position: relative;
        z-index: 1;
    }

    .show-more-icon {
        width: 24px;
        height: 24px;
        transition: all 0.3s ease;
        position: relative;
        z-index: 1;
    }

    .show-more-button:hover .show-more-icon {
        transform: translateY(3px);
    }

    .show-more-button[aria-expanded="true"] .show-more-icon {
        transform: rotate(180deg);
    }

    .show-more-button[aria-expanded="true"]:hover .show-more-icon {
        transform: rotate(180deg) translateY(-3px);
    }

    .view-all-container {
        text-align: center;
    }

    .view-all-button {
        display: inline-block;
        padding: 20px 50px;
        background: #f28738;
        color: #ffffff;
        text-decoration: none;
        font-weight: 700;
        font-size: 1.1rem;
        border-radius: 50px;
        transition: var(--transition);
        box-shadow: 0 10px 25px rgba(255, 107, 53, 0.3);
        position: relative;
        overflow: hidden;
    }

    .view-all-button::before {
        content: '';
        position: absolute;
		color: #fff;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transform: translateX(-100%);
        transition: transform 0.6s ease;
    }

    .view-all-button:hover {
        transform: translateY(-5px);
		color: #fff;
        box-shadow: 0 15px 35px rgba(255, 107, 53, 0.4);
    }

    .view-all-button:hover::before {
		color: #fff;
        transform: translateX(100%);
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 1400px) {
        .categories-grid {
            grid-template-columns: repeat(3, 1fr);
            grid-template-rows: repeat(3, auto);
            gap: 20px;
        }
    }

    @media (max-width: 991px) {
        .printed-products {
            padding: 80px 0;
        }

        .printed-products-title {
            font-size: 2.8rem;
        }

        .categories-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .category-name {
            font-size: 1.3rem;
        }
    }

    @media (max-width: 767px) {
        .printed-products {
            padding: 60px 0;
        }

        .container {
            padding: 0 20px;
        }

        .printed-products-title {
            font-size: 2.2rem;
        }

        .categories-grid {
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .category-content {
            transform: translateY(60%);
        }

        .show-more-button,
        .view-all-button {
            padding: 15px 35px;
            font-size: 1rem;
        }
    }
</style>

<section class="printed-products">
    <div class="container">
        <div class="printed-products-content">
            <header class="printed-products-header">
                <h1 class="printed-products-title">
                    {{ $language_name == 'french' ? 'LES BASIQUES LES PLUS POPULAIRES' : 'Most Popular Basics' }}
                </h1>
                <p class="printed-products-subtitle">
                    {{ $language_name == 'french' ? 'Matériel de marketing classique avec des résultats cohérents.' : 'Classic marketing materials with consistent results.' }}
                </p>
            </header>

            @if(isset($our_printed_products_category) && count($our_printed_products_category) > 0)
                <div class="categories-grid">
                    @foreach($our_printed_products_category as $key => $category)
                        @php
                            // Get category images from categoryImages array (CI uses array access)
                            $categoryImages = $category['categoryImages'] ?? [];
                            $categoryImage = $categoryImages[$website_store_id] ?? null;
                            
                            // Get image using geCategoryImage logic (CI function equivalent)
                            if ($categoryImage) {
                                $imageName = $language_name == 'French' && !empty($categoryImage['image_french'])
                                    ? $categoryImage['image_french']
                                    : $categoryImage['image'];
                                    
                                // Replicate geCategoryImage function logic
                                $imagePath = public_path('uploads/category/large/' . $imageName);
                                if (file_exists($imagePath)) {
                                    $src = url('uploads/category/large/' . $imageName);
                                } else {
                                    $src = url('uploads/category/' . $imageName);
                                }
                                
                                $alt_img = $imageName;
                            } else {
                                // Fallback to category image
                                $imageName = $language_name == 'french' && !empty($category['image_french'])
                                    ? $category['image_french']
                                    : $category['image'];
                                    
                                // Replicate geCategoryImage function logic
                                $imagePath = public_path('uploads/category/large/' . $imageName);
                                if (file_exists($imagePath)) {
                                    $src = url('uploads/category/large/' . $imageName);
                                } else {
                                    $src = url('uploads/category/' . $imageName);
                                }
                                
                                $alt_img = $imageName;
                            }
                            
                            // Get category name based on language (CI uses array access)
                            $categoryName = $language_name == 'french' 
                                ? ucfirst($category['name_french']) 
                                : ucfirst($category['name']);
                                
                            $filenameWithoutExtension = pathinfo($alt_img, PATHINFO_FILENAME);
                            
                            // Hide cards after 9th item
                            $hiddenClass = $key >= 9 ? 'category-card-hidden' : '';
                            $delay = ($key % 9) * 100;

                            //encode
                            $categoryId = base64_encode($category['id']);
                        @endphp
                        
                        <div class="product-card {{ $hiddenClass }}" style="animation-delay: {{ $delay }}ms">
                            <a href="{{ url('Products?category_id=' . $categoryId) }}" class="product-image">
                                <img src="{{ $src }}" alt="{{ $filenameWithoutExtension }}" loading="lazy">
                            </a>
                            <div class="product-info">
                                {{-- <div class="product-category">Category</div> --}}
                                <h3 class="printed-product-title">
                                    <a href="{{ url('Products?category_id=' . $categoryId) }}">{{ $categoryName }}</a>
                                </h3>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- @if(count($our_printed_products_category) > 8)
                    <div class="show-more-container">
                        <button class="show-more-button" onclick="toggleCategories()" aria-expanded="false">
                            <span class="show-more-text">
                                {{ $language_name == 'french' ? 'Afficher plus' : 'Show More' }}
                            </span>
                            <svg class="show-more-icon" viewBox="0 0 24 24" width="24" height="24">
                                <path fill="currentColor" d="M7 10l5 5 5-5z"/>
                            </svg>
                        </button>
                    </div>
                @endif --}}

                {{-- <div class="view-all-container">
                    <a href="{{ url('Products') }}" class="view-all-button">
                        {{ $language_name == 'french' ? 'Voir tout' : 'View All' }}
                    </a>
                </div> --}}
            @endif
        </div>
    </div>
</section>

<script>
(function() {
    // Function to check if element is in viewport
    function isInViewport(element) {
        const rect = element.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    }

    // Function to handle animation of cards
    // function animateCards() {
    //     const cards = document.querySelectorAll('.product-card:not(.category-card-hidden)');
    //     cards.forEach(card => {
    //         if (isInViewport(card) && !card.classList.contains('animate')) {
    //             card.classList.add('animate');
    //         }
    //     });
    // }

    // Enhanced toggle categories function
    window.toggleCategories = function() {
        const hiddenCards = document.querySelectorAll('.category-card-hidden');
        const showMoreBtn = document.querySelector('.show-more-button');
        const showMoreText = showMoreBtn.querySelector('.show-more-text');
        const showMoreIcon = showMoreBtn.querySelector('.show-more-icon');
        
        const isExpanded = showMoreBtn.getAttribute('aria-expanded') === 'true';
        
        if (!isExpanded) {
            showMoreBtn.setAttribute('aria-expanded', 'true');
            hiddenCards.forEach((card, index) => {
                card.style.display = 'block';
                setTimeout(() => {
                    card.classList.add('animate');
                }, index * 100);
            });
            
            showMoreText.textContent = '{{ $language_name == 'french' ? 'Afficher moins' : 'Show Less' }}';
            showMoreIcon.style.transform = 'rotate(180deg)';
        } else {
            showMoreBtn.setAttribute('aria-expanded', 'false');
            hiddenCards.forEach((card) => {
                card.classList.remove('animate');
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.display = 'none';
                    card.style.opacity = '';
                    card.style.transform = '';
                }, 300);
            });
            
            showMoreText.textContent = '{{ $language_name == 'french' ? 'Afficher plus' : 'Show More' }}';
            showMoreIcon.style.transform = 'rotate(0deg)';
        }
    };

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        const hiddenCards = document.querySelectorAll('.category-card-hidden');
        const showMoreBtn = document.querySelector('.show-more-button');
        
        hiddenCards.forEach(card => {
            card.style.display = 'none';
        });
        
        if (showMoreBtn) {
            showMoreBtn.setAttribute('aria-expanded', 'false');
        }

        animateCards();
        window.addEventListener('scroll', animateCards);
    });
})();
</script>
