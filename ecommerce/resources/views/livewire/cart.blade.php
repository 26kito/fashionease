<div class="cart-table-warp">
    {{-- Modal --}}
    <div class="modal" id="modalcart" tabindex="-1" role="dialog" wire:ignore>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>
    {{-- End of Modal --}}

    <div class="select-all mb-4">
        <div class="select-all-checkbox form-check d-inline-block ms-1">
            <input type="checkbox" id="select-all" class="form-check-input">
            <label for="select-all" class="select-all-text form-check-label">Pilih Semua</label>
        </div>
        <a data-bs-toggle="modal" data-bs-target="#modalcart" wire:ignore class="delete-all-cart-items text-danger">Hapus</a>
    </div>

    <table id="cartform">
        <thead>
            <tr>
                <th></th>
                <th class="product-th">Product</th>
                <th class="quy-th">Quantity</th>
                <th class="size-th">Size</th>
                <th class="total-th">Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ( $carts as $row )
            <tr>
                <td>
                    <input type="checkbox" name="cartid[]" id="id[{{$row->CartID}}]" value="{{ $row->CartID }}"
                        class="cartid form-check-input ms-1 {{ ($row->AvailStock != 0) ? 'availstock' : '' }}" {{
                        ($row->AvailStock == 0) ? 'disabled' : '' }}>
                </td>
                <td class="product-col">
                    <a href="/product/{{ $row->product_id }}">
                        <img src="{{ asset('asset/img/cart/'. $row->image ) }}" alt="{{ $row->image }}">
                    </a>
                    <div class="pc-title">
                        <h4>{{ $row->ProdName }}</h4>
                        <p class="text-danger">{{ "Sisa stok: ". $row->AvailStock }}</p>
                    </div>
                </td>
                <td class="quy-col">
                    <div class="quantity form-group">
                        <input wire:click="decrement('{{ $row->CartID }}', '{{ $row->ProductID }}')" type="button" class="btn" value="-">
                        <input type="text" value="{{ $row->qty }}" class="qty" readonly disabled>
                        <input wire:click="increment('{{ $row->CartID }}', '{{ $row->ProductID }}')" type="button" class="btn" value="+">
                    </div>
                </td>
                <td class="size-col">
                    <h4>{{ $row->size }}</h4>
                </td>
                <td class="total-col">
                    <h4>{{ rupiah($row->price) }}</h4>
                </td>
                <td>
                    <a wire:click="initProp('{{ $row->CartID }}', '{{ $row->ProductID }}')" data-bs-toggle="modal"
                        data-bs-target="#modalcart" class="removeCartItem btn btn-danger">
                        Hapus
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@push('js')
@if (Session::has('status'))
<script>
    let status = {{ Session::get('status') }}

    if (status == 200) {
        let event = new CustomEvent('toastr', {
            'detail': {
                'status': 'success', 
                'message': 'Pesanan berhasil dihapus'
            }
        });

        window.dispatchEvent(event);
	}
</script>
@endif
<script>
    $('.delete-all-cart-items').on('click', () => {
        $('.modal-body').html(`
            <h5 class="text-center mt-3 mb-4">Hapus semua barang?</h5>
            <p class="text-center mb-4">Produk yang kamu pilih akan dihapus dari keranjang.</p>
            <div class="d-flex flex-column">
                <button type="button" id="removeAllCartItems" class="btn btn-primary mb-2">Hapus Barang</button>
                <button type="button" id="addAllCartItemsToWishlist" class="btn btn-secondary mb-3" data-dismiss="modal">
                    Pindahkan ke Wishlist
                </button>
            </div>
        `)
    })

    $('.removeCartItem').on('click', () => {
        $('.modal-body').html(`
            <h5 class="text-center mt-3 mb-4">Hapus barang?</h5>
            <p class="text-center mb-4">Produk yang kamu pilih akan dihapus dari keranjang.</p>
            <div class="d-flex flex-column">
                <button type="button" id="removeCartItem" class="btn btn-primary mb-2">Hapus Barang</button>
                <button type="button" id="addCartItemToWishlist" class="btn btn-secondary mb-3" data-dismiss="modal">
                    Pindahkan ke Wishlist
                </button>
            </div>
        `)
    })
    
    $(document).on('click', '#removeAllCartItems', () => {
        Livewire.emit('removeAllCartItems');
    })

    $(document).on('click', '#addAllCartItemsToWishlist', () => {
        Livewire.emit('addAllCartItemsToWishlist');
        $('.modal').modal('hide');
    })

    $(document).on('click', '#removeCartItem', () => {
        Livewire.emit('removeCartItem');
    })

    $(document).on('click', '#addCartItemToWishlist', () => {
        Livewire.emit('addCartItemToWishlist');
    })

    // Check or Uncheck All checkboxes
    $("#select-all").change(function() {
        let checked = $(this).is(':checked');
        if(checked) {
            $(".availstock").each(function() {
                $(this).prop("checked", true);
                $('.delete-all-cart-items').show();
            });
        } else{
            $(".availstock").each(function() {
                $(this).prop("checked", false);
                $('.delete-all-cart-items').hide();
            });
        }
    });
 
    // Changing state of CheckAll checkbox 
    $(".availstock").click(function() {
        if($(".availstock").length == $(".availstock:checked").length) {
            $("#select-all").prop("checked", true);
            $('.delete-all-cart-items').show();
        } else {
            $("#select-all").prop("checked", false);
            $('.delete-all-cart-items').hide();
        }
    });
</script>
@endpush