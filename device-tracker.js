/**
 * Alhadiya Enhanced Device Tracking & Interaction Logger
 * Version: 3.0 - Complete Section-Based Tracking
 * Features: Device Info, Section Tracking, Form Events, Video Events, Live Session
 */

(function($) {
    'use strict';
    
    // Global tracking object
    window.AlhadiyaTracker = {
        sessionId: null,
        isActive: false,
        deviceInfo: {},
        sectionTracking: {},
        userInteractions: {
            scrollDepth: 0,
            clickCount: 0,
            keypressCount: 0,
            mouseMovements: 0,
            lastKeyPressed: '',
            clickPositions: []
        },
        currentSection: null,
        sectionTimes: {},
        formData: {},
        videoEvents: {},
        startTime: Date.now(),
        lastActivity: Date.now()
    };

    // Section mapping for tracking
    const SECTIONS = {
        'hero-section': 'Hero/Banner Area',
        'course-section-1': 'Course Introduction',
        'course-section-2': 'Course Benefits',
        'review-section': 'Customer Reviews',
        'faq-section': 'FAQ Section',
        'order-section': 'Order Form',
        'footer': 'Footer Area'
    };

    // Event types for comprehensive tracking
    const EVENT_TYPES = {
        PAGE_VIEW: 'page_view',
        SECTION_VIEW: 'section_view',
        SECTION_TIME: 'section_time_spent',
        SCROLL_DEPTH: 'scroll_depth',
        BUTTON_CLICK: 'button_click',
        FORM_EVENT: 'form_event',
        VIDEO_EVENT: 'video_event',
        FAQ_TOGGLE: 'faq_toggle',
        SWIPER_NAVIGATION: 'swiper_navigation',
        DEVICE_INFO: 'device_info_update',
        USER_ACTIVITY: 'user_activity'
    };

    // Initialize when DOM is ready
    $(document).ready(function() {
        if (typeof ajax_object !== 'undefined') {
            initializeTracker();
        }
    });

    /**
     * Initialize comprehensive tracking system
     */
    function initializeTracker() {
        // Get or create session ID
        AlhadiyaTracker.sessionId = getSessionId();
        
        // Collect device information
        collectDeviceInformation();
        
        // Setup all event listeners
        setupEventListeners();
        
        // Initialize section tracking
        initializeSectionTracking();
        
        // Start periodic updates
        startPeriodicUpdates();
        
        // Send initial page view
        trackEvent(EVENT_TYPES.PAGE_VIEW, 'Page Load', window.location.pathname);
        
        AlhadiyaTracker.isActive = true;
        console.log('Alhadiya Tracker initialized successfully');
    }

    /**
     * Collect comprehensive device information
     */
    function collectDeviceInformation() {
        // Basic device info
        const userAgent = navigator.userAgent;
        const language = navigator.language || navigator.userLanguage || 'en-US';
        const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone || 'UTC';
        
        // Screen information
        const screenWidth = screen.width;
        const screenHeight = screen.height;
        const viewportWidth = window.innerWidth;
        const viewportHeight = window.innerHeight;
        const devicePixelRatio = window.devicePixelRatio || 1;
        
        // Browser detection
        const browserInfo = detectBrowser(userAgent);
        
        // Operating system detection
        const osInfo = detectOS(userAgent);
        
        // Device type detection
        const deviceType = detectDeviceType(userAgent, screenWidth);
        
        // Touch capability
        const touchSupport = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
        
        // CPU cores
        const cpuCores = navigator.hardwareConcurrency || 'unknown';
        
        // Store device info
        AlhadiyaTracker.deviceInfo = {
            language: language,
            timezone: timezone,
            browser: browserInfo.name,
            browser_version: browserInfo.version,
            os: osInfo,
            device_type: deviceType,
            screen_size: screenWidth + 'x' + screenHeight,
            screen_resolution: (screenWidth * devicePixelRatio) + 'x' + (screenHeight * devicePixelRatio),
            viewport_size: viewportWidth + 'x' + viewportHeight,
            device_orientation: getOrientation(),
            touchscreen_detected: touchSupport ? 1 : 0,
            cpu_cores: cpuCores,
            user_agent: userAgent
        };
        
        // Get connection info
        getConnectionInfo();
        
        // Get battery info
        getBatteryInfo();
        
        // Get memory info
        getMemoryInfo();
        
        // Send device info to server
        sendDeviceInfo();
    }

    /**
     * Setup all event listeners for tracking
     */
    function setupEventListeners() {
        // Scroll tracking
        let scrollTimeout;
        $(window).on('scroll', function() {
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(trackScrolling, 100);
        });
        
        // Click tracking
        $(document).on('click', function(e) {
            trackClick(e);
        });
        
        // Keypress tracking
        $(document).on('keydown', function(e) {
            trackKeypress(e);
        });
        
        // Mouse movement tracking (throttled)
        let mouseTimeout;
        $(document).on('mousemove', function(e) {
            clearTimeout(mouseTimeout);
            mouseTimeout = setTimeout(() => trackMouseMovement(e), 200);
        });
        
        // Window resize
        $(window).on('resize', function() {
            updateViewportSize();
        });
        
        // Form events
        $('input, textarea, select').on('focus', function(e) {
            trackFormEvent('focus', $(this));
        }).on('change', function(e) {
            trackFormEvent('change', $(this));
        });
        
        // Form submission
        $('form').on('submit', function(e) {
            trackFormEvent('submit', $(this));
        });
        
        // Button tracking
        $('button, .btn, .callbtnlaptop, .float, a[href^="tel:"], a[href^="https://wa.me"]').on('click', function(e) {
            trackButtonClick($(this));
        });
        
        // FAQ toggle tracking
        $('.faq-item, .accordion-item').on('click', function() {
            trackFAQToggle($(this));
        });
        
        // Video event tracking
        $('video').each(function() {
            setupVideoTracking(this);
        });
        
        // Swiper navigation if exists
        if (typeof Swiper !== 'undefined') {
            setupSwiperTracking();
        }
        
        // Page visibility
        $(document).on('visibilitychange', function() {
            trackVisibilityChange();
        });
        
        // Before unload
        $(window).on('beforeunload', function() {
            sendFinalData();
        });
        
        // Floating button tracking
        $('.alhadiya-floating-btn').on('click', function() {
            const eventType = $(this).data('track-event');
            trackEvent(EVENT_TYPES.BUTTON_CLICK, 'Floating Button', eventType);
        });
    }

    /**
     * Initialize section tracking with IntersectionObserver
     */
    function initializeSectionTracking() {
        const observerOptions = {
            threshold: 0.5, // 50% of section must be visible
            rootMargin: '0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const sectionId = entry.target.id || entry.target.className;
                    const sectionName = SECTIONS[sectionId] || sectionId || 'Unknown Section';
                    
                    // Track section view
                    trackSectionView(sectionId, sectionName);
                    
                    // Start timing for this section
                    startSectionTiming(sectionId);
                } else {
                    const sectionId = entry.target.id || entry.target.className;
                    // Stop timing for this section
                    stopSectionTiming(sectionId);
                }
            });
        }, observerOptions);
        
        // Observe all major sections
        $('section, .section, [id*="section"], [class*="section"]').each(function() {
            observer.observe(this);
        });
    }

    /**
     * Track scroll depth and section progression
     */
    function trackScrolling() {
        const scrollTop = $(window).scrollTop();
        const documentHeight = $(document).height();
        const windowHeight = $(window).height();
        const scrollPercent = Math.round((scrollTop / (documentHeight - windowHeight)) * 100);
        
        if (scrollPercent > AlhadiyaTracker.userInteractions.scrollDepth && scrollPercent <= 100) {
            AlhadiyaTracker.userInteractions.scrollDepth = scrollPercent;
            
            // Send milestone events
            if (scrollPercent % 25 === 0 || scrollPercent === 100) {
                trackEvent(EVENT_TYPES.SCROLL_DEPTH, 'Scroll Milestone', scrollPercent + '%');
            }
        }
        
        // Update activity timestamp
        AlhadiyaTracker.lastActivity = Date.now();
    }

    /**
     * Track click events with position
     */
    function trackClick(event) {
        AlhadiyaTracker.userInteractions.clickCount++;
        
        const clickData = {
            x: event.pageX,
            y: event.pageY,
            element: event.target.tagName.toLowerCase(),
            timestamp: Date.now()
        };
        
        AlhadiyaTracker.userInteractions.clickPositions.push(clickData);
        
        // Keep only last 50 clicks for performance
        if (AlhadiyaTracker.userInteractions.clickPositions.length > 50) {
            AlhadiyaTracker.userInteractions.clickPositions = 
                AlhadiyaTracker.userInteractions.clickPositions.slice(-50);
        }
        
        AlhadiyaTracker.lastActivity = Date.now();
    }

    /**
     * Track keypress events
     */
    function trackKeypress(event) {
        AlhadiyaTracker.userInteractions.keypressCount++;
        AlhadiyaTracker.userInteractions.lastKeyPressed = event.key;
        
        // Track every 10 keypresses
        if (AlhadiyaTracker.userInteractions.keypressCount % 10 === 0) {
            trackEvent('keypress_milestone', 'Keypress Count', AlhadiyaTracker.userInteractions.keypressCount);
        }
        
        AlhadiyaTracker.lastActivity = Date.now();
    }

    /**
     * Track mouse movements
     */
    function trackMouseMovement(event) {
        AlhadiyaTracker.userInteractions.mouseMovements++;
        AlhadiyaTracker.lastActivity = Date.now();
    }

    /**
     * Track button clicks
     */
    function trackButtonClick(button) {
        const buttonText = button.text().trim() || button.val() || 'Unknown Button';
        const buttonClass = button.attr('class') || '';
        const buttonId = button.attr('id') || '';
        
        const buttonData = {
            text: buttonText.substring(0, 50),
            class: buttonClass,
            id: buttonId,
            href: button.attr('href') || ''
        };
        
        trackEvent(EVENT_TYPES.BUTTON_CLICK, 'Button Click', JSON.stringify(buttonData));
    }

    /**
     * Track form events
     */
    function trackFormEvent(eventType, element) {
        const fieldName = element.attr('name') || element.attr('id') || 'unknown_field';
        const fieldType = element.attr('type') || element.prop('tagName').toLowerCase();
        const fieldValue = element.val();
        
        // Store form data for auto-fill (only basic info)
        if (['name', 'phone', 'email', 'address'].includes(fieldName.toLowerCase())) {
            AlhadiyaTracker.formData[fieldName] = fieldValue;
            // Save to localStorage for auto-fill
            localStorage.setItem('alhadiya_form_' + fieldName, fieldValue);
        }
        
        const formEventData = {
            event_type: eventType,
            field_name: fieldName,
            field_type: fieldType,
            has_value: fieldValue ? 'yes' : 'no'
        };
        
        trackEvent(EVENT_TYPES.FORM_EVENT, 'Form ' + eventType, JSON.stringify(formEventData));
    }

    /**
     * Track FAQ toggle events
     */
    function trackFAQToggle(faqElement) {
        const faqTitle = faqElement.find('.faq-question, .accordion-header, h3, h4').first().text().trim();
        const isOpen = faqElement.hasClass('active') || faqElement.hasClass('show');
        
        const faqData = {
            title: faqTitle.substring(0, 100),
            action: isOpen ? 'close' : 'open'
        };
        
        trackEvent(EVENT_TYPES.FAQ_TOGGLE, 'FAQ Toggle', JSON.stringify(faqData));
    }

    /**
     * Setup video tracking
     */
    function setupVideoTracking(videoElement) {
        const video = $(videoElement);
        const videoId = video.attr('id') || 'video_' + Math.random().toString(36).substr(2, 9);
        
        video.on('loadeddata', function() {
            trackEvent(EVENT_TYPES.VIDEO_EVENT, 'Video Ready', videoId);
        });
        
        video.on('play', function() {
            trackEvent(EVENT_TYPES.VIDEO_EVENT, 'Video Play', videoId);
        });
        
        video.on('pause', function() {
            trackEvent(EVENT_TYPES.VIDEO_EVENT, 'Video Pause', videoId);
        });
        
        video.on('ended', function() {
            trackEvent(EVENT_TYPES.VIDEO_EVENT, 'Video Ended', videoId);
        });
        
        video.on('waiting', function() {
            trackEvent(EVENT_TYPES.VIDEO_EVENT, 'Video Buffering', videoId);
        });
    }

    /**
     * Setup Swiper tracking if available
     */
    function setupSwiperTracking() {
        // Wait for Swiper initialization
        setTimeout(function() {
            $('.swiper').each(function() {
                const swiper = this.swiper;
                if (swiper) {
                    swiper.on('slideChange', function() {
                        trackEvent(EVENT_TYPES.SWIPER_NAVIGATION, 'Slide Change', 'Slide ' + (this.activeIndex + 1));
                    });
                }
            });
        }, 1000);
    }

    /**
     * Track section view
     */
    function trackSectionView(sectionId, sectionName) {
        if (AlhadiyaTracker.currentSection !== sectionId) {
            AlhadiyaTracker.currentSection = sectionId;
            
            trackEvent(EVENT_TYPES.SECTION_VIEW, 'Section View', sectionName);
        }
    }

    /**
     * Start timing for a section
     */
    function startSectionTiming(sectionId) {
        if (!AlhadiyaTracker.sectionTimes[sectionId]) {
            AlhadiyaTracker.sectionTimes[sectionId] = {
                startTime: Date.now(),
                totalTime: 0
            };
        } else {
            AlhadiyaTracker.sectionTimes[sectionId].startTime = Date.now();
        }
    }

    /**
     * Stop timing for a section
     */
    function stopSectionTiming(sectionId) {
        if (AlhadiyaTracker.sectionTimes[sectionId] && AlhadiyaTracker.sectionTimes[sectionId].startTime) {
            const timeSpent = Date.now() - AlhadiyaTracker.sectionTimes[sectionId].startTime;
            AlhadiyaTracker.sectionTimes[sectionId].totalTime += timeSpent;
            
            // Send time spent data
            const sectionName = SECTIONS[sectionId] || sectionId;
            trackEvent(EVENT_TYPES.SECTION_TIME, 'Section Time', JSON.stringify({
                section: sectionName,
                time: Math.round(timeSpent / 1000) // seconds
            }));
            
            AlhadiyaTracker.sectionTimes[sectionId].startTime = null;
        }
    }

    /**
     * Track page visibility changes
     */
    function trackVisibilityChange() {
        const isVisible = !document.hidden;
        trackEvent('visibility_change', 'Page Visibility', isVisible ? 'visible' : 'hidden');
        
        if (isVisible) {
            AlhadiyaTracker.lastActivity = Date.now();
        }
    }

    /**
     * Get connection information
     */
    function getConnectionInfo() {
        if ('connection' in navigator) {
            const connection = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
            if (connection) {
                AlhadiyaTracker.deviceInfo.connection_type = connection.effectiveType || connection.type || 'unknown';
                AlhadiyaTracker.deviceInfo.connection_speed = connection.downlink ? connection.downlink + ' Mbps' : 'unknown';
            }
        }
    }

    /**
     * Get battery information
     */
    function getBatteryInfo() {
        if ('getBattery' in navigator) {
            navigator.getBattery().then(function(battery) {
                AlhadiyaTracker.deviceInfo.battery_level = battery.level;
                AlhadiyaTracker.deviceInfo.battery_charging = battery.charging ? 1 : 0;
                
                // Listen for battery changes
                battery.addEventListener('chargingchange', function() {
                    AlhadiyaTracker.deviceInfo.battery_charging = battery.charging ? 1 : 0;
                    sendDeviceInfo();
                });
                
                battery.addEventListener('levelchange', function() {
                    AlhadiyaTracker.deviceInfo.battery_level = battery.level;
                    sendDeviceInfo();
                });
            });
        }
    }

    /**
     * Get memory information
     */
    function getMemoryInfo() {
        if ('memory' in performance) {
            const memory = performance.memory;
            AlhadiyaTracker.deviceInfo.memory_info = (memory.usedJSHeapSize / 1024 / 1024).toFixed(2); // MB
        }
        
        if ('storage' in navigator && 'estimate' in navigator.storage) {
            navigator.storage.estimate().then(function(estimate) {
                AlhadiyaTracker.deviceInfo.storage_info = estimate.quota ? 
                    (estimate.quota / 1024 / 1024 / 1024).toFixed(2) : 'unknown'; // GB
            });
        }
    }

    /**
     * Send device information to server
     */
    function sendDeviceInfo() {
        const data = {
            action: 'update_client_device_details',
            nonce: ajax_object.device_info_nonce,
            session_id: AlhadiyaTracker.sessionId,
            ...AlhadiyaTracker.deviceInfo
        };
        
        $.post(ajax_object.ajax_url, data)
            .done(function(response) {
                console.log('Device info updated successfully');
            })
            .fail(function(xhr, status, error) {
                console.error('Failed to update device info:', error);
            });
    }

    /**
     * Track custom events
     */
    function trackEvent(eventType, eventName, eventValue) {
        const data = {
            action: 'track_custom_event',
            nonce: ajax_object.event_nonce,
            session_id: AlhadiyaTracker.sessionId,
            event_type: eventType,
            event_name: eventName,
            event_value: eventValue ? eventValue.toString() : ''
        };
        
        $.post(ajax_object.ajax_url, data)
            .done(function(response) {
                // Event tracked successfully
            })
            .fail(function(xhr, status, error) {
                console.error('Failed to track event:', error);
            });
    }

    /**
     * Start periodic updates
     */
    function startPeriodicUpdates() {
        // Send activity summary every 30 seconds
        setInterval(function() {
            if (AlhadiyaTracker.isActive) {
                const activityData = {
                    scroll_depth: AlhadiyaTracker.userInteractions.scrollDepth,
                    click_count: AlhadiyaTracker.userInteractions.clickCount,
                    keypress_count: AlhadiyaTracker.userInteractions.keypressCount,
                    mouse_movements: AlhadiyaTracker.userInteractions.mouseMovements,
                    time_active: Math.round((Date.now() - AlhadiyaTracker.startTime) / 1000),
                    last_activity: Math.round((Date.now() - AlhadiyaTracker.lastActivity) / 1000)
                };
                
                trackEvent(EVENT_TYPES.USER_ACTIVITY, 'Activity Summary', JSON.stringify(activityData));
            }
        }, 30000);
        
        // Auto-fill form fields from stored data
        autoFillForms();
    }

    /**
     * Auto-fill form fields
     */
    function autoFillForms() {
        const formFields = ['name', 'phone', 'email', 'address'];
        
        formFields.forEach(function(fieldName) {
            const storedValue = localStorage.getItem('alhadiya_form_' + fieldName);
            if (storedValue) {
                $('input[name="' + fieldName + '"], input[id="' + fieldName + '"]').val(storedValue);
            }
        });
    }

    /**
     * Send final data before page unload
     */
    function sendFinalData() {
        const finalData = {
            action: 'update_client_device_details',
            nonce: ajax_object.device_info_nonce,
            session_id: AlhadiyaTracker.sessionId,
            scroll_depth_max: AlhadiyaTracker.userInteractions.scrollDepth,
            click_count: AlhadiyaTracker.userInteractions.clickCount,
            keypress_count: AlhadiyaTracker.userInteractions.keypressCount,
            mouse_movements: AlhadiyaTracker.userInteractions.mouseMovements,
            time_spent: Math.round((Date.now() - AlhadiyaTracker.startTime) / 1000)
        };
        
        // Use sendBeacon for reliable data sending
        if (navigator.sendBeacon) {
            const formData = new FormData();
            Object.keys(finalData).forEach(key => {
                formData.append(key, finalData[key]);
            });
            navigator.sendBeacon(ajax_object.ajax_url, formData);
        } else {
            // Fallback
            $.ajaxSetup({async: false});
            $.post(ajax_object.ajax_url, finalData);
            $.ajaxSetup({async: true});
        }
    }

    /**
     * Utility functions
     */
    function getSessionId() {
        let sessionId = getCookie('device_session');
        if (!sessionId) {
            sessionId = 'session_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
            setCookie('device_session', sessionId, 30); // 30 days
        }
        return sessionId;
    }
    
    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
        return null;
    }
    
    function setCookie(name, value, days) {
        const expires = new Date(Date.now() + days * 864e5).toUTCString();
        document.cookie = `${name}=${value}; expires=${expires}; path=/`;
    }
    
    function detectBrowser(userAgent) {
        let browser = 'Unknown';
        let version = 'Unknown';
        
        if (userAgent.indexOf('Chrome') > -1 && userAgent.indexOf('Edg') === -1) {
            browser = 'Chrome';
            version = userAgent.match(/Chrome\/([0-9.]+)/)?.[1] || 'Unknown';
        } else if (userAgent.indexOf('Firefox') > -1) {
            browser = 'Firefox';
            version = userAgent.match(/Firefox\/([0-9.]+)/)?.[1] || 'Unknown';
        } else if (userAgent.indexOf('Safari') > -1 && userAgent.indexOf('Chrome') === -1) {
            browser = 'Safari';
            version = userAgent.match(/Version\/([0-9.]+)/)?.[1] || 'Unknown';
        } else if (userAgent.indexOf('Edg') > -1) {
            browser = 'Edge';
            version = userAgent.match(/Edg\/([0-9.]+)/)?.[1] || 'Unknown';
        }
        
        return { name: browser, version: version };
    }
    
    function detectOS(userAgent) {
        if (userAgent.indexOf('Windows') > -1) return 'Windows';
        if (userAgent.indexOf('Mac') > -1) return 'macOS';
        if (userAgent.indexOf('Android') > -1) return 'Android';
        if (userAgent.indexOf('iPhone') > -1 || userAgent.indexOf('iPad') > -1) return 'iOS';
        if (userAgent.indexOf('Linux') > -1) return 'Linux';
        return 'Unknown';
    }
    
    function detectDeviceType(userAgent, screenWidth) {
        if (/Mobile|Android|iPhone|iPod|BlackBerry|Windows Phone/i.test(userAgent)) {
            return 'Mobile';
        } else if (/iPad|Tablet/i.test(userAgent) || screenWidth >= 768 && screenWidth <= 1024) {
            return 'Tablet';
        } else {
            return 'Desktop';
        }
    }
    
    function getOrientation() {
        if (screen.orientation) {
            return screen.orientation.type;
        } else if (window.orientation !== undefined) {
            return Math.abs(window.orientation) === 90 ? 'landscape' : 'portrait';
        } else {
            return window.innerWidth > window.innerHeight ? 'landscape' : 'portrait';
        }
    }
    
    function updateViewportSize() {
        AlhadiyaTracker.deviceInfo.viewport_size = window.innerWidth + 'x' + window.innerHeight;
        AlhadiyaTracker.deviceInfo.device_orientation = getOrientation();
        sendDeviceInfo();
    }

})(jQuery);