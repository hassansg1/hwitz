@extends('layouts.general_theme.main')
@section('custom-css')
<style>
.userProfilePic {
    margin-top: 5px !important;
}
.action-icon{
    font-size: 22px;
}
.not-allowed {
    cursor: not-allowed;
}
</style>
@endsection
@section('content')
<div class="container-fluid">
  <div class="contentBox responsiveBox">
    <h2 class="text-uppercase font-700 mb-3 text-center heading">Admin Dashboard</h2>
    @if(session()->has('message'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert" style="float:right;">×</button>
            {{ session()->get('message') }}
        </div>
    @endif
    <div class="row">
        <div class="col-3">
            <label>Select Owner:</label>
        </div>
        <div class="col-9">
        <select id="select_owner" name="owner_id" class="form-control">
            <option value="" disabled="disabled" selected>Select Owner</option>
            @foreach($owners as $owner)
                <option value="{{ $owner->id }}" @if(session()->get('owner_id') ==  $owner->id) selected="selected" @endif>{{ $owner->firstname . ' ' . $owner->lastname }}</option>
            @endforeach
        </select>
        </div>
    </div>
    <br/>
    <div class="row container_building" style="display: none">
        <div class="col-3">
            <label>Select Building:</label>
        </div>
        <div class="col-9">
            <select id="select_building" name="building_id" class="form-control">
                <option value="" disabled="disabled" selected>Select Building</option>
                @foreach($buildings as $building)
                    <option value="{{ $building->id }}" @if(session()->get('building_id') ==  $building->id) selected="selected" @endif>{{ $building->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <br/>
    <div class="row container_resident" style="display: none">

        <div class="row col-12">
            <div class="container container_resident_sub">
            </div>
        </div>
    </div>
    </div>
  </div>
@endsection
@section('scripts')
<script>
    @if(session()->get('owner_id'))
        selectOwner("{{ session()->get('owner_id') }}");
    @endif

    @if(session()->get('building_id'))
        selectBuilding("{{ session()->get('building_id') }}");
    @endif

    $('#select_owner').on('change', function () {
        selectOwner(this.value);
    });

    $('#select_building').on('change', function () {
        selectBuilding(this.value);
    });

    function selectBuilding(buildingId){
        $.ajax({
            url: '/getAllresidentsOfaBuilding/'+ buildingId,
            type: 'GET',
            success: function (data) {
                $('.container_resident_sub').html(data.html);
            }
        });

        $('.container_resident').show();
    }

    function selectOwner(userId){
        $('.container_resident').show();
        $('.container_resident_sub').html('');

        $.ajax({
            url: '/getOwnerBuildings',
            type: 'GET',
            data: { userId: userId},
            success: function (data) {
                $('#select_building').html(data.html);
            }
        });

        $('.container_building').show();
    }

</script>
@endsection
