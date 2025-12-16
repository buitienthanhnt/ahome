import './bootstrap';
import '../css/app.css';
/**import swiper css library */
// import 'swiper/css';
// import 'swiper/css/navigation';
// import 'swiper/css/pagination';
/**import slick css source library */
import "slick-carousel/slick/slick.css";
import "slick-carousel/slick/slick-theme.css";
import '../css/custom/swiper/pagination.css';

import { createRoot } from 'react-dom/client';
import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

// const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    id: 'acar-global', // default id='app' (custom id must be sync with inertia function in blade template file app.blade.php)
    title: (title) => title, // title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.tsx`, import.meta.glob('./Pages/**/*.tsx')),
    setup({ el, App, props }) {
        const root = createRoot(el);
        root.render(<App {...props} />);
    },
    progress: {
        color: '#4B5563',
        showSpinner: true,
    },
});
