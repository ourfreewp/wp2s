(function(windowObject, documentObject) {
    const adrollDataList = windowObject.wp2s_pixels_adroll?.pixels?.adroll;
    const adrollData = Array.isArray(adrollDataList) && adrollDataList.length > 0 
        ? adrollDataList[0] 
        : null;

    if (!adrollData || !adrollData.advertiserId || !adrollData.pixelId) {
        console.error("AdRoll configuration is missing or incomplete.");
        return;
    }

    windowObject.__adroll_loaded = true;
    windowObject.adroll = windowObject.adroll || [];
    const adrollMethods = ['setProperties', 'identify', 'track', 'identify_email'];
    
    const loadExternalScript = (scriptUrl) => {
        const scriptElement = documentObject.createElement('script');
        scriptElement.async = true;
        scriptElement.src = scriptUrl;
        const firstScriptInDocument = documentObject.getElementsByTagName('script')[0];
        firstScriptInDocument.parentNode.insertBefore(scriptElement, firstScriptInDocument);
    };

    adrollMethods.forEach(methodName => {
        windowObject.adroll[methodName] = windowObject.adroll[methodName] || ((...methodArguments) => {
            windowObject.adroll.push([methodName, methodArguments]);
        });
    });

    const roundtripScriptUrl = `https://s.adroll.com/j/${adrollData.advertiserId}/roundtrip.js`;
    loadExternalScript(roundtripScriptUrl);

    windowObject.adroll.track("pageView");
})(window, document);