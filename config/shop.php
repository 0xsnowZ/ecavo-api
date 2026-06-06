<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Delivery Fee
    |--------------------------------------------------------------------------
    | The flat delivery fee applied to every order. Override in .env with
    | DELIVERY_FEE=X.XX. Set FREE_DELIVERY_AT to a minimum order subtotal
    | above which delivery is free (leave null to always charge).
    */
    'delivery_fee'     => env('DELIVERY_FEE', 5.99),
    'free_delivery_at' => env('FREE_DELIVERY_AT', null),

];
