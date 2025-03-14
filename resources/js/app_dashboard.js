import { addNewProduct } from './dashboard/products.js';
import { addNewUser } from './dashboard/users.js';
import { addNewRole } from './dashboard/roles.js';
import { addNewDepartment } from './dashboard/departments.js';
import { addNewTag } from './dashboard/tags.js';
import { deliveryScanner } from './components/scanner.js';


$('#addNewUser').on('click', addNewUser);
$('#addNewProduct').on('click', addNewProduct);
$('#addNewCargo').on('click', addNewRole);
$('#addNewDepartment').on('click', addNewDepartment);
$('#addNewTag').on('click', addNewTag);
$('#deliveryScanner').on('click', deliveryScanner);