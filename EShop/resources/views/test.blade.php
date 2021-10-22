<form method="post" action="{{url('start')}}">
    {{csrf_field()}}
    <input type="text" name="price">
    <input type="text" name="list" value='[
    {
        "id": 4,
    "price": 157000,
    "num":  1
    },
    {
    "id": 21,
    "price": 6656,
    "num":  1
    },
    {
    "id": 22,
    "price": 6656,
    "num":  1
    }
    ]'>
    <button type="submit">تکمیل خرید</button>
</form>
