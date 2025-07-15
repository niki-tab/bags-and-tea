/**
 * Alpine.js Components
 * 
 * This file contains reusable Alpine.js components for the application
 */

window.cookieConsent = function() {
    return {
        show: localStorage.getItem('cookieConsent') === null,
        showModal: false,
        preferences: {
            necessary: true,
            analytics: true,
            marketing: true,
            functional: true
        },
        accept() {
            localStorage.setItem('cookieConsent', 'accepted');
            localStorage.setItem('cookiePreferences', JSON.stringify(this.preferences));
            this.show = false;
            this.showModal = false;
        },
        reject() {
            this.preferences = {
                necessary: true,
                analytics: false,
                marketing: false,
                functional: false
            };
            localStorage.setItem('cookieConsent', 'rejected');
            localStorage.setItem('cookiePreferences', JSON.stringify(this.preferences));
            this.show = false;
            this.showModal = false;
        },
        personalize() {
            this.showModal = true;
        },
        savePreferences() {
            localStorage.setItem('cookieConsent', 'customized');
            localStorage.setItem('cookiePreferences', JSON.stringify(this.preferences));
            this.show = false;
            this.showModal = false;
        },
        closeModal() {
            this.showModal = false;
        }
    }
}