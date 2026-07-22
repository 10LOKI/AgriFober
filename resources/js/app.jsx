import './bootstrap';
import { createRoot } from 'react-dom/client';
import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { AnimatePresence, motion } from 'framer-motion';

createInertiaApp({
    title: (title) => (title ? `${title} — Agrifober` : 'Agrifober'),
    resolve: (name) =>
        resolvePageComponent(`./Pages/${name}.jsx`, import.meta.glob('./Pages/**/*.jsx')),
    setup({ el, App, props }) {
        createRoot(el).render(
            <App {...props}>
                {({ Component, props: pageProps, key }) => (
                    <AnimatePresence mode="wait" initial={false}>
                        <motion.div
                            key={key}
                            initial={{ opacity: 0, y: 14 }}
                            animate={{ opacity: 1, y: 0 }}
                            exit={{ opacity: 0, y: -14 }}
                            transition={{ duration: 0.32, ease: [0.4, 0, 0.2, 1] }}
                        >
                            <Component {...pageProps} />
                        </motion.div>
                    </AnimatePresence>
                )}
            </App>,
        );
    },
    progress: {
        color: '#059669',
    },
});
