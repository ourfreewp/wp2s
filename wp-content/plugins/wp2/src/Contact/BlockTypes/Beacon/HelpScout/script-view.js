document.addEventListener("DOMContentLoaded", function() {
    const beaconElements = document.querySelectorAll('.beacon-contact a, .beacon-contact button');
    
    beaconElements.forEach((element) => {
        element.addEventListener('click', (event) => {
            event.preventDefault();
            console.log('Contact Button Clicked');
            Beacon('reset');
            Beacon('init', '');
            Beacon('toggle');
        });
    });

    const beacons = document.querySelectorAll('[data-beacon-id] .wp-element-button');

    beacons.forEach(beacon => {
        beacon.addEventListener('click', () => {
            const parent = beacon.parentElement;
            const beaconName = parent.getAttribute('data-beacon-name');
            const beaconText = parent.getAttribute('data-beacon-text');
            const beaconSubject = parent.getAttribute('data-beacon-subject');
            const beaconId = parent.getAttribute('data-beacon-id');

            Beacon('reset');

            Beacon('prefill', {
                subject: beaconSubject,
                text: beaconText
            });

            Beacon('toggle');
        });
    });
});