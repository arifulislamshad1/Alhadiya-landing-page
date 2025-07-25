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
            scrollY: 0,
            clickCount: 0,
            keypressCount: 0,
            mouseMovements: 0,
            lastKeyPressed: '',
            clickPositions: [],
            capsLockState: 'unknown'
        },
        currentSection: null,
        sectionTimes: {},
        formData: {},
        videoEvents: {},
        startTime: Date.now(),
        lastActivity: Date.now()
    };

    // Section mapping for tracking - Updated per requirements
    const SECTIONS = {
        'course-section-1': 'অর্গানিক মেহেদী তৈরির সহজ উপায়',
        'course-section-2': 'মেহেদী রঙ বাড়ানোর গোপন টিপস',
        'course-section-3': 'প্যাকেজিং ও সার্টিফিকেশন',
        'review-section': 'Review Section',
        'video-section': 'Video Section',
        'order-section': 'Order Form Section',
        'hero-section': 'Hero/Banner Area',
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
        
        // Send immediate test event to verify tracking
        setTimeout(function() {
            trackEvent('tracker_test', 'Immediate Test Event', 'Tracker initialized and working - ' + new Date().toISOString());
        }, 1000);
        
        AlhadiyaTracker.isActive = true;
        console.log('AlhadiyaTracker: Initialized successfully', {
            sessionId: AlhadiyaTracker.sessionId,
            deviceInfo: AlhadiyaTracker.deviceInfo,
            ajaxUrl: ajax_object ? ajax_object.ajax_url : 'Not available',
            nonces: ajax_object ? {
                device_info: ajax_object.device_info_nonce ? 'Available' : 'Missing',
                event: ajax_object.event_nonce ? 'Available' : 'Missing'
            } : 'Ajax object not available'
        });
    }

    /**
     * Collect comprehensive device information including caps lock and geolocation
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
        
        // Caps Lock detection
        detectCapsLockState();
        
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
        
        // Send initial detailed device info as event with enhanced info
        const deviceDetailString = `Language: ${language} | Timezone: ${timezone} | Browser: ${browserInfo.name} ${browserInfo.version} | Engine: ${browserInfo.engine} | OS: ${osInfo.name} ${osInfo.version} | Platform: ${navigator.platform} | Device: ${deviceType} | Screen: ${screenWidth}x${screenHeight} | CPU: ${navigator.hardwareConcurrency || 'unknown'} cores | Touch: ${touchSupport ? 'Yes' : 'No'} | Caps Lock: ${AlhadiyaTracker.userInteractions.capsLockState}`;
        
        trackEvent('device_info_detailed', 'Device Information Collected', deviceDetailString);
        
        // Get IP-based geolocation
        getIPGeolocation();
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
        
        // Form events with enhanced payment method tracking
        $('input, textarea, select').on('focus', function(e) {
            trackFormEvent('focus', $(this));
        }).on('change', function(e) {
            trackFormEvent('change', $(this));
            
            // Special tracking for payment method selection
            const element = $(this);
            const fieldName = element.attr('name') || '';
            const fieldValue = element.val() || '';
            
            if (fieldName.includes('payment') || element.hasClass('payment-method') || element.attr('data-payment')) {
                trackEvent('payment_method_select', 'Payment Method Selected', fieldValue);
            }
        });
        
        // Payment method radio button tracking (specific for bkash, nagad, etc.)
        $('input[type="radio"]').on('change', function() {
            const element = $(this);
            if (element.is(':checked')) {
                const fieldName = element.attr('name') || '';
                const fieldValue = element.val() || '';
                
                if (fieldName.includes('payment') || fieldValue.toLowerCase().includes('bkash') || 
                    fieldValue.toLowerCase().includes('nagad') || fieldValue.toLowerCase().includes('rocket')) {
                    trackEvent('payment_method_select', 'Payment Method Selected', fieldValue);
                }
            }
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
        const scrollY = window.pageYOffset || document.documentElement.scrollTop;
        const documentHeight = $(document).height();
        const windowHeight = $(window).height();
        const scrollPercent = Math.round((scrollTop / (documentHeight - windowHeight)) * 100);
        
        // Update scroll Y position
        AlhadiyaTracker.userInteractions.scrollY = scrollY;
        
        if (scrollPercent > AlhadiyaTracker.userInteractions.scrollDepth && scrollPercent <= 100) {
            AlhadiyaTracker.userInteractions.scrollDepth = scrollPercent;
            
            // Send detailed scroll events with ScrollY
            const scrollDetailString = `Scroll Depth: ${scrollPercent}% | ScrollY: ${scrollY}px | Position: ${scrollTop}px of ${documentHeight}px | Window: ${windowHeight}px | Visible: ${Math.round((windowHeight / documentHeight) * 100)}%`;
            
            // Send milestone events with details
            if (scrollPercent % 25 === 0 || scrollPercent === 100) {
                trackEvent('scroll_milestone', 'Scroll Milestone Reached', scrollDetailString);
            } else if (scrollPercent % 10 === 0) {
                // Send every 10% for more granular tracking
                trackEvent('scroll_progress', 'Scroll Progress', scrollDetailString);
            }
            
            // Track scroll depth specifically as per requirements
            trackEvent('scroll_depth', 'Scroll Depth', `${scrollPercent}%`);
        }
        
        // Update activity timestamp
        AlhadiyaTracker.lastActivity = Date.now();
    }

    /**
     * Track click events with detailed position and element info
     */
    function trackClick(event) {
        AlhadiyaTracker.userInteractions.clickCount++;
        
        const element = event.target;
        const clickData = {
            x: event.pageX,
            y: event.pageY,
            clientX: event.clientX,
            clientY: event.clientY,
            element: element.tagName.toLowerCase(),
            elementId: element.id || '',
            elementClass: element.className || '',
            elementText: $(element).text().trim().substring(0, 100) || '',
            timestamp: Date.now()
        };
        
        AlhadiyaTracker.userInteractions.clickPositions.push(clickData);
        
        // Keep only last 50 clicks for performance
        if (AlhadiyaTracker.userInteractions.clickPositions.length > 50) {
            AlhadiyaTracker.userInteractions.clickPositions = 
                AlhadiyaTracker.userInteractions.clickPositions.slice(-50);
        }
        
        // Send detailed click event
        const clickDetailString = `Position: X:${clickData.x}, Y:${clickData.y} | Element: ${clickData.element}${clickData.elementId ? '#' + clickData.elementId : ''}${clickData.elementClass ? '.' + clickData.elementClass.split(' ')[0] : ''} | Text: "${clickData.elementText}"`;
        
        trackEvent('click_detailed', 'Click Event', clickDetailString);
        
        AlhadiyaTracker.lastActivity = Date.now();
    }

    /**
     * Track keypress events with detailed key information
     */
    function trackKeypress(event) {
        AlhadiyaTracker.userInteractions.keypressCount++;
        AlhadiyaTracker.userInteractions.lastKeyPressed = event.key;
        
        // Get detailed key information
        const keyInfo = {
            key: event.key,
            code: event.code,
            keyCode: event.keyCode,
            shiftKey: event.shiftKey,
            ctrlKey: event.ctrlKey,
            altKey: event.altKey,
            metaKey: event.metaKey,
            target: event.target.tagName.toLowerCase(),
            targetId: event.target.id || '',
            targetName: event.target.name || ''
        };
        
        // Track individual keypress with details
        const keyDetailString = `Key: "${keyInfo.key}" (${keyInfo.code}) | Target: ${keyInfo.target}${keyInfo.targetId ? '#' + keyInfo.targetId : ''}${keyInfo.targetName ? '[name=' + keyInfo.targetName + ']' : ''} | Modifiers: ${keyInfo.shiftKey ? 'Shift+' : ''}${keyInfo.ctrlKey ? 'Ctrl+' : ''}${keyInfo.altKey ? 'Alt+' : ''}${keyInfo.metaKey ? 'Meta+' : ''}`;
        
        trackEvent('keypress_detailed', 'Key Pressed', keyDetailString);
        
        // Track every 10 keypresses milestone
        if (AlhadiyaTracker.userInteractions.keypressCount % 10 === 0) {
            trackEvent('keypress_milestone', 'Keypress Milestone', `Total: ${AlhadiyaTracker.userInteractions.keypressCount} keys | Last: "${keyInfo.key}"`);
        }
        
        AlhadiyaTracker.lastActivity = Date.now();
    }

    /**
     * Track mouse movements with cursor position
     */
    function trackMouseMovement(event) {
        AlhadiyaTracker.userInteractions.mouseMovements++;
        
        // Store current cursor position
        AlhadiyaTracker.userInteractions.lastCursorPosition = {
            x: event.pageX,
            y: event.pageY,
            timestamp: Date.now()
        };
        
        // Track cursor position every 100 movements for analysis
        if (AlhadiyaTracker.userInteractions.mouseMovements % 100 === 0) {
            trackEvent('cursor_position', 'Cursor Position', `X:${event.pageX}, Y:${event.pageY} | Movements: ${AlhadiyaTracker.userInteractions.mouseMovements}`);
        }
        
        AlhadiyaTracker.lastActivity = Date.now();
    }

    /**
     * Track button clicks with enhanced details for specific requirements
     */
    function trackButtonClick(button) {
        const buttonText = button.text().trim() || button.val() || 'Unknown Button';
        const buttonClass = button.attr('class') || '';
        const buttonId = button.attr('id') || '';
        const buttonHref = button.attr('href') || '';
        
        // Check for specific requirement buttons
        if (buttonClass.includes('float')) {
            // WhatsApp button tracking
            trackEvent('button_whatsapp_click', 'WhatsApp Button Click', buttonText);
            trackEvent('button_visibility', 'Button Visible', 'WhatsApp button (.float) - ' + buttonText);
        } else if (buttonClass.includes('callbtnlaptop')) {
            // Call button tracking
            trackEvent('button_call_click', 'Call Button Click', buttonText);
            trackEvent('button_visibility', 'Button Visible', 'Call button (.callbtnlaptop) - ' + buttonText);
        }
        
        const buttonData = {
            text: buttonText.substring(0, 50),
            class: buttonClass,
            id: buttonId,
            href: buttonHref,
            timestamp: new Date().toISOString()
        };
        
        // Enhanced button click details
        const buttonDetailString = `Button: "${buttonText}" | Class: ${buttonClass} | ID: ${buttonId} | Type: ${buttonHref ? 'Link' : 'Button'} | Time: ${new Date().toLocaleTimeString()}`;
        
        trackEvent(EVENT_TYPES.BUTTON_CLICK, 'Button Click', buttonDetailString);
        
        // Legacy format for compatibility
        trackEvent('button_click_detailed', 'Button Click Detailed', JSON.stringify(buttonData));
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
                
                // Send detailed connection info as event
                const connectionDetailString = `Connection Type: ${connection.effectiveType || connection.type || 'unknown'} | Speed: ${connection.downlink ? connection.downlink + ' Mbps' : 'unknown'} | RTT: ${connection.rtt ? connection.rtt + 'ms' : 'unknown'} | Save Data: ${connection.saveData ? 'Yes' : 'No'}`;
                trackEvent('connection_info_detailed', 'Connection Information', connectionDetailString);
                
                // Listen for connection changes
                connection.addEventListener('change', function() {
                    const newType = connection.effectiveType || connection.type || 'unknown';
                    const newSpeed = connection.downlink ? connection.downlink + ' Mbps' : 'unknown';
                    
                    AlhadiyaTracker.deviceInfo.connection_type = newType;
                    AlhadiyaTracker.deviceInfo.connection_speed = newSpeed;
                    
                    const connectionChangeString = `Connection Changed: Type: ${newType} | Speed: ${newSpeed} | RTT: ${connection.rtt ? connection.rtt + 'ms' : 'unknown'}`;
                    trackEvent('connection_change', 'Connection Type Changed', connectionChangeString);
                    
                    sendDeviceInfo();
                });
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
                
                // Send initial battery info as detailed event
                const batteryDetailString = `Battery Level: ${Math.round(battery.level * 100)}% | Charging: ${battery.charging ? 'Yes' : 'No'} | Charging Time: ${battery.chargingTime !== Infinity ? battery.chargingTime + 's' : 'Unknown'} | Discharging Time: ${battery.dischargingTime !== Infinity ? battery.dischargingTime + 's' : 'Unknown'}`;
                trackEvent('battery_info_detailed', 'Battery Information', batteryDetailString);
                
                // Listen for battery changes
                battery.addEventListener('chargingchange', function() {
                    AlhadiyaTracker.deviceInfo.battery_charging = battery.charging ? 1 : 0;
                    const chargingChangeString = `Battery Charging Changed: ${battery.charging ? 'Started Charging' : 'Stopped Charging'} | Level: ${Math.round(battery.level * 100)}%`;
                    trackEvent('battery_status_change', 'Battery Status Change', chargingChangeString);
                    sendDeviceInfo();
                });
                
                battery.addEventListener('levelchange', function() {
                    AlhadiyaTracker.deviceInfo.battery_level = battery.level;
                    const levelChangeString = `Battery Level Changed: ${Math.round(battery.level * 100)}% | Charging: ${battery.charging ? 'Yes' : 'No'}`;
                    trackEvent('battery_level_change', 'Battery Level Change', levelChangeString);
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
        // Validate required data
        if (!eventType || !eventName || !AlhadiyaTracker.sessionId) {
            console.warn('AlhadiyaTracker: Missing required data for event tracking', {
                eventType: eventType,
                eventName: eventName,
                sessionId: AlhadiyaTracker.sessionId
            });
            return;
        }
        
        const data = {
            action: 'track_custom_event',
            nonce: ajax_object.event_nonce,
            session_id: AlhadiyaTracker.sessionId,
            event_type: eventType,
            event_name: eventName,
            event_value: eventValue ? eventValue.toString() : ''
        };
        
        // Debug logging (uncomment for debugging)
        console.log('AlhadiyaTracker: Sending event', {
            type: eventType,
            name: eventName,
            value: eventValue ? eventValue.toString().substring(0, 100) + '...' : '',
            sessionId: AlhadiyaTracker.sessionId.substring(0, 15) + '...'
        });
        
        $.post(ajax_object.ajax_url, data)
            .done(function(response) {
                console.log('AlhadiyaTracker: Event tracked successfully', eventType);
            })
            .fail(function(xhr, status, error) {
                console.error('AlhadiyaTracker: Failed to track event', {
                    eventType: eventType,
                    status: status,
                    error: error,
                    responseText: xhr.responseText
                });
            });
    }

    /**
     * Start periodic updates
     */
    function startPeriodicUpdates() {
        // Send detailed activity summary every 30 seconds
        setInterval(function() {
            if (AlhadiyaTracker.isActive) {
                const timeActive = Math.round((Date.now() - AlhadiyaTracker.startTime) / 1000);
                const lastActivity = Math.round((Date.now() - AlhadiyaTracker.lastActivity) / 1000);
                
                const activityDetailString = `Session Time: ${Math.floor(timeActive / 60)}m ${timeActive % 60}s | Last Activity: ${lastActivity}s ago | Scroll: ${AlhadiyaTracker.userInteractions.scrollDepth}% | ScrollY: ${AlhadiyaTracker.userInteractions.scrollY}px | Clicks: ${AlhadiyaTracker.userInteractions.clickCount} | Keys: ${AlhadiyaTracker.userInteractions.keypressCount} | Mouse: ${AlhadiyaTracker.userInteractions.mouseMovements} | Current Section: ${AlhadiyaTracker.currentSection || 'Unknown'} | Last Key: ${AlhadiyaTracker.userInteractions.lastKeyPressed || 'None'} | Caps Lock: ${AlhadiyaTracker.userInteractions.capsLockState}`;
                
                trackEvent('activity_summary', 'User Activity Summary', activityDetailString);
                
                // Send latest click position if available
                if (AlhadiyaTracker.userInteractions.clickPositions.length > 0) {
                    const lastClick = AlhadiyaTracker.userInteractions.clickPositions[AlhadiyaTracker.userInteractions.clickPositions.length - 1];
                    const lastClickString = `Last Click: X:${lastClick.x}, Y:${lastClick.y} | Element: ${lastClick.element} | Time: ${new Date(lastClick.timestamp).toLocaleTimeString()}`;
                    trackEvent('last_click_position', 'Last Click Position', lastClickString);
                }
                
                // Send current cursor position if available
                if (AlhadiyaTracker.userInteractions.lastCursorPosition) {
                    const cursor = AlhadiyaTracker.userInteractions.lastCursorPosition;
                    const cursorString = `Current Cursor: X:${cursor.x}, Y:${cursor.y} | Mouse Movements: ${AlhadiyaTracker.userInteractions.mouseMovements}`;
                    trackEvent('cursor_position_summary', 'Current Cursor Position', cursorString);
                }
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
     * Detect Caps Lock state
     */
    function detectCapsLockState() {
        $(document).on('keypress', function(event) {
            const char = String.fromCharCode(event.which);
            if (char && char.match(/[a-zA-Z]/)) {
                const isUpperCase = char === char.toUpperCase();
                const isShiftPressed = event.shiftKey;
                
                if (isUpperCase && !isShiftPressed) {
                    AlhadiyaTracker.userInteractions.capsLockState = 'ON';
                } else if (!isUpperCase && !isShiftPressed) {
                    AlhadiyaTracker.userInteractions.capsLockState = 'OFF';
                }
                
                // Track caps lock state change
                trackEvent('caps_lock_state', 'Caps Lock State', AlhadiyaTracker.userInteractions.capsLockState);
            }
        });
    }
    
    /**
     * Get IP-based geolocation information
     */
    function getIPGeolocation() {
        // Try multiple IP geolocation services for reliability
        const ipServices = [
            'https://ipapi.co/json/',
            'https://ipinfo.io/json',
            'https://api.ipify.org?format=json'
        ];
        
        function tryIPService(index) {
            if (index >= ipServices.length) {
                console.log('AlhadiyaTracker: All IP services failed');
                trackEvent('geolocation_info', 'IP Geolocation', 'Failed to get location info');
                return;
            }
            
            $.get(ipServices[index])
                .done(function(data) {
                    let locationString = '';
                    if (data.city && data.country) {
                        locationString = `IP: ${data.ip || 'unknown'} | City: ${data.city} | Country: ${data.country} | ISP: ${data.org || data.isp || 'unknown'}`;
                    } else if (data.ip) {
                        locationString = `IP: ${data.ip} | Location: unknown`;
                    }
                    
                    if (locationString) {
                        trackEvent('geolocation_info', 'IP Geolocation', locationString);
                        console.log('AlhadiyaTracker: Geolocation obtained', data);
                    }
                })
                .fail(function() {
                    console.log('AlhadiyaTracker: IP service ' + (index + 1) + ' failed, trying next...');
                    tryIPService(index + 1);
                });
        }
        
        // Start with first service
        tryIPService(0);
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