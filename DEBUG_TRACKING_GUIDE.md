# 🐛 Alhadiya Device Tracking - Debug Guide

## 🔍 **Event Logging সমস্যা সমাধান**

### **✅ এখন যা ঠিক করা হয়েছে:**

#### **1. Enhanced Event Tracking:**
- ✅ **Click Events**: এখন X/Y position, element details, text content সহ full info track করে
- ✅ **Keypress Events**: Key name, code, target element, modifiers সব details
- ✅ **Device Info**: Browser, OS, battery, connection সব detailed information
- ✅ **Scroll Events**: Percentage, position, viewport details
- ✅ **Activity Summary**: Session time, clicks, keys, scroll depth সব info

#### **2. Debug Features যোগ করা হয়েছে:**
- ✅ **Console Logging**: সব events console এ দেখা যাবে
- ✅ **Error Handling**: Failed events এর details
- ✅ **Test Events**: Automatic test events tracking verify করার জন্য
- ✅ **Fallback System**: Main tracker fail হলে backup system

#### **3. Event Types (Total 30+ Types):**
```
✅ Device Information Events:
   - device_info_detailed
   - battery_info_detailed
   - connection_info_detailed
   - battery_status_change
   - battery_level_change
   - connection_change

✅ User Interaction Events:
   - click_detailed
   - keypress_detailed
   - keypress_milestone
   - last_click_position

✅ Scroll Events:
   - scroll_milestone (25%, 50%, 75%, 100%)
   - scroll_progress (every 10%)

✅ Activity Tracking:
   - activity_summary (every 30 seconds)
   - tracker_test
   - fallback_page_view

✅ All Other Events:
   - page_view, section_view, button_click
   - form_field_focus, form_field_change
   - order events, video events, FAQ events
```

## 🔧 **Testing Instructions:**

### **1. Check Console (F12 > Console):**
আপনার browser console এ এই messages দেখতে পাবেন:
```
✅ Footer tracking script loaded
✅ AlhadiyaTracker: Initialized successfully
✅ AlhadiyaTracker: Sending event
✅ AlhadiyaTracker: Event tracked successfully
```

### **2. Test Events:**
- **Page Load**: সাইট open করার সাথে সাথে
- **Click Test**: যেকোনো জায়গায় click করুন
- **Key Test**: কোনো input field এ type করুন
- **Scroll Test**: page scroll করুন
- **Battery Test**: Mobile device এ battery status change করুন

### **3. Admin Panel Check:**
1. WordPress Admin > Device Tracking
2. Live Tracking page দেখুন
3. Session Details এ events log check করুন
4. Filter করে specific events দেখুন

## 📊 **Expected Event Details:**

### **Click Event Example:**
```
Event Type: click_detailed
Event Name: Click Event
Event Value: Position: X:860, Y:274 | Element: button#submit-order-btn | Text: "অর্ডার করুন"
```

### **Keypress Event Example:**
```
Event Type: keypress_detailed
Event Name: Key Pressed
Event Value: Key: "a" (KeyA) | Target: input#customer_name | Modifiers: 
```

### **Device Info Example:**
```
Event Type: device_info_detailed
Event Name: Device Information Collected
Event Value: IP Info: en-US | Browser: Microsoft Edge 138.0.0.0 | OS: Windows | Device: Desktop | Screen: 1920x1080 | CPU: 8 cores | Touch: No
```

### **Battery Info Example:**
```
Event Type: battery_info_detailed
Event Name: Battery Information
Event Value: Battery Level: 54% | Charging: Yes | Charging Time: 3600s | Discharging Time: Unknown
```

### **Activity Summary Example:**
```
Event Type: activity_summary
Event Name: User Activity Summary
Event Value: Session Time: 2m 15s | Last Activity: 5s ago | Scroll: 45% | Clicks: 12 | Keys: 23 | Mouse: 156 | Current Section: course-section-1 | Last Key: a
```

## 🚀 **Performance Optimizations:**

### **Database Performance:**
- ✅ Events 90 দিন পর auto-delete
- ✅ Database indexing optimized
- ✅ Rate limiting (30 req/min per IP)

### **JavaScript Performance:**
- ✅ Event throttling (scroll, mouse move)
- ✅ Batch processing
- ✅ Memory management (50 clicks stored max)

## 🔍 **Troubleshooting:**

### **If Events Not Showing:**
1. **Check Console**: F12 > Console tab
2. **Check Network**: F12 > Network tab, look for admin-ajax.php calls
3. **Check Nonces**: Make sure nonces are available
4. **Check Session**: Make sure session_id is generated

### **Common Issues:**
- ❌ **Ajax Object Missing**: Make sure jQuery is loaded
- ❌ **Nonce Invalid**: Clear cache and refresh
- ❌ **Database Error**: Check WordPress database connection
- ❌ **Rate Limited**: Wait 1 minute and try again

### **Debug Commands (Console):**
```javascript
// Check if tracker is loaded
console.log(typeof AlhadiyaTracker);

// Check tracker status
console.log(AlhadiyaTracker.isActive);

// Check session ID
console.log(AlhadiyaTracker.sessionId);

// Check device info
console.log(AlhadiyaTracker.deviceInfo);

// Check click positions
console.log(AlhadiyaTracker.userInteractions.clickPositions);

// Manual test event
AlhadiyaTracker.trackEvent('manual_test', 'Manual Test', 'Testing from console');
```

## ✅ **Verification Checklist:**

- [ ] Console shows "AlhadiyaTracker: Initialized successfully"
- [ ] Test events appear in admin panel
- [ ] Click events show X/Y coordinates
- [ ] Keypress events show key details
- [ ] Battery info shows percentage and charging status
- [ ] Activity summary shows every 30 seconds
- [ ] Scroll events show at 25%, 50%, 75%, 100%
- [ ] Device info shows browser, OS, screen size, CPU cores

---

**🎯 Result**: এখন আপনার event logging system সম্পূর্ণভাবে কাজ করবে এবং admin panel এ full details সহ সব events দেখতে পাবেন।