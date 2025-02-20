import { addNewProduct, eliminarProduto } from './dashboard/products.js';
import { addNewUser } from './dashboard/users.js';



$('#addNewUser').on('click', addNewUser);
$('#addNewProduct').on('click', addNewProduct);
// $('#addNewCargo').on('click', addNewCargo);
// $('#addNewDepartment').on('click', addNewDepartment);

