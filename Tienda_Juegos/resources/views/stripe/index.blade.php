<form action= "/checkout" method="POST">
    <input type="hidden" name="_token" value="{{ csfr_token() }}">
    @csrf
    <button type="submit" id="checkout-button">Checkout</button>
</form>