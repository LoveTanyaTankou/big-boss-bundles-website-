/*
=========================================================
BIG BOSS BUNDLES
STORE ENGINE
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

        return this.cart.reduce((total,item)=>{

            return total + (item.quantity || 1);

        },0);

    },

    cartTotal() {

        return this.cart.reduce((total,item)=>{

            return total + (item.price * (item.quantity || 1));

        },0);

    },

    updateCartBadge() {

        const badge =
            document.querySelector("[data-cart-count]");

        if(badge){

            badge.textContent = this.cartCount();

        }

    },

    add(item){

        const existing = this.cart.find(product =>

            product.id === item.id &&
            product.length === item.length &&
            product.lace === item.lace &&
            product.density === item.density

        );

        if(existing){

            existing.quantity++;

        }else{

            item.quantity = 1;

            this.cart.push(item);

        }

        this.saveCart();

        this.updateCartBadge();

        this.toast(item.name + " added to your bag");

    },

    remove(index){

        this.cart.splice(index,1);

        this.saveCart();

        this.updateCartBadge();

    },

    clear(){

        this.cart=[];

        this.saveCart();

        this.updateCartBadge();

    },

    toast(message){

        const toast=document.getElementById("shopping-toast");

        if(!toast) return;

        toast.textContent=message;

        toast.classList.add("show");

        setTimeout(()=>{

            toast.classList.remove("show");

        },2500);

    }

};

document.addEventListener("DOMContentLoaded",()=>{

    BBB.updateCartBadge();

});
