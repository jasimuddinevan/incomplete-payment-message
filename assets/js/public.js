jQuery(document).ready(function($) {
    // Only run if the overlay exists
    if ($('#ipm-message-overlay').length) {
        var settings = ipm_settings || {};
        var popupDelay = settings.popup_delay || 120000; // 2 minutes default
        var minDisplayTime = settings.min_display_time || 20000; // 20 seconds default
        
        // Show the popup after delay
        setTimeout(function() {
            $('#ipm-message-overlay').removeClass('ipm-hidden');
            
            // Start countdown timer
            var secondsLeft = minDisplayTime / 1000;
            updateCountdown(secondsLeft);
            
            var countdownInterval = setInterval(function() {
                secondsLeft--;
                updateCountdown(secondsLeft);
                
                if (secondsLeft <= 0) {
                    clearInterval(countdownInterval);
                    $('#ipm-close-btn').prop('disabled', false).text('Close');
                }
            }, 1000);
            
            // Close button handler
            $('#ipm-close-btn').click(function() {
                if (!$(this).prop('disabled')) {
                    $('#ipm-message-overlay').addClass('ipm-hidden');
                }
            });
        }, popupDelay);
        
        // Update countdown display
        function updateCountdown(seconds) {
            $('#ipm-countdown').text('You can close this message in ' + seconds + ' seconds');
            $('#ipm-close-btn').text('Close (' + seconds + 's)');
        }
    }
});