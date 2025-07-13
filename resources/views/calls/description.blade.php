<div class="bg-white rounded shadow p-4 mb-1">
    <p><b>Всего звонков:</b> @isset($data['total_calls']) {{ $data['total_calls']}} @else 0 @endisset</p>
    <p><b>Суммарное время:</b> @isset($data['total_duration']) {{ $data['total_duration'] }} @else 0 @endisset</p>

    @isset($data['operator']) <p><b>Оператор: </b> {{$data['operator']}}</p> @endisset
</div>

