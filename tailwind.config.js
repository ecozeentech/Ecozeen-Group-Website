import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/**
 * Centralized Ecozeen Group design tokens. These are the fallback/base
 * tokens described in the brand specification. Administrator-configurable
 * theme values (see ThemeSetting) are rendered at runtime as CSS custom
 * properties in resources/views/components/theme-variables.blade.php and
 * referenced here via var(...) so the compiled Tailwind utilities stay in
 * sync with whatever the CMS persists, without requiring a rebuild for
 * routine color/typography changes.
 *
 * @type {import('tailwindcss').Config}
 */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/View/**/*.php',
        './app/Livewire/**/*.php',
    ],

    darkMode: 'class',

    theme: {
        extend: {
            colors: {
                brand: {
                    navy: 'var(--color-primary, #0F172A)',
                    blue: 'var(--color-secondary, #2563EB)',
                    cyan: 'var(--color-accent, #38BDF8)',
                    emerald: 'var(--color-success, #10B981)',
                },
                surface: {
                    canvas: 'var(--color-background, #F8FAFC)',
                    DEFAULT: 'var(--color-surface, #FFFFFF)',
                    border: 'var(--color-border, #E2E8F0)',
                },
                ink: {
                    DEFAULT: 'var(--color-text, #111827)',
                    muted: 'var(--color-text-muted, #64748B)',
                },
            },
            fontFamily: {
                heading: ['var(--font-heading)', 'Manrope', ...defaultTheme.fontFamily.sans],
                sans: ['var(--font-body)', 'Inter', ...defaultTheme.fontFamily.sans],
            },
            maxWidth: {
                container: 'var(--container-width, 1280px)',
            },
            boxShadow: {
                level1: '0 1px 3px 0 rgba(15, 23, 42, 0.04), 0 1px 2px -1px rgba(15, 23, 42, 0.02)',
                level2: '0 10px 15px -3px rgba(15, 23, 42, 0.06), 0 4px 6px -4px rgba(15, 23, 42, 0.03)',
                level3: '0 20px 25px -5px rgba(15, 23, 42, 0.1), 0 8px 10px -6px rgba(15, 23, 42, 0.04)',
            },
            borderRadius: {
                token: 'var(--radius-base, 0.5rem)',
            },
        },
    },

    plugins: [forms, typography],
};
