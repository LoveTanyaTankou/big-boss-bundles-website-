/*
===========================================================
BIG BOSS BUNDLES
wig.js
Part 1 - Foundation
===========================================================
*/

const CART_STORAGE_KEY = "bbb-shopping-cart";

const WIG_OPTIONS = {
    lengths: [
        '12"',
        '14"',
        '16"',
        '18"',
        '20"',
        '22"',
        '24"',
        '26"',
        '28"',
        '30"',
        '32"',
        '34"',
        '36"',
        '38"',
        '40"'
    ],

    densities: [
        "180%",
        "200%",
        "220%",
        "250%",
        "300%"
    ],

    colors: [
        "Natural Color",
        "Jet Black",
        "1B",
        "Dark Brown",
        "Medium Brown",
        "613 Blonde",
        "Burgundy",
        "Custom Color"
    ],

    capSizes: [
        "Small",
        "Medium",
        "Large"
    ],

    constructions: [
        "Glueless",
        "Glue Required"
    ]
};

const COLLECTIONS = {
    laos: {
        title: "LAOS",
        badge: "Premium Luxury Hair",
        textures: []
    },

    cambodian: {
        title: "Cambodian",
        badge: "Luxury Collection",
        textures: []
    },

    indianRaw: {
        title: "Indian Raw",
        badge: "Authentic Raw Hair",
        textures: []
    },

    burmese: {
        title: "Burmese",
        badge: "Luxury Collection",
        textures: []
    },

    vietnamese: {
        title: "Vietnamese",
        badge: "Luxury Collection",
        textures: []
    }
};

let shoppingCart = JSON.parse(
    localStorage.getItem(CART_STORAGE_KEY)
) || [];
