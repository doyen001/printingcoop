/**
 * Sticky Header Effect
 * Makes the header menu bar sticky when scrolling down
 */
$(document).ready(function() {
    var $headerMenuBar = $('.header-menu-bar');
    console.log($headerMenuBar);
    var headerHeight = $headerMenuBar.outerHeight();
    var lastScrollTop = 0;
    var headerOffset = $headerMenuBar.offset().top;    // Start sticky effect immediately when scrolling
    var scrollThreshold = 0; 
    
    // Initialize
    initStickyHeader();
    
    /**
     * Initialize sticky header functionality
     */
    function initStickyHeader() {
        // Apply sticky effect on all pages with header-menu-bar and larger screens
        if ($headerMenuBar.length > 0 && $(window).width() > 768) {
            $(window).on('scroll', handleScroll);
            handleScroll(); // Check initial state
        }
    }
    
    /**
     * Handle scroll events
     */
    function handleScroll() {
        var scrollTop = $(window).scrollTop();
        
        if (scrollTop > headerOffset) {
            // Add sticky class when scrolling down
            if (!$headerMenuBar.hasClass('sticky')) {
                $headerMenuBar.addClass('sticky');
                // Use fixed height for sticky header (60px desktop, 50px mobile)
                var stickyHeight = $(window).width() <= 768 ? 50 : 60;
                $('body').css('padding-top', stickyHeight + 'px');
            }
        } else {
            // Remove sticky class when at top
            if ($headerMenuBar.hasClass('sticky')) {
                $headerMenuBar.removeClass('sticky');
                $('body').css('padding-top', '0');
            }
        }
        
        lastScrollTop = scrollTop;
    }
    
    /**
     * Check if current page is home page
     */
    function isHomePage() {
        // Check if URL contains home page indicators
        var currentPath = window.location.pathname;
        var homePagePatterns = [
            '/',
            '/index',
            '/home',
            '/homes'
        ];
        
        return homePagePatterns.some(function(pattern) {
            return currentPath === pattern || currentPath.endsWith(pattern);
        });
    }
    
    /**
     * Handle window resize
     */
    $(window).on('resize', function() {
        if ($(window).width() <= 768) {
            // Remove sticky effect on mobile
            $headerMenuBar.removeClass('sticky');
            $('body').css('padding-top', '0');
            $(window).off('scroll', handleScroll);
        } else if ($headerMenuBar.length > 0) {
            // Re-enable on larger screens
            $(window).off('scroll', handleScroll);
            initStickyHeader();
        }
    });
    
    /**
     * Handle mobile menu toggle with sticky header
     */
    $(document).on('click', '.mobile-menu-toggle', function() {
        if ($headerMenuBar.hasClass('sticky')) {
            // Adjust mobile menu position when header is sticky
            $('#mySidenav').css('top', headerHeight + 'px');
        }
    });
    
    /**
     * Smooth scroll to anchor links
     */
    $('a[href^="#"]').on('click', function(e) {
        var target = $(this.getAttribute('href'));
        if (target.length) {
            e.preventDefault();
            var offset = $headerMenuBar.hasClass('sticky') ? headerHeight : 0;
            $('html, body').animate({
                scrollTop: target.offset().top - offset
            }, 800);
        }
    });
});
