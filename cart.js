window.BBBCart = (() => {
  const KEY = "bbbCart";

  function getCart() {
    try {
      return JSON.parse(localStorage.getItem(KEY) || "[]");
    } catch {
      return [];
    }
  }

  function saveCart(cart) {
    localStorage.setItem(KEY, JSON.stringify(cart));
    updateCount();
  }

  function makeKey(item) {
    return [
      item.productId || item.name || "",
      item.texture || "",
      item.length || "",
      item.density || "",
     item.laceSize || "",
item.color || "",
item.capSize || ""
    ].join("|");
  }

  function add(item) {
    const cart = getCart();

    const normalized = {
      productId: item.productId || "",
      name: item.name || "Product",
      price: Number(item.price || 0),
      quantity: Math.max(1, Number(item.quantity || 1)),
      image: item.image || "",
      texture: item.texture || "",
      length: item.length || "",
      density: item.density || "",
      laceSize: item.laceSize || "",
color: item.color || "",
capSize: item.capSize || ""
    };

    normalized.key = makeKey(normalized);

    const existing = cart.find(cartItem => cartItem.key === normalized.key);

    if (existing) {
      existing.quantity += normalized.quantity;
    } else {
      cart.push({
        id:
          typeof crypto !== "undefined" && crypto.randomUUID
            ? crypto.randomUUID()
            : String(Date.now()) + Math.random(),
        ...normalized
      });
    }

    saveCart(cart);
    return cart;
  }

  function remove(id) {
    saveCart(getCart().filter(item => item.id !== id));
  }

  function setQuantity(id, quantity) {
    const cart = getCart();
    const item = cart.find(item => item.id === id);

    if (!item) return;

    const qty = Number(quantity);

    if (qty <= 0) {
      remove(id);
      return;
    }

    item.quantity = qty;
    saveCart(cart);
  }

  function increase(id) {
    const cart = getCart();
    const item = cart.find(item => item.id === id);

    if (!item) return;

    item.quantity += 1;
    saveCart(cart);
  }

  function decrease(id) {
    const cart = getCart();
    const item = cart.find(item => item.id === id);

    if (!item) return;

    item.quantity -= 1;

    if (item.quantity <= 0) {
      saveCart(cart.filter(cartItem => cartItem.id !== id));
    } else {
      saveCart(cart);
    }
  }

  function clear() {
    localStorage.removeItem(KEY);
    updateCount();
  }

  function count() {
    return getCart().reduce(
      (sum, item) => sum + Number(item.quantity || 0),
      0
    );
  }

  function total() {
    return getCart().reduce(
      (sum, item) =>
        sum +
        Number(item.price || 0) * Number(item.quantity || 0),
      0
    );
  }

  function formattedTotal() {
    return `$${total().toFixed(2)}`;
  }

  function updateCount() {
    document.querySelectorAll("[data-cart-count]").forEach(el => {
      el.textContent = count();
    });
  }

  function checkoutData() {
    return getCart().map(item => ({
      productId: item.productId,
      name: item.name,
      quantity: item.quantity,
      texture: item.texture,
      length: item.length,
      density: item.density,
      laceSize: item.laceSize,
      color: item.color
    }));
  }

  document.addEventListener("DOMContentLoaded", updateCount);

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
