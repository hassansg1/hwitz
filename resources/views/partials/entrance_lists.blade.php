<div class="table-responsive">
    <table>
        <tr>
            <th>Building</th>
            <th>Entrance</th>
        </tr>
        @foreach($data as $asset)
            <tr class="border_bottom">
                <td>{{ $asset['building_name'] }}</td>
                <td>{{ $asset['asset_name'] }}</td>
            </tr>
        @endforeach
    </table>
</div>