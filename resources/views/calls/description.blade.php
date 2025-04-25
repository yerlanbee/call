<div class="bg-white rounded shadow p-4 mb-1">
    <p><b>Всего звонков:</b> {{ $data['total_calls']}}</p>
    <p><b>Суммарное время:</b> {{ $data['total_duration'] }}</p>

    @isset($data['operator']) <p><b>Оператор: </b> {{$data['operator']}}</p> @endisset
</div>

