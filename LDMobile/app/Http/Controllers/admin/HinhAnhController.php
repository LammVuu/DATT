<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\MAUSP;
use App\Models\HINHANH;

class HinhAnhController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->admin='admin/content/';
    }
    public function index()
    {
        return view($this->admin."hinh-anh");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if($request->ajax()){
            $html = '<tr data-id="3">
                        <td class="vertical-center w-10">0</td>
                        <td class="vertical-center w-30">iPhone 12 PRO MAX</td>
                        <td class="vertical-center w-45">
                            <div class="d-flex">
                                <img src="images/phone/iphone_12_red.jpg" alt="" width="50px">
                                <img src="images/phone/iphone_12_red.jpg" alt="" width="50px">
                                <img src="images/phone/iphone_12_red.jpg" alt="" width="50px">
                            </div>
                        </td>
                        {{-- nút --}}
                        <td class="vertical-center w-15">
                            <div class="d-flex justify-content-evenly">
                                <div class="info-hinhanh-btn info-btn"><i class="fas fa-info"></i></div>
                                <div data-id="1" class="edit-hinhanh-modal-show edit-btn"><i class="fas fa-pen"></i></div>
                                <div data-id="1" data-object="hinhanh" class="delete-hinhanh-btn delete-btn">
                                    <i class="fas fa-trash"></i>
                                </div>
                            </div>
                        </td>
                    </tr>';
            return $html;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        if($request->ajax()){
            $html = '<tr data-id="1">
                        <td class="vertical-center w-10">0</td>
                        <td class="vertical-center w-30">iPhone 12 PRO MAX</td>
                        <td class="vertical-center w-45">
                            <div class="d-flex">
                                <img src="images/phone/iphone_12_black.jpg" alt="" width="50px">
                                <img src="images/phone/iphone_12_black.jpg" alt="" width="50px">
                                <img src="images/phone/iphone_12_black.jpg" alt="" width="50px">
                            </div>
                        </td>
                        {{-- nút --}}
                        <td class="vertical-center w-15">
                            <div class="d-flex justify-content-evenly">
                                <div class="info-hinhanh-btn info-btn"><i class="fas fa-info"></i></div>
                                <div data-id="1" class="edit-hinhanh-modal-show edit-btn"><i class="fas fa-pen"></i></div>
                                <div data-id="1" data-object="hinhanh" class="delete-hinhanh-btn delete-btn">
                                    <i class="fas fa-trash"></i>
                                </div>
                            </div>
                        </td>
                    </tr>';
            return $html;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /*==============================================================================================================
                                                    Function
    ================================================================================================================*/
}
