<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SANPHAM;
use App\Models\MAUSP;
use App\Models\NHACUNGCAP;
use App\Models\SLIDESHOW_CTMSP;
use App\Models\DANHGIASP;
use App\Models\SLIDESHOW;
use App\Models\KHUYENMAI;
use App\Classes\Helper;
class SanPhamController extends Controller
{
    //
    public function getSupplier(){
        $supplier = NHACUNGCAP::all();
        $count =count($supplier);
        for($i=0;$i<$count;$i++){
         $supplier[$i]->anhdaidien = Helper::$URL."logo/".$supplier[$i]->anhdaidien;
        }
        return response()->json([
            'status' => true,
            'message' => '',
    		'data' => $supplier
    	]);
    }
    public function getSlideshow(){
        $slideshow = SLIDESHOW::all();
        $count = count($slideshow);
        for($i=0;$i<$count;$i++){
            $slideshow[$i]->hinhanh = Helper::$URL."slideshow/".$slideshow[$i]->hinhanh;
        }
        return response()->json([
            'status' => true,
            'message' => '',
    		'data' => $slideshow
    	]);
    }
    public function getBanner(Request $request){
        
    }
    public function getVoucherHot(){
        
    }
    public function getHotSale(){
        
        $listProductHotSale = array();
        $listProduct = SANPHAM::all();
        $listDiscount = KHUYENMAI::orderBy("chietkhau", "desc")->get();
        $max = 0; 
        $min = 0;
        $count =  count($listDiscount);

        if($count >= 2){
            $max = $listDiscount[0]->id;
            $min = $listDiscount[1]->id;
        }else{
            $max = $listDiscount[0]->id;
        }
        
        //ktra chi lay san pham khac dung luong
        foreach($listProduct as $product){
            $valid = false;
            if($product->id_km == $max || $product->id_km == $min){
               if(!empty($listProductHotSale)){
                foreach($listProductHotSale as $productHotSale){
                    if($product->id_msp == $productHotSale->id_msp){
                        if($product->dungluong != $productHotSale->dungluong){
                            $valid = true;
                        }else $valid = false;  //truong hop cung mau nhung cung dung luong. neu khong set lai = false thi se bang true o lan lap truoc
                    }else{
                        $valid = true; //truong hop khac mau
                    }
                }
               }else array_push($listProductHotSale, $product);
                
               if($valid == true){
                    array_push($listProductHotSale, $product);
                }
           }
        }
        foreach($listProductHotSale as $product){
            $product->tensp = $product->tensp." ".$product->dungluong;
            $product->hinhanh = Helper::$URL."phone/".$product->hinhanh;
            $product->giamgia = KHUYENMAI::find($product->id_km)->chietkhau;
            $allJudge = DANHGIASP::where("id_sp", $product->id)->get();
            $totalVote = 0;
            foreach($allJudge as $judge){
                $totalVote += $judge->danhgia;
            }
            $product->tongluotvote = $totalVote;
            $product->tongdanhgia = count($allJudge);
        }
        return response()->json([
            'status' => true,
            'message' => '',
    		'data' => $listProductHotSale
    	]);
    }
    public function getAllProduct(Request $request){
        $page = !empty($request->page) ? $request->page : 1;
    	$itemsPerPage = !empty($request->per_page) ? $request->per_page : 5;
        $listProduct = SANPHAM::inRandomOrder()->skip(($page - 1) * $itemsPerPage)->take($itemsPerPage)->groupBy()->get();
        
        foreach($listProduct as $product){
            $product->tensp = $product->tensp." ".$product->dungluong;
            $product->hinhanh = Helper::$URL."phone/".$product->hinhanh;
            $product->giamgia = KHUYENMAI::find($product->id_km)->chietkhau;
            $allJudge = DANHGIASP::where("id_sp", $product->id)->get();
            $totalVote = 0;
            foreach($allJudge as $judge){
                $totalVote += $judge->danhgia;
            }
            $product->tongluotvote = $totalVote;
            $product->tongdanhgia = count($allJudge);
        }
        return response()->json([
            'status' => 'true',
            'message' => '',
    		'data' => $listProduct
    	]); 
    }
    public function getFeaturedProduct(){

        $listProductNew = array();
        $totalProductLeft= 10;
        $listProductOrderBy = SANPHAM::orderBy('id',"desc")->get();

        //ktra chi lay san pham khac dung luong
        foreach($listProductOrderBy as $product){
            $valid = false;
            if(!empty($listProductNew)){
                foreach($listProductNew as $productNew){
                    if($product->id_mausp == $productNew->id_mausp){
                        if($product->dungluong != $productNew->dungluong){
                                $valid = true;
                            }else $valid = false;  //truong hop cung mau nhung cung dung luong. neu khong set lai = false thi se bang true o lan lap truoc
                        }else{
                            $valid = true; //truong hop khac mau
                    }
                }
            }else {
                array_push($listProductNew, $product);
                $totalProductLeft--;
            }
            if($valid == true){
                array_push($listProductNew, $product);
                $totalProductLeft--;
            }
            if($totalProductLeft == 0){
                break; 
            }
        }

        foreach($listProductNew as $product){
            $product->tensp = $product->tensp." ".$product->dungluong;
            $product->hinhanh = Helper::$URL."phone/".$product->hinhanh;
            $product->giamgia = KHUYENMAI::find($product->id_km)->chietkhau;
            $allJudge = DANHGIASP::where("id_sp", $product->id)->get();
            $totalVote = 0;
            foreach($allJudge as $judge){
                $totalVote += $judge->danhgia;
            }
            $product->tongluotvote = $totalVote;
            $product->tongdanhgia = count($allJudge);
        }

        return response()->json([
            'status' => 'true',
            'message' => '',
    		'data' => $listProductNew
    	]);
    }
    public function getDetailProduct($id){
        $color = array();
        array_push($color, "M.Sắc");
        $storage = array();
        array_push($storage, "D.Lượng");
       
        $productCurrent = SANPHAM::find($id);
        $cateProduct = MAUSP::find($productCurrent->id_msp);
        $product = SANPHAM::where('id_msp', $cateProduct->id)->get();
        $nhacungcap = NHACUNGCAP::find($cateProduct->id_ncc);
        $nhacungcap->anhdaidien = Helper::$URL."logo/".$nhacungcap->anhdaidien;
     
        $dem = count($product);
        for($i=0;$i<$dem;$i++){
           
            if($this->checkArray($color, $product[$i]->mausac)){
                array_push($color, $product[$i]->mausac);
            }
          if($this->checkArray($storage,$product[$i]->dungluong)){
           array_push($storage, $product[$i]->dungluong);
         }
        }
        $cateProduct->nhacungcap = $nhacungcap;
        $cateProduct->mausac = $color;
        $cateProduct->dungluong = $storage;
      
        return response()->json([
            'status' => true,
            'message' => '',
    		'data' => $cateProduct
    	]);
    }

