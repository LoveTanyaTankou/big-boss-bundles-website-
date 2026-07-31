/*
====================================================
BIG BOSS BUNDLES
wig-product.js
PART 1
====================================================
*/

const LENGTHS = [
12,14,16,18,20,22,24,26,28,30
];

const DENSITIES = [
"180%",
"200%",
"220%",
"250%",
"300%"
];

const HAIR_COLORS = [
"Natural Black (1B)",
"Jet Black (1)",
"Dark Brown (2)",
"Medium Brown (4)",
"Light Brown (6)",
"Burgundy (99J)",
"Blonde (613)"
];

const CAP_SIZES = [
"Small",
"Medium",
"Large"
];

const CONSTRUCTIONS = [
"Glueless",
"Glue Required"
];

const params = new URLSearchParams(window.location.search);

const collection =
(params.get("collection") || "").toLowerCase();

const texture =
params.get("texture") || "";

const image =
document.getElementById("mainImage");

const title =
document.getElementById("productTitle");

const collectionText =
document.getElementById("productCollection");

const price =
document.getElementById("productPrice");

const lengthSelect =
document.getElementById("lengthSelect");

const densitySelect =
document.getElementById("densitySelect");

const colorSelect =
document.getElementById("colorSelect");

const capSizeSelect =
document.getElementById("capSizeSelect");

const constructionSelect =
document.getElementById("constructionSelect");

const addButton =
document.getElementById("addToBag");
/*
====================================================
PART 2
POPULATE DROPDOWNS
====================================================
*/

function fillSelect(select, values) {

    if (!select) return;

    values.forEach(value => {

        const option = document.createElement("option");

        option.value = value;
        option.textContent = value;

        select.appendChild(option);

    });

}

fillSelect(lengthSelect, LENGTHS);
fillSelect(densitySelect, DENSITIES);
fillSelect(colorSelect, HAIR_COLORS);
fillSelect(capSizeSelect, CAP_SIZES);
fillSelect(constructionSelect, CONSTRUCTIONS);
/*
====================================================
PART 3
LOAD PRODUCT & PRICE
====================================================
*/

function getCurrentProduct() {

    if (typeof WIG_PRODUCTS === "undefined") {

        console.error("WIG_PRODUCTS not found.");

        return null;

    }

    return WIG_PRODUCTS.find(product => {

        return (
            product.collection.toLowerCase() === collection &&
            product.texture.toLowerCase() === texture.toLowerCase()
        );

    });

}

function updatePrice() {

    const length = Number(lengthSelect.value);

    let currentPrice = 0;

    if (
        typeof getBundlePrice === "function" &&
        (collection === "laos" || collection === "cambodian")
    ) {

        currentPrice = getBundlePrice(collection, length);

    }

    if (!currentPrice) {

        const product = getCurrentProduct();

        if (product) {

            currentPrice = product.price;

        }

    }

    price.textContent =
        "$" + Number(currentPrice).toFixed(2);

}

function loadProduct() {

    const product = getCurrentProduct();

    if (!product) {

        title.textContent = "Product Not Found";

        return;

    }

    image.src = product.image;
    image.alt = product.texture;

    title.textContent =
        product.collection.toUpperCase() +
        " " +
        product.texture;

    collectionText.textContent =
        product.collection.toUpperCase();

    lengthSelect.value = 12;

    updatePrice();

}

loadProduct();

lengthSelect.addEventListener(
    "change",
    updatePrice
);
/*
====================================================
PART 4
ADD TO BAG
====================================================
*/

addButton.addEventListener("click", function () {

    const product = getCurrentProduct();

    if (!product) {
        alert("Unable to add this wig to your bag.");
        return;
    }

    const cartItem = {
        id: product.id || (product.collection + "-" + product.texture),
        name: title.textContent,
        collection: product.collection,
        texture: product.texture,
        image: product.image,
        length: Number(lengthSelect.value),
        density: densitySelect.value,
        color: colorSelect.value,
        capSize: capSizeSelect.value,
        construction: constructionSelect.value,
        price: Number(
            price.textContent.replace("$", "")
        ),
        quantity: 1
    };

    let cart = JSON.parse(localStorage.getItem("bbbCart")) || [];

    const existingItem = cart.find(item =>
        item.id === cartItem.id &&
        item.length === cartItem.length &&
        item.density === cartItem.density &&
        item.color === cartItem.color &&
        item.capSize === cartItem.capSize &&
        item.construction === cartItem.construction
    );

    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push(cartItem);
    }

    localStorage.setItem("bbbCart", JSON.stringify(cart));

    if (typeof updateCartCount === "function") {
        updateCartCount();
    }

    alert("Added to your bag!");

});
