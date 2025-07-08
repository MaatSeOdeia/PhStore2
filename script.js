let cart = [{ name: "Brainrot Premium", price: 20.0, quantity: 1 }];

function updateCartCount() {
  document.getElementById("cartCount").textContent = cart.reduce((sum, item) => sum + item.quantity, 0);
  const cartDiv = document.getElementById("cart");
  cartDiv.innerHTML = "";
  cart.forEach(item => {
    const div = document.createElement("div");
    div.textContent = item.name + " x" + item.quantity + " - R$ " + (item.price * item.quantity).toFixed(2);
    cartDiv.appendChild(div);
  });
}

function checkout() {
  if (cart.length === 0) return alert("Seu carrinho está vazio!");
  const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
  const itemsList = cart.map(item => item.name + " x" + item.quantity).join(", ");
  alert(`Compra finalizada!\nItens: ${itemsList}\nTotal: R$ ${total.toFixed(2)}`);

  fetch("notificar.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ produto: itemsList, valor: total.toFixed(2), status: "Pago" })
  })
  .then(res => res.text())
  .then(txt => alert("Servidor respondeu:\n" + txt))
  .catch(err => alert("Erro:\n" + err));
  cart = [];
  updateCartCount();
}

updateCartCount();