    public function checkArray(array $array, String $string){
        $dem = count($array);
        for($i=0;$i<$dem;$i++){
            if($array[$i] == $string){
                return false;
            }
        }
        return true;
    }

    public function changeColorOrStorageProduct($id, Request $request){
            if(!empty($request->mausac) && !empty($request->dungluong)){
                $product = SANPHAM::where("id_msp", $id)->where("mausac","like",$request->mausac)->where("dungluong",$request->dungluong)->get();
                $count = count($product);
                
            }else if(!empty($request->mausac)){
                $product = SANPHAM::where("id_msp",$id)->where("mausac","like", $request->mausac)->get();
                $count = count($product);
    
            }else if(!empty($request->dungluong)){
                $product = SANPHAM::where("id_msp", $id)->where("dungluong","like", $request->dungluong)->get();
                $count = count($product);
               
            }
            foreach($product as $pro){
                $pro->hinhanh = Helper::$URL."phone/".$pro->hinhanh;
                $pro->giamgia = KHUYENMAI::find($pro->id_km)->chietkhau;
                $allJudge = DANHGIASP::where("id_sp", $pro->id)->get();
                $totalVote = 0;
                foreach($allJudge as $judge){
                    $totalVote += $judge->danhgia;
                }
                $pro->tongluotvote = $totalVote;
                $pro->tongdanhgia = count($allJudge);
            }
            
            return response()->json([
                'status' => 'true',
                'message' => '',
                'data' => $product
            ]);
    }
    public function getRelatedProduct($id){
        $listIdCateFirst = array();
        $listIdCateFinish = array();
        $listIdCateResult = array();
        $listIdProductFirst = array();
        $listIdProductFinish = array();
        $listIdProductResult = array();
        $product = SANPHAM::find($id);
        $cateProduct = MAUSP::find($product->id_msp);
        $listCateRelated = MAUSP::where("id_ncc",  $cateProduct->id_ncc)->where('id', '!=', $product->id_msp)->get();
        foreach($listCateRelated as $cate){
            array_push($listIdCateFirst, $cate->id);
        }
        $listIdCateFinish = array_rand($listIdCateFirst, 5);
        foreach($listIdCateFinish as $cate){
            array_push($listIdCateResult, $listIdCateFirst[$cate]);
        }
        //random lan 2
        $listProduct = SANPHAM::whereIn("id_msp", $listIdCateResult)->get();
        
        foreach($listProduct as $pro){
            array_push($listIdProductFirst, $pro->id);
        }
        $listIdProductFinish = array_rand($listIdProductFirst, 5);
        foreach($listIdProductFinish as $cate){
            array_push($listIdProductResult, $listIdProductFirst[$cate]);
        }
        $listResult = SANPHAM::whereIn('id', $listIdProductResult)->get();
       
        foreach($listResult as $pro){
            $pro->hinhanh = Helper::$URL."phone/".$pro->hinhanh;
            $pro->giamgia = KHUYENMAI::find($pro->id_km)->chietkhau;
            $allJudge = DANHGIASP::where("id_sp", $pro->id)->get();
            $totalVote = 0;
            foreach($allJudge as $judge){
                $totalVote += $judge->danhgia;
            }
            $pro->tongluotvote = $totalVote;
        }
        return response()->json([
            'status' => 'true',
            'message' => '',
            'data' => $listResult
        ]);
    }

