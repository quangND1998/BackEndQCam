<table>
    <thead>
        <tr>
            <th>Đơn hàng</th>
            <th>Khách hàng</th>
            <th>Thời gian</th>
            <th>Tổng giá trị</th>
            <th>Còn lại</th>
            <th>Ghi nhận HH </th>
            <th>Hoa Hồng Đã thanh toán</th>
            <th>Hoa Hồng Chưa thanh toán</th>
            <th>Trạng thái</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order_packages as $order)
        <tr>
            <td>{{ $order->order_number }}</td>
            <td>{{ $order->customer?->name }}</td>
            <td>{{ $order->created_at }}</td>
            <td>@if($order->status =='decline')-{{$order->grand_total}} @else{{$order->grand_total}}@endif</td>
            <td>@if($order->status =='decline')-{{$order->price_percent}}@else{{$order->price_percent}}@endif</td>
            <td>@if(count($order->commissions_packages) >0){{$order->commissions_packages[0]->commission_amount??0}} @else 0 @endif</td>
            <td>@if(count($order->commissions_packages) >0){{$order->commissions_packages[0]->commission_paid??0}} @else 0 @endif</td>
            <td>@if(count($order->commissions_packages) >0){{$order->commissions_packages[0]->commission_unpaid??0}}@else 0 @endif</td>
            <td>{{ $order->status }}</td>
        </tr>
        @endforeach
    </tbody>
</table>