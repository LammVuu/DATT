{{-- modal xóa --}}
<div class="modal fade" id="delete-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="delete-object-form" action="{{route('user/delete-object')}}" method="POST">
                @csrf
                <div class="modal-body p-60">
                    <div id="delete-content" class="fz-20"></div>
                    <div class="mt-30 d-flex justify-content-between">
                        <div class="cancel-btn w-48" data-bs-dismiss="modal">Hủy</div>
                        <div id="delete-btn" class="checkout-btn w-48">Xóa</div>
                    </div>
                </div>

                <input type="hidden" id="id" name="id">
                <input type="hidden" id="object" name="object">
            </form>
        </div>
    </div>
</div>