import './bootstrap';
import Alpine from 'alpinejs';
import sdk from '@farcaster/frame-sdk';

window.Alpine = Alpine;

// Farcaster Frame Initialization
const initFarcaster = async () => {
    try {
        const context = await sdk.context;
        console.log('Farcaster Context:', context);
        
        // Signal to the Farcaster client that the app is ready
        sdk.actions.ready();
    } catch (error) {
        console.error('Farcaster SDK initialization failed:', error);
    }
};

initFarcaster();
Alpine.start();