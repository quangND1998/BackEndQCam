/* eslint-env node */
const colors = require("tailwindcss/colors")
const plugin = require("tailwindcss/plugin");


module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        './resources/js/**/*.js'
    ],
    darkMode: "class", // or 'media' or 'class'
    theme: {
        asideScrollbars: {
            light: "light",
            gray: "gray",
        },
        extend: {
            zIndex: {
                "-1": "-1",
            },
            flexGrow: {
                5: "5",
            },
            maxHeight: {
                "screen-menu": "calc(100vh - 3.5rem)",
                modal: "calc(100vh - 160px)",
            },
            transitionProperty: {
                position: "right, left, top, bottom, margin, padding",
                textColor: "color",
            },
            keyframes: {
                "fade-out": {
                    from: { opacity: 1 },
                    to: { opacity: 0 },
                },
                "fade-in": {
                    from: { opacity: 0 },
                    to: { opacity: 1 },
                },
            },
            animation: {
                "fade-out": "fade-out 250ms ease-in-out",
                "fade-in": "fade-in 250ms ease-in-out",
            },

        },
        colors: {
            ...colors,
            bg_green : '#3B5F41',
            bg_green_default : '#4A7751',
            bg_green_hover : '#4A7751',
            bg_green_active : '#4A7751',
            color_green: '#E1FCEF',
            color_gray: '#8E8E8E',
            color_dark_gray: '#333333',
            color_Orange: '#FF9B00',
            white : '#FFFFFF',
            green : '#CFFFBE',
            black : '#333333',
            aside_menu_item_active : '#CFFFBE',
            btn_green: '#25CB2C',
            color_dark_green: '#14804A',
            color_dark_red: '#D12953',
            color_red: '#FAF0F3',
            color_dark_blue: '#4F5AED',
            color_blue: '#F0F1FA'

        }
    },
    plugins: [
        require("@tailwindcss/forms"),
        plugin(function ({ matchUtilities, theme }) {
            matchUtilities({
                "aside-scrollbars": (value) => {
                    const track = value === "light" ? "100" : "900";
                    const thumb = value === "light" ? "300" : "600";
                    const color = value === "light" ? "gray" : value;

                    return {
                        scrollbarWidth: "thin",
                        scrollbarColor: `${theme(`colors.${color}.${thumb}`)} ${theme(
                            `colors.${color}.${track}`
                        )}`,
                        "&::-webkit-scrollbar": {
                            width: "8px",
                            height: "8px",
                        },
                        "&::-webkit-scrollbar-track": {
                            backgroundColor: theme(`colors.${color}.${track}`),
                        },
                        "&::-webkit-scrollbar-thumb": {
                            borderRadius: "0.25rem",
                            backgroundColor: theme(`colors.${color}.${thumb}`),
                        },
                    };
                },
            },
                { values: theme("asideScrollbars") }
            );
        }),
    ],
};
