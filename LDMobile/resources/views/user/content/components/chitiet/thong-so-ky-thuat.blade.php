<div class='detail-specifications'>Thông số kỹ thuật</div>
<table class='table border'>
    <tbody class='fz-14 '>
        <tr>
            <td class='w-40 center-td'>Màn hình</td>
            <td>
                {{  
                    $phone['cauhinh']['thong_so_ky_thuat']['man_hinh']['cong_nghe_mh'] . ', ' .
                    $phone['cauhinh']['thong_so_ky_thuat']['man_hinh']['ty_le_mh'] .  '"' 
                }}
            </td>
        </tr>
        <tr>
            <td class='w-40 center-td'>Camera sau</td>
            <td>
                {{
                    $phone['cauhinh']['thong_so_ky_thuat']['camera_sau']['do_phan_giai']
                }}
            </td>
        </tr>
        <tr>
            <td class='w-40 center-td'>Camera trước</td>
            <td>
                {{
                    $phone['cauhinh']['thong_so_ky_thuat']['camera_truoc']['do_phan_giai']
                }}
            </td>
        </tr>
        <tr>
            <td class='w-40 center-td'>Hệ điều hành</td>
            <td>
                {{
                    $phone['cauhinh']['thong_so_ky_thuat']['HDH_CPU']['HDH']
                }}
            </td>
        </tr>
        <tr>
            <td class='w-40 center-td'>CPU</td>
            <td>
                {{
                    $phone['cauhinh']['thong_so_ky_thuat']['HDH_CPU']['CPU']
                }}
            </td>
        </tr>
        <tr>
            <td class='w-40 center-td'>RAM</td>
            <td>
                {{
                    $phone['cauhinh']['thong_so_ky_thuat']['luu_tru']['RAM']
                }}
            </td>
        </tr>
        <tr>
            <td class='w-40 center-td'>Bộ nhớ trong</td>
            <td>
                {{
                    $phone['cauhinh']['thong_so_ky_thuat']['luu_tru']['bo_nho_trong']
                }}
            </td>
        </tr>
        <tr>
            <td class='w-40 center-td'>SIM</td>
            <td>
                {{
                    $phone['cauhinh']['thong_so_ky_thuat']['ket_noi']['SIM'] . ', ' .
                    $phone['cauhinh']['thong_so_ky_thuat']['ket_noi']['mang_mobile']
                }}
            </td>
        </tr>
        <tr>
            <td class='w-40 center-td'>Pin</td>
            <td>
                {{
                    $phone['cauhinh']['thong_so_ky_thuat']['pin']['loai'] . ', ' .
                    $phone['cauhinh']['thong_so_ky_thuat']['pin']['dung_luong']
                }}
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div class='main-btn w-100 p-10 fz-16' data-bs-toggle="modal" data-bs-target="#specifications-modal">Xem thêm</div>
            </td>
        </tr>
    </tbody>
</table>