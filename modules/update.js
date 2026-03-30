const items = require("../data/store");

function updateItem(id, name, quantity, price) {
    id = Number(id);

    let found = false;

    for (let i = 0; i < items.length; i++) {
        if (items[i].id === id) {
            items[i].name = name;
            items[i].quantity = Number(quantity);
            items[i].price = Number(price);

            console.log("Item updated successfully!");
            found = true;
            break;
        }
    }

    if (!found) {
        console.log("Item not found.");
    }
}

module.exports = { updateItem };