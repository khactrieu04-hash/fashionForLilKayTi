<?php

namespace App\Services;

use App\Repository\Eloquent\ProductRepository;
use App\Repository\Eloquent\ProductReviewRepository;

class HomeService
{
    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var ProductReviewRepository
     */
    private $productReviewRepository;

    /**
     * ProductService constructor.
     *
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository, ProductReviewRepository $productReviewRepository)
    {
        $this->productRepository = $productRepository;
        $this->productReviewRepository = $productReviewRepository;
    }

    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // lấy sản phẩm bán chạy nhất
        $bellingProducts = $this->productRepository->getBestSellingProduct();

        // lấy sản phẩm mới nhất
        $newProducts = $this->productRepository->getNewProducts();
        $productIds = collect($bellingProducts)
            ->merge($newProducts)
            ->pluck('id')
            ->unique()
            ->values()
            ->all();
        $ratings = $this->productReviewRepository->avgRatingsProducts($productIds);
        $quantities = $this->productRepository->getQuantitiesBuyProducts($productIds);

        foreach ($bellingProducts as $bellingProduct) {
            $bellingProduct->avg_rating = $ratings->get($bellingProduct->id, 0);
        }

        foreach ($newProducts as $newProduct) {
            $newProduct->avg_rating = $ratings->get($newProduct->id, 0);
            $newProduct->sum = $quantities->get($newProduct->id, 0);
        }
        // trả dữ liệu cho controller
        return [
            'title' => TextLayoutTitle("payment_method"),
            'bellingProducts' => $bellingProducts,
            'newProducts' => $newProducts,
        ];
    }
}
