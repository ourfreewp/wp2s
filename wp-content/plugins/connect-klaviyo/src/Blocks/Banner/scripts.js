document.addEventListener('DOMContentLoaded', function () {
    // Loop through all window properties
    Object.keys(window).forEach(key => {
        // Check for wp2s_klaviyo_* objects
        if (key.startsWith('wp2_klaviyo_')) {
            const data = window[key];
            
            // Check if 'type' exists and add it as a body class
            if (data.type) {
                document.body.classList.add('wp2-klaviyo-' + data.type);
            }
        }
    });
});