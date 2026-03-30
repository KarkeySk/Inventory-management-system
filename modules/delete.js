const fs = require('fs');
const path = require('path');

const DATA_FILE = path.join(__dirname, '../data/inventory.json');

function deleteItem(itemId) {
  try {
    if (!itemId) {
      throw new Error('Item ID is required');
    }

    const data = JSON.parse(fs.readFileSync(DATA_FILE, 'utf8'));
    const initialLength = data.items.length;
    
    data.items = data.items.filter(item => item.id !== itemId);
    
    if (data.items.length === initialLength) {
      throw new Error(`Item with ID ${itemId} not found`);
    }

    fs.writeFileSync(DATA_FILE, JSON.stringify(data, null, 2));
    return { success: true, message: `Item ${itemId} deleted successfully` };
  } catch (error) {
    return { success: false, error: error.message };
  }
}

module.exports = { deleteItem };
