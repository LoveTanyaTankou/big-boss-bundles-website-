/*
=========================================================
BIG BOSS BUNDLES
SHOPPING CART
=========================================================
*/

function renderCart() {

    const cartContainer =
        document.getElementById("cartItems");

    if (!cartContainer) return;

    cartContainer.innerHTML = "";

    if (BBB.cart.length === 0) {

        cartContainer.innerHTML = `

            <div class="empty-cart">

                <h2>Your bag is empty.</h2>

                <p>Start shopping to add luxury hair products.</p>

            </div>

        `;

        updateSummary();

        return;

    }

    BBB.cart.forEach((item,index)=>{

        cartContainer.innerHTML += `

        <div class="cart-item">

            <img
                src="${item.productImage}"
                alt="${item.name}"
                class="cart-image"
            >

            <div class="cart-details">

                <h3>${item.name}</h3>

                <p>${item.collection}</p>

                <p>${item.texture}</p>

                <p>${item.length}"</p>

                <p>Qty: ${item.quantity}</p>

            </div>

            <div class="cart-price">

                $${(item.price * item.quantity).toFixed(2)}

            </div>

            <button
                onclick="removeItem(${index})"
                class="remove-button"
            >

                Remove

            </button>

        </div>

        `;

    });

    updateSummary();

}

function updateSummary(){

    const subtotal =
        document.getElementById("subtotal");

    const total =
        document.getElementById("total");

    if(subtotal){

        subtotal.textContent =
            "$" + BBB.cartTotal().toFixed(2);

    }

    if(total){

        total.textContent =
            "$" + BBB.cartTotal().toFixed(2);

    }

}

function removeItem(index){

    BBB.remove(index);

    renderCart();

}

document.addEventListener("DOMContentLoaded",()=>{

    renderCart();

});
