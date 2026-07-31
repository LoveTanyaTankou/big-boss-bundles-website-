/*
=========================================================
BIG BOSS BUNDLES
PRODUCT PAGE
=========================================================
*/

function getProductById(id) {
    return PRODUCTS.find(product => product.id == id);
}

function getUrlParameter(name) {
    const params = new URLSearchParams(window.location.search);
    return params.get(name);
}

function loadProduct() {

    const productId = getUrlParameter("id");

    if (!productId) {
        return;
    }

    const product = getProductById(productId);

    if (!product) {
        return;
    }

    const title = document.getElementById("productTitle");
    const collection = document.getElementById("productCollection");
    const texture = document.getElementById("productTexture");
    const luxury = document.getElementById("productLuxury");
    const modelImage = document.getElementById("modelImage");
    const productImage = document.getElementById("productImage");

    if (title) {
        title.textContent =
            product.collection + " " + product.texture;
    }

    if (collection) {
        collection.textContent = product.collection;
    }

    if (texture) {
        texture.textContent = product.texture;
    }

    if (luxury) {
        luxury.textContent = product.luxury;
    }

    if (modelImage) {
        modelImage.src = product.modelImage;
        modelImage.alt = product.collection;
    }

    if (productImage) {
        productImage.src = product.productImage;
        productImage.alt = product.collection;
    }

}

document.addEventListener("DOMContentLoaded", loadProduct);
