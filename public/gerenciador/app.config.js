(function() {
    'use strict';

    angular
        .module('painel-inteligencia')
        .config(theme);

    function theme($mdThemingProvider){

        // Extend the red theme with a few different colors
        var customColor = $mdThemingProvider.extendPalette('cyan', {
            '500': '535a5a',
            'contrastDefaultColor': 'light'
        });

        // Register the new color palette map with the name <code>neonRed</code>
        $mdThemingProvider.definePalette('customColor', customColor);

        // Use that theme for the primary intentions
        $mdThemingProvider.theme('default').primaryPalette('customColor');

    }
})();
