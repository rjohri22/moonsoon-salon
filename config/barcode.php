<?php
if(!is_dir(storage_path('app/public/barcodes')))
{
    mkdir(storage_path('app/public/barcodes')) ;
}
return [
    'store_path' => storage_path('app/public/barcodes'),
];
