import { addNewProduct } from './dashboard/products.js';
import { addNewUser } from './dashboard/users.js';
import { addNewRole } from './dashboard/roles.js';
import { addNewDepartment } from './dashboard/departments.js';



$('#addNewUser').on('click', addNewUser);
$('#addNewProduct').on('click', addNewProduct);
$('#addNewCargo').on('click', addNewRole);
$('#addNewDepartment').on('click', addNewDepartment);

