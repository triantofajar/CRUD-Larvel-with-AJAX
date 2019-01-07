@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Task Laravel</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!--Member Form Register -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">Form Member</div>
                            <div class="card-body">
                                <div class="container">
                                    <form id="form-member" method="post"> 
                                        <input type="hidden" id="id" name="id">
                                        <div class="row">
                                            <label style="font-weight: bold;">Name :</label>
                                            <input type="text" id="name" name="name" class="form-control">
                                        </div>
                                        <div class="row">
                                            <label style="font-weight: bold;">Age :</label>
                                            <input type="text" id="age" name="age" class="form-control">
                                        </div>
                                        <div class="row">
                                            <label style="font-weight: bold;">Address :</label>
                                            <textarea name="address" id="address" class="form-control"></textarea>
                                        </div>
                                        <div class="row" style="margin-top: 10px;">
                                            <button type="button" class="btn btn-primary" id="submit">Submit</button>
                                            &nbsp;&nbsp;
                                            <button type="reset" class="btn btn-danger">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end page Member-->

                    <!--Member Table -->
                    <div class="col-md-12" style="margin-top: 30px;">
                        <div class="card">
                            <div class="card-header">Member List</div>
                            <div class="card-body">
                                <div class="container">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <td>Name</td>
                                                <td>Age</td>
                                                <td>Address</td>
                                                <td>Action</td>
                                            </tr>
                                        </thead>
                                        <tbody id = "datamember">
                                            @foreach($member as $row)
                                            <tr>
                                                <td>{{$row->name}}</td>
                                                <td>{{$row->age}}</td>
                                                <td>{{$row->address}}</td>
                                                <td><button type="button" class="btn btn-warning btn-sm" onclick="getEdit({!! $row->id !!})">Edit</button>
                                                    <button type="button" class="btn btn-danger btn-sm">Delete</button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tbody id="reloadMember"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end list Member-->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        //action ajax store member
        $("#submit").click(function(event) {
            var data = $('form#form-member').serialize();
            $.ajax({
                type: 'POST',
                url: '{{ url('member/store') }}',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: data,
                beforeSend: function() {
                                  
                },
                dataType: 'json',
                success : function(response){
                    $('form#form-member').trigger('reset')
                    $('#reloadMember').empty()
                    $.each(response, function (i,obj){
                        $('#reloadMember').append(reloadTable(obj))
                    })
                    $('#datamember').empty()
                },
                error: function(err){
                    console.log(err)
                }
            })
            event.preventDefault();
        });
        //end store
    });

    //reload table
    function reloadTable(value)
    {
        return '<tr>'
            +'<td>'+value.name+'</td>' 
            +'<td>'+value.age+'</td>'
            +'<td>'+value.address+'</td>'
            +'<td><button type="button" class="btn btn-warning btn-sm" onclick="getEdit('+value.id+')">Edit</button>'
                +'<button type="button" class="btn btn-danger btn-sm">Delete</button></td>'
        +'</tr>'
    }

    //get data for edit member
    function getEdit(value)
    {
        $.ajax({
            type: 'GET',
            url: '{{ url('member/edit') }}/'+value,
            dataType: 'json',
            success : function (response){
                console.log(response)
                $('#id').empty()
                $('#name').empty()
                $('#age').empty()
                $('#address').empty()
                $('#submit').hide()
                $('#submit').after('<button class=" btn btn-success" type="button" id="edit_'+value+'" onclick="updateMember('+value+')">Edit</button>');
                
                $('#submit_'+value+'').hide()
            

                $('#id').val(response.id)
                $('#name').val(response.name)
                $('#age').val(response.age)
                $('#address').val(response.address)

            },
            error: function(err){
                console.log(err)
            }
        })
    }

    //confirm before delete record
    function ConfirmDelete()
    {
        var del = confirm("Are you sure you want to delete this data ? ");
        if (del)
            return true;
        else
            return false;
    }


    function updateMember(val)
    {
        //action ajax update member
        var data = $('form#form-member').serialize();
        $.ajax({
            type: 'POST',
            url: '{{ url('member/update') }}',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: data,
            beforeSend: function() {
                            
            },
            dataType: 'json',
            success : function (response){
                $('form#form-member').trigger('reset')
                $('#edit_'+val+'').hide()
                $('#edit_'+val+'').after('<button type="button" class="btn btn-primary" id="submit_'+val+'">Submit</button>');
                $('#reloadMember').empty()
                $.each(response, function (i,obj){
                    $('#reloadMember').append(reloadTable(obj))
                })
                $('#datamember').empty()
            },
            error: function(err){
                console.log(err)
            }
        })
        //end update
    }

</script>
@endpush
