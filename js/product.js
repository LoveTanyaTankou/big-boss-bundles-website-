/*
=========================================================
BIG BOSS BUNDLES
PRODUCT ENGINE
=========================================================
*/

function getProductById(id) {

    return PRODUCTS.find(product => product.id === id);

}

function getProductsByCollection(collection) {

    return PRODUCTS.filter(product =>
        product.collection === collection
    );

}

function getProductsByType(type) {

    return PRODUCTS.filter(product =>
        product.productType === type
    );

}

function getFeaturedProducts() {

    return PRODUCTS.filter(product =>
        product.bestseller === true
    );

}

function getProductPrice(product, options = {}) {

    const length = options.length;

    if (!length) {

        return null;

    }

    const collection = product.pricing.toLowerCase();

    if (
        typeof getBundlePrice === "function" &&
        product.productType === "bundle"
    ) {

        return getBundlePrice(collection, length);

    }

    return null;

}
