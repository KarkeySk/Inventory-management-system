const items = require('../data/store');

// ADD ITEM
function addItem(id, name, quantity, price) {
  // check if ID already exists
  const existingItem = items.find(item => item.id === id);

  if (existingItem) {
    console.log(" Item with this ID already exists!");
    return;
  }

  // create new item
  const newItem = {
    id: id,
    name: name,
    quantity: quantity,
    price: price
  };

  items.push(newItem);

  console.log("Item added successfully!");
}


// VIEW ITEMS
function viewItems() {
  if (items.length === 0) {
    console.log("Inventory is empty!");
    return;
  }

  console.log("\n Inventory List:");
  console.log("-----------------------------------");

  items.forEach(item => {
    console.log(`ID: ${item.id}`);
    console.log(`Name: ${item.name}`);
    console.log(`Quantity: ${item.quantity}`);
    console.log(`Price: ${item.price}`);
    console.log("-----------------------------------");
  });
}


// EXPORT FUNCTIONS
module.exports = {
  addItem,
  viewItems
};