    public function getCompareProduct($id, Request $request){
        $listResult = array();
        $price = $request->price;
        $listProduct = SANPHAM::where('id_msp','!=',$id)->where(function($query) use ($price){
            $query->where('gia','<=',$price+1000000);
            $query->where('gia','>=',$price-1000000);
        })->get();
        foreach($listProduct as $product){
            $count = false;
            if(!empty($listResult)){
                foreach($listResult as $pro){
                    if($product->id_msp == $pro->id_msp){
                        $count = true;
                    }
                }

                if($count == false ){
                    array_push($listResult, $product);
                }
            }else {
                array_push($listResult, $product);
            }
        }
        foreach($listResult as $pro){
            $pro->hinhanh = Helper::$URL."phone/".$pro->hinhanh;
            $pro->giamgia = KHUYENMAI::find($pro->id_km)->chietkhau;
            $allJudge = DANHGIASP::where("id_sp", $pro->id)->get();
            $totalVote = 0;
            foreach($allJudge as $judge){
                $totalVote += $judge->danhgia;
            }
            $pro->tongluotvote = $totalVote;
            $pro->tongdanhgia = count($allJudge);
        }
        return response()->json([
            'status' => 'true',
            'message' => '',
            'data' => $listResult
        ]);
    }
    public function getSlideShowOfProduct($id){
        $listResult = array();
        $product = SANPHAM::find($id);
        $id_msp = $product->id_msp;
        $listSlideShow = SLIDESHOW_CTMSP::where('id_msp', $id_msp)->get();
        foreach($listSlideShow as $product){
            array_push($listResult, Helper::$URL."phone/slideshow/".$product->hinhanh);
        }
        return response()->json([
            'status' => 'true',
            'message' => '',
            'data' => $listResult
        ]);
    }
    public function getRamAndStorage(){
        $listRam  = array();
        $listStorage = array();
        $ram = SANPHAM::select('ram')->distinct()->get();
        $storage =  SANPHAM::select('dungluong')->distinct()->get();
        foreach($ram as $s){
            array_push($listRam, $s->ram);
        }
        foreach($storage as $r){
            array_push($listStorage, $r->dungluong);
        }
        return response()->json([
            'status' => 'true',
            'message' => '',
            'data' => ([
                "ram"=>$listRam,
                "dungluong"=>$listStorage
                ])
        ]);
    }
    public function getProductFilter(Request $request){
        $page = !empty($request->page) ? $request->page : 1;
    	$itemsPerPage = !empty($request->per_page) ? $request->per_page : 5;
        $priceMax = $request->priceMax;
        $priceMin = $request->priceMin;
        if(!empty($priceMax)&&!empty($request->ram)&&!empty($request->dungluong)){
            $listProduct = SANPHAM::where('ram', $request->ram)->where('dungluong', $request->dungluong)->where(function($query) use ($priceMax, $priceMin){
                $query->where('gia','<=',$priceMax);
                $query->where('gia','>=',$priceMin);
            })->skip(($page - 1) * $itemsPerPage)->take($itemsPerPage)->groupBy('id_msp')->get();
        }else if(!empty($priceMax)&&!empty($request->ram)){
            $listProduct = SANPHAM::where('ram', $request->ram)->where(function($query) use ($priceMax, $priceMin){
                $query->where('gia','<=',$priceMax);
                $query->where('gia','>=',$priceMin);
            })->skip(($page - 1) * $itemsPerPage)->take($itemsPerPage)->groupBy('id_msp')->get();
        }else if(!empty($priceMax)&&!empty($request->dungluong)){
            $listProduct = SANPHAM::where('dungluong', $request->dungluong)->where(function($query) use ($priceMax, $priceMin){
                $query->where('gia','<=',$priceMax);
                $query->where('gia','>=',$priceMin);
            })->skip(($page - 1) * $itemsPerPage)->take($itemsPerPage)->groupBy('id_msp')->get();
        }else if(!empty($request->ram)&&!empty($request->dungluong)){
            $listProduct = SANPHAM::where('ram', $request->ram)->where('dungluong', $request->dungluong)->skip(($page - 1) * $itemsPerPage)->take($itemsPerPage)->groupBy('id_msp')->get();
        }else if(!empty($request->ram)){
            $listProduct = SANPHAM::where('ram', $request->ram)->skip(($page - 1) * $itemsPerPage)->take($itemsPerPage)->groupBy('id_msp')->get();
        }else if(!empty($request->dungluong)){
            $listProduct = SANPHAM::where('dungluong', $request->dungluong)->skip(($page - 1) * $itemsPerPage)->take($itemsPerPage)->groupBy('id_msp')->get();
        }else if(!empty($priceMax)){
            $listProduct = SANPHAM::where(function($query) use ($priceMax, $priceMin){
                $query->where('gia','<=',$priceMax);
                $query->where('gia','>=',$priceMin);
            })->skip(($page - 1) * $itemsPerPage)->take($itemsPerPage)->groupBy('id_msp')->get();
        }
        foreach($listProduct as $product){
            $product->tensp = $product->tensp." ".$product->dungluong;
            $product->hinhanh = Helper::$URL."phone/".$product->hinhanh;
            $product->giamgia = KHUYENMAI::find($product->id_km)->chietkhau;
            $allJudge = DANHGIASP::where("id_sp", $product->id)->get();
            $totalVote = 0;
            foreach($allJudge as $judge){
                $totalVote += $judge->danhgia;
            }
            $product->tongluotvote = $totalVote;
            $product->tongdanhgia = count($allJudge);
        }
        return response()->json([
            'status' => 'true',
            'message' => '',
            'data' => $listProduct
        ]);
    }
}
