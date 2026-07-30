/*
=========================================================
BIG BOSS BUNDLES
STORE ENGINE
=========================================================
Shared shopping cart and store functions.
This file will be used by every page.
=========================================================
*/

const BBB = {

    cart: JSON.parse(localStorage.getItem("bbb-cart")) || [],

    saveCart() {
        localStorage.setItem(
            "bbb-cart",
            JSON.stringify(this.cart)
        );
    },

    cartCount() {
        return this.cart.length;
    },

    updateCartBadge() {

        const badge = document.querySelector("[data-cart-count]");

        if (badge) {
            badge.textContent = this.cart.length;
        }

    },

    add(item) {

        this.cart.push(item);

        this.saveCart();

        this.updateCartBadge();

        this.toast(item.name + " added to your bag");

    },

    toast(message) {

        const toast =
            document.getElementById("shopping-toast");

        if (!toast) return;

        toast.textContent = message;

        toast.classList.add("show");

        setTimeout(() => {

            toast.classList.remove("show");

        },2500);

    }

};

document.addEventListener("DOMContentLoaded",()=>{

    BBB.updateCartBadge();

});
