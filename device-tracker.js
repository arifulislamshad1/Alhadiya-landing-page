/**
 * Comprehensive Device Tracking System
 * WordPress Theme: Alhadiya Organic Mehendi Course
 * Version: 2.0
 * Features: Language, Timezone, Connection, Battery, Memory, CPU, Touch, Browser, Scroll, Clicks, Keypress
 */

(function($) {
    'use strict';
    
    // Global variables
    let deviceTracker = {
        sessionId: null,
        isTracking: false,
        trackingData: {},
        scrollDepth: 0,
        clickCount: 0,
        keypressCount: 0,
        mouseMovements: 0,
        batteryStatus: null,
        connectionInfo: null,
        performanceData: {},
        lastBatteryUpdate: 0,
        lastConnectionUpdate: 0
    };

    // Initialize tracking when DOM is ready
    $(document).ready(function() {
        if (typeof ajax_object !== 'undefined') {
            initializeDeviceTracking();
        }
    });

    /**
     * Initialize comprehensive device tracking
     */
    function initializeDeviceTracking() {
        // Get or create session ID
        deviceTracker.sessionId = getCookie('device_session') || generateSessionId();
        
        // Collect initial device information
        collectDeviceInformation();
        
        // Set up event listeners
        setupEventListeners();
        
        // Send initial data
        sendDeviceData();
        
        // Set up periodic updates
        setupPeriodicUpdates();
        
        deviceTracker.isTracking = true;
        console.log('Device tracking initialized successfully');
    }

    /**
     * Collect comprehensive device information
     */
    function collectDeviceInformation() {
        // Browser and basic info
        const userAgent = navigator.userAgent;
        const browserInfo = getBrowserInfo();
        
        // Screen and viewport information
        const screenInfo = getScreenInfo();
        
        // Language and timezone
        const languageInfo = getLanguageInfo();
        
        // Connection information
        getConnectionInfo();
        
        // Battery information
        getBatteryInfo();
        
        // Memory and performance
        getPerformanceInfo();
        
        // CPU cores
        const cpuCores = navigator.hardwareConcurrency || 'unknown';
        
        // Touch capability
        const touchCapable = getTouchCapability();
        
        // Device orientation
        const orientation = getDeviceOrientation();
        
        // Compile tracking data
        deviceTracker.trackingData = {
            // Browser Information
            browser: browserInfo.name,
            browser_version: browserInfo.version,
            user_agent: userAgent,
            
            // Screen Information
            screen_size: screenInfo.screenSize,
            screen_resolution: screenInfo.screenResolution,
            viewport_size: screenInfo.viewportSize,
            device_orientation: orientation,
            
            // Language and Location
            language: languageInfo.language,
            timezone: languageInfo.timezone,
            
            // Hardware Information
            cpu_cores: cpuCores,
            touchscreen_detected: touchCapable ? 1 : 0,
            
            // Performance data will be updated separately
            memory_info: null,
            storage_info: null,
            battery_level: null,
            battery_charging: null,
            connection_type: null,
            connection_speed: null
        };
    }

    /**
     * Get browser information
     */
    function getBrowserInfo() {
        const userAgent = navigator.userAgent;
        let browser = 'Unknown';
        let version = 'Unknown';
        
        // Chrome
        if (userAgent.indexOf('Chrome') > -1 && userAgent.indexOf('Edg') === -1) {
            browser = 'Chrome';
            version = userAgent.match(/Chrome\/([0-9.]+)/)?.[1] || 'Unknown';
        }
        // Firefox
        else if (userAgent.indexOf('Firefox') > -1) {
            browser = 'Firefox';
            version = userAgent.match(/Firefox\/([0-9.]+)/)?.[1] || 'Unknown';
        }
        // Safari
        else if (userAgent.indexOf('Safari') > -1 && userAgent.indexOf('Chrome') === -1) {
            browser = 'Safari';
            version = userAgent.match(/Version\/([0-9.]+)/)?.[1] || 'Unknown';
        }
        // Edge
        else if (userAgent.indexOf('Edg') > -1) {
            browser = 'Edge';
            version = userAgent.match(/Edg\/([0-9.]+)/)?.[1] || 'Unknown';
        }
        // Internet Explorer
        else if (userAgent.indexOf('Trident') > -1) {
            browser = 'Internet Explorer';
            version = userAgent.match(/rv:([0-9.]+)/)?.[1] || 'Unknown';
        }
        
        return { name: browser, version: version };
    }

    /**
     * Get screen and viewport information
     */
    function getScreenInfo() {
        return {
            screenSize: screen.width + 'x' + screen.height,
            screenResolution: screen.width * (window.devicePixelRatio || 1) + 'x' + screen.height * (window.devicePixelRatio || 1),
            viewportSize: window.innerWidth + 'x' + window.innerHeight
        };
    }

    /**
     * Get language and timezone information
     */
    function getLanguageInfo() {
        const language = navigator.language || navigator.userLanguage || 'en-US';
        const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone || 'UTC';
        
        return { language, timezone };
    }

    /**
     * Get touch capability
     */
    function getTouchCapability() {
        return 'ontouchstart' in window || navigator.maxTouchPoints > 0 || navigator.msMaxTouchPoints > 0;
    }

    /**
     * Get device orientation
     */
    function getDeviceOrientation() {
        if (screen.orientation) {
            return screen.orientation.type;
        } else if (window.orientation !== undefined) {
            return Math.abs(window.orientation) === 90 ? 'landscape' : 'portrait';
        } else {
            return window.innerWidth > window.innerHeight ? 'landscape' : 'portrait';
        }
    }

    /**
     * Get connection information
     */
    function getConnectionInfo() {
        if ('connection' in navigator) {
            const connection = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
            if (connection) {
                deviceTracker.connectionInfo = {
                    type: connection.effectiveType || connection.type || 'unknown',
                    speed: connection.downlink ? connection.downlink + ' Mbps' : 'unknown'
                };
                
                // Listen for connection changes
                connection.addEventListener('change', function() {
                    deviceTracker.connectionInfo = {
                        type: connection.effectiveType || connection.type || 'unknown',
                        speed: connection.downlink ? connection.downlink + ' Mbps' : 'unknown'
                    };
                    
                    // Throttle connection updates
                    if (Date.now() - deviceTracker.lastConnectionUpdate > 5000) {
                        sendConnectionUpdate();
                        deviceTracker.lastConnectionUpdate = Date.now();
                    }
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
                deviceTracker.batteryStatus = {
                    level: battery.level,
                    charging: battery.charging
                };
                
                // Listen for battery changes
                battery.addEventListener('chargingchange', function() {
                    deviceTracker.batteryStatus.charging = battery.charging;
                    sendBatteryUpdate();
                });
                
                battery.addEventListener('levelchange', function() {
                    deviceTracker.batteryStatus.level = battery.level;
                    sendBatteryUpdate();
                });
            }).catch(function(error) {
                console.log('Battery API not supported:', error);
            });
        }
    }

    /**
     * Get performance and memory information
     */
    function getPerformanceInfo() {
        // Memory information
        if ('memory' in performance) {
            deviceTracker.performanceData.memory = {
                used: performance.memory.usedJSHeapSize / 1024 / 1024, // MB
                total: performance.memory.totalJSHeapSize / 1024 / 1024, // MB
                limit: performance.memory.jsHeapSizeLimit / 1024 / 1024 // MB
            };
        }
        
        // Storage estimation
        if ('storage' in navigator && 'estimate' in navigator.storage) {
            navigator.storage.estimate().then(function(estimate) {
                deviceTracker.performanceData.storage = {
                    quota: estimate.quota ? (estimate.quota / 1024 / 1024 / 1024).toFixed(2) : 'unknown', // GB
                    usage: estimate.usage ? (estimate.usage / 1024 / 1024).toFixed(2) : 'unknown' // MB
                };
            });
        }
    }

    /**
     * Setup event listeners for user interactions
     */
    function setupEventListeners() {
        // Scroll depth tracking
        $(window).on('scroll', throttle(trackScrollDepth, 250));
        
        // Click tracking
        $(document).on('click', trackClicks);
        
        // Keypress tracking
        $(document).on('keypress', trackKeypress);
        
        // Mouse movement tracking (throttled)
        $(document).on('mousemove', throttle(trackMouseMovement, 500));
        
        // Window resize tracking
        $(window).on('resize', throttle(trackWindowResize, 1000));
        
        // Visibility change tracking
        $(document).on('visibilitychange', trackVisibilityChange);
        
        // Before unload - send final data
        $(window).on('beforeunload', function() {
            sendFinalData();
        });
    }

    /**
     * Track scroll depth
     */
    function trackScrollDepth() {
        const scrollTop = $(window).scrollTop();
        const documentHeight = $(document).height();
        const windowHeight = $(window).height();
        const scrollPercent = Math.round((scrollTop / (documentHeight - windowHeight)) * 100);
        
        if (scrollPercent > deviceTracker.scrollDepth && scrollPercent <= 100) {
            deviceTracker.scrollDepth = scrollPercent;
            
            // Send scroll depth milestones
            if (scrollPercent % 25 === 0 || scrollPercent === 100) {
                sendEventData('scroll_depth', 'Scroll Milestone', scrollPercent + '%');
            }
        }
    }

    /**
     * Track clicks
     */
    function trackClicks(event) {
        deviceTracker.clickCount++;
        
        const element = event.target;
        const elementInfo = {
            tag: element.tagName.toLowerCase(),
            id: element.id || '',
            class: element.className || '',
            text: $(element).text().substring(0, 50) || ''
        };
        
        // Track important clicks
        if (elementInfo.tag === 'button' || elementInfo.tag === 'a' || elementInfo.class.includes('btn')) {
            sendEventData('click', 'Element Click', JSON.stringify(elementInfo));
        }
    }

    /**
     * Track keypress
     */
    function trackKeypress(event) {
        deviceTracker.keypressCount++;
        
        // Track every 10 keypresses to avoid spam
        if (deviceTracker.keypressCount % 10 === 0) {
            sendEventData('keypress', 'Keypress Count', deviceTracker.keypressCount.toString());
        }
    }

    /**
     * Track mouse movement
     */
    function trackMouseMovement() {
        deviceTracker.mouseMovements++;
    }

    /**
     * Track window resize
     */
    function trackWindowResize() {
        const newViewportSize = window.innerWidth + 'x' + window.innerHeight;
        const newOrientation = getDeviceOrientation();
        
        sendEventData('resize', 'Viewport Resize', newViewportSize);
        sendEventData('orientation', 'Orientation Change', newOrientation);
    }

    /**
     * Track visibility change
     */
    function trackVisibilityChange() {
        const isVisible = !document.hidden;
        sendEventData('visibility', 'Visibility Change', isVisible ? 'visible' : 'hidden');
    }

    /**
     * Send device data to server
     */
    function sendDeviceData() {
        const data = {
            action: 'update_client_device_details',
            nonce: ajax_object.device_info_nonce,
            session_id: deviceTracker.sessionId,
            ...deviceTracker.trackingData
        };
        
        // Add current performance data
        if (deviceTracker.performanceData.memory) {
            data.memory_info = deviceTracker.performanceData.memory.used;
        }
        
        if (deviceTracker.performanceData.storage) {
            data.storage_info = parseFloat(deviceTracker.performanceData.storage.usage);
        }
        
        if (deviceTracker.batteryStatus) {
            data.battery_level = deviceTracker.batteryStatus.level;
            data.battery_charging = deviceTracker.batteryStatus.charging ? 1 : 0;
        }
        
        if (deviceTracker.connectionInfo) {
            data.connection_type = deviceTracker.connectionInfo.type;
            data.connection_speed = deviceTracker.connectionInfo.speed;
        }
        
        $.post(ajax_object.ajax_url, data)
            .done(function(response) {
                console.log('Device data sent successfully');
            })
            .fail(function(xhr, status, error) {
                console.error('Failed to send device data:', error);
            });
    }

    /**
     * Send event data
     */
    function sendEventData(eventType, eventName, eventValue) {
        const data = {
            action: 'track_custom_event',
            nonce: ajax_object.event_nonce,
            session_id: deviceTracker.sessionId,
            event_type: eventType,
            event_name: eventName,
            event_value: eventValue.toString()
        };
        
        $.post(ajax_object.ajax_url, data)
            .done(function(response) {
                // console.log('Event tracked:', eventType);
            })
            .fail(function(xhr, status, error) {
                console.error('Failed to track event:', error);
            });
    }

    /**
     * Send battery update
     */
    function sendBatteryUpdate() {
        if (Date.now() - deviceTracker.lastBatteryUpdate > 10000) { // Throttle to every 10 seconds
            const batteryData = JSON.stringify(deviceTracker.batteryStatus);
            sendEventData('battery_status_change', 'Battery Status', batteryData);
            deviceTracker.lastBatteryUpdate = Date.now();
        }
    }

    /**
     * Send connection update
     */
    function sendConnectionUpdate() {
        const connectionData = JSON.stringify(deviceTracker.connectionInfo);
        sendEventData('connection_change', 'Connection Type', connectionData);
    }

    /**
     * Send final data before page unload
     */
    function sendFinalData() {
        const finalData = {
            action: 'update_client_device_details',
            nonce: ajax_object.device_info_nonce,
            session_id: deviceTracker.sessionId,
            scroll_depth_max: deviceTracker.scrollDepth,
            click_count: deviceTracker.clickCount,
            keypress_count: deviceTracker.keypressCount,
            mouse_movements: deviceTracker.mouseMovements
        };
        
        // Use sendBeacon for reliable data sending on page unload
        if (navigator.sendBeacon) {
            const formData = new FormData();
            Object.keys(finalData).forEach(key => {
                formData.append(key, finalData[key]);
            });
            navigator.sendBeacon(ajax_object.ajax_url, formData);
        } else {
            // Fallback to synchronous AJAX
            $.ajaxSetup({async: false});
            $.post(ajax_object.ajax_url, finalData);
            $.ajaxSetup({async: true});
        }
    }

    /**
     * Setup periodic updates
     */
    function setupPeriodicUpdates() {
        // Update performance data every 30 seconds
        setInterval(function() {
            getPerformanceInfo();
            sendDeviceData();
        }, 30000);
        
        // Send activity summary every 60 seconds
        setInterval(function() {
            sendEventData('activity_summary', 'User Activity', JSON.stringify({
                clicks: deviceTracker.clickCount,
                keypress: deviceTracker.keypressCount,
                scrollDepth: deviceTracker.scrollDepth,
                mouseMovements: deviceTracker.mouseMovements
            }));
        }, 60000);
    }

    /**
     * Utility functions
     */
    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
        return null;
    }

    function generateSessionId() {
        return 'session_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
    }

    function throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        }
    }

    // Expose deviceTracker globally for debugging
    window.deviceTracker = deviceTracker;

})(jQuery);