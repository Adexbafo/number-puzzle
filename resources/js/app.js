import './bootstrap';
import Alpine from 'alpinejs';
import sdk from '@farcaster/frame-sdk';

window.Alpine = Alpine;
window.farcaster = sdk;


// Farcaster Frame Initialization
const initFarcaster = async () => {
    // Immediately signal ready to clear the splash screen
    sdk.actions.ready();

    try {
        const context = await sdk.context;
        console.log('Farcaster Context:', context);
    } catch (error) {
        console.error('Farcaster SDK initialization failed:', error);
    }
};


initFarcaster();
Alpine.start();