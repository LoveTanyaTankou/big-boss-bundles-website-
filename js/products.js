/*
=========================================================
BIG BOSS BUNDLES
PRODUCT DATABASE
=========================================================
*/

const PRODUCTS = [

{
    id:1,

    collection:"LAOS",

    texture:"Deep Wave",

    luxury:"Premium Luxury Hair",

    modelImage:
    "images/LAOS Hair Images/laos-deep-wave-wig-model.png",

    productImage:
    "images/LAOS Hair Images/laos-deep-wave-wig-product.png",

   pricing:"laos",

    bestseller:true

},

{
    id:2,

    collection:"LAOS",

    texture:"Body Wave",

    luxury:"Premium Luxury Hair",

    modelImage:
    "images/LAOS Hair Images/laos-body-wave-wig-model.png",

    productImage:
    "images/LAOS Hair Images/laos-body-wave-wig-product.png",

    price:189.99,

    bestseller:false

},

{
    id:3,

    collection:"Cambodian",

    texture:"Body Wave",

    luxury:"Luxury Collection",

    modelImage:
    "images/Cambodian Hair Images/cambodian-body-wave-wig-model.png",

    productImage:
    "images/Cambodian Hair Images/cambodian-body-wave-wig-product.png",

    pricing:"cambodian",

    bestseller:false

},

{
    id:4,

    collection:"Burmese",

    texture:"Straight",

    luxury:"Luxury Collection",

    modelImage:
    "images/Burmese Hair Images/burmese-straight-wig-model.png",

    productImage:
    "images/Burmese Hair Images/burmese-straight-wig-product.jpeg",

  pricing:"burmese",

    bestseller:false

}

];

/*
=========================================================
BIG BOSS BUNDLES
AUTO PRODUCT UPGRADE
=========================================================
*/

PRODUCTS.forEach(product => {

    if (!product.productType) {

        if (
            product.modelImage &&
            product.modelImage.toLowerCase().includes("-wig-")
        ) {

            product.productType = "wig";

        } else {

            product.productType = "bundle";

        }

    }

    if (!product.inStock) {

        product.inStock = true;

    }

    if (!product.pricing) {

        switch (product.collection.toLowerCase()) {

            case "laos":
                product.pricing = "laos";
                break;

            case "cambodian":
                product.pricing = "cambodian";
                break;

            case "burmese":
                product.pricing = "burmese";
                break;

            default:
                product.pricing = "";
        }

    }

});
