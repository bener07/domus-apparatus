<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Card Layout Example</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <style>
        .product-img {
            max-height: 100px;
            object-fit: cover;
            border-radius: 10px;
        }
        .number-no-arrows::-webkit-inner-spin-button,
        .number-no-arrows::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        .number-no-arrows {
            -moz-appearance: textfield;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <i class="bi-trash bi"></i>
        <ul id="cart_items" class="list-unstyled">
            <!-- First Card -->
            <li>
                <div class="card mb-3" style="max-width: 100%; border-radius: 10px; display: grid; grid-template-columns: auto 1fr auto; align-items: center;">
                    <!-- Image -->
                    <img src="https://officechai.com/wp-content/uploads/2016/05/3-Photoshop-Funny-CEO-Falls-Asleep-Work-Employees-Edit-Memes.jpg" alt="Elody Crist" class="product-img">
                    
                         <!-- Card Body -->
                    <div class="p-2">
                        <div>
                            <h5 class="card-title mb-1" style="font-size: large;">Computador Acer 1234</h5>
                        </div>
                        <div class="container">
                            <div class="col-10">
                                <p class="card-text"><small class="text-body-secondary">Adicionad há 24 min</small></p>
                                <div class="container row" style="width: 120%">
                                    <div class="input-group mb-3 col-8" id="quantity-5cart_items" style="width: 130px;">
                                        <button class="btn btn-outline-secondary btn-decrement m-0" type="button">-</button>
                                        <input type="number" class="number-no-arrows form-control text-center m-0 quantity-input" value="5" max="5" min="1" style="max-width: 50px;">
                                        <button class="btn btn-outline-secondary btn-increment" type="button">+</button>
                                    </div>
                                    <a class="fs-1 btn btn-danger col-4" id="remove-product-btn-5cart_items">
                                        <i class="bi bi-trash" style="color:black"></i>
                                    </a>
                                </div>
                            </div>
                            <!-- Remove Button -->
                        </div>
                    </div>
                    
                </div>
            </li>

            <!-- Second Card -->
            <li>
                <div class="card mb-3" style="max-width: 100%; border-radius: 10px; display: grid; grid-template-columns: auto 1fr auto; align-items: center;">
                    <!-- Image -->
                    <img src="https://officechai.com/wp-content/uploads/2016/05/3-Photoshop-Funny-CEO-Falls-Asleep-Work-Employees-Edit-Memes.jpg" 
                         alt="Victoria Gerhold" 
                         class="product-img">
                    
                    <!-- Card Body -->
                    <div class="p-2">
                        <h5 class="card-title mb-1" style="font-size: large;">Victoria Gerhold</h5>
                        <p class="card-text"><small class="text-body-secondary">Adicionado agora</small></p>
                        <p class="card-text mb-1 fs-6">
                            <strong>Quantidade:</strong> <span class="badge bg-secondary">1</span>    
                        </p>
                        <div class="input-group mb-3" id="quantity-6cart_items">
                            <button class="btn btn-outline-secondary btn-decrement m-0" type="button">-</button>
                            <input type="number" class="number-no-arrows form-control text-center m-0 quantity-input" value="1" max="5" min="1" style="max-width: 50px;">
                            <button class="btn btn-outline-secondary btn-increment" type="button">+</button>
                        </div>
                    </div>
                    
                    <!-- Remove Button -->
                    <div class="text-end" style="padding: 10px;">
                        <button class="btn btn-danger btn-sm me-2" id="remove-product-btn-6cart_items">Remover Tudo</button>
                    </div>
                </div>
            </li>
        </ul>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
