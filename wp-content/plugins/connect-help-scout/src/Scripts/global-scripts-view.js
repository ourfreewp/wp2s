// HelpScout Beacon Loader and Configuration
(function(window, document, Beacon) {
    function loadBeaconScript() {
        var firstScript = document.getElementsByTagName("script")[0];
        var beaconScript = document.createElement("script");
        
        beaconScript.type = "text/javascript";
        beaconScript.async = true;
        beaconScript.src = "https://beacon-v2.helpscout.net";
        firstScript.parentNode.insertBefore(beaconScript, firstScript);
    }

    if (!window.Beacon) {
        window.Beacon = function(method, options, data) {
            window.Beacon.readyQueue.push({
                method: method,
                options: options,
                data: data
            });
        };
        window.Beacon.readyQueue = [];
    }

    if (document.readyState === "complete") {
        loadBeaconScript();
    } else {
        window.addEventListener("load", loadBeaconScript);
    }

    document.addEventListener("DOMContentLoaded", function() {
        if (
            window.wp2s &&
            wp2s.help_scout &&
            wp2s.help_scout.beacons
        ) {
            // Iterate over each Beacon ID and initialize it with the associated config
            Object.entries(wp2s.help_scout.beacons).forEach(([beaconId, config]) => {
                console.log('Initializing HelpScout Beacon with ID:', beaconId);
                
                // Apply configuration if available for this specific Beacon ID
                if (config) {
                    console.log('Applying Configuration for Beacon:', beaconId, config);
                    window.Beacon('config', config);
                }
            });
        } else {
            console.warn('No HelpScout Beacon IDs found.');
        }
    });

})(window, document, window.Beacon || {});