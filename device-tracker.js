/**
 * Simple Alhadiya Device Tracker - Working Version
 * Similar to GitHub first upload - Reliable and Simple
 */

(function($) {
    'use strict';
    
    // Simple global tracker object
    window.deviceTracker = {
        sessionId: null,
        isTracking: false,
        deviceData: {},
        userActivity: {
            scrollDepth: 0,
            clickCount: 0,
            keypressCount: 0,
            lastKeyPressed: '',
            timeSpent: 0
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        initializeDeviceTracker();
    });

    /**
     * Initialize device tracking
     */
    function initializeDeviceTracker() {
        // Generate session ID
        deviceTracker.sessionId = generateSessionId();
        
        // Collect basic device info
        collectDeviceInfo();
        
        // Send initial device info
        sendDeviceInfo();
        
        // Setup event listeners
        setupEventListeners();
        
        // Start tracking
        deviceTracker.isTracking = true;
        
        // Send page view event
        trackEvent('page_view', 'Page Load', window.location.href);
        
        console.log('Device Tracker initialized successfully');
    }

    /**
     * Generate unique session ID
     */
    function generateSessionId() {
        return 'sess_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
    }

    /**
     * Collect device information
     */
    function collectDeviceInfo() {
        // Get user agent info
        const userAgent = navigator.userAgent;
        
        // Basic device detection
        deviceTracker.deviceData = {
            user_agent: userAgent,
            language: navigator.language || 'unknown',
            timezone: getTimezone(),
            screen_size: screen.width + 'x' + screen.height,
            device_type: getDeviceType(userAgent),
            browser: getBrowserName(userAgent),
            os: getOSName(userAgent),
            touchscreen: isTouchDevice(),
            cpu_cores: navigator.hardwareConcurrency || 'unknown'
        };
        
        // Get battery info if available
        if ('getBattery' in navigator) {
            navigator.getBattery().then(function(battery) {
                deviceTracker.deviceData.battery_level = Math.round(battery.level * 100);
                deviceTracker.deviceData.battery_charging = battery.charging;
                
                // Track battery info
                trackEvent('battery_info', 'Battery Status', 
                    'Level: ' + deviceTracker.deviceData.battery_level + '%, Charging: ' + 
                    (deviceTracker.deviceData.battery_charging ? 'Yes' : 'No'));
            });
        }
        
        // Get connection info if available
        if ('connection' in navigator) {
            const connection = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
            if (connection) {
                deviceTracker.deviceData.connection_type = connection.effectiveType || connection.type || 'unknown';
                trackEvent('connection_info', 'Connection Type', deviceTracker.deviceData.connection_type);
            }
        }
    }

    /**
     * Get timezone
     */
    function getTimezone() {
        try {
            return Intl.DateTimeFormat().resolvedOptions().timeZone;
        } catch (e) {
            return 'UTC';
        }
    }

    /**
     * Get device type
     */
    function getDeviceType(userAgent) {
        if (/tablet|ipad|playbook|silk/i.test(userAgent)) {
            return 'Tablet';
        }
        if (/mobile|iphone|ipod|android|blackberry|opera|mini|windows\sce|palm|smartphone|iemobile/i.test(userAgent)) {
            return 'Mobile';
        }
        return 'Desktop';
    }

    /**
     * Get browser name
     */
    function getBrowserName(userAgent) {
        if (userAgent.indexOf('Chrome') > -1) return 'Chrome';
        if (userAgent.indexOf('Firefox') > -1) return 'Firefox';
        if (userAgent.indexOf('Safari') > -1) return 'Safari';
        if (userAgent.indexOf('Edge') > -1) return 'Edge';
        if (userAgent.indexOf('Opera') > -1) return 'Opera';
        return 'Unknown';
    }

    /**
     * Get OS name
     */
    function getOSName(userAgent) {
        if (userAgent.indexOf('Windows NT') > -1) return 'Windows';
        if (userAgent.indexOf('Mac OS') > -1) return 'MacOS';
        if (userAgent.indexOf('Android') > -1) return 'Android';
        if (userAgent.indexOf('iPhone') > -1 || userAgent.indexOf('iPad') > -1) return 'iOS';
        if (userAgent.indexOf('Linux') > -1) return 'Linux';
        return 'Unknown';
    }

    /**
     * Check if touch device
     */
    function isTouchDevice() {
        return 'ontouchstart' in window || navigator.maxTouchPoints > 0;
    }

    /**
     * Send device info to server
     */
    function sendDeviceInfo() {
        if (typeof ajax_object === 'undefined') {
            console.log('Ajax object not available');
            return;
        }

        $.post(ajax_object.ajax_url, {
            action: 'update_client_device_details',
            nonce: ajax_object.device_info_nonce,
            session_id: deviceTracker.sessionId,
            device_data: JSON.stringify(deviceTracker.deviceData)
        }).done(function(response) {
            console.log('Device info sent successfully');
        }).fail(function() {
            console.log('Failed to send device info');
        });
    }

    /**
     * Setup event listeners
     */
    function setupEventListeners() {
        // Scroll tracking
        let scrollTimeout;
        $(window).on('scroll', function() {
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(function() {
                trackScrolling();
            }, 200);
        });

        // Click tracking
        $(document).on('click', function(e) {
            trackClick(e);
        });

        // Keypress tracking
        $(document).on('keypress', function(e) {
            trackKeypress(e);
        });

        // Button tracking for specific buttons
        $('.float').on('click', function() {
            trackEvent('button_click', 'WhatsApp Button', 'WhatsApp button clicked');
        });

        $('.callbtnlaptop').on('click', function() {
            trackEvent('button_click', 'Call Button', 'Call button clicked');
        });

        // Payment method tracking
        $('input[type="radio"]').on('change', function() {
            if ($(this).is(':checked')) {
                const value = $(this).val();
                if (value && (value.includes('bkash') || value.includes('nagad') || value.includes('rocket'))) {
                    trackEvent('payment_method_select', 'Payment Method Selected', value);
                }
            }
        });

        // Section tracking with IntersectionObserver
        setupSectionTracking();

        // Send activity summary every 30 seconds
        setInterval(function() {
            sendActivitySummary();
        }, 30000);
    }

    /**
     * Track scrolling
     */
    function trackScrolling() {
        const scrollTop = $(window).scrollTop();
        const documentHeight = $(document).height();
        const windowHeight = $(window).height();
        const scrollPercent = Math.round((scrollTop / (documentHeight - windowHeight)) * 100);

        if (scrollPercent > deviceTracker.userActivity.scrollDepth && scrollPercent <= 100) {
            deviceTracker.userActivity.scrollDepth = scrollPercent;
            
            // Track major scroll milestones
            if (scrollPercent % 25 === 0 || scrollPercent === 100) {
                trackEvent('scroll_depth', 'Scroll Depth', scrollPercent + '%');
            }
        }
    }

    /**
     * Track clicks
     */
    function trackClick(event) {
        deviceTracker.userActivity.clickCount++;
        
        const clickData = {
            x: event.pageX,
            y: event.pageY,
            element: event.target.tagName.toLowerCase(),
            time: new Date().toISOString()
        };

        trackEvent('click_position', 'Click Position', 
            'X:' + clickData.x + ', Y:' + clickData.y + ' on ' + clickData.element);
    }

    /**
     * Track keypresses
     */
    function trackKeypress(event) {
        deviceTracker.userActivity.keypressCount++;
        deviceTracker.userActivity.lastKeyPressed = event.key;
        
        // Track every 10 keypresses
        if (deviceTracker.userActivity.keypressCount % 10 === 0) {
            trackEvent('key_press', 'Key Press Count', deviceTracker.userActivity.keypressCount);
        }
    }

    /**
     * Setup section tracking
     */
    function setupSectionTracking() {
        // Section mapping
        const sections = [
            'course-section-1',
            'course-section-2', 
            'course-section-3',
            'review-section'
        ];

        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        const sectionId = entry.target.id;
                        let sectionName = '';
                        
                        switch(sectionId) {
                            case 'course-section-1':
                                sectionName = 'অর্গানিক মেহেদী তৈরির সহজ উপায়';
                                break;
                            case 'course-section-2':
                                sectionName = 'মেহেদী রঙ বাড়ানোর গোপন টিপস';
                                break;
                            case 'course-section-3':
                                sectionName = 'প্যাকেজিং ও সার্টিফিকেশন';
                                break;
                            case 'review-section':
                                sectionName = 'Review Section';
                                break;
                            default:
                                sectionName = sectionId;
                        }
                        
                        trackEvent('section_view', 'Section Viewed', sectionName);
                    }
                });
            }, { threshold: 0.5 });

            // Observe all sections
            sections.forEach(function(sectionId) {
                const element = document.getElementById(sectionId);
                if (element) {
                    observer.observe(element);
                }
            });
        }
    }

    /**
     * Send activity summary
     */
    function sendActivitySummary() {
        if (!deviceTracker.isTracking) return;

        deviceTracker.userActivity.timeSpent = Math.round((Date.now() - (deviceTracker.startTime || Date.now())) / 1000);
        
        const summary = 'Time: ' + Math.floor(deviceTracker.userActivity.timeSpent / 60) + 'm ' + 
                       (deviceTracker.userActivity.timeSpent % 60) + 's, ' +
                       'Scroll: ' + deviceTracker.userActivity.scrollDepth + '%, ' +
                       'Clicks: ' + deviceTracker.userActivity.clickCount + ', ' +
                       'Keys: ' + deviceTracker.userActivity.keypressCount;
        
        trackEvent('activity_summary', 'User Activity', summary);
    }

    /**
     * Track custom event
     */
    function trackEvent(eventType, eventName, eventValue) {
        if (typeof ajax_object === 'undefined') return;

        const data = {
            action: 'track_custom_event',
            nonce: ajax_object.event_nonce,
            session_id: deviceTracker.sessionId,
            event_type: eventType,
            event_name: eventName,
            event_value: eventValue || ''
        };

        $.post(ajax_object.ajax_url, data)
            .done(function(response) {
                console.log('Event tracked: ' + eventType);
            })
            .fail(function() {
                console.log('Failed to track event: ' + eventType);
            });
    }

    // Make trackEvent available globally for manual tracking
    window.trackEvent = trackEvent;

})(jQuery);