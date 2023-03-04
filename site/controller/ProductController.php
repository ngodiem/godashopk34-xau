<?php 
class ProductController {
	function list() {
		// echo "đây là trang sản phẩm";
		$productRepository = new ProductRepository();
		
		$conds = [];
		$sorts = [];
		$page = !empty($_GET["page"]) ? $_GET["page"] : 1;
		$item_per_page = 4;


		$category_id = !empty($_GET["category_id"]) ? $_GET["category_id"] : null;
		$categoryName = "tất cả sản phẩm"; // detail()
		if(!empty($category_id)) {
			$conds = [
				"category_id" => [
					"type" => "=",
					"val"  => $category_id
				]
			];
		// detail()
		$categoryRepository = new CategoryRepository();
		$category = $categoryRepository->find($category_id);
		$categoryName = $category->getName();
		}

		// tìm kiếm theo tên
		$search = !empty($_GET["search"]) ? $_GET["search"] : null;
		if(!empty($search)) {
			$conds = [
				"name" => [
					"type" => "LIKE",
					"val"  => "'%$search%'"
				]
			];
		}


		$price_range = !empty($_GET["price-range"]) ? $_GET["price-range"] : null;
       // SELECT * FROM view_product WHERE $price_range BETWEEN 100000 AND 200000
		if(!empty( $price_range)) {
			$temp  = explode("-", $price_range);
			$start = $temp[0];
			$end   = $temp[1];
			$conds = [
				"sale_price" => [ // sale_price giá bán
					"type" => "BETWEEN",
					"val"  =>  "$start AND $end"
				]
			];
// SELECT * FROM view_product WHERE $price_range BETWEEN 1000000 AND greater
			if($end == "greater") {
				$conds = [
					"sale_price" => [
						"type" => ">=",
						"val"  => $start
					]
				];
			}
		}
// sắp xếp 
		$sort = !empty($_GET["sort"]) ? $_GET["sort"] : null;
		if(!empty($sort)) {

		$temp = explode("-", $sort);
		$name = $temp[0];
		$orderType = $temp[1];
		$map = ["price" => "sale_price", "alpha" => "name" , "created" =>"created_date"];

		$column = $map[$name];

		$sorts = [$column => $orderType];

		}
// Phân trang
		$totalProducts = $productRepository->getBy($conds, $sorts);
		$totalPage = ceil(count($totalProducts) / $item_per_page); //  ceil là làm tròn lên

// tất cả sp
		
		$products = $productRepository->getBy($conds, $sorts, $page, $item_per_page);


// danh mục sp
		$categoryRepository = new 	CategoryRepository();
		$categories = $categoryRepository->getAll();


		require "view/product/list.php";
	}

	// ajax
	function ajaxSearch() {
		// echo "haha";
		$conds = [];
		$sorts = [];
		$search = !empty($_GET["pattern"]) ? $_GET["pattern"] : null;
		if(!empty($search)) {
			$conds = [
				"name" => [
					"type" => "LIKE",
					"val"  => "'%$search%'"
				]
			];
		}
		$productRepository = new ProductRepository();
		$products = $productRepository->getBy($conds, $sorts);
		require "view/product/ajaxSearch.php";
	}

	function detail() {
		$id = $_GET["id"];
		$productRepository = new ProductRepository;
		$product = $productRepository->find($id);
		$category_id = $product->getCategoryId();
		$price_range = null; // khai báo biến $price_range=null để không sài đến
		$categoryName = $product->getCategory()->getName(); // sp này thuộc danh mục nào

		$categoryRepository =  new CategoryRepository();
		$categories = $categoryRepository->getAll();

		$sorts = [];
		$conds = [
			"category_id" => [
			"type" => "=",
			"val" => $category_id
			],
			"id" => [
				"type" => "!=",
				"val" => $id
				]			
		];
		$relatedProducts = $productRepository->getBy($conds, $sorts);
		require "view/product/detail.php";
	}
}


?>