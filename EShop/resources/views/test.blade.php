<form method="post" action="{{url('start')}}">
    {{csrf_field()}}
    <input type="text" name="price">
    <input type="text" name="list" value='[
    {
        "id": 78,
    "price": 157000,
    "num":  1
    },
    {
    "id": 79,
    "price": 120000,
    "num":  1
    },
    {
    "id": 80,
    "price":200000,
    "num":  1
    }
    ]'>
    <button type="submit">تکمیل خرید</button>
</form>
