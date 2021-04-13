var cart = loadCart();

function addCart(plateId, restaurantId, comments, complementsChecked, itemsChecked, plateTotalPrice) {
  const { items, quantity } = cart;

  items.push({
    id: items.length ? items[items.length - 1].id + 1 : 1,
    plate_id: plateId,
    restaurant_id: restaurantId,
    comments: comments,
    plate_total_price: plateTotalPrice,
    ...(complementsChecked.length && { plate_complements: complementsChecked }),
    ...(itemsChecked.length && { plate_items: itemsChecked })
  });

  const total = items.reduce((totalItems, item) => {
    return totalItems + item.plate_total_price;
  }, 0);

  cart.quantity = items.length;
  cart.total = total;

  localStorage.setItem('cart', JSON.stringify(cart));
}

function removeCart(id) {
  cart.items = cart.items.filter((item) => item.id !== id);
  updateCart(cart.items);
}

function cleanCart() {
  localStorage.removeItem('cart');
}

function updateCart(cartItems) {  
  cart.items = cartItems;

  const total = cart.items.reduce((totalItems, item) => {
    return totalItems + item.plate_total_price;
  }, 0);

  cart.quantity = cart.items.length;
  cart.total = total;

  localStorage.setItem('cart', JSON.stringify(cart));
}

function loadCart() {
  let storeCart = JSON.parse(localStorage.getItem('cart'));

  if (!storeCart || !Object.keys(storeCart).length)
    storeCart = {
      items: [],
      total: 0,
      quantity: 0,
    };

  return storeCart;
}

function getCart() {
  return cart;
}




























// function addItemCart(item) {
//     var priceItems = item.plate.complements.reduce((totalComplements, complement) => 
//         totalComplements + complement.itens.reduce((totalItems, item) => totalItems + item.price, 0)
//     , 0);

//     items.push({
//         id: items.length ? items[items.length-1].id + 1 : 1,
//         plate_id: item.plate.id_plate,
//         plate_price: item.plate.price,
//         plate_promo: item.plate.promo,
//         ...(item.plate.promo && { plate_promo_price: item.plate.promo_price }),
//         price_items: priceItems
//     });

//     saveCart();

//     console.log('Items', items);
// }