<?php 
    public function importProducts(){
        $products_file = file_get_contents('products/products.json');
        $read_file = json_decode($products_file,true);
        try {
            Products::truncate();
            foreach ($read_file as $row) {
                Products::create([
                    'product_id'        => $row['product_id'],
                    'title'             => $row['title'],
                    'category_id'       => $row['category_id'],
                    'category_title'    => $row['category_title'],
                    'author'            => $row['author'],
                    'list_price'        => $row['list_price'],
                    'stock_quantity'    => $row['stock_quantity'],
                    'active'            => 1,
                    'local_writer'      => 0,
                ]);
            }
            return "All products imported";
        } catch (\Throwable $th) {
            return "An error has occurred, please check your database settings or json file.";
        }
    }
?>