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

/*
====================================================
PRODUCT DATABASE
====================================================
*/

const WIG_PRODUCTS = [

/* ============================
LAOS
============================ */

{
    collection: "laos",
    texture: "Straight",
    image: "images/LAOS Hair Images/laos-straight-wig-model.png",
    product: "images/LAOS Hair Images/laos-straight-wig-product.png",
    price: 329.99
},

{
    collection: "laos",
    texture: "Body Wave",
    image: "images/LAOS Hair Images/laos-body-wave-wig-model.png",
    product: "images/LAOS Hair Images/laos-body-wave-wig-product.png",
    price: 329.99
},

{
    collection: "laos",
    texture: "Deep Wave",
    image: "images/LAOS Hair Images/laos-deep-wave-wig-model.png",
    product: "images/LAOS Hair Images/laos-deep-wave-wig-product.png",
    price: 349.99
},

{
    collection: "laos",
    texture: "Curly",
    image: "images/LAOS Hair Images/laos-curly-model.png",
    product: "images/LAOS Hair Images/laos-curly-wig-product.png",
    price: 349.99
},

{
    collection: "laos",
    texture: "Kinky Curly",
    image: "images/LAOS Hair Images/laos-kinky-curly-model.png",
    product: "images/LAOS Hair Images/laos-kinky-curly-product.png",
    price: 359.99
},

{
    collection: "laos",
    texture: "Kinky Straight",
    image: "images/LAOS Hair Images/laos-kinky-straight-wig-model.png",
    product: "images/LAOS Hair Images/laos-kinky-straight-wig-product.png",
    price: 349.99
},

/* ============================
CAMBODIAN
============================ */

{
    collection: "cambodian",
    texture: "Straight",
    image: "images/Cambodian Hair Images/cambodian-straight-wig-model.png",
    product: "images/Cambodian Hair Images/cambodian-straight-wig-product.jpg",
    price: 299.99
},

{
    collection: "cambodian",
    texture: "Body Wave",
    image: "images/Cambodian Hair Images/cambodian-body-wave-wig-model.png",
    product: "images/Cambodian Hair Images/cambodian-body-wave-wig-product.png",
    price: 299.99
},

{
    collection: "cambodian",
    texture: "Deep Wave",
    image: "images/Cambodian Hair Images/cambodian-deep-wave-wig-model.png",
    product: "images/Cambodian Hair Images/cambodian-deep-wave-wig-product.png",
    price: 319.99
},

{
    collection: "cambodian",
    texture: "Curly",
    image: "images/Cambodian Hair Images/cambodian-curly-wig-model.png",
    product: "images/Cambodian Hair Images/cambodian-curly-wig-product.png",
    price: 319.99
}

];
