window.BBBCart = (() => {

  const KEY = "bbbCart";


  /* =========================================================
     GET CART
  ========================================================= */

  function getCart() {

    try {

      return JSON.parse(
        localStorage.getItem(KEY) || "[]"
      );

    } catch {

      return [];

    }

  }


  /* =========================================================
     SAVE CART
  ========================================================= */

  function saveCart(cart) {

    localStorage.setItem(
      KEY,
      JSON.stringify(cart)
    );

    updateCount();

  }


  /* =========================================================
     CREATE UNIQUE CART KEY
  ========================================================= */

  function makeKey(item) {

    return [

     item.productId || item.name || "",

item.brand || "",

      item.texture || "",

      item.length || "",

      item.density || "",

      item.laceSize || "",

   item.color || "",

item.capSize || "",

item.baseSize || "",

item.itemType || "",

      item.classSessionId || "",

      item.classDate || "",

      item.studentType || "",

      item.studentName || ""

    ].join("|");

  }


  /* =========================================================
     ADD ITEM
  ========================================================= */

  function add(item) {

    const cart = getCart();


    const normalized = {

      productId:
        item.productId || "",

      name:
        item.name || "Product",

      price:
        Number(item.price || 0),

  quantity:
  Math.max(
    1,
    Number(item.quantity || 1)
  ),

image:
  item.image || "",

brand:
  item.brand || "",

   /* HAIR PRODUCT OPTIONS */

texture:
  item.texture || "",

length:
  item.length || "",

density:
  item.density || "",

laceSize:
  item.laceSize || "",

color:
  item.color || "",

capSize:
  item.capSize || "",

baseSize:
  item.baseSize || "",

/* ITEM TYPE */


      itemType:
        item.itemType || "product",


      /* BEAUTY ACADEMY INFORMATION */

      classSessionId:
        item.classSessionId || "",

      classDate:
        item.classDate || "",

      classTime:
        item.classTime || "",

      studentType:
        item.studentType || "",

      studentAge:
        item.studentAge || "",

      studentName:
        item.studentName || "",

      studentEmail:
        item.studentEmail || "",

      studentPhone:
        item.studentPhone || "",

      parentName:
        item.parentName || "",

      parentPhone:
        item.parentPhone || "",

      parentEmail:
        item.parentEmail || ""

    };


    normalized.key =
      makeKey(normalized);


    /*
      Academy registrations should remain
      separate registrations.

      Normal products may combine quantities.
    */

    const existing =
      normalized.itemType === "class"
        ? null
        : cart.find(
            cartItem =>
              cartItem.key ===
              normalized.key
          );


    if(existing){

      existing.quantity +=
        normalized.quantity;

    }else{

      cart.push({

        id:
          typeof crypto !== "undefined" &&
          crypto.randomUUID

            ? crypto.randomUUID()

            : String(Date.now()) +
              Math.random(),

        ...normalized

      });

    }


    saveCart(cart);

    return cart;

  }


  /* =========================================================
     REMOVE ITEM
  ========================================================= */

  function remove(id) {

    saveCart(
      getCart().filter(
        item => item.id !== id
      )
    );

  }


  /* =========================================================
     SET QUANTITY
  ========================================================= */

  function setQuantity(
    id,
    quantity
  ){

    const cart =
      getCart();


    const item =
      cart.find(
        item => item.id === id
      );


    if(!item){
      return;
    }


    /*
      Class registrations always represent
      one student / one seat.
    */

    if(item.itemType === "class"){

      item.quantity = 1;

      saveCart(cart);

      return;

    }


    const qty =
      Number(quantity);


    if(qty <= 0){

      remove(id);

      return;

    }


    item.quantity = qty;

    saveCart(cart);

  }


  /* =========================================================
     INCREASE QUANTITY
  ========================================================= */

  function increase(id){

    const cart =
      getCart();


    const item =
      cart.find(
        item => item.id === id
      );


    if(!item){
      return;
    }


    if(item.itemType === "class"){

      item.quantity = 1;

      saveCart(cart);

      return;

    }


    item.quantity += 1;

    saveCart(cart);

  }


  /* =========================================================
     DECREASE QUANTITY
  ========================================================= */

  function decrease(id){

    const cart =
      getCart();


    const item =
      cart.find(
        item => item.id === id
      );


    if(!item){
      return;
    }


    if(item.itemType === "class"){

      remove(id);

      return;

    }


    item.quantity -= 1;


    if(item.quantity <= 0){

      saveCart(
        cart.filter(
          cartItem =>
            cartItem.id !== id
        )
      );

    }else{

      saveCart(cart);

    }

  }


  /* =========================================================
     CLEAR CART
  ========================================================= */

  function clear(){

    localStorage.removeItem(KEY);

    updateCount();

  }


  /* =========================================================
     CART COUNT
  ========================================================= */

  function count(){

    return getCart().reduce(

      (sum, item) =>
        sum +
        Number(
          item.quantity || 0
        ),

      0

    );

  }


  /* =========================================================
     CART TOTAL
  ========================================================= */

  function total(){

    return getCart().reduce(

      (sum, item) =>

        sum +

        Number(
          item.price || 0
        ) *

        Number(
          item.quantity || 0
        ),

      0

    );

  }


  /* =========================================================
     FORMATTED TOTAL
  ========================================================= */

  function formattedTotal(){

    return `$${total().toFixed(2)}`;

  }


  /* =========================================================
     UPDATE CART COUNT
  ========================================================= */

  function updateCount(){

    document
      .querySelectorAll(
        "[data-cart-count]"
      )
      .forEach(el => {

        el.textContent =
          count();

      });

  }


  /* =========================================================
     CHECKOUT DATA
  ========================================================= */

  function checkoutData(){

    return getCart().map(
      item => ({

        productId:
          item.productId,

        name:
          item.name,

        price:
          item.price,

        quantity:
          item.quantity,

        image:
          item.image,

        itemType:
          item.itemType || "product",


  /* HAIR OPTIONS */

brand:
  item.brand,

texture:
  item.texture,

length:
  item.length,

density:
  item.density,

laceSize:
  item.laceSize,

color:
  item.color,

capSize:
  item.capSize,

baseSize:
  item.baseSize,


/* CLASS INFORMATION */

        classSessionId:
          item.classSessionId,

        classDate:
          item.classDate,

        classTime:
          item.classTime,

        studentType:
          item.studentType,

        studentAge:
          item.studentAge,

        studentName:
          item.studentName,

        studentEmail:
          item.studentEmail,

        studentPhone:
          item.studentPhone,

        parentName:
          item.parentName,

        parentPhone:
          item.parentPhone,

        parentEmail:
          item.parentEmail

      })
    );

  }


  /* =========================================================
     INITIALIZE CART COUNT
  ========================================================= */

  document.addEventListener(
    "DOMContentLoaded",
    updateCount
  );


  /* =========================================================
     PUBLIC CART FUNCTIONS
  ========================================================= */

  return {

    getCart,

    saveCart,

    add,

    remove,

    setQuantity,

    increase,

    decrease,

    clear,

    count,

    total,

    formattedTotal,

    updateCount,

    checkoutData

  };

})();
