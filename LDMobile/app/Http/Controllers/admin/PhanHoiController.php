<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PHANHOI;
use App\Models\TAIKHOAN;
class PhanHoiController extends Controller
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
        //
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $listReply = PHANHOI::where('id_dg', $id)->get();
        $html = '<tbody id="lst_reply">';
        foreach($listReply as $reply){
            $user = TAIKHOAN::find($reply->id_tk);
            $html .= '<tr data-id="'.$reply->id.'"><td class="vertical-center w-10">'.$user->hoten.'</td>
            <td class="vertical-center w-10">'.$reply->noidung.'</td>
            <td class="vertical-center w-10">'.$reply->thoigian.'</td>
            <td class="vertical-center w-15">
            <div class="d-flex justify-content-evenly">
                <div data-id="'.$reply->id.'" data-object="review" id="delete-reply-btn" class="delete-reply-btn delete-btn">
                    <i class="fas fa-trash"></i>
                </div>
            </div>
        </td>
            </tr>';
            
        }
        $html .='</tbody>';
        return $html;
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $reply = PHANHOI::find($id);
        $reply->delete();
        $listReply = PHANHOI::where('id_dg', $id)->get();
        return null;
    }
}
