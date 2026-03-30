const fs = require('fs');
const path = require('path');

const DATA_FILE = path.join(__dirname, '../data/inventory.json');

function updateItem(itemId, updates) {
  try {
    if (!itemId || !updates) {
      throw new Error('Item ID and updates are required');
    }

    const data = JSON.parse(fs.readFileSync(DATA_FILE, 'utf8'));
    const item = data.items.find(item => item.id === itemId);
    
    if (!item) {
      throw new Error(`Item with ID ${itemId} not found`);
    }

    Object.assign(item, updates);
    
    fs.writeFileSync(DATA_FILE, JSON.stringify(data, null, 2));
    return { success: true, message: `Item ${itemId} updated successfully`, item };
  } catch (error) {
    return { success: false, error: error.message };
  }
}

module.exports = { updateItem };
