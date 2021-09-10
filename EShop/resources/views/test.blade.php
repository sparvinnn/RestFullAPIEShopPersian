<form method="post" action="{{url('shop')}}">
    {{csrf_field()}}
    <input type="text" name="price">
    <button type="submit">تکمیل خرید</button>
</form